<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourseIdOnlinePaymentsSuccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payment_successes', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->after("application_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_payment_successes', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });
    }
}
