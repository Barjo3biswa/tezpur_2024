<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MeritListWaitingListFlagAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->boolean('selected_in_merit_list')->default(false)->comment("1 merit, 0 waiting");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->dropColumn('selected_in_merit_list');
        });
    }
}
