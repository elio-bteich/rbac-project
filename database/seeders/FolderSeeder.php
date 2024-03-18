<?php

namespace Database\Seeders;

use App\Models\Folder;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $folder = new Folder();
        $folder->id = 1;
        $folder->name = 'Saint-Nazaire';
        $folder->save();

        $folder = new Folder();
        $folder->id = 2;
        $folder->name = 'Nantes';
        $folder->save();
    }
}
