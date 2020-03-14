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
            'email' => 'usertest@mail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
