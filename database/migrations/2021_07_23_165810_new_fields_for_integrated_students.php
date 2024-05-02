<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewFieldsForIntegratedStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            $table->string('statistics_mark', 20)->default(0.0)->after("english_mark")->comment("10+2 mark integrated program");
            $table->string('biology_mark', 20)->default(0.0)->after("statistics_mark")->comment("10+2 mark integrated program");
            $table->string('english_mark_10', 20)->default(0.0)->after("biology_mark")->comment("10th mark integrated program");
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
            $table->dropColumn('statistics_mark','biology_mark','english_mark_10');
        });
    }
}
