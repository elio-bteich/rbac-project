<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check if a role can add a sub role to a specific role
     *
     * @param Role $role: Role to which we want to add a sub role
     * @return bool
     */
    public function canAddSubRoleTo(Role $role): bool
    {
        $user_role = Auth::user()->role;
        return $user_role->isChild($role) || $user_role->is($role);
    }

    /**
     * Check if a role can delete a specific role
     *
     * @param Role $role: Role which we want to delete
     * @return bool
     */
    public function canDeleteRole(Role $role): bool
    {
        $user_role = Auth::user()->role;
        return $user_role->isChild($role);
    }

    /**
     * Check if a role can modify a specific role
     *
     * @param Role $role: Role which we want to modify
     * @return bool
     */
    public function canModifyRolePermissionsOf(Role $role): bool
    {
        $user_role = Auth::user()->role;
        return $user_role->isChild($role);
    }

    /**
     * Get the users associated with a specific role
     *
     * @param $role
     * @return Application|Factory|View
     */
    public function getUsersByRole(Role $role)
    {
        $users = $role->users;

        return view('admin.users-list', compact('users'));
    }

    /**
     * Recursive function to fetch roles and their relative depth.
     * Role with a depth 0 is the higher one between the roles fetched.
     *
     * @param Role $role
     * @param int $depth
     * @return \Illuminate\Support\Collection
     */
    private function getRolesToDelete(Role $role, $depth = 0): \Illuminate\Support\Collection
    {
        $roles = collect([['role' => $role, 'depth' => $depth]]);

        foreach ($role->childRoles as $childRole) {
            $roles = $roles->merge($this->getRolesToDelete($childRole, $depth + 1));
        }

        return $roles;
    }

    /**
     * Get the permissions of a role
     *
     * @param Role $role
     * @return Application|Factory|View
     */
    public function getPermissions(Role $role)
    {
        $permissions = $role->permissions;
        return view('permissions.permissions-list', compact('permissions'));
    }

    /**
     * Render the permissions to modify
     *
     * @param Role $role
     * @return string
     */
    public function getPermissionsToModify(Role $role): string
    {
        $permissions = $role->getAllowedPermissions();
        return view('roles.modify-permissions-form', compact('permissions', 'role'))->render();
    }

    /**
     * Show the form for creating a new role.
     *
     * @return View
     */
    public function create()
    {
        $roles = Role::all();

        return view('roles.create', compact('roles'));
    }

    /**
     * Store a newly created role in storage
     *
     * @param Request $request: the role creation request
     * @return JsonResponse: the redirection after the request process
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'parent_role_id' => 'nullable|exists:roles,id'
        ]);

        $name = $request->input('name');

        if ($request->input('parent_role_id') === null) {
            Role::createRootRole($name);
        } else {
            $parentRole = Role::find($request->input('parent_role_id'));
            if ($parentRole) {
                Role::createRole($name, $parentRole);
            } else {
                return new JsonResponse(['error' => 'Parent role has not been found!'], 400);
            }
        }

        $roles = Auth::user()->role ? Auth::user()->role->getAllChildren() : null;
        $currentRole = Auth::user()->role;
        if ($currentRole) {
            $currentRole->parent_role_id = null;
        }
        $roles->add($currentRole);

        $rolesTree = $this::transformToJSTreeFormat($roles);

        return new JsonResponse($rolesTree);
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param Role $role
     * @return View
     */
    public function edit(Role $role): View
    {
        $allChildren = $role->getAllChildren();

        $excludeIds = $allChildren->pluck('id')->push($role->id)->toArray();

        $roles = Role::all()->except($excludeIds);

        return view('roles.edit', compact('role', 'roles'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param Request $request
     * @param  Role  $role
     * @return RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id),
            ],
            'parent_role' => '',
        ]);

        $parentRole = Role::find($request->input('parent_role'));

        if ($request->input('parent_role') !== 'root') {
            if (!$parentRole || $parentRole === $role) {
                return redirect()->route('roles.edit', $role)->with('error', 'Parent role has not been found!');
            }

            if ($role->isChild($parentRole)) {
                return redirect()->route('roles.edit', $role)->with('error', 'Parent role is already a child or sub-child of this role!');
            }

            $role->parent_role_id = $request->input('parent_role');

        } else {

            $role->parent_role_id = null;
        }

        $role->name = $request->input('name');

        $role->save();

        return redirect()->route('admin.home')->with('success', 'Role updated successfully!');
    }

    /**
     * Update the permissions of the role
     *
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function updatePermissions(Request $request, Role $role)
    {
        if ($role->parentRole) {
            $allPermissionIds = $role->parentRole->permissions->pluck('id')->toArray();

            $selectedPermissionIds = $request->input('permissions', []);
            $unselectedPermissionIds = array_diff($allPermissionIds, $selectedPermissionIds);

            $this->updatePermissionsRecursively($role, $unselectedPermissionIds);

            $role->permissions()->sync($selectedPermissionIds);
        } else {
            return redirect()->back()->with('error', 'Cannot update root permissions.');
        }
        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

    protected function updatePermissionsRecursively(Role $role, array $unselectedPermissionIds)
    {
        // Update permissions for the current role
        $role->permissions()->detach($unselectedPermissionIds);

        // Traverse sub-roles and update permissions recursively
        foreach ($role->childRoles as $subRole) {
            $this->updatePermissionsRecursively($subRole, $unselectedPermissionIds);
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  Role  $role
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        // Fetch roles and their dependencies
        $rolesToDelete = $this->getRolesToDelete($role);

        // Sort roles in reverse order based on dependencies
        $rolesToDelete = $rolesToDelete->sortByDesc('depth');


        // Delete roles and their corresponding database records
        DB::beginTransaction();
        try {
            foreach ($rolesToDelete as $roleToDelete) {
                $roleToDelete['role']->delete();
            }
            DB::commit();

            $roles = Role::all();
            $rolesTree = $this->transformToJSTreeFormat($roles);

            return new JsonResponse(['success' => $rolesTree]);

        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse(['error' => 'An error occurred while deleting the role.']);
        }
    }

    /**
     * Transform a collection of Roles to a JS Tree array format
     *
     * @param Collection $roles
     * @return array: JS tree array
     */
    public static function transformToJSTreeFormat(Collection $roles): array
    {
        $rolesTree = [];
        foreach ($roles as $role) {
            $roleData = [
                'id' => $role->id,
                'parent' => $role->parent_role_id ?: '#',
                'text' => $role->name,
            ];
            $rolesTree[] = $roleData;
        }
        return $rolesTree;
    }
}
