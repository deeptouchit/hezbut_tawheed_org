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

        // Deployment commands
        $commands = [
            'git pull origin ' . escapeshellarg($targetBranch),
            'php artisan migrate --force',
            'php artisan optimize:clear',
            'php artisan config:cache',
            'php artisan route:cache',
        ];

        $fullCommand = implode(' && ', $commands);

        // Execute command
        $output = [];
        $exitCode = 0;

        // Run process in project root
        $process = Process::fromShellCommandline($fullCommand, $basePath);
        $process->setTimeout(300); // 5 minutes timeout

        try {
            $process->run();
            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();
            $exitCode = $process->getExitCode();

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
            file_put_contents(
                storage_path('logs/deploy.log'),
                "[" . date('Y-m-d H:i:s') . "] Deploy by {$pusher}\nOutput:\n" . $output . "\nErrors:\n" . $errorOutput . "\n" . str_repeat('=', 60) . "\n\n",
                FILE_APPEND
            );

            if ($exitCode !== 0) {
                Log::error('GitHub Webhook: Deployment failed with exit code ' . $exitCode, $logData);
                return response()->json([
                    'success' => false,
                    'message' => 'Deployment failed',
                    'exit_code' => $exitCode,
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
