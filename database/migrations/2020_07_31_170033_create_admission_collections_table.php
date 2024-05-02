<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_collections', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('receipt_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('application_id');
                $table->unsignedBigInteger('fee_head_id');
                $table->unsignedBigInteger('fee_id');
                $table->float('amount', 8, 2);
                $table->float('free_amount', 8, 2);
                $table->tinyInteger('is_free')->default(0);
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
        Schema::dropIfExists('admission_collections');
    }
}
