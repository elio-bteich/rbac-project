<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_role_id',
    ];

    /**
     * The relationships that should be hidden when converting the model to an array or JSON.
     *
     * @var array
     */
    protected $hidden = [
        'childRoles'
    ];


    /**
     * Get the child roles associated with this role
     *
     * @return HasMany
     */
    public function childRoles(): HasMany
    {
        return $this->hasMany(Role::class, 'parent_role_id', 'id');
    }

    /**
     * Create a child role for this role
     *
     * @return mixed
     */
    public function createChildRole(string $name) {
        if (!Role::where(['name' => $name])->first()) {
            $role = Role::create(['name' => $name]);
            return $this->childRoles()->save($role);
        }
        return false;
    }

    /**
     * Create a role
     *
     * @param string $name
     * @param Role $parentRole
     * @return mixed
     */
    public static function createRole(string $name, Role $parentRole) {
        return Role::create(['name' => $name, 'parent_role_id' => $parentRole->id]);
    }

    /**
     * Create a root role
     *
     * @param string $name
     * @return mixed
     */
    public static function createRootRole(string $name) {
        return Role::create([
            'name' => $name,
            'parent_role_id' => null
        ]);
    }

    /**
     * Get the permissions allowed to be assigned to a Role
     *
     * @return Collection
     */
    public function getAllowedPermissions(): Collection
    {
        return $this->parentRole ? $this->parentRole->permissions : $this->permissions;
    }

    /**
     * Get the parents of a role including the role until the root role
     * the root role is the first element and so on and so forth
     *
     * @return Collection
     */
    public function getParentsUntilRoot(): Collection
    {
        $parents = new Collection();
        $currentRole = $this;

        while ($currentRole) {
            $parents->push($currentRole);
            $currentRole = $currentRole->parentRole;
        }

        return $parents->reverse();
    }

    /**
     * Get children and sub children of a role
     *
     * @return Collection
     */
    public function getAllChildren(bool $includeSelf=false): \Illuminate\Support\Collection
    {
        $children = collect();

        foreach ($this->childRoles as $childRole) {
            $children->push($childRole);
            $children = $children->merge($childRole->getAllChildren());
        }

        if ($includeSelf) {
            $children->add($this);
        }

        return $children;
    }

    /**
     * Give a permission to this role
     *
     * @param string $permissionName
     * @param string $targetType
     * @param Folder $folder
     * @return bool: true if the permission has been found and given, false otherwise
     */
    public function givePermissionTo(string $permissionName, string $targetType, Folder $folder): bool {

        $permission = FolderPermission::getPermissionByAttributes($permissionName, $targetType, $folder->id);

        if ($permission) {

            if ($this->parentRole &&
                !$this->parentRole->hasPermissionTo($permissionName, $targetType, $folder)) {
                echo("Parent role doesn't have the permission to "
                ."$permissionName $targetType in $folder->name!\n");
                return false;
            }

            $this->permissions()->attach($permission);
            return true;
        }

        echo("Permission $permissionName a $folder->name $targetType not found!\n");
        return false;
    }

    /**
     * Check if a role is a child of this role
     *
     * @param Role $role
     * @return bool
     */
    public function isChild(Role $role): bool
    {
        $currRole = $role;

        while ($currRole && !$currRole->is($this)) {
            $currRole = $currRole->parentRole;
        }

        return ($currRole !== $role) && ($currRole && $currRole->is($this));
    }

    /**
     * Check if a role is a child or sub child of this role
     *
     * @param Role $role
     * @return bool
     */
    public function isChildOrSubChildOf(Role $role): bool
    {
        return $role->getAllChildren()->contains('id', $this->id);
    }


    /**
     * Check if a permission is associated to this role
     *
     * @param Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions->contains($permission);
    }

    /**
     * Check if a permission is associated to this role
     *
     * @param string $permissionName
     * @param string $targetType
     * @param Folder $folder
     * @return bool
     */
    public function hasPermissionTo(string $permissionName, string $targetType, Folder $folder): bool
    {
        $permission = $this->permissions()
            ->where('name', $permissionName)
            ->where('target_type', $targetType)
            ->whereHas('typeable', function ($query) use ($folder) {
                $query->where('folder_id', $folder->id);
            })
            ->first();

        return $permission !== null;
    }

    /**
     * Get the parent role associated with this role
     *
     * @return BelongsTo
     */
    public function parentRole(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the permissions associated with this role
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    /**
     * Get the users associated with this role.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
