<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Administrator',
        'email' => 'admin@admin.com.br',
        'password' => app('hash')->make('praquesenha'),
    ];
});

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'brand' => $faker->unique()->company,
        'description' => $faker->text(25),
        'value' => $faker->randomFloat(2, 1, 50),
        'qty_stock' => $faker->randomDigit,
    ];
});
