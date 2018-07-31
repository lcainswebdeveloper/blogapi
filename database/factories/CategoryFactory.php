<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'title' => $title = 'My Category',
        'slug' => slugify($title),
        'user_id' => 1
    ];
});

