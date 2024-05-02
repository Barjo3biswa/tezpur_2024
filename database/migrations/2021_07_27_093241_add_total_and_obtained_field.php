<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalAndObtainedField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            $table->string("academic_10_exam_type", 50)->nullable()->after("academic_10_subject");
            $table->decimal('academic_10_total_mark')->nullable()->default(0.00)->after("academic_10_exam_type");
            $table->decimal('academic_10_mark_obtained')->nullable()->default(0.00)->after("academic_10_total_mark");

            $table->string("academic_12_exam_type", 50)->nullable()->after("academic_12_subject");
            $table->decimal('academic_12_total_mark')->nullable()->default(0.00)->after("academic_12_exam_type");
            $table->decimal('academic_12_mark_obtained')->nullable()->default(0.00)->after("academic_12_total_mark");

            $table->string("academic_graduation_exam_type", 50)->nullable()->after("academic_graduation_subject");
            $table->decimal('academic_graduation_total_mark')->nullable()->default(0.00)->after("academic_graduation_exam_type");
            $table->decimal('academic_graduation_mark_obtained')->nullable()->default(0.00)->after("academic_graduation_total_mark");

            $table->string("academic_post_graduation_exam_type", 50)->nullable()->after("academic_post_graduation_subject");
            $table->decimal('academic_post_graduation_total_mark')->nullable()->default(0.00)->after("academic_post_graduation_exam_type");
            $table->decimal('academic_post_graduation_mark_obtained')->nullable()->default(0.00)->after("academic_post_graduation_total_mark");

            $table->string("academic_diploma_exam_type", 50)->nullable()->after("academic_diploma_subject");
            $table->decimal('academic_diploma_total_mark')->nullable()->default(0.00)->after("academic_diploma_exam_type");
            $table->decimal('academic_diploma_mark_obtained')->nullable()->default(0.00)->after("academic_diploma_total_mark");
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
                "academic_10_exam_type",
                'academic_10_total_mark',
                'academic_10_mark_obtained',
                "academic_12_exam_type",
                'academic_12_total_mark',
                'academic_12_mark_obtained',
                "academic_graduation_exam_type",
                'academic_graduation_total_mark',
                'academic_graduation_mark_obtained',
                "academic_post_graduation_exam_type",
                'academic_post_graduation_total_mark',
                'academic_post_graduation_mark_obtained',
                "academic_diploma_exam_type",
                'academic_diploma_total_mark',
                'academic_diploma_mark_obtained']);
        });
    }
}
