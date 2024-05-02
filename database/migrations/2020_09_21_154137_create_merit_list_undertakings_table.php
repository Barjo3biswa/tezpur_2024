<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeritListUndertakingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merit_list_undertakings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('merit_list_id');
            $table->unsignedBigInteger('application_id');
            $table->string('undertaking_link');
            $table->string('destination_path');
            $table->string('attachment_type');
            $table->text('remark_by_admin')->nullable();
            $table->string('status')->comment("pending","accepted", "rejected")->default("pending");
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
        Schema::dropIfExists('merit_list_undertakings');
    }
}
