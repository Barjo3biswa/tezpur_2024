<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_heads', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->boolean('applicable_on')->comment('1=at the time of admission, 2 = on every semester/year');
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
        Schema::dropIfExists('fee_heads');
    }
}
