<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAuthentication;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'admin';
        $user->email = 'admin@gmail.com';
        $user->assignRole('admin');
        $user->save();

        $user->authentications()->save(UserAuthentication::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => Carbon::now(),
            'remember_token' => null
        ]));

        for ($i = 1; $i <= 100; $i++) {
            $user = new User;
            $user->name = 'Coordinatrice ' . $i;
            $user->email = 'Coordinatrice' . $i . '@gmail.com';
            $user->assignRole('Coordinatrice Saint-Nazaire');
            $user->save();

            $user->authentications()->save(UserAuthentication::create([
                'email' => 'coordinatrice' . $i . '@example.com',
                'password' => Hash::make('newpassword'),
                'email_verified_at' => Carbon::now(),
                'remember_token' => null
            ]));
        }
    }
}
