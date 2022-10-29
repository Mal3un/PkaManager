<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 12; $i <= 16; $i++){
            $arr[] = [
                'name' => 'K'. $i,
                'duration' => '20'.(6+$i).'/08/15',
            ];
        }
        Course::insert($arr);
    }
}
