<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(App\UserSkill::class, function (Faker $faker) {
    return [
        'user_id'=>$faker->user_id,
        'skill_id'=>$faker->skill_id
    ];
});