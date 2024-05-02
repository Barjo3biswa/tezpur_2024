<?php

use Doctrine\DBAL\Schema\Column;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamCenterCountryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_centers', function (Blueprint $table) {
            $table->bigInteger('country_id')->unsigned()->nullable()->after("pin");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_centers', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });
    }
}
