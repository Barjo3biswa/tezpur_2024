<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('course_id')->unsigned();
            $table->integer('admission_category_id')->unsigned();
            $table->boolean('practical')->comment('0=without practical,1=with practical')->default(0);
            $table->string('type',100)->comment('type=admission,examination')->default('admission');
            $table->string('financial_year',50);
            $table->year('year');
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('admission_category_id')->references('id')->on('admission_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
