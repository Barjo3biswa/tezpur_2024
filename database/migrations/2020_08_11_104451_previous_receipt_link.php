<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PreviousReceiptLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('previous_receipt_id')->nullable()->after("receipt_no");
            $table->unsignedBigInteger('previous_received_amount')->nullable()->after("previous_receipt_id");
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
            $table->dropColumn(['previous_receipt_id', "previous_received_amount"]);
        });
    }
}
