<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrator;

class AdministratorSeeder extends Seeder
{
    public function run()
    {
        Administrator::create([
            'email' => 'admin@hellocse.com',
            'password' => \Hash::make('password123'), // Use a secure password
        ]);
    }
}
