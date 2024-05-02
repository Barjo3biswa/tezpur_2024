<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OnlinePaymentProcess2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_payment_processings', function (Blueprint $table) {
            $table->dateTime('cron_checked_at')->nullable()->after("online_payment_successes_id");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_payment_processings', function (Blueprint $table) {
            $table->dropColumn('cron_checked_at');
        });
    }
}
