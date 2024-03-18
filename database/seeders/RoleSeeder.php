<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Folder;
use App\Models\FolderPermission;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::createRootRole('admin');

        if ($role1) {
            $folder = Folder::where('name', 'Saint-Nazaire')->first();
            $role1->givePermissionTo('create', Contact::class, $folder);
            $role1->givePermissionTo('read', Contact::class, $folder);
            $role1->givePermissionTo('edit', Contact::class, $folder);
            $role1->givePermissionTo('delete', Contact::class, $folder);

            $folder = Folder::where('name', 'Nantes')->first();
            $role1->givePermissionTo('create', Contact::class, $folder);
            $role1->givePermissionTo('read', Contact::class, $folder);
            $role1->givePermissionTo('edit', Contact::class, $folder);
            $role1->givePermissionTo('delete', Contact::class, $folder);
        }


        $role2 = $role1->createChildRole('Coordinatrice Saint-Nazaire');

        if ($role2) {
            $folder = Folder::where('name', 'Saint-Nazaire')->first();
            $role2->givePermissionTo('create', Contact::class, $folder);
            $role2->givePermissionTo('read', Contact::class, $folder);
            $role2->givePermissionTo('edit', Contact::class, $folder);
            $role2->givePermissionTo('delete', Contact::class, $folder);
        }
    }
}
