<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Log;

class FolderPermission extends Permission
{
    use HasFactory;

    protected $table = 'folder_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'folder_id',
    ];

    /**
     * Create a FolderPermission and a Permission instance and link them together
     *
     * @param array $attributes
     * @return mixed
     */
    public static function createPermission(array $attributes = [])
    {
        $permissionData = [
            'name' => $attributes['name'],
            'target_type' => $attributes['target_type'],
        ];

        if (isset($attributes['name']) && isset($attributes['target_type']) && isset($attributes['folder_id'])) {
            $permission = FolderPermission::getPermissionByAttributes(
                $attributes['name'],
                $attributes['target_type'],
                $attributes['folder_id']
            );

            if (!$permission) {
                $permission = Permission::create($permissionData);

                $folderPermission = FolderPermission::create([
                    'folder_id' => $attributes['folder_id'],
                ]);

                $folderPermission->permission()->save($permission);
            }else {
                echo("Permission already exists!\n");
            }
        }

        return $folderPermission ?? null;
    }

    /**
     * Get the permission that have all these attributes
     *
     * @param string $name
     * @param string $targetType
     * @param int $folderId
     * @return mixed
     */
    public static function getPermissionByAttributes(string $name,
                                                     string $targetType,
                                                     int $folderId)
    {
        $permissions = Permission::getPermissionsByNameAndTargetType($name, $targetType);
        return $permissions->whereHas('typeable', function ($query) use ($folderId) {
            $query->where('folder_id', $folderId);
        })->first();
    }

    /**
     * Get the permissions that have all these attributes
     *
     * @param string $name
     * @param string $targetType
     * @param array $folderIds
     * @return mixed
     */
    public static function getPermissionsByAttributes(string $name,
                                                     string $targetType,
                                                     array $folderIds)
    {
        $permissions = Permission::getPermissionsByNameAndTargetType($name, $targetType);

        return $permissions->whereHas('typeable', function ($query) use ($folderIds) {
            $query->whereIn('folder_id', $folderIds);
        })->get();

    }

    /**
     * Get the folder associated with this permission.
     *
     * @return BelongsTo
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Get the base permission to which this permission type is associated
     *
     * @return MorphOne
     */
    public function permission(): MorphOne
    {
        return $this->morphOne(Permission::class, 'typeable', 'type_name', 'type_id');
    }
}
