<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index(Request $request)
    {
        // authorizeAccess('role_access');

        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 20);

        $query = Role::query()->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $roles = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);

        if ($request->ajax()) {
            $html = view('admin.roles.partials.table', compact('roles'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => [
                    'total' => $roles->total(),
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                ]
            ]);
        }

        return view('admin.roles.index', compact('roles', 'search', 'perPage'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request): JsonResponse
    {
        // authorizeAccess('role_create');

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'guard_name' => 'nullable|string|max:255',
            ]);

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => $validated['guard_name'] ?? 'web',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'data' => $role
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Role store failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role'
            ], 500);
        }
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id): JsonResponse
    {
        // authorizeAccess('role_edit');

        try {
            $role = Role::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
                'guard_name' => 'nullable|string|max:255',
            ]);

            $role->update([
                'name' => $validated['name'],
                'guard_name' => $validated['guard_name'] ?? 'web',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'data' => $role
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Role update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role'
            ], 500);
        }
    }

    /**
     * Delete the specified role
     */
    public function destroy($id): JsonResponse
    {
        // authorizeAccess('role_delete');

        try {
            $role = Role::findOrFail($id);

            // Prevent deleting super_admin role
            if ($role->name === 'super_admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete super_admin role'
                ], 422);
            }

            // Check if role has users
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role that has users assigned'
                ], 422);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Role delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role'
            ], 500);
        }
    }

    /**
     * Get role for editing
     */
    public function edit($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $role
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }
    }

     /**
     * Show role permissions page
     */
    public function permissions($id)
    {
        // authorizeAccess('role_access');

        $role = Role::findOrFail($id);

        // এখন orderBy('group_name') কাজ করবে
        $permissions = Permission::orderBy('group_name')
            ->orderBy('name')
            ->get();

        // অথবা আপনার স্কোপ ব্যবহার করুন
        // $permissions = Permission::orderByGroup()->get();

        $rolePermissions = $role->permissions()->pluck('name')->toArray();

        $permissionsByGroup = $permissions->groupBy('group_name');

        return view('admin.roles.permissions', compact('role', 'permissionsByGroup', 'rolePermissions'));
    }

    /**
     * Sync role permissions
     */
    public function syncPermissions(Request $request, $id): JsonResponse
    {
        // authorizeAccess('role_edit');

        try {
            $role = Role::findOrFail($id);

            $validated = $request->validate([
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            $permissions = $validated['permissions'] ?? [];
            $role->syncPermissions($permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permissions synced successfully',
                'permissions_count' => count($permissions)
            ]);

        } catch (\Exception $e) {
            Log::error('Permission sync failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync permissions'
            ], 500);
        }
    }
}
