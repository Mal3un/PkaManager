<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr =[];
        $arr[]=[
            'username'=>'10',
            'password'=>bcrypt('10'),
            'role_id'=>3,
            'avatar'=> "images/avatar/role3.png"
        ];

        for($i = 1; $i <= 16; $i++){
            $arr[] = [
                'username' => 100+$i,
                'password' => bcrypt(100+$i),
                'avatar'=> "images/avatar/role2.png",
                'role_id' => 2,
            ];
        }
        for($i = 1; $i <= 500; $i++){
            $arr[] = [
                'username' => 100100 + $i,
                'password' => bcrypt(100100 + $i),
                'avatar'=> "images/avatar/role1.png",
                'role_id' => 1,
            ];
        }
        User::insert($arr);
    }
}
