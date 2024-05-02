<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('receipt_no', 100);
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('application_id');
            $table->string('pay_method', 100);
            $table->string('transaction_id', 100)->nullable();
            $table->string('type', 100)->comment("admission, examination, semester_fee");
            $table->float('total', 8,2);
            $table->year('year', 4);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admission_receipts');
    }
}
