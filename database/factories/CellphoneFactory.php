<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cellphone;
use Faker\Generator as Faker;

$factory->define(Cellphone::class, function (Faker $faker) {
    return [
        'model' => $faker->colorName(),
        "imei" => $faker->numberBetween(100000000,99999999),
        "brand" => $faker->company(),
        "status" => 0,
        "company_id" => $faker->numberBetween(1,3),
        "department_id" => $faker->numberBetween(1,8)
    ];
});
