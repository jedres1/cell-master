<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AssignmentCellphoneEmployee;
use Faker\Generator as Faker;

$factory->define(AssignmentCellphoneEmployee::class, function (Faker $faker) {
    return [
        "cellphone_id" => $faker->numberBetween(1,20),
        "employee_id" => $faker->numberBetween(1,20),
        "status" => $faker->numberBetween(0,1)
    ];
});
