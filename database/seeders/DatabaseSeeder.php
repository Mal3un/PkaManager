<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Score;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        Student::factory(10)->create();
//        Teacher::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(MajorSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(TeacherSeeder::class);
//        $this->call(ClasseSeeder::class);
    }
}
