<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UndertakingForBtechStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_list_undertakings', function (Blueprint $table) {
            $table->string('doc_name', 100)->nullable()->default("undertaking");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merit_list_undertakings', function (Blueprint $table) {
            $table->dropColumn('doc_name');
        });
    }
}
