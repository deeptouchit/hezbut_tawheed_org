<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class GitHubDeployController extends Controller
{
    /**
     * Handle GitHub Webhook Deployment
     */
    public function deploy(Request $request)
    {
        $secret = config('services.github.webhook_secret', env('GITHUB_WEBHOOK_SECRET'));
        $targetBranch = env('GITHUB_WEBHOOK_BRANCH', 'main');

        // Verify GitHub Signature
        $signature = $request->header('X-Hub-Signature-256');
        if ($secret) {
            if (!$signature) {
                Log::warning('GitHub Webhook: Missing signature header');
                return response()->json(['error' => 'Missing signature header'], 403);
            }

            $computedSignature = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($computedSignature, $signature)) {
                Log::warning('GitHub Webhook: Invalid signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        // Check if event is push
        $event = $request->header('X-GitHub-Event', 'push');
        if ($event === 'ping') {
            Log::info('GitHub Webhook: Ping received successfully');
            return response()->json(['message' => 'Pong! Webhook connected successfully']);
        }

        if ($event !== 'push') {
            return response()->json(['message' => "Event {$event} ignored"], 200);
        }

        $payload = $request->json()->all();
        $ref = $payload['ref'] ?? '';
        $expectedRef = "refs/heads/{$targetBranch}";

        if ($ref !== $expectedRef) {
            return response()->json([
                'message' => "Push was to {$ref}, ignoring (watching {$expectedRef})"
            ], 200);
        }

        $pusher = $payload['pusher']['name'] ?? 'unknown';
        $commit = $payload['head_commit']['id'] ?? 'unknown';
        $commitMsg = $payload['head_commit']['message'] ?? '';

        Log::info("GitHub Webhook: Deployment initiated by {$pusher} (Commit: {$commit} - {$commitMsg})");

        // Base project path
        $basePath = base_path();

        // Auto-fix SSH key permissions if they are too open (0644 -> 0600)
        $homeDir = getenv('HOME') ?: ('/home/' . get_current_user());
        $possibleSshDirs = array_unique(array_filter([
            $homeDir . '/.ssh',
            '/home/u516838200/.ssh',
        ]));

        foreach ($possibleSshDirs as $sshDir) {
            if (is_dir($sshDir)) {
                @chmod($sshDir, 0700);
                $keyFiles = glob($sshDir . '/id_*');
                if ($keyFiles) {
                    foreach ($keyFiles as $file) {
                        if (!str_ends_with($file, '.pub')) {
                            @chmod($file, 0600);
                        }
                    }
                }
                if (file_exists($sshDir . '/config')) {
                    @chmod($sshDir . '/config', 0600);
                }
            }
        }

        // Public HTTPS repository fallback (no SSH key required)
        $publicRepoUrl = 'https://github.com/deeptouchit/hezbut_tawheed_org.git';

        $cleanBranch = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $targetBranch) ?: 'main';

        // Deployment commands with SSH auto-fix and safe git fetch & reset
        $commands = [
            "rm -f .git/index.lock 2>/dev/null || true",
            "chmod 700 ~/.ssh 2>/dev/null || true",
            "chmod 600 ~/.ssh/id_* 2>/dev/null || true",
            "chmod 600 /home/u516838200/.ssh/id_* 2>/dev/null || true",
            "git -c safe.directory='*' fetch origin {$cleanBranch}",
            "git -c safe.directory='*' reset --hard origin/{$cleanBranch}",
            "php artisan migrate --force || true",
            "php artisan optimize:clear",
        ];

        $fullCommand = implode(' && ', $commands);

        // Execute command
        $output = '';
        $errorOutput = '';
        $exitCode = 0;

        try {
            if (function_exists('proc_open')) {
                $process = Process::fromShellCommandline($fullCommand, $basePath);
                $process->setTimeout(300); // 5 minutes timeout
                $process->run();
                $output = $process->getOutput();
                $errorOutput = $process->getErrorOutput();
                $exitCode = $process->getExitCode();
            } elseif (function_exists('exec')) {
                $cmd = "cd " . escapeshellarg($basePath) . " && (" . $fullCommand . ") 2>&1";
                $execLines = [];
                exec($cmd, $execLines, $exitCode);
                $output = implode("\n", $execLines);
                if ($exitCode !== 0) {
                    $errorOutput = $output;
                }
            } elseif (function_exists('shell_exec')) {
                $cmd = "cd " . escapeshellarg($basePath) . " && (" . $fullCommand . ") 2>&1";
                $output = (string) shell_exec($cmd);
                $exitCode = 0;
            } else {
                throw new \RuntimeException('Neither proc_open, exec, nor shell_exec is enabled on this PHP installation.');
            }

            $logData = [
                'timestamp' => now()->toIso8601String(),
                'pusher' => $pusher,
                'commit' => $commit,
                'message' => $commitMsg,
                'exit_code' => $exitCode,
                'output' => $output,
                'error_output' => $errorOutput,
            ];

            // Write to dedicated deploy log
            $logDir = storage_path('logs');
            if (!file_exists($logDir)) {
                @mkdir($logDir, 0755, true);
            }
            @file_put_contents(
                storage_path('logs/deploy.log'),
                "[" . date('Y-m-d H:i:s') . "] Deploy by {$pusher}\nCommand: {$fullCommand}\nExit Code: {$exitCode}\nOutput:\n" . $output . "\nErrors:\n" . $errorOutput . "\n" . str_repeat('=', 60) . "\n\n",
                FILE_APPEND
            );

            if ($exitCode !== 0) {
                Log::error('GitHub Webhook: Deployment failed with exit code ' . $exitCode, $logData);
                return response()->json([
                    'success' => false,
                    'message' => 'Deployment failed',
                    'exit_code' => $exitCode,
                    'command' => $fullCommand,
                    'output' => $output,
                    'error' => $errorOutput,
                ], 500);
            }

            Log::info('GitHub Webhook: Deployment finished successfully', $logData);

            return response()->json([
                'success' => true,
                'message' => 'Deployment completed successfully',
                'commit' => $commit,
                'output' => $output,
            ], 200);

        } catch (\Exception $e) {
            Log::error('GitHub Webhook: Exception during deploy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
