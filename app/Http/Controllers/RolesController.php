<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_roles');
    }
    
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $groups = PermissionGroup::with('permissions')->get();

        // New array to hold groups and permissions with added name_label in Pascal Case
        $permissionsgroup = $groups->map(function ($group) {
            // Add a name_label property to each permission in Pascal Case
            $permissions = $group->permissions->map(function ($permission) {
                // Split by underscore, capitalize each piece, then join with spaces
                $words = explode('_', $permission->name);
                $capitalizedWords = array_map(function ($word) {
                    return Str::ucfirst($word);
                }, $words);
                $permission->name_label = implode(' ', $capitalizedWords);
            });

            // Assign the modified permissions back to the group
            $group->permissions = $permissions;
            return $group;
        });

        $permissionsgroup = collect(json_decode($permissionsgroup, false)); // false to get objects instead of arrays.

        return view('roles.create', compact('permissionsgroup'));
    }

    public function edit(Role $role)
    {
        // Get only the IDs of the current permissions
        $currentPermissionIds = $role->permissions->pluck('id');
        
        $groups = PermissionGroup::with('permissions')->get();

        // New array to hold groups and permissions with added name_label in Pascal Case
        $permissionsgroup = $groups->map(function ($group) {
            // Add a name_label property to each permission in Pascal Case
            $permissions = $group->permissions->map(function ($permission) {
                // Split by underscore, capitalize each piece, then join with spaces
                $words = explode('_', $permission->name);
                $capitalizedWords = array_map(function ($word) {
                    return Str::ucfirst($word);
                }, $words);
                $permission->name_label = implode(' ', $capitalizedWords);
            });

            // Assign the modified permissions back to the group
            $group->permissions = $permissions;
            return $group;
        });

        $permissionsgroup = collect(json_decode($permissionsgroup, false)); // false to get objects instead of arrays.

        return view('roles.edit', compact('role', 'currentPermissionIds','permissionsgroup'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
            'permissionsradio' => 'sometimes|array',
            'permissionsradio.*' => 'exists:permissions,id',
        ]);

        // Start a transaction
        DB::beginTransaction();
        try {
            // Create the role
            $role = Role::create([
                'name' => $validated['role_name'],
                'description' => $validated['description'] ?? null,
            ]);

            // Prepare permissions from checkboxes
            $checkboxPermissions = $validated['permissions'];

            // Extract and prepare permissions from radio buttons
            $radioPermissions = $request->input('permissionsradio', []);

            // Merge checkbox and radio permissions
            $allPermissions = array_merge($checkboxPermissions, array_values($radioPermissions));

            // Attach permissions to the role
            $role->permissions()->attach($allPermissions);

            // Commit the transaction
            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while creating the role.');
        }
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
            'permissionsradio' => 'sometimes|array',
            'permissionsradio.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $validated['role_name'],
                'description' => $validated['description'] ?? null,
            ]);

            $allPermissions = array_merge($validated['permissions'], array_values($request->input('permissionsradio', [])));
            $role->permissions()->sync($allPermissions); // Use sync to update permissions

            DB::commit();
            return redirect()->route('roles.create')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating role.');
        }
    }

    public function destroy($id)  {

        Role::whereId($id)->update([
            'deleted_at' => Carbon::now(),
           // 'deleted_by' => Auth::id(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Roles Deleted successfully.');

      
    }
}
