<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailManagementController extends Controller
{
    /**
     * Display mail settings page
     */
    public function index()
    {
        return view('admin.mail-settings.index');
    }

    /**
     * Update mail settings in .env file
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MAIL_MAILER'               => 'required|string|max:30',
            'MAIL_HOST'                 => 'required|string|max:255',
            'MAIL_PORT'                 => 'required|integer|min:1|max:65535',
            'MAIL_USERNAME'             => 'required|email|max:255',
            'MAIL_PASSWORD'             => 'required|string|max:255',
            'MAIL_ENCRYPTION'           => 'required|string|max:20',
            'MAIL_FROM_ADDRESS'         => 'required|email|max:255',
            'MAIL_FROM_NAME'            => 'nullable|string|max:255',
            'MAIL_SUPPORT_HOST'         => 'required|string|max:255',
            'MAIL_SUPPORT_PORT'         => 'required|integer|min:1|max:65535',
            'MAIL_SUPPORT_USERNAME'     => 'required|email|max:255',
            'MAIL_SUPPORT_PASSWORD'     => 'required|string|max:255',
            'MAIL_SUPPORT_ENCRYPTION'   => 'required|string|max:20',
            'MAIL_SUPPORT_FROM_ADDRESS' => 'required|email|max:255',
            'MAIL_ACCOUNT_HOST'         => 'required|string|max:255',
            'MAIL_ACCOUNT_PORT'         => 'required|integer|min:1|max:65535',
            'MAIL_ACCOUNT_USERNAME'     => 'required|email|max:255',
            'MAIL_ACCOUNT_PASSWORD'     => 'required|string|max:255',
            'MAIL_ACCOUNT_ENCRYPTION'   => 'required|string|max:20',
            'MAIL_ACCOUNT_FROM_ADDRESS' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ভ্যালিডেশন ত্রুটি',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            $envPath = base_path('.env');

            if (!File::exists($envPath)) {
                throw new Exception('.env file not found.');
            }

            $envContent = File::get($envPath);

            $keysToUpdate = [
                'MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD',
                'MAIL_ENCRYPTION', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME',
                'MAIL_SUPPORT_HOST', 'MAIL_SUPPORT_PORT', 'MAIL_SUPPORT_USERNAME',
                'MAIL_SUPPORT_PASSWORD', 'MAIL_SUPPORT_ENCRYPTION', 'MAIL_SUPPORT_FROM_ADDRESS',
                'MAIL_ACCOUNT_HOST', 'MAIL_ACCOUNT_PORT', 'MAIL_ACCOUNT_USERNAME',
                'MAIL_ACCOUNT_PASSWORD', 'MAIL_ACCOUNT_ENCRYPTION', 'MAIL_ACCOUNT_FROM_ADDRESS',
            ];

            foreach ($keysToUpdate as $key) {
                $value = (string) ($validated[$key] ?? '');
                $envContent = $this->setEnvValue($envContent, $key, $value);
            }

            File::put($envPath, $envContent);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            $message = 'মেইল সেটিংস সফলভাবে আপডেট করা হয়েছে।';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('admin.email.index')->with([
                'message' => $message,
                'alert-type' => 'success'
            ]);

        } catch (Exception $e) {
            Log::error('Mail settings update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'মেইল সেটিংস আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => $errorMessage]);
        }
    }

    /**
     * Send a test email using selected mailbox
     */
    public function testMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'   => 'required|email|max:255',
            'mailbox' => 'required|string|in:primary,support,account'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন ত্রুটি',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = $request->email;
            $mailbox = $request->mailbox;

            $this->overrideMailConfig($mailbox);

            Mail::send('admin.mail-settings.test-email', [
                'mailbox' => $mailbox,
                'date' => now()->format('Y-m-d H:i:s')
            ], function ($message) use ($email, $mailbox) {
                $message->to($email)
                    ->subject('টেস্ট ইমেইল - ' . ucfirst($mailbox) . ' Mailbox - ' . config('app.name'));
            });

            Artisan::call('config:clear');

            Log::info('Test email sent successfully', [
                'to' => $email,
                'mailbox' => $mailbox
            ]);

            return response()->json([
                'success' => true,
                'message' => "টেস্ট ইমেইল সফলভাবে পাঠানো হয়েছে। {$mailbox} মেইলবক্স ব্যবহার করা হয়েছে।"
            ]);

        } catch (Exception $e) {
            Log::error('Test email sending failed: ' . $e->getMessage(), [
                'to' => $request->email ?? null,
                'mailbox' => $request->mailbox ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'টেস্ট ইমেইল পাঠাতে ব্যর্থ হয়েছে। ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test SMTP connection
     */
    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mailbox' => 'required|string|in:primary,support,account'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন ত্রুটি',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mailbox = $request->mailbox;
            $config = $this->getMailboxConfig($mailbox);

            $host = $config['host'];
            $port = $config['port'];
            $timeout = 15;

            Log::info('SMTP Test: Starting', ['host' => $host, 'port' => $port]);

            $socket = null;
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ]);

            if ($port == 465) {
                $socket = @stream_socket_client(
                    "ssl://{$host}:{$port}",
                    $errno,
                    $errstr,
                    $timeout,
                    STREAM_CLIENT_CONNECT,
                    $context
                );
            } else {
                $socket = @stream_socket_client(
                    "tcp://{$host}:{$port}",
                    $errno,
                    $errstr,
                    $timeout
                );
            }

            if (!$socket) {
                throw new Exception("পোর্ট {$port} এ সংযোগ করা যায়নি: {$errstr}");
            }

            stream_set_timeout($socket, $timeout);
            $welcome = fgets($socket, 1024);

            if (substr($welcome, 0, 3) != '220') {
                throw new Exception('সার্ভার সঠিক রেসপন্স দেয়নি: ' . trim($welcome));
            }

            fputs($socket, "EHLO localhost\r\n");
            $response = '';
            while ($line = fgets($socket, 1024)) {
                $response .= $line;
                if (substr($line, 3, 1) == ' ') break;
            }

            if (strpos($response, 'AUTH') !== false) {
                fputs($socket, "AUTH LOGIN\r\n");
                fgets($socket, 1024);
                fputs($socket, base64_encode($config['username']) . "\r\n");
                fgets($socket, 1024);
                fputs($socket, base64_encode($config['password']) . "\r\n");
                $authResponse = fgets($socket, 1024);

                if (substr($authResponse, 0, 3) != '235') {
                    throw new Exception('অথেনটিকেশন ব্যর্থ হয়েছে');
                }
            }

            fputs($socket, "QUIT\r\n");
            fclose($socket);

            return response()->json([
                'success' => true,
                'message' => ucfirst($mailbox) . " মেইলবক্সের সাথে সংযোগ সফল হয়েছে!"
            ]);

        } catch (Exception $e) {
            Log::error('Connection test failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'সংযোগ ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current mail configuration
     */
    public function getConfig()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'primary' => [
                    'mailer'       => env('MAIL_MAILER'),
                    'host'         => env('MAIL_HOST'),
                    'port'         => env('MAIL_PORT'),
                    'encryption'   => env('MAIL_ENCRYPTION'),
                    'from_address' => env('MAIL_FROM_ADDRESS'),
                    'from_name'    => env('MAIL_FROM_NAME'),
                ],
                'support' => [
                    'host'         => env('MAIL_SUPPORT_HOST'),
                    'port'         => env('MAIL_SUPPORT_PORT'),
                    'encryption'   => env('MAIL_SUPPORT_ENCRYPTION'),
                    'from_address' => env('MAIL_SUPPORT_FROM_ADDRESS'),
                ],
                'account' => [
                    'host'         => env('MAIL_ACCOUNT_HOST'),
                    'port'         => env('MAIL_ACCOUNT_PORT'),
                    'encryption'   => env('MAIL_ACCOUNT_ENCRYPTION'),
                    'from_address' => env('MAIL_ACCOUNT_FROM_ADDRESS'),
                ]
            ]
        ]);
    }

    /**
     * Override mail configuration for test email
     */
    private function overrideMailConfig(string $mailbox): void
    {
        $config = $this->getMailboxConfig($mailbox);

        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.host'       => $config['host'],
            'mail.mailers.smtp.port'       => $config['port'],
            'mail.mailers.smtp.username'   => $config['username'],
            'mail.mailers.smtp.password'   => $config['password'],
            'mail.mailers.smtp.encryption' => $config['encryption'],
            'mail.from.address'            => $config['from_address'],
            'mail.from.name'               => $config['from_name'] ?? config('app.name'),
        ]);
    }

    /**
     * Get mailbox configuration array
     */
    private function getMailboxConfig(string $mailbox): array
    {
        switch ($mailbox) {
            case 'primary':
                return [
                    'host'         => env('MAIL_HOST'),
                    'port'         => env('MAIL_PORT', 587),
                    'username'     => env('MAIL_USERNAME'),
                    'password'     => env('MAIL_PASSWORD'),
                    'encryption'   => env('MAIL_ENCRYPTION', 'tls'),
                    'from_address' => env('MAIL_FROM_ADDRESS'),
                    'from_name'    => env('MAIL_FROM_NAME', config('app.name')),
                ];
            case 'support':
                return [
                    'host'         => env('MAIL_SUPPORT_HOST', env('MAIL_HOST')),
                    'port'         => env('MAIL_SUPPORT_PORT', env('MAIL_PORT', 587)),
                    'username'     => env('MAIL_SUPPORT_USERNAME'),
                    'password'     => env('MAIL_SUPPORT_PASSWORD'),
                    'encryption'   => env('MAIL_SUPPORT_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
                    'from_address' => env('MAIL_SUPPORT_FROM_ADDRESS'),
                    'from_name'    => env('MAIL_FROM_NAME', config('app.name')),
                ];
            case 'account':
                return [
                    'host'         => env('MAIL_ACCOUNT_HOST', env('MAIL_HOST')),
                    'port'         => env('MAIL_ACCOUNT_PORT', env('MAIL_PORT', 587)),
                    'username'     => env('MAIL_ACCOUNT_USERNAME'),
                    'password'     => env('MAIL_ACCOUNT_PASSWORD'),
                    'encryption'   => env('MAIL_ACCOUNT_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
                    'from_address' => env('MAIL_ACCOUNT_FROM_ADDRESS'),
                    'from_name'    => env('MAIL_FROM_NAME', config('app.name')),
                ];
            default:
                throw new Exception("Invalid mailbox: {$mailbox}");
        }
    }

    /**
     * Set environment variable value in .env content
     */
    private function setEnvValue(string $envContent, string $key, string $value): string
    {
        $escapedValue = str_replace('"', '\\"', $value);
        $formattedValue = '"' . $escapedValue . '"';
        $pattern = '/^' . preg_quote($key, '/') . '\\s*=.*$/m';

        if (preg_match($pattern, $envContent)) {
            return preg_replace($pattern, $key . '=' . $formattedValue, $envContent);
        }

        return rtrim($envContent) . PHP_EOL . $key . '=' . $formattedValue . PHP_EOL;
    }
}
