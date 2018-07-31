<?php

use Faker\Generator as Faker;

$factory->define(App\BlogPost::class, function (Faker $faker) {
    return [
        'title' => $title = 'My Post',
        'slug' => slugify($title),
        'content' => '<h1>This is my content</h1>',
        'user_id' => 1
    ];
});

