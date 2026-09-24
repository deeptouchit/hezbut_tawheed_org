<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
  public function index(Request $request)
{
    $search = $request->get('search', '');
    $group = $request->get('group', '');
    $perPage = $request->get('per_page', 20);

    $query = Permission::query()
        ->orderBy('id', 'desc');  // শুধু id desc

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('group_name', 'like', "%{$search}%");
        });
    }

    if ($group) {
        $query->where('group_name', $group);
    }

    $permissions = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);

    $groups = Permission::select('group_name')
        ->distinct()
        ->whereNotNull('group_name')
        ->pluck('group_name')
        ->toArray();

    if ($request->ajax()) {
        $html = view('admin.permissions.partials.table', compact('permissions'))->render();
        return response()->json([
            'success' => true,
            'html' => $html,
            'pagination' => [
                'total' => $permissions->total(),
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
            ]
        ]);
    }

    return view('admin.permissions.index', compact('permissions', 'search', 'group', 'perPage', 'groups'));
}

    /**
     * Store a newly created permission
     */
    public function store(Request $request): JsonResponse
    {
        // authorizeAccess('permission_create');

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name',
                'group_name' => 'required|string|max:255',
            ]);

            $permission = Permission::create([
                'name' => $validated['name'],
                'group_name' => $validated['group_name'],
                'guard_name' => 'web',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully',
                'data' => $permission
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Permission store failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create permission'
            ], 500);
        }
    }

    /**
     * Update permission
     */
    public function update(Request $request, $id): JsonResponse
    {
        // authorizeAccess('permission_edit');

        try {
            $permission = Permission::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name,' . $id,
                'group_name' => 'required|string|max:255',
            ]);

            $permission->update([
                'name' => $validated['name'],
                'group_name' => $validated['group_name'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'data' => $permission
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Permission update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permission'
            ], 500);
        }
    }

    /**
     * Delete permission
     */
    public function destroy($id): JsonResponse
    {
        // authorizeAccess('permission_delete');

        try {
            $permission = Permission::findOrFail($id);

            if ($permission->roles()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete permission that is assigned to roles'
                ], 422);
            }

            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Permission delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete permission'
            ], 500);
        }
    }

    /**
     * Get permission for editing
     */
    public function edit($id): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }
    }

    /**
     * Get all permission groups (for dropdown)
     */
    public function getGroups(): JsonResponse
    {
        $groups = Permission::select('group_name')
            ->distinct()
            ->whereNotNull('group_name')
            ->pluck('group_name')
            ->toArray();

        return response()->json([
            'success' => true,
            'groups' => $groups
        ]);
    }
}
