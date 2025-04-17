<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin=User::where('username','admin')->first();
        $names=['Food','Rent','Study','Entertainment'];


        foreach ($names as $name){
            Category::create([
                'name'=>$name,
                'disabled'=>'no',
//                'date'=>Carbon::now(),
                'user_id'=>$admin->id,
            ]);
        }

    }
}
