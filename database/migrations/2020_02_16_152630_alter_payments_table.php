<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payment_processings', function (Blueprint $table) {
            $table->string('payment_type', 100)->nullable()->default('application')->after("payment_done")->comment("application, admission, examination");
        });
        Schema::table('online_payment_successes', function (Blueprint $table) {
            $table->string('payment_type', 100)->nullable()->default('application')->after("status")->comment("application, admission, examination");
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
            $table->dropColumn('payment_type');
        });
        Schema::table('online_payment_successes', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });
    }
}
