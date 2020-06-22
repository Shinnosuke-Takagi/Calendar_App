<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use App\User;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'user_id' => fn() => factory(User::class)->create()->id,
        'name' => $faker->word(),
        'color' => $faker->colorName,
        'details' => $faker->sentence(5),
        'start' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'end' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'created_at' => $faker->datetime(),
        'updated_at' => $faker->datetime(),
    ];
});
