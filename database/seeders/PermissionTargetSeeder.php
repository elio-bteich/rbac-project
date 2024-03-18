<?php

namespace Database\Seeders;

use App\Models\PermissionTarget;
use Illuminate\Database\Seeder;

class PermissionTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PermissionTarget::create([
            'type' => 'App\Models\Contact',
            'name' => 'Contact'
        ]);
    }
}
