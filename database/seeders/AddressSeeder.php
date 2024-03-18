<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address = new Address();
        $address->id = 1;
        $address->street = '6 Rue Gaetan Rondeau';
        $address->city = 'Nantes';
        $address->postal_code = '44000';
        $address->save();

        $address = new Address();
        $address->id = 2;
        $address->street = 'Avenue Francois Mitterrand';
        $address->city = 'Nantes';
        $address->postal_code = '44200';
        $address->save();
    }
}
