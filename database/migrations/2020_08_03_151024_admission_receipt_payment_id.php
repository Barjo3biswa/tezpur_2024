<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdmissionReceiptPaymentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('online_payment_id')->after("application_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->dropColumn('"online_payment_id');
        });
    }
}
