<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColumnSubjectIdInListPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('list_points', 'subject_id')) {
            Schema::table('list_points', function (Blueprint $table) {
                $table->foreignId('subject_id')->after('classe_id');
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
        Schema::table('list_points', function (Blueprint $table) {
            //
        });
    }
}
