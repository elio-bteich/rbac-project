<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['role', 'permissions'];

    /**
     * Assigns a role to a user
     *
     * @param string $name
     * @return bool: true if the assignment succeeded, false otherwise
     */
    public function assignRole(string $name): bool
    {
        $role = Role::where(
            ['name' => $name]
        )->first();

        if ($role) {
            $role->users()->save($this);
            return true;
        }

        echo("Role not found!\n");
        return false;
    }

    /**
     * Get the authentications associated with this user.
     *
     * @return HasMany
     */
    public function authentications(): HasMany
    {
        return $this->hasMany(UserAuthentication::class);
    }

    /**
     * Get the different folders of a permission assigned to this user
     *
     * @param string $permissionName
     * @param string $targetModel
     * @return array
     */
    public function getFoldersOfPermission(string $permissionName, string $targetModel) {
        $folders = [];

        foreach ($this->permissions as $permission) {
            if ($permission->name === $permissionName) {
                $folders[] = $permission->typeable->folder;
            }
        }

        return $folders;
    }

    /**
     * Check if user has this permission
     *
     * @param Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions->contains($permission);
    }

    /**
     * Check if the user has this role
     *
     * @param string $roleName
     * @return bool: true if user has this role, false otherwise
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Get the permissions of the user
     *
     * @return HasManyThrough
     */
    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, RoleHasPermissions::class, 'role_id', 'id', 'role_id', 'permission_id');
    }

    /**
     * Get the role associated with this user.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
