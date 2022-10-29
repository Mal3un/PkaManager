<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->smallInteger('class_mode')->default(1);
            $table->smallInteger('class_type')->default(1);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained();
            $table->foreignId('major_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
