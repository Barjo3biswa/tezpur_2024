<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewPhdColumnsAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            $table->string('part_time_details')->nullable();
            $table->string('academic_experience')->nullable();
            $table->string('publication_details')->nullable();
            $table->string('statement_of_purpose')->nullable();
            $table->string('bank_acc')->nullable();
            $table->string('ifsc_no')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('pan_no')->nullable();
            $table->boolean('is_full_time')->nullable();
            // second form
            $table->string('passed_or_appeared_qualified_exam')->nullable();
            $table->string('qualified_national_level_test')->nullable();
            $table->json('proposed_area_of_research')->nullable();
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
            $table->dropColumn([
                "part_time_details","academic_experience", "publication_details", "statement_of_purpose",
                "bank_acc", "ifsc_no", "branch_name", "branch_code", "pan_no", "is_full_time", "passed_or_appeared_qualified_exam",
                "proposed_area_of_research", "qualified_national_level_test"
            ]);
        });
    }
}
