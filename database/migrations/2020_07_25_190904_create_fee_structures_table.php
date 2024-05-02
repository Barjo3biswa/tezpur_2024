<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id')->unsigned();
            $table->integer('fee_head_id')->unsigned();
            $table->decimal('amount',20,2);
            $table->boolean('is_free')->comment('0:no,1:yes')->default(0);
            $table->softDeletes();
            $table->boolean('status')->default(1)->comment('0=inactive,1=active');
            $table->timestamps();
            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
            $table->foreign('fee_head_id')->references('id')->on('fee_heads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_structures');
    }
}
