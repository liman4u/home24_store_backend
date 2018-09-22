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


$factory->define(App\Models\Product::class, function (Faker $faker) {


    $createdAt = \Carbon\Carbon::now();

    return [
        'name' => $faker->text(10),
        'description' => $faker->text(20),
        'price' => $faker->randomFloat(2,0,4),
        'quantity' => $faker->randomNumber(3),
        'created_at' => $createdAt,
        'updated_at' => $createdAt,
    ];

});
