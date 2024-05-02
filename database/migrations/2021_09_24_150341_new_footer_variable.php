<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewFooterVariable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->longText('footer_text')->nullable()->after("status");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->dropColumn('footer_text');
        });
    }
}
