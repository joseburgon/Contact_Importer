<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->name,        
        'birthday' => $faker->date('Ymd', 'now'),
        'phone' => $faker->e164PhoneNumber,
        'address' => $faker->address,
        'card' => $faker->creditCardNumber,
        'card_brand' => $faker->creditCardType,
        'email' => $faker->email,
    ];
});
