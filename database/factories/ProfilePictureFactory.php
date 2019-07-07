<?php

use Faker\Generator as Faker;

use App\File;


$factory->define(App\File::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'path' =>  $faker->imageUrl(640, 640, 'people',true),
    ];
});
