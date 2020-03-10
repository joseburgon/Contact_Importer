<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'name' => 'User Test',
            'email' => 'user.test.12@mail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
