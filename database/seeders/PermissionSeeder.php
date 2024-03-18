<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Folder;
use App\Models\FolderPermission;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        FolderPermission::createPermission([
            'name' => 'create',
            'target_type' => Contact::class,
            'folder_id' => 1
        ]);

        FolderPermission::createPermission([
            'name' => 'create',
            'target_type' => Contact::class,
            'folder_id' => 2
        ]);

        FolderPermission::createPermission([
            'name' => 'read',
            'target_type' => Contact::class,
            'folder_id' => 1
        ]);

        FolderPermission::createPermission([
            'name' => 'read',
            'target_type' => Contact::class,
            'folder_id' => 2
        ]);

        FolderPermission::createPermission([
            'name' => 'edit',
            'target_type' => Contact::class,
            'folder_id' => 1
        ]);

        FolderPermission::createPermission([
            'name' => 'delete',
            'target_type' => Contact::class,
            'folder_id' => 1
        ]);

        FolderPermission::createPermission([
            'name' => 'edit',
            'target_type' => Contact::class,
            'folder_id' => 2
        ]);

        FolderPermission::createPermission([
            'name' => 'delete',
            'target_type' => Contact::class,
            'folder_id' => 2
        ]);
    }
}
