<?php

use Faker\Generator as Faker;

$factory->define(App\UserInformation::class, function (Faker $faker) {
    return [
        'bio' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'profile_picture_id' => factory('App\File')->create()->id,
        'search_job' => $faker->boolean
    ];
});