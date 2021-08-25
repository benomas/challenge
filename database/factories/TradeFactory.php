<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Trade;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

$factory->define(Trade::class, function (Faker $faker) {
    return [
        'type'      => $faker->randomElement(['buy', 'sell']),
        'user_id'   => $faker->numberBetween(1, 100),
        'symbol'    => strtoupper(Str::random(3)),
        'shares'    => $faker->numberBetween(10, 30),
        'price'     => $faker->randomNumber(),
        'timestamp' => Carbon::createFromTimestamp($faker->unixTime)
    ];
});
