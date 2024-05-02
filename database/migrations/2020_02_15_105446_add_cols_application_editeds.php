<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsApplicationEditeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->string('guardian_name')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('mother_income')->after('mother_occupation')->nullable();
            $table->string('father_income')->after('father_occupation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            //
        });
    }
}
