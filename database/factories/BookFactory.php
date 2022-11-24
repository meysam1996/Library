<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    $publishers = App\Publisher::pluck('id')->toArray();
    $subjects = App\Subject::pluck('id')->toArray();
    return [
        'name' => $faker->name,
        'summary' => $faker->text,
        'description' => $faker->text(100),
        'printer_key' => $faker->numberBetween(1, 15),
        'serial_number' => $faker->unique()->numerify('######'),
        'publisher_id' =>$faker->randomElement($publishers),
        'subject_id' =>$faker->randomElement($subjects),
    ];
});
