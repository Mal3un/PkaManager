<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterChangeColumnStudentsIdToNullaberInListPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('list_points', 'students_id')) {
            Schema::table('list_points', function (Blueprint $table) {
                $table->foreignId('students_id')->nullable()->change();
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
        Schema::table('nullaber_in_list_points', function (Blueprint $table) {
            //
        });
    }
}
