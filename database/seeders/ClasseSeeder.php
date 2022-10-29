<?php

namespace Database\Seeders;

use App\Enums\ClassTypeEnum;
use App\Models\Classe;
use App\Models\Course;
use App\Models\Major;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr =[];
        $faker = Factory::create('vi_VN');
        $subject = Subject::query()->pluck('id')->toArray();
        $name= Subject::query()->pluck('name')->toArray();
        $coursename= Course::query()->pluck('name')->toArray();
        $teacher = Teacher::query()->pluck('id')->toArray();
        $major = Major::query()->pluck('id')->toArray();
        $course = Course::query()->pluck('id')->toArray();
        for($i = 1; $i <= 32; $i++){
            $arr[] = [
                'name' =>  $name[array_rand($name)] .' 1.' . $faker->randomElement([1,2,3,4,5]).'.'. $faker->randomElement([1,2,3]). ' - ' . $coursename[array_rand($coursename)],
                'class_type' => $faker->randomElement(ClassTypeEnum::getValues()),
                'subject_id' => $subject[array_rand($subject)],
                'teacher_id' => $teacher[array_rand($teacher)],
                'major_id' => $major[array_rand($major)],
                'course_id' => $course[array_rand($course)],
            ];

        }
        Classe::insert($arr);
    }
}
