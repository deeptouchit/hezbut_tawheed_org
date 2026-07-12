<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
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
     * Show the login form
     */
 public function create(): View|RedirectResponse
{
    // If user is already logged in, redirect to appropriate dashboard
    if (Auth::check()) {
        $user = Auth::user();
        return redirect()->to($this->getRedirectRoute($user));
    }

    return view('admin.auth.login');
}

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate the request first
        $validated = $request->validated();

        // Get the user by email
        $user = User::where('email', $request->email)->first();

        // =============================================
        // 1. EMAIL VALIDATION
        // =============================================
        if (!$user) {
            $this->logFailedAttempt($request, 'Email not found');

            // Add a small delay to prevent timing attacks
            usleep(500000);

            return $this->loginFailedResponse(
                'আপনার প্রদত্ত ইমেইল ঠিকানাটি আমাদের ডাটাবেসে নেই। দয়া করে সঠিক ইমেইল ব্যবহার করুন।',
                'email'
            );
        }

        // =============================================
        // 2. PASSWORD VALIDATION
        // =============================================
        if (!Hash::check($request->password, $user->password)) {
            $this->logFailedAttempt($request, 'Invalid password', $user);
            $this->incrementLoginAttempts($user);

            $remainingAttempts = $this->getRemainingAttempts($user);
            $errorMessage = 'পাসওয়ার্ড ভুল!';

            if ($remainingAttempts > 0) {
                $errorMessage .= " আরো {$remainingAttempts} বার চেষ্টা করতে পারবেন।";
            }

            return $this->loginFailedResponse($errorMessage, 'password');
        }

        // =============================================
        // 3. ACCOUNT STATUS CHECK
        // =============================================
        $statusCheck = $this->checkAccountStatus($user);
        if ($statusCheck !== true) {
            $this->logFailedAttempt($request, $statusCheck['reason'], $user);
            return $this->loginFailedResponse($statusCheck['message'], 'email');
        }

        // =============================================
        // 4. ACCOUNT LOCK CHECK
        // =============================================
        if ($this->isAccountLocked($user)) {
            $lockedUntil = Carbon::parse($user->locked_until);
            $minutesRemaining = ceil(now()->diffInMinutes($lockedUntil));

            $this->logFailedAttempt($request, 'Account locked', $user);

            return $this->loginFailedResponse(
                "আপনার অ্যাকাউন্ট {$minutesRemaining} মিনিটের জন্য লক করা হয়েছে। অনেকবার ভুল পাসওয়ার্ড দেওয়ার কারণে।",
                'email'
            );
        }

        // =============================================
        // 5. ATTEMPT LOGIN
        // =============================================
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Clear login attempts on successful login
            $this->clearLoginAttempts($user);

            // Update last login information
            $this->updateLastLoginInfo($user, $request);

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            // Log successful login
            $this->logSuccessfulLogin($request, $user);

            // Set remember me cookie if needed
            if ($request->boolean('remember')) {
                $this->setRememberMeCookie($user);
            }

            // Redirect based on user role
            return $this->redirectBasedOnRole($user);
        }

        // =============================================
        // 6. DEFAULT FAILED RESPONSE
        // =============================================
        $this->logFailedAttempt($request, 'Unknown error', $user);
        return $this->loginFailedResponse(
            'লগইন প্রক্রিয়া ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।',
            'email'
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Log logout activity
        if ($user) {
            Log::info('User logged out', [
                'user_id'     => $user->id,
                'email'       => $user->email,
                'name'        => $user->name,
                'role'        => $user->role,
                'ip'          => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'logout_time' => now()->toDateTimeString()
            ]);
        }

        // Perform logout
        Auth::guard('web')->logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear all cookies
        $cookies = $this->clearAuthCookies($request);

        $response = redirect('/admin/login')
            ->with('message', 'আপনি সফলভাবে লগআউট করেছেন।')
            ->with('alert-type', 'success')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

        // Attach cookies to response
        foreach ($cookies as $cookie) {
            $response->withCookie($cookie);
        }

        return $response;
    }

    // =============================================
    // PRIVATE HELPER METHODS
    // =============================================

    /**
     * Check account status and return appropriate response
     */
    private function checkAccountStatus(User $user): array|bool
    {
        // Check if account is active
        if (isset($user->status)) {
            if ($user->status === 'inactive' || $user->status === 0) {
                return [
                    'success' => false,
                    'reason'  => 'Account inactive',
                    'message' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে। অ্যাকাউন্ট সক্রিয় করতে প্রশাসকের সাথে যোগাযোগ করুন।'
                ];
            }

            if ($user->status === 'banned' || $user->status === 2) {
                return [
                    'success' => false,
                    'reason'  => 'Account banned',
                    'message' => 'আপনার অ্যাকাউন্ট ব্লক করা হয়েছে। বিস্তারিত জানতে প্রশাসকের সাথে যোগাযোগ করুন।'
                ];
            }
        }

        // Check email verification (optional - can be disabled for some roles)
        $skipVerificationRoles = ['super_admin', 'admin', 'manager'];
        $userRole = $user->role ?? $this->getUserRoleName($user);

        if (!in_array($userRole, $skipVerificationRoles) && is_null($user->email_verified_at)) {
            return [
                'success' => false,
                'reason'  => 'Email not verified',
                'message' => 'আপনার ইমেইল এখনও ভেরিফাই করা হয়নি। দয়া করে আপনার ইমেইল চেক করে ভেরিফাই করুন।'
            ];
        }

        return true;
    }

    /**
     * Check if account is locked due to too many failed attempts
     */
    private function isAccountLocked(User $user): bool
    {
        if ($user->locked_until && Carbon::parse($user->locked_until)->isFuture()) {
            return true;
        }

        // Remove lock if expired
        if ($user->locked_until && Carbon::parse($user->locked_until)->isPast()) {
            $user->update([
                'locked_until' => null,
                'failed_login_attempts' => 0
            ]);
        }

        return false;
    }

    /**
     * Increment login attempts for the user
     */
    private function incrementLoginAttempts(User $user): void
    {
        $attempts = ($user->failed_login_attempts ?? 0) + 1;

        if ($attempts >= $this->maxAttempts) {
            // Lock the account
            $user->update([
                'failed_login_attempts' => $attempts,
                'locked_until'          => now()->addMinutes($this->decayMinutes)
            ]);

            // Log account lock
            Log::warning('Account locked due to too many failed attempts', [
                'user_id'      => $user->id,
                'email'        => $user->email,
                'attempts'     => $attempts,
                'locked_until' => $user->locked_until
            ]);
        } else {
            $user->update(['failed_login_attempts' => $attempts]);
        }
    }

    /**
     * Clear login attempts for the user
     */
    private function clearLoginAttempts(User $user): void
    {
        $user->update([
            'failed_login_attempts' => 0,
            'locked_until'          => null
        ]);
    }

    /**
     * Get remaining login attempts before lock
     */
    private function getRemainingAttempts(User $user): int
    {
        $attempts = $user->failed_login_attempts ?? 0;
        return max(0, $this->maxAttempts - $attempts);
    }

    /**
     * Update last login information for the user
     */
    private function updateLastLoginInfo(User $user, Request $request): void
    {
        $user->update([
            'last_login_at'         => now(),
            'last_login_ip'         => $request->ip(),
            'last_login_user_agent' => $request->userAgent()
        ]);

        // Update customer last login if exists
        // if ($user->customer) {
        //     $user->customer->update([
        //         'last_login_at' => now(),
        //         'last_login_ip' => $request->ip()
        //     ]);
        // }
    }

    /**
     * Set remember me cookie
     */
    private function setRememberMeCookie(User $user): void
    {
        $rememberToken = $user->getRememberToken();
        $cookieName    = 'remember_web_' . hash('sha256', get_class($user));

        Cookie::queue($cookieName, $rememberToken, 43200); // 30 days
    }

    /**
     * Clear authentication cookies
     */
    private function clearAuthCookies(Request $request): array
    {
        $cookies = [];

        // Clear Laravel remember me cookie
        $cookieName = 'remember_web_' . hash('sha256', User::class);
        $cookies[]  = Cookie::forget($cookieName);

        // Clear session cookie if exists
        if ($request->hasCookie(config('session.cookie'))) {
            $cookies[] = Cookie::forget(config('session.cookie'));
        }

        return $cookies;
    }

    /**
     * Log failed login attempt
     */
    private function logFailedAttempt(Request $request, string $reason, ?User $user = null): void
    {
        Log::warning('Failed login attempt', [
            'email'      => $request->email,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'reason'     => $reason,
            'user_id'    => $user?->id,
            'timestamp'  => now()->toDateTimeString()
        ]);
    }

    /**
     * Log successful login
     */
    private function logSuccessfulLogin(Request $request, User $user): void
    {
        Log::info('Successful login', [
            'user_id'    => $user->id,
            'email'      => $user->email,
            'name'       => $user->name,
            'role'       => $user->role ?? $this->getUserRoleName($user),
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp'  => now()->toDateTimeString()
        ]);
    }

    /**
     * Get user role name (supports both Spatie and direct role)
     */
    private function getUserRoleName(User $user): string
    {
        if (method_exists($user, 'getRoleNames')) {
            $roles = $user->getRoleNames();
            return $roles->isNotEmpty() ? $roles->first() : 'customer';
        }

        return $user->role ?? 'customer';
    }

    /**
     * Get redirect route based on user role
     */
    private function getRedirectRoute(User $user): string
    {
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole(['super_admin', 'admin', 'manager'])) {
                return route('admin.dashboard');
            } elseif ($user->hasRole('customer')) {
                return route('customer.dashboard');
            } elseif ($user->hasRole('delivery_man')) {
                return route('delivery.dashboard');
            }
        } else {
            // Fallback for direct role property
            $role = $user->role ?? 'customer';

            if (in_array($role, ['super_admin', 'admin', 'manager'])) {
                return route('admin.dashboard');
            } elseif ($role === 'customer') {
                return route('customer.dashboard');
            } elseif ($role === 'delivery_man') {
                return route('delivery.dashboard');
            }
        }

        return route('home');
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        $redirectRoute = 'home';
        $roleName = 'ইউজার';

        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole(['super_admin', 'admin', 'manager'])) {
                $redirectRoute = 'admin.dashboard';

                $roleName = match(true) {
                    $user->hasRole('super_admin') => 'সুপার এডমিন',
                    $user->hasRole('admin')       => 'এডমিন',
                    $user->hasRole('manager')     => 'ম্যানেজার',
                    default                       => 'এডমিন'
                };
            } elseif ($user->hasRole('customer')) {
                $redirectRoute = 'customer.dashboard';
                $roleName      = 'গ্রাহক';
            } elseif ($user->hasRole('delivery_man')) {
                $redirectRoute = 'delivery.dashboard';
                $roleName      = 'ডেলিভারি ম্যান';
            }
        } else {
            // Fallback for direct role property
            $role = $user->role ?? 'customer';

            if (in_array($role, ['super_admin', 'admin', 'manager'])) {
                $redirectRoute = 'admin.dashboard';
                $roleName = match($role) {
                    'super_admin' => 'সুপার এডমিন',
                    'admin' => 'এডমিন',
                    'manager' => 'ম্যানেজার',
                    default => 'এডমিন'
                };
            } elseif ($role === 'customer') {
                $redirectRoute = 'customer.dashboard';
                $roleName = 'গ্রাহক';
            } elseif ($role === 'delivery_man') {
                $redirectRoute = 'delivery.dashboard';
                $roleName = 'ডেলিভারি ম্যান';
            }
        }

        // Check if route exists, fallback to home
        if (!\Illuminate\Support\Facades\Route::has($redirectRoute)) {
            $redirectRoute = 'home';
        }

        // Get greeting based on time
        $hour = now()->hour;
        $greeting = match(true) {
            $hour < 12 => 'সুপ্রভাত',
            $hour < 18 => 'শুভ অপরাহ্ন',
            default => 'শুভ সন্ধ্যা'
        };

        return redirect()->route($redirectRoute)->with([
            'message' => "{$greeting} {$user->name}! আপনি {$roleName} হিসেবে লগইন করেছেন।",
            'alert-type' => 'success'
        ]);
    }

    /**
     * Handle failed login response
     */
    private function loginFailedResponse(string $errorMessage, string $field = 'email'): RedirectResponse
    {
        // Add a small delay to prevent brute force attacks
        usleep(500000);

        return redirect()->route('admin.login')
            ->withInput(request()->only('email', 'remember'))
            ->withErrors([$field => $errorMessage])
            ->with([
                'message' => 'লগইন ব্যর্থ হয়েছে!',
                'alert-type' => 'error'
            ]);
    }
}
