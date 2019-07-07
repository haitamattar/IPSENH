<?php

use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => 'Test project by ' . $faker->domainName,
        'header_image_id' => factory('App\File')->create([
            'path' =>  $faker->imageUrl(1080, 250, 'nature',true)
        ])->id,
        'description' => $faker->realText(500),
    ];
});
