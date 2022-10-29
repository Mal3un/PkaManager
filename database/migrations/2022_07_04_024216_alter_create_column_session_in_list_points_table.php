<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCreateColumnSessionInListPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('list_points', 'session')) {
            Schema::table('list_points', function (Blueprint $table) {
                $table->smallInteger('session')->default(1);
            });
        }
        if (!Schema::hasColumn('scores', 'subject_id')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->foreignId('subject_id')->constrained();
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

    }
}
