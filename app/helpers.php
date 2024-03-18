<?php

    use App\Models\FolderPermission;
    use App\Models\Role;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;

    /**
     * Check if the auth user has a specific permission
     *
     * @param $permissionName
     * @param $targetName
     * @param $folders
     * @return bool
     */
    function can($permissionName, $targetName, $folders): bool
    {
        foreach ($folders as $folder) {
            $permission = FolderPermission::getPermissionByAttributes($permissionName, $targetName, $folder->id);
            if ($permission && Auth::user()->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the auth user can edit a user
     *
     * @param User $user: The user that should be edited
     * @return bool
     */
    function canDeleteUser(User $user): bool
    {
        return ($user->role && $user->role->isChildOrSubChildOf(Auth::user()->role)) || !$user->role;
    }

    /**
     * Check if the auth user can edit a user
     *
     * @param User $user: The user that should be edited
     * @return bool
     */
    function canEditUser(User $user): bool
    {
        return ($user->role && $user->role->isChildOrSubChildOf(Auth::user()->role)) || !$user->role;
    }

    /**
     * Check if the auth user has any sub role
     *
     * @return bool
     */
    function hasAnySubRole(): bool
    {
        return Auth::user()->role->with('childRoles') && Auth::user()->role->childRoles->isNotEmpty();
    }

?>