<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'target_type',
        'type_name',
        'type_id'
    ];

    /**
     * Get the permissions that have this name and this target type
     *
     * @param string $name
     * @param string $targetType
     * @return mixed
     */
    public static function getPermissionsByNameAndTargetType(string $name, string $targetType) {
        return Permission::where('name', $name)
            ->where('target_type', $targetType);
    }

    /**
     * Get the roles associated with this permission
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id');
    }

    /**
     * Get the permission type associated
     *
     * @return MorphTo
     */
    public function typeable(): MorphTo
    {
        return $this->morphTo('type', 'type_name', 'type_id');
    }
}
