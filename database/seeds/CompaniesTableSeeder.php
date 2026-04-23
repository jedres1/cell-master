<?php

use Illuminate\Database\Seeder;
use App\Company;
class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create(['company_name' => 'PUBLIMOVIL']);
        Company::create(['company_name' => 'PUBLIMAGEN']);
        Company::create(['company_name' => 'URBMAN']);
    }
}
