<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleName = ['user', 'admin'];
        foreach ($roleName as $role) {
           $roles[$role]= Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::where('username', 'admin')->first();
        $user =User::where('username', 'ram')->first();
        $admin->roles()->attach($roles['admin']);
        $user->roles()->attach($roles['user']);
    }
//    public function run(): void
//    {
//        $roleName = ['user', 'admin'];
//        foreach ($roleName as $role) {
//            $roles[$role]= Role::firstOrCreate(['name' => $role]);
//        }
//
//        $user = User::where('username', 'admin')->first();
////        $user =User::where('username', 'ram')->first();
//
////        $admin->roles()->attach($roles['admin']);
//        $user->roles()->attach($roles['admin']);
//    }
}
