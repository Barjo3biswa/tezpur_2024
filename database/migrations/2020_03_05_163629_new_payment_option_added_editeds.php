<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewPaymentOptionAddedEditeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->tinyInteger('re_payment')->default(0)->after("resubmit_allow");
            $table->float('re_payment_amount')->default(400.00)->after("re_payment");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn(["re_payment", "re_payment_amount"]);
        });
    }
}
