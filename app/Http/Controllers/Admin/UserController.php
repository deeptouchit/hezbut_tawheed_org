<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Email verification filter
        if ($request->filled('email_verified')) {
            if ($request->email_verified == 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->email_verified == 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Per page
        $perPage = $request->get('per_page', 20);
        if ($perPage == 'all') {
            $users = $query->latest()->get();
        } else {
            $users = $query->latest()->paginate((int)$perPage);
        }

        // For AJAX request
        if ($request->ajax()) {
            $html = view('admin.users.partials.table', compact('users'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'email_verified_at' => $request->has('verify_email') ? now() : null,
            ]);

            $user->assignRole($request->role);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'ইউজার সফলভাবে তৈরি করা হয়েছে');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'ইউজার তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->getRoleNames()->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            DB::beginTransaction();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $user->syncRoles([$request->role]);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'ইউজার সফলভাবে আপডেট করা হয়েছে');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'ইউজার আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'আপনি নিজের অ্যাকাউন্ট ডিলিট করতে পারবেন না'
                    ], 403);
                }
                return redirect()->back()->with('error', 'আপনি নিজের অ্যাকাউন্ট ডিলিট করতে পারবেন না');
            }

            $user->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ইউজার সফলভাবে মুছে ফেলা হয়েছে'
                ]);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'ইউজার সফলভাবে মুছে ফেলা হয়েছে');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ইউজার মুছে ফেলতে ব্যর্থ হয়েছে'
                ], 500);
            }
            return redirect()->back()->with('error', 'ইউজার মুছে ফেলতে ব্যর্থ হয়েছে');
        }
    }

    /**
     * Update user status (AJAX).
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'আপনি নিজের স্ট্যাটাস পরিবর্তন করতে পারবেন না'
                ], 403);
            }

            $user->status = $request->status;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে'
            ], 500);
        }
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $userIds = json_decode($request->user_ids);

        // Remove current user from deletion
        $userIds = array_diff($userIds, [auth()->id()]);

        if (empty($userIds)) {
            return redirect()->back()->with('error', 'আপনি নিজের অ্যাকাউন্ট ডিলিট করতে পারবেন না');
        }

        User::whereIn('id', $userIds)->delete();

        return redirect()->back()->with('success', count($userIds) . ' টি ইউজার সফলভাবে মুছে ফেলা হয়েছে');
    }

    /**
     * Export users.
     */
    public function export(Request $request)
    {
        // Export logic here
        return redirect()->back()->with('success', 'এক্সপোর্ট ফিচার শীঘ্রই আসছে');
    }
}
