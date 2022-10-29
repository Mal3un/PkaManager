<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Course;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr =[];
        $faker = \Faker\Factory::create('vi_VN');
        $course = Course::query()->pluck('id')->toArray();
        $major = Major::query()->pluck('id')->toArray();
        $user = User::query()->where('role_id','=',1)->pluck('id')->toArray();
        for($i = 1; $i <= 500; $i++){
            $arr[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'birthdate' => $faker->date($format = 'Y-m-d', $max = '2004-01-01'),
                'address' => $faker->address. ' - ' . $faker->city,
                'email' => (100100 + $i) . "@st.phenikaa-uni.edu.vn",

                'gender' =>  $faker->randomElement([1,2,3]),
                'user_id' => $user[$i-1],
                'course_id' => $course[array_rand($course)],
                'major_id' => $major[array_rand($major)],
            ];

        }
        Student::insert($arr);

    }
}
