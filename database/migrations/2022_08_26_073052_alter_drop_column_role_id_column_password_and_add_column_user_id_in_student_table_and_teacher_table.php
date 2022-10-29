<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDropColumnRoleIdColumnPasswordAndAddColumnUserIdInStudentTableAndTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('students', 'role_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign('students_role_id_foreign');
                $table->dropColumn('role_id');
                $table->foreignId('user_id')->constrained();
            });
        }
        if (Schema::hasColumn('students', 'password')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropcolumn('password');
            });
        }

        if (Schema::hasColumn('teachers', 'role_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropForeign('teachers_role_id_foreign');
                $table->dropColumn('role_id');
                $table->foreignId('user_id')->constrained();
            });
        }
        if (Schema::hasColumn('teachers', 'password')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropcolumn('password');
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
        Schema::table('student_table_and_teacher', function (Blueprint $table) {
            //
        });
    }
}
