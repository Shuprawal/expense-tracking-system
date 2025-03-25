<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        user::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin1234'),
            'role' => 'admin'

        ]);
        user::create([
            'first_name' => 'Ram',
            'last_name' => 'Ram',
            'username' => 'ram',
            'email' => 'ram@gmail.com',
            'password' => bcrypt('ram12345'),
            'role' => 'user'

        ]);

    }
}
