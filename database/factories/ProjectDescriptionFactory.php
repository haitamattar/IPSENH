<?php

use App\ProjectDescription;
use Faker\Generator as Faker;

$factory->define(ProjectDescription::class, function (Faker $faker) {
    return [
        'project_id' => 1, // Default setting, override this when using the factory
        'order' => 0, // Default setting, override this when using the factory
        'type' => 'text', // Default setting, override this when using the factory
        'title' => $faker->realText(25),
        'description' => $faker->realText(1000, 2),
        'image_id' => null // Default setting, override this when using the factory
    ];
});
