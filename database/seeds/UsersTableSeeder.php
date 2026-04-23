<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'email' => 'gera@mail.com',
        ], [
            'name' => 'Gera',
            'email' => 'gera@mail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
        ]);
    }
}
