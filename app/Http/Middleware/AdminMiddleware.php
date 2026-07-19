<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Allowed admin roles
     */
    protected array $allowedRoles = ['super_admin', 'admin', 'manager'];

    /**
     * Role-based redirect routes
     */
    protected array $roleRedirects = [
        'customer'     => 'customer.dashboard',
        'delivery_man' => 'delivery.dashboard',
        'default'      => 'login'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // =============================================
        // 1. CHECK AUTHENTICATION
        // =============================================
        if (!Auth::check()) {
            $this->logUnauthorizedAccess($request, 'Not authenticated');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please login first.',
                    'code'    => 401
                ], 401);
            }

            return redirect()->route('admin.login')
                ->with('error', 'আপনাকে লগইন করতে হবে।')
                ->with('alert-type', 'error');
        }

        $user = Auth::user();

        // =============================================
        // 2. CHECK ACCOUNT STATUS
        // =============================================
        $statusCheck = $this->checkAccountStatus($user);
        if ($statusCheck !== true) {
            $this->logUnauthorizedAccess($request, $statusCheck['reason'], $user);
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $statusCheck['message'],
                    'code' => 403
                ], 403);
            }

            return redirect()->route('admin.login')
                ->withErrors(['email' => $statusCheck['message']])
                ->with('alert-type', 'error');
        }

        // =============================================
        // 3. CHECK ROLE PERMISSION
        // =============================================
        $allowedRoles = !empty($roles) ? $roles : $this->allowedRoles;

        if (!$this->userHasAllowedRole($user, $allowedRoles)) {
            $this->logUnauthorizedAccess($request, "Role: {$this->getUserRole($user)} not allowed", $user);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this resource.',
                    'code' => 403
                ], 403);
            }

            return $this->handleUnauthorizedAccess($user, $request);
        }

        // =============================================
        // 4. CHECK ADDITIONAL PERMISSIONS (Route-specific)
        // =============================================
        if (!$this->hasAdditionalPermissions($user, $request)) {
            $this->logUnauthorizedAccess($request, "Insufficient permissions for route: {$request->route()->getName()}", $user);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You don\'t have permission to perform this action.',
                    'code' => 403
                ], 403);
            }

            abort(403, 'আপনার এই কাজটি করার অনুমতি নেই।');
        }

        // =============================================
        // 5. LOG ADMIN ACCESS (Optional - 5% of requests)
        // =============================================
        $this->logAdminAccess($request, $user);

        // =============================================
        // 6. UPDATE LAST ACTIVITY
        // =============================================
        $this->updateLastActivity($user);

        return $next($request);
    }

    /**
     * Check user account status
     */
    private function checkAccountStatus($user): array|bool
    {
        // Check if user model has status field (using property_exists only)
        if (property_exists($user, 'status')) {
            $status = $user->status;

            if ($status === 'inactive' || $status === 0 || $status === '0') {
                return [
                    'success' => false,
                    'reason' => 'Account inactive',
                    'message' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে। প্রশাসকের সাথে যোগাযোগ করুন।'
                ];
            }

            if ($status === 'banned' || $status === '2') {
                return [
                    'success' => false,
                    'reason' => 'Account banned',
                    'message' => 'আপনার অ্যাকাউন্ট ব্লক করা হয়েছে। প্রশাসকের সাথে যোগাযোগ করুন।'
                ];
            }
        }

        // Check email verification (optional)
        if (property_exists($user, 'email_verified_at') && is_null($user->email_verified_at)) {
            return [
                'success' => false,
                'reason' => 'Email not verified',
                'message' => 'আপনার ইমেইল ভেরিফাই করা হয়নি। দয়া করে আপনার ইমেইল চেক করুন।'
            ];
        }

        return true;
    }

    /**
     * Check if user has allowed role
     */
    private function userHasAllowedRole($user, array $allowedRoles): bool
    {
        // Method 1: Using Spatie's hasRole (if available)
        if (method_exists($user, 'hasRole')) {
            foreach ($allowedRoles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
            return false;
        }

        // Method 2: Direct role property check
        $userRole = $user->role ?? null;

        if ($userRole && in_array($userRole, $allowedRoles)) {
            return true;
        }

        // Method 3: Check roles relationship (if exists)
        if (method_exists($user, 'roles') && $user->roles()->exists()) {
            $userRoles = $user->roles->pluck('name')->toArray();
            return !empty(array_intersect($allowedRoles, $userRoles));
        }

        return false;
    }

    /**
     * Get user's role name
     */
    private function getUserRole($user): string
    {
        if (method_exists($user, 'getRoleNames')) {
            $roles = $user->getRoleNames();
            return $roles->isNotEmpty() ? $roles->first() : 'unknown';
        }

        if (method_exists($user, 'hasRole') && property_exists($user, 'roles')) {
            return $user->roles->first()->name ?? 'unknown';
        }

        return $user->role ?? 'unknown';
    }

    /**
     * Check additional permissions (route-specific)
     */
    private function hasAdditionalPermissions($user, Request $request): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // Skip permission check for dashboard routes
        $routeName = $request->route()->getName();

        if (str_contains($routeName, 'dashboard') ||
            str_contains($routeName, 'profile') ||
            str_contains($routeName, 'logout') ||
            $routeName === 'admin.home') {
            return true;
        }

        // Check specific permission for the route using Spatie
        if (method_exists($user, 'hasPermissionTo') && $routeName) {
            // Convert route name to permission format
            $permission = str_replace('admin.', '', $routeName);

            // // Check if user has permission
            if (!$user->hasPermissionTo($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user is super admin
     */
    private function isSuperAdmin($user): bool
    {
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('super_admin');
        }

        if (method_exists($user, 'hasRole') && property_exists($user, 'roles')) {
            return $user->roles->contains('name', 'super_admin');
        }

        return ($user->role ?? '') === 'super_admin';
    }

    /**
     * Handle unauthorized access
     */
    private function handleUnauthorizedAccess($user, Request $request): Response
    {
        $role = $this->getUserRole($user);
        $redirectRoute = $this->roleRedirects[$role] ?? $this->roleRedirects['default'];

        // Check if route exists
        if (!\Illuminate\Support\Facades\Route::has($redirectRoute)) {
            $redirectRoute = 'admin.login';
        }

        // Set appropriate error message
        $message = $this->getUnauthorizedMessage($role);

        // Store the intended URL
        session()->put('url.intended', url()->current());

        return redirect()->route($redirectRoute)
            ->with('error', $message)
            ->with('alert-type', 'error');
    }

    /**
     * Get unauthorized message based on role
     */
    private function getUnauthorizedMessage(string $role): string
    {
        return match($role) {
            'customer' => 'আপনি একজন সাধারণ গ্রাহক। এই পৃষ্ঠাটি অ্যাক্সেস করার জন্য আপনার প্রশাসনিক অনুমতি নেই।',
            'delivery_man' => 'আপনি একজন ডেলিভারি ম্যান। এই পৃষ্ঠাটি শুধুমাত্র প্রশাসকদের জন্য।',
            'super_admin', 'admin', 'manager' => 'আপনার যথেষ্ট অনুমতি নেই এই পৃষ্ঠাটি অ্যাক্সেস করার জন্য।',
            default => 'আপনার এই পৃষ্ঠাটি অ্যাক্সেস করার অনুমতি নেই।'
        };
    }

    /**
     * Log unauthorized access attempts
     */
    private function logUnauthorizedAccess(Request $request, string $reason, $user = null): void
    {
        Log::warning('Unauthorized admin access attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'reason' => $reason,
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'user_role' => $user?->role ?? $this->getUserRole($user),
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Log successful admin access (sampled to reduce log spam)
     */
    private function logAdminAccess(Request $request, $user): void
    {
        // Only log 5% of requests to avoid log spam
        if (rand(1, 100) <= 5) {
            Log::info('Admin access granted', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role ?? $this->getUserRole($user),
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'timestamp' => now()->toDateTimeString()
            ]);
        }
    }

    /**
     * Update user's last activity timestamp
     */
    private function updateLastActivity($user): void
    {
        // Simply check if the property exists and update
        // We don't need to check database column existence here
        if (property_exists($user, 'last_activity_at')) {
            $user->update(['last_activity_at' => now()]);
        }

        // Update session last activity
        session(['last_activity' => now()]);
    }

    /**
     * Get allowed roles (public method for route definitions)
     */
    public static function getAllowedRoles(): array
    {
        return ['super_admin', 'admin', 'manager'];
    }
}
