<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColumnMajorIdAndCourseIdInExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('exams', 'major_id')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->foreignId('major_id');
            });
        }
        if (!Schema::hasColumn('exams', 'course_id')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->foreignId('course_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            //
        });
    }
}
