<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => 'Lewis Cains',
        'email' => 'lewis@lcainswebdeveloper.co.uk',
        'password' => bcrypt('123456'), // secret
        'remember_token' => str_random(10),
    ];
});

