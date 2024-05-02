<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WithdrawSeatApplicable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->boolean('withdraw_seat_applicable')->default(false)->after("release_seat_applicable");
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
            $table->dropColumn('withdraw_seat_applicable');
        });
    }
}
