<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contact = new Contact();
        $contact->name = 'Baptiste Flamand';
        $contact->email = 'baptisteflamand@gmail.com';
        $contact->phone_number = '0643243278';
        $contact->organization_id = 1;
        $contact->save();
        $folder = Folder::find(1);
        $contact->folders()->attach($folder->id);
        $contact->save();

        $contact = new Contact();
        $contact->name = 'Jean Dupont';
        $contact->email = 'jeandupont@gmail.com';
        $contact->phone_number = '0653672458';
        $contact->organization_id = 2;
        $contact->save();
        $folder = Folder::find(2);
        $contact->folders()->attach($folder->id);
        $contact->save();

        $contact = new Contact();
        $contact->name = 'Pierre Martin';
        $contact->email = 'pierremartin@gmail.com';
        $contact->phone_number = '0601324313';
        $contact->organization_id = 1;
        $contact->save();

        $folder = Folder::find(1);
        $contact->folders()->attach($folder->id);
        $contact->save();

        for ($i = 1; $i <= 50; $i++) {
            $contact = new Contact();
            $contact->name = 'CPAM' . $i;
            $contact->email = 'cpam'. $i . '@gmail.com';
            $contact->phone_number = 0601324313 + $i;
            $contact->organization_id = 1;
            $contact->save();
            $folder = Folder::find(1);
            $contact->folders()->attach($folder->id);
            $contact->save();
        }

        for ($i = 1; $i <= 50; $i++) {
            $contact = new Contact();
            $contact->name = 'Google' . $i;
            $contact->email = 'google'. $i . '@gmail.com';
            $contact->phone_number = 0601321367 + $i;
            $contact->organization_id = 2;
            $contact->save();
            $folder = Folder::find(2);
            $contact->folders()->attach($folder->id);
            $contact->save();
        }
    }
}
