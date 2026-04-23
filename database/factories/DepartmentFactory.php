<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Department;
use Faker\Generator as Faker;

$factory->define(Department::class, function (Faker $faker) {
    return [
        'department_name'=> $faker->unique()->randomElement([
            'ventas',
            'administracion',
            'mantenimiento',
            'it',
            'produccion',
            'diseño',
            'impresion',
            'proyectos',
            'finanzas',
            'gerencia',
            'mediageeks',
            'mediatech',
            'familiar',
            'bodega',
            'digitales',
            'presidencia'
            ]),
    ];
});
