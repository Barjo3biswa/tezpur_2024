<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeApplicationAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            //
            $table->string('physics_mark')->nullable();
            $table->string('chemistry_mark')->nullable();
            $table->string('mathematics_mark')->nullable();
            $table->string('english_mark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            //
        });
    }
}
