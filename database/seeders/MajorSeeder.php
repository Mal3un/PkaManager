<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $name =['Công nghệ thông tin','Dược','Điện - Điện tử','Quản trị kinh doanh','Du lịch'];
        $arr =[];
        foreach ($name as $value) {
            $arr[] = [
                'name' => $value,
            ];
        }
        Major::insert($arr);
    }
}
