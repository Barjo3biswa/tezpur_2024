<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColApplicationEdited extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->boolean('is_btech')->after('student_id')->default(false);
            $table->integer('branch_id')->nullable();
            $table->string('father_mobile')->after('father_occupation')->nullable();
            $table->string('father_email')->after('father_mobile')->nullable();
            $table->string('mother_mobile')->after('mother_occupation')->nullable();
            $table->string('mother_email')->after('mother_occupation')->nullable();
            $table->string('sub_caste')->after('caste_id')->nullable();
            $table->boolean('is_pwd')->after('person_with_disablity')->default(false);
            $table->boolean('is_foreign')->after('form_step')->default(false);
            $table->boolean('is_kmigrant')->after('nationality')->default(false);
            $table->boolean('is_jk_student')->after('is_kmigrant')->default(false);
            $table->boolean('is_ex_servicement')->after('is_jk_student')->default(false);
            $table->string('adhaar')->after('dob')->nullable();
            $table->string('nad_id')->after('adhaar')->nullable();
            $table->boolean('is_bpl')->after('is_pwd')->default(false);
            $table->boolean('is_minority')->after('is_bpl')->default(false);
            $table->boolean('is_accomodation_needed')->after('is_minority')->default(false);
            $table->boolean('is_employed')->after('is_accomodation_needed')->default(false);
            $table->string('correspondence_co')->after('permanent_contact_number')->nullable();
            $table->string('correspondence_house_no')->after('correspondence_co')->nullable();
            $table->string('correspondence_street_locality')->after('correspondence_house_no')->nullable();
            $table->string('permanent_co')->after('mother_occupation')->nullable();
            $table->string('permanent_house_no')->after('permanent_co')->nullable();
            $table->string('permanent_street_locality')->after('permanent_house_no')->nullable();
            $table->boolean('is_accepted')->after('selected_caste_id')->default(false);
            $table->dateTime('accepted_at')->after('is_accepted')->nullable();
            $table->integer('accepted_by')->after('accepted_at')->nullable();
            $table->string('place_residence')->nullable();
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
