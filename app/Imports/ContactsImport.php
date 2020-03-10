<?php

namespace App\Imports;

use App\Contact;
use Maatwebsite\Excel\Concerns\ToModel;

class ContactsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contact([
            //
            'name' => $row['name'],
            'birthday' => $row['birthday'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'card' => $row['card'],
            'card_brand' => $row['card_brand'],
            'email' => $row['email'],
        ]);
    }
}
