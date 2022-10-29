<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Role;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $arr =[];
                for($i = 1; $i <= 3; $i++){
                    $arr[] = [
                        'type' => $i
                    ];

                }
                Role::insert($arr);
    }
}
