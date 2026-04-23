<?php

use Illuminate\Database\Seeder;
use App\Number;

class NumbersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Number::class, 20)->create();
    }
}
