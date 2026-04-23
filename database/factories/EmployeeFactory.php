<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'employee_name' => $faker->name(),
        'company_id'=> $faker->numberBetween(1,3),
        'department_id' => $faker->numberBetween(1,8),
        'job_title'=>$faker->jobTitle()
    ];
});
