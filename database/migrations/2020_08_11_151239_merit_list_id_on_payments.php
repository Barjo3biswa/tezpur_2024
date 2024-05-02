<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MeritListIdOnPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payment_processings', function (Blueprint $table) {
            $table->bigInteger('merit_list_id')->nullable()->after("course_id");
        });
        Schema::table('online_payment_successes', function (Blueprint $table) {
            $table->bigInteger('merit_list_id')->nullable()->after("course_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_payment_processings', function (Blueprint $table) {
            $table->dropColumn('merit_list_id');
        });
        Schema::table('online_payment_successes', function (Blueprint $table) {
            $table->dropColumn('merit_list_id');
        });
    }
}
