<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Borrow;
use Faker\Generator as Faker;

$factory->define(Borrow::class, function (Faker $faker) {
    return [
        'user_id' => $faker->unique()->numberBetween(1,\App\User::count()),
        'status' => $faker->randomElement(['reserved']),
        'deadline' => $faker->numberBetween(1,4)
    ];
});
