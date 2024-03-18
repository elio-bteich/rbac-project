<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            AddressSeeder::class,
            OrganizationSeeder::class,
            FolderSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ContactSeeder::class,
            PermissionTargetSeeder::class
        ];
        foreach ($seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
