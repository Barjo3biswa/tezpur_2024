<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('merit_list_id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('student_id');
            $table->string('reason_from_list');
            $table->longText('reason');
            $table->string("by_id")->nullable();
            $table->string("by_type")->nullable();
            $table->string('status')->default("request_sent")->comment("request_sent, rejected, approved");
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
        Schema::dropIfExists('withdrawal_requests');
    }
}
