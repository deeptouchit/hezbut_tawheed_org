<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CustomerLoginController extends Controller
{
    /**
     * Maximum number of login attempts allowed
     */
    protected $maxAttempts = 5;

    /**
     * Decay minutes for login attempts
     */
    protected $decayMinutes = 15;

    /**
     * Show customer login form
     */
    public function showLoginForm(): View
    {
        // If customer is already logged in, redirect to dashboard
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('customer.auth.login');
    }

    /**
     * Handle customer login
     */
    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // Find customer by phone
        $customer = Customer::where('phone', $request->phone)->first();

        // =============================================
        // 1. PHONE VALIDATION
        // =============================================
        if (!$customer) {
            $this->logFailedAttempt($request, 'Phone not found');
            usleep(500000);

            return back()->withErrors([
                'phone' => 'এই ফোন নম্বরটি রেজিস্টার করা নেই।'
            ])->withInput();
        }

        // =============================================
        // 2. PASSWORD VALIDATION
        // =============================================
        if (!Hash::check($request->password, $customer->password)) {
            $this->logFailedAttempt($request, 'Invalid password', $customer);
            $this->incrementLoginAttempts($customer);

            $remainingAttempts = $this->getRemainingAttempts($customer);
            $errorMessage = 'পাসওয়ার্ড ভুল!';

            if ($remainingAttempts > 0) {
                $errorMessage .= " আরো {$remainingAttempts} বার চেষ্টা করতে পারবেন।";
            }

            return back()->withErrors([
                'phone' => $errorMessage
            ])->withInput();
        }

        // =============================================
        // 3. ACCOUNT STATUS CHECK
        // =============================================
        $statusCheck = $this->checkAccountStatus($customer);
        if ($statusCheck !== true) {
            $this->logFailedAttempt($request, $statusCheck['reason'], $customer);
            return back()->withErrors([
                'phone' => $statusCheck['message']
            ])->withInput();
        }

        // =============================================
        // 4. ACCOUNT LOCK CHECK
        // =============================================
        if ($this->isAccountLocked($customer)) {
            $lockedUntil = Carbon::parse($customer->locked_until);
            $minutesRemaining = ceil(now()->diffInMinutes($lockedUntil));

            return back()->withErrors([
                'phone' => "আপনার অ্যাকাউন্ট {$minutesRemaining} মিনিটের জন্য লক করা হয়েছে।"
            ])->withInput();
        }

        // =============================================
        // 5. ATTEMPT LOGIN WITH CUSTOMER GUARD
        // =============================================
        if (Auth::guard('customer')->attempt([
            'phone' => $request->phone,
            'password' => $request->password
        ], $request->boolean('remember'))) {

            // Clear login attempts on successful login
            $this->clearLoginAttempts($customer);

            // Update last login information
            $this->updateLastLoginInfo($customer, $request);

            // Regenerate session
            $request->session()->regenerate();

            // Log successful login
            $this->logSuccessfulLogin($request, $customer);

            // Redirect to customer dashboard
            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'স্বাগতম! আপনি সফলভাবে লগইন করেছেন।');
        }

        return back()->withErrors([
            'phone' => 'লগইন ব্যর্থ হয়েছে।'
        ])->withInput();
    }

    /**
     * Handle customer logout
     */
    public function logout(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if ($customer) {
            Log::info('Customer logged out', [
                'customer_id' => $customer->id,
                'phone' => $customer->phone,
                'ip' => $request->ip()
            ]);
        }

        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')
            ->with('success', 'আপনি লগআউট করেছেন।');
    }

    // =============================================
    // PRIVATE HELPER METHODS
    // =============================================

    /**
     * Check account status
     */
    private function checkAccountStatus($customer): array|bool
    {
        if (isset($customer->status)) {
            if ($customer->status === 'inactive' || $customer->status === 0) {
                return [
                    'success' => false,
                    'reason'  => 'Account inactive',
                    'message' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে। প্রশাসকের সাথে যোগাযোগ করুন।'
                ];
            }

            if ($customer->status === 'banned') {
                return [
                    'success' => false,
                    'reason'  => 'Account banned',
                    'message' => 'আপনার অ্যাকাউন্ট ব্লক করা হয়েছে। প্রশাসকের সাথে যোগাযোগ করুন।'
                ];
            }
        }

        return true;
    }

    /**
     * Check if account is locked
     */
    private function isAccountLocked($customer): bool
    {
        if ($customer->locked_until && Carbon::parse($customer->locked_until)->isFuture()) {
            return true;
        }

        if ($customer->locked_until && Carbon::parse($customer->locked_until)->isPast()) {
            $customer->update([
                'locked_until' => null,
                'failed_login_attempts' => 0
            ]);
        }

        return false;
    }

    /**
     * Increment login attempts
     */
    private function incrementLoginAttempts($customer): void
    {
        $attempts = ($customer->failed_login_attempts ?? 0) + 1;

        if ($attempts >= $this->maxAttempts) {
            $customer->update([
                'failed_login_attempts' => $attempts,
                'locked_until' => now()->addMinutes($this->decayMinutes)
            ]);
        } else {
            $customer->update(['failed_login_attempts' => $attempts]);
        }
    }

    /**
     * Clear login attempts
     */
    private function clearLoginAttempts($customer): void
    {
        $customer->update([
            'failed_login_attempts' => 0,
            'locked_until'          => null
        ]);
    }

    /**
     * Get remaining attempts
     */
    private function getRemainingAttempts($customer): int
    {
        $attempts = $customer->failed_login_attempts ?? 0;
        return max(0, $this->maxAttempts - $attempts);
    }

    /**
     * Update last login info
     */
    private function updateLastLoginInfo($customer, Request $request): void
    {
        $customer->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);
    }

    /**
     * Log failed attempt
     */
    private function logFailedAttempt(Request $request, string $reason, $customer = null): void
    {
        Log::warning('Failed customer login attempt', [
            'phone'       => $request->phone,
            'ip'          => $request->ip(),
            'reason'      => $reason,
            'customer_id' => $customer?->id,
            'timestamp'   => now()
        ]);
    }

    /**
     * Log successful login
     */
    private function logSuccessfulLogin(Request $request, $customer): void
    {
        Log::info('Successful customer login', [
            'customer_id' => $customer->id,
            'phone'       => $customer->phone,
            'name'        => $customer->name,
            'ip'          => $request->ip(),
            'timestamp'   => now()
        ]);
    }
}
