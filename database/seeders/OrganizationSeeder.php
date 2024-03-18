<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organization = new Organization();
        $organization->id = 1;
        $organization->name = 'CPAM';
        $organization->email = 'cpam@gmail.com';
        $organization->phone_number = '0233235685';
        $organization->address_id = 1;
        $organization->save();

        $organization = new Organization();
        $organization->id = 2;
        $organization->name = 'Google';
        $organization->email = 'google@gmail.com';
        $organization->phone_number = '0277665544';
        $organization->address_id = 2;
        $organization->save();
    }
}
