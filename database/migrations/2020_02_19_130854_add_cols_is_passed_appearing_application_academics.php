<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsIsPassedAppearingApplicationAcademics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            $table->boolean('academic_10_is_passed_appearing')->default(false)->after('academic_10_remarks');
            $table->boolean('academic_12_is_passed_appearing')->default(false)->after('academic_12_remarks');
            $table->boolean('academic_graduation_is_passed_appearing')->default(false)->after('academic_graduation_remarks');
            $table->boolean('academic_post_graduation_is_passed_appearing')->default(false)->after('academic_post_graduation_remarks');
            $table->boolean('academic_diploma_is_passed_appearing')->default(false)->after('academic_diploma_remarks');

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
