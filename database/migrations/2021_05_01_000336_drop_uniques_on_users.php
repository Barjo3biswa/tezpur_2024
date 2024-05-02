<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUniquesOnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(["email"]);
            $table->dropUnique(["mobile_no"]);
            $table->unique(['session_id', "email"]);
            $table->unique(['session_id', "mobile_no"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique(["email"]);
            $table->unique(["mobile_no"]);
            $table->dropUnique(['session_id', "email"]);
            $table->dropUnique(['session_id', "mobile_no"]);
        });
    }
}
