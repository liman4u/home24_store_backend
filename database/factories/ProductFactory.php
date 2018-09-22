<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

function randDate()
{
    return \Carbon\Carbon::now()
        ->subDays(rand(1, 100))
        ->subHours(rand(1, 23))
        ->subMinutes(rand(1, 60));
}

$factory->define(App\Models\Product::class, function (Faker $faker) {


    $createdAt = randDate();

    return [
        'name' => $faker->text(10),
        'description' => $faker->text(20),
        'price' => $faker->randomNumber(2),
        'quantity' => $faker->randomNumber(3),
        'created_at' => $createdAt,
        'updated_at' => $createdAt,
    ];

});
