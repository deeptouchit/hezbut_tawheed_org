<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CacheToolsController extends Controller
{
    private const COMMANDS = [
        'optimize:clear' => 'Clear everything (cache, config, route, view, compiled)',
        'cache:clear'    => 'Application cache clear',
        'config:clear'   => 'Configuration cache clear',
        'route:clear'    => 'Route cache clear',
        'view:clear'     => 'Compiled view clear',
        'event:clear'    => 'Event cache clear',
        'clear-compiled' => 'Compiled class clear',
        'logs:view'      => 'View latest Laravel log lines',
        'logs:clear'     => 'Clear Laravel log file',
    ];

    /**
     * Display cache tools page
     */
    public function index()
    {
        // authorizeAccess('cache-tools_access'); // Uncomment if you have authorization

        return view('admin.cache-tools.index', [
            'commands'    => self::COMMANDS,
            'lastCommand' => session('command_name'),
            'lastOutput'  => session('command_output'),
            'logOutput'   => $this->getLogOutput(),
        ]);
    }

    /**
     * Run predefined cache command
     */
    public function run(Request $request)
    {
        // authorizeAccess('cache-tools_run');

        $validated = $request->validate([
            'command' => 'required|string',
        ]);

        $command = $validated['command'];

        if (!array_key_exists($command, self::COMMANDS)) {
            return redirect()->route('admin.cache-tools.index')->with([
                'message'    => 'Invalid command request.',
                'alert-type' => 'error',
            ]);
        }

        try {
            if ($command === 'logs:view') {
                $output = $this->getLogOutput();
            } elseif ($command === 'logs:clear') {
                $logFile = storage_path('logs/laravel.log');
                if (File::exists($logFile)) {
                    File::put($logFile, '');
                    $output = 'laravel.log has been cleared successfully.';
                } else {
                    $output = 'laravel.log file does not exist.';
                }
            } else {
                Artisan::call($command);
                $output = trim(Artisan::output());
            }

            return redirect()->route('admin.cache-tools.index')->with([
                'message'        => 'Command executed successfully: ' . $command,
                'alert-type'     => 'success',
                'command_name'   => $command,
                'command_output' => $output !== '' ? $output : 'No output returned.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Cache command execution failed', [
                'command' => $command,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.cache-tools.index')->with([
                'message'        => 'Command failed: ' . $command,
                'alert-type'     => 'error',
                'command_name'   => $command,
                'command_output' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Run custom artisan command
     */
    public function runCustom(Request $request, Kernel $kernel)
    {
        // authorizeAccess('cache-tools_custom-run');

        $validated = $request->validate([
            'custom_command' => 'required|string|max:500',
        ]);

        $customCommand = trim($validated['custom_command']);
        $normalizedCommand = $this->normalizeCustomCommand($customCommand);

        if ($normalizedCommand === '') {
            return redirect()->route('admin.cache-tools.index')->with([
                'message' => 'Command cannot be empty.',
                'alert-type' => 'error',
            ]);
        }

        // Security: Block dangerous characters
        if (strpbrk($normalizedCommand, ';|&`$><') !== false) {
            return redirect()->route('admin.cache-tools.index')->with([
                'message' => 'Invalid command pattern detected.',
                'alert-type' => 'error',
            ]);
        }

        try {
            $input = new StringInput($normalizedCommand);
            $outputBuffer = new BufferedOutput();
            $exitCode = $kernel->handle($input, $outputBuffer);
            $output = trim($outputBuffer->fetch());

            return redirect()->route('admin.cache-tools.index')->with([
                'message' => $exitCode === 0
                    ? 'Custom command executed successfully.'
                    : 'Custom command finished with errors (exit code: ' . $exitCode . ').',
                'alert-type' => $exitCode === 0 ? 'success' : 'warning',
                'command_name' => 'custom: ' . $normalizedCommand,
                'command_output' => $output !== '' ? $output : 'No output returned.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Custom cache tool command execution failed', [
                'command' => $normalizedCommand,
                'raw_command' => $customCommand,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.cache-tools.index')->with([
                'message' => 'Custom command failed.',
                'alert-type' => 'error',
                'command_name' => 'custom: ' . $normalizedCommand,
                'command_output' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get all cache status (AJAX)
     */
    public function getStatus(Request $request)
    {
        if ($request->ajax()) {
            $status = [
                'config' => [
                    'cached' => File::exists(base_path('bootstrap/cache/config.php')),
                    'path' => base_path('bootstrap/cache/config.php')
                ],
                'routes' => [
                    'cached' => File::exists(base_path('bootstrap/cache/routes-v7.php')),
                    'path' => base_path('bootstrap/cache/routes-v7.php')
                ],
                'views' => [
                    'cached' => File::exists(storage_path('framework/views')),
                    'path' => storage_path('framework/views')
                ],
                'events' => [
                    'cached' => File::exists(base_path('bootstrap/cache/events.php')),
                    'path' => base_path('bootstrap/cache/events.php')
                ]
            ];

            return response()->json([
                'success' => true,
                'status' => $status
            ]);
        }

        return redirect()->route('admin.cache-tools.index');
    }

    /**
     * Normalize custom command (remove php artisan prefix)
     */
    private function normalizeCustomCommand(string $command): string
    {
        $command = trim($command);
        $command = preg_replace('/^php\s+artisan\s+/i', '', $command) ?? $command;
        $command = preg_replace('/^artisan\s+/i', '', $command) ?? $command;

        return trim($command);
    }

    /**
     * Get log output (last 400 lines)
     */
    private function getLogOutput(): string
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return 'laravel.log file does not exist.';
        }

        $content = File::get($logFile);

        if (trim($content) === '') {
            return 'laravel.log is currently empty.';
        }

        $lines = preg_split('/\r\n|\r|\n/', $content) ?: [];
        $tail = array_slice($lines, -400);

        return implode(PHP_EOL, $tail);
    }
}
