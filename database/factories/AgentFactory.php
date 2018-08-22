<?php

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

$factory->define(App\Models\Agent::class, function (Faker\Generator $faker) {
    return [
        'agent_name' => 'Agent ' . $faker->name,
        'agent_address' => 'Agent ' . $faker->address,
        'agent_license' => 'MST ' . $faker->randomNumber(),
        'status' => array_rand([AGENT_INACTIVE, AGENT_ACTIVE])
    ];

});
