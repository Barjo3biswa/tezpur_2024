<template>
  <div class="container">
    <div class="row" v-if="!step == 0">
      <div class="btn-group btn-group-justified" id="btn-steps">
        <a @click="goToStepOne" class="btn btn-info" :class="{ active: step == 1 }" data-form="step-one-update">
          <i class="fa fa-users"></i> Personal Information 
        </a>
        <a @click="goToStepTwo" class="btn btn-info" :class="
          step == 1 && step_one_form.submitted == false
            ? 'disabled'
            : step == 2
              ? 'active'
              : ''
        " data-form="step-two-update">
          <i class="fa fa-map-marker"></i> Address & Contact Information 
        </a>
        <a @click="goToStepThree" class="btn btn-info" :class="
          (step == 2 || step == 1) && step_two_form.submitted == false
            ? 'disabled'
            : step == 3
              ? 'active'
              : ''
        " data-form="step-three-update">
          <i class="fa fa-graduation-cap"></i> Academic Details
        </a>
        <a @click="goToStepFour" class="btn btn-info" :class="
          (step == 3 || step == 2 || step == 1) &&
            step_three_form.submitted == false
            ? 'disabled'
            : step == 4
              ? 'active'
              : ''
        " data-form="step-four-update">
          <i class="fa fa-paperclip"></i> Documents Upload
        </a>
      </div>
      <br />
    </div>
    <div class="row" v-if="!step == 0">
      <span v-if="initial_step.is_mba==false" style="color:rgb(196, 35, 35);">(You are applying through {{ this.application_type }})</span>
    </div>
    <div class="row justify-content-center" v-if="step == 0 && mode == 'create'">
      <!-- <div class="col-md-1"></div> -->
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <h5 class="text-center" style="font-size:14px;font-weight:550;">
              Program Type 
              <span v-if="initial_step.is_mba==false" style="color:rgb(196, 35, 35);">(You are applying through {{ this.application_type }})</span>
            </h5>
            <div class="row">
              <div class="col-md-12">
                <select v-model="initial_step.course_type" class="form-control input-sm" required
                  @change="courseTypeChanged">
                  <option value selected disabled>--SELECT--</option>
                  <option :value="type" :key="type.id" v-for="type in initial_step.course_types">{{ type.name }}
                  </option>
                </select>
              </div>
            </div>
            <!-- <section v-if="initial_step.course_type">
              <h5 class="text-center" style="font-size:14px;font-weight:550;">
                Select Program
              </h5>
              <div class="row">
                <div class="col-md-12">
                  <select
                    v-model="initial_step.course"
                    class="form-control input-sm"
                    @change="courseChanged"
                  >
                    <option value selected disabled>--SELECT--</option>
                    <option
                      :value="course"
                      :key="course.id"
                      v-for="course in filteredCourses"
                      >{{ course.name }}</option
                    >
                  </select>
                </div>
              </div>
            </section>   -->
            <!-- <div class="row" style="margin-top:14px;" v-if="initial_step.course_alert">
              <div class="col-md-12">
                <div class="alert alert-success" role="alert" v-html="initial_step.course_alert">
                  
                </div>
              </div>
            </div> -->
            <!-- <div
              class="row"
              v-if="
                is_preference == true &&
                  is_sub_preference == false &&
                  initial_step.course
              ">
            -->
            <div class="row" v-if="initial_step.course_type">
              <div class="col-md-4">
                <h5 style="font-size:14px;font-weight:550;margin-left:20px;">
                  Choose Program By Preference
                </h5>
              </div>
              <div class="col-md-6 i_qualify">
                <h5 style="font-size:14px;font-weight:550;margin-left:20px;">
                  I Qualify
                </h5>
              </div>

              <div class="col-md-2 choose_preference" >
                <h5 style="font-size:14px;font-weight:550;margin-left:20px;">
                  Choose Preference
                </h5>
              </div>

              <div class="row" v-if="initial_step.is_pref_limit_exceeded == true">
                <div class="col-md-12" style="padding:14px;">
                  <div class="alert-blue">
                    <i class="fa fa-info text-white"></i> You can select upto
                    two programs only.
                  </div>
                </div>
              </div>
              <small class="text-danger text-center" v-if="initial_step.preference_error" style="margin-left: 70%;">{{
                initial_step.preference_error
              }}</small>
              <section v-for="course in initial_step.combined_courses" :key="course.id">
                <section v-if="course.id != initial_step.course.id">
                  <section v-if="
                    !(
                      course.sub_combination_id > 0 &&
                      course.sub_preference == false
                    )
                  ">
                    <div class="col-md-4">
                      <div class="row2">
                        <div class="col-checkbox">
                          <div class="checkbox" style="padding-left:14px;">
                            <!--<input type="checkbox" :checked="course.is_checked == true" :disabled="
                              (initial_step.is_pref_limit_exceeded == true &&
                                course.is_checked == false
                                ? true
                                : false) || (
                                initial_step.course_group_23.includes(course.group) && course.is_checked == false
                              )
                            " @change="checkCourse(course)" />-->
                            <input type="checkbox" :checked="course.is_checked == true" :disabled="
                              (initial_step.is_pref_limit_exceeded == true &&
                                course.is_checked == false
                                ? true
                                : false) || (
                                initial_step.course_group_23.includes(application_type === 'TUEE' ? course.group : course.cuet_group) && course.is_checked == false
                              )
                            " @change="checkCourse(course)" />

                          </div>
                        </div>
                        <div class="col-course-name text-primaey text-bold" style="margin-top:10px;font-size:16px;">
                          {{ course.name }}
                        </div>
                      </div>
                    </div>
                    <!-- new insert 10-08-2022 -->
                    <!-- initial_step.preference_count != initial_step.selection_count -->
                    <div class="col-md-6">
                      <div class="row2">
                        <div class="col-checkboxii">
                          <div class="checkbox" style="padding-left:14px;">
                            <input type="checkbox" :checked="course.is_checked
                              ? true
                              : false" :disabled="(
                              initial_step.is_pref_limit_exceeded == true &&
                                course.is_checked == false
                                ? true
                                : false) || (
                                  initial_step.course_group_23.includes(application_type === 'TUEE' ? course.group : course.cuet_group) && course.is_checked == false
                              )
                            " @change="checkCourseNew(course)" />

                          </div>
                        </div>
                        <div class="col-course-name text-info text-bold" style="margin-top:10px;"
                          v-html="course.eligibility"></div>
                      </div>
                    </div>
                    <!-- Ends-->
                     <div class="col-md-2" style="padding-bottom:12px;">
                      <section v-if="course.is_checked == true">
                        <select class="form-control input-sm input-sm" @change="preferenceChanged($event, course.id)"
                          :disabled="
                            initial_step.is_pref_limit_exceeded == true &&
                              course.is_checked == false
                              ? true
                              : false
                          ">
                          <option value selected disabled>--SELECT--</option>
                          <!--<option v-for="(n, index) in initial_step.combined_courses
                          .length" :key="index" :value="n">{{ n }}</option>-->
                          <option v-for="(n, index) in initial_step.course_preference_limit" :key="index" :value="n">{{ n }}</option>
                        </select>
                        <small><strong class="text-info">Preference : {{ course.preference }}</strong></small>
                      </section>
                    </div>

                    <div class="col-md-12">
                      <legend>
                      </legend>
                    </div>


                  </section>
                </section>
              </section>
            </div>
            <div class="row" v-if="is_sub_preference == true && initial_step.course">
              <h5 class="text-center" style="font-size:14px;font-weight:550;">
                Select Program & Preference
              </h5>
              <ul class="text-notice">
                <li><small>If you select only 1 program please select preference as 1.</small></li>
                <li v-if="is_integrated"><small>If you select both the programs then specify your preferences
                    accordingly.</small></li>
                <li v-else><small>If you select other programs then specify your preferences accordingly.</small></li>
              </ul>
              <small class="text-danger text-center" v-if="initial_step.preference_error" style="margin:12px 12px;">{{
                initial_step.preference_error
              }}</small>
              <section v-for="course in initial_step.combined_courses" :key="course.id">
                <section v-if="course.id != initial_step.course.id">
                  <div class="col-md-7">
                    <div class="row2">
                      <div class="col-checkbox">
                        <div class="checkbox" style="padding-left:14px;">
                          <input type="checkbox" v-model="course.is_checked" />
                        </div>
                      </div>
                      <div class="col-course-name">
                        {{ course.name }}
                      </div>
                    </div>
                  </div>
                  <!--<div class="col-md-5" style="padding-bottom:12px;" v-if="course.is_checked == true">
                    <select class="form-control input-sm input-sm" @change="preferenceChanged($event, course.id)">
                      <option value selected disabled>--SELECT--</option>
                      <option v-for="(n, index) in initial_step.combined_courses
                      .length - 1" :key="index" :value="n">{{ n }}</option>
                    </select>
                    <small><strong class="text-info">Preference : {{ course.preference }}</strong></small>
                  </div>-->
                </section>
              </section>
            </div>
            <div class="row" v-if="
              initial_step.selection_count == true && initial_step.preference_count == true && isAllowNext == true && this.initial_step.program_selection_step == true 
            "> 
            <!--<div class="row"
              v-if="initial_step.selection_count == true && this.initial_step.program_selection_step == true">-->
              <!--<div
                class="row"
                v-if="
                  initial_step.course && initial_step.ready == true && isAllowNext 
                ">
              -->
              <div class="col-md-12" style="padding-top:18px;">
                <div class="panel panel-info">
                  <h5 class="text-center" style="font-size:14px;font-weight:550;">
                    <span v-if="isPHD == true">Pre-requisites</span>
                    <span v-else>Declaration</span>
                  </h5>
                  <!--<div class="panel-body text-info text-bold"> I have read through the prospectus throughly <a href="this.base_url+notifications/2023/Prospectus_2023.pdf">Click Here</a>  </div>-->

                  <section v-if="is_lateral == true">
                    <div class="row">
                      <div class="col-md-12">
                        <h5 class="text-center" style="font-weight:600;">
                          <strong>Do you possess 60% marks in Mathematics?</strong>
                        </h5>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label class="radio-inline" style="margin-left:14px;">
                          <input type="radio" :value="true" v-model="is_60" id="disablity" />
                          <span class="text-warning">Yes</span>
                        </label>
                        <label class="radio-inline">
                          <input type="radio" :value="false" v-model="is_60" id="disablity" />
                          <span class="text-warning">No</span>
                        </label>
                      </div>
                    </div>
                    <div class="row" v-if="is_60 == false">
                      <div class="col-md-12">
                        <h5 class="text-center text-danger" style="font-weight:600;">
                          <strong>Sorry, you don't qualify for this course</strong>
                        </h5>
                      </div>
                    </div>
                  </section>
                  <div class="row" v-if="is_60 == true">
                    <div class="col-md-12">
                      <div class="checkbox" style="padding-left:12px;">
                        <label>
                          <input type="checkbox" v-model="initial_step.is_qualify" />
                          <span v-if="this.application_type=='NET_JRF'"><b>I hereby acknowledge that I have successfully qualified for the National Level Test (NET/GATE/SLET/JRF/MPhil). 
                            If my qualification is not valid, my application will be subject to rejection.</b></span>
                          I have read through the prospectus
                          throughly
                        </label>
                        <button @click="openProspectusInNewWindow()">Click Here</button>                   
                      </div>
                      <div class="row text-center" style="padding-bottom:14px;" v-if="initial_step.is_qualify">
                        <button class="btn btn-sm btn-success" @click="goToStepOne">
                          NEXT
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="this.initial_step.program_selection_step == false">
              <div class="alert alert-danger">
                <strong>Error!! Unable to continue :: </strong> Either same preference is given for multiple courses or
                Preference selection is not in sequence .
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="row" v-if="step == 1">
      <div class="col-md-12">
        <form name="step-one-update" autocomplete="off" class="form-horizontal application-form">
          <br />
          <div class="step personal_information">
            <fieldset id="personal_information">
              <legend>
                <span class="pull-right text-danger">(Step 1)</span>
              </legend>
              <div class="row">
                <div class="col-md-6">
                  <form-input :label="'1. (a) First Name'" :input_data="step_one_form.first_name"
                    :errors="step_one_form.errors" :error_attr="'first_name'" :placeholder="'First Name'" :required="true"
                    :disabled="applications_count == '0' ? false : true"
                    @inputDataChanged="step_one_form.first_name = $event"></form-input>
                  <form-input :label="'(b) Middle Name'" :input_data="step_one_form.middle_name"
                    :errors="step_one_form.errors" :error_attr="'middle_name'" :placeholder="'Middle Name'"
                    :required="false" :disabled="applications_count == '0' ? false : true"
                    :label_style="'margin-left:12px;'" @inputDataChanged="step_one_form.middle_name = $event">
                  </form-input>
                  <form-input :label="'(c) Last Name'" :input_data="step_one_form.last_name"
                    :errors="step_one_form.errors" :error_attr="'last_name'" :placeholder="'Last Name'" :required="false"
                    :disabled="applications_count == '0' ? false : true" :label_style="'margin-left:12px;'"
                    @inputDataChanged="step_one_form.last_name = $event">
                  </form-input>
                  <form-select :label="'2. Religion / Faith'" :options="religions" :errors="step_one_form.errors"
                    :error_attr="'religion'" :value_attr="'value'" :name_attr="'name'" :required="true"
                    :old_value="step_one_form.religion" :old_value_attr="'name'"
                    @changedSelect="step_one_form.religion = $event"></form-select>
                  <form-input :label="'Please Specify'" :input_data="step_one_form.religion"
                    :errors="step_one_form.errors" :error_attr="'religion'" :placeholder="'Please specify'"
                    :required="false" :label_style="'margin-left:12px;'" :show_text_limit="true"
                    @inputDataChanged="step_one_form.other = $event" v-if="showOtherInput == true"></form-input>

                  <form-select :label="'3. Gender'" :options="gender_options" :errors="step_one_form.errors"
                    :error_attr="'gender'" :value_attr="'value'" :name_attr="'name'" :required="true"
                    :old_value="step_one_form.gender" :old_value_attr="'name'"
                    @changedSelect="step_one_form.gender = $event"></form-select>

                  <form-select :label="'4. Marital Status'" :options="martital_options" :errors="step_one_form.errors"
                    :error_attr="'marital_status'" :value_attr="'value'" :name_attr="'name'" :required="true"
                    :old_value="step_one_form.marital_status" :old_value_attr="'name'"
                    @changedSelect="step_one_form.marital_status = $event"></form-select>

                  <date-picker :label="'5. Date of birth ( as per matriculation certificate)'"
                    :input_data="step_one_form.dob" :errors="step_one_form.errors" :error_attr="'dob'" :required="true"
                    @dateChanged="step_one_form.dob = $event" :not_after="disabledAfter" v-if="is_foreign==false">
                    ></date-picker>

                  <date-picker :label="'5. Date of birth'"
                    :input_data="step_one_form.dob" :errors="step_one_form.errors" :error_attr="'dob'" :required="true"
                    @dateChanged="step_one_form.dob = $event" :not_after="disabledAfter" v-if="is_foreign==true">
                    ></date-picker>

                  <form-select :label="'6. (a) Category'" :options="castes" :errors="step_one_form.errors"
                    :error_attr="'caste'" :value_attr="'id'" :name_attr="'name'" :required="true"
                    :old_value="step_one_form.caste" :old_value_attr="'id'" @changedSelect="step_one_form.caste = $event"
                    v-if="is_foreign == false"></form-select>

                  <form-input :label="'(b) Caste/Tribe'" :input_data="step_one_form.sub_caste"
                    :errors="step_one_form.errors" :error_attr="'sub_caste'" :placeholder="'Caste'"
                    :label_style="'margin-left:12px;'" @inputDataChanged="step_one_form.sub_caste = $event"
                    v-if="is_foreign == false"></form-input>
                  <form-input :label="
                    is_foreign == true ? '6. PAN NO' : '7. PAN NO'
                  " :input_data="step_one_form.pan_no" :errors="step_one_form.errors" :error_attr="'pan_no'"
                    :placeholder="'PAN No'" @inputDataChanged="step_one_form.pan_no = $event" :required="false" v-if=" is_foreign == false">
                  </form-input>
                  <form-input :label="
                    is_foreign == true ? '6. Aadhaar No' : '7. Aadhaar No'
                  " :input_data="step_one_form.adhaar" :errors="step_one_form.errors" :error_attr="'adhaar'"
                    :placeholder="'Aadhaar No'" @inputDataChanged="step_one_form.adhaar = $event"  v-if=" is_foreign == false"></form-input>

                  <form-input :label="is_foreign == true ? '7. (a) DigiLocker ID' : '8. (a).DigiLocker ID'"
                    :input_data="step_one_form.nad_id" :errors="step_one_form.errors" :error_attr="'nad_id'"
                    :required="false" :placeholder="'DigiLocker ID'"
                    @inputDataChanged="step_one_form.nad_id = $event"  v-if=" is_foreign == false"></form-input>
                    <small class="text-primary" @click="openPreviewInNewWindowII('https://www.digilocker.gov.in/')"  v-if=" is_foreign == false"><i
                          class="fa fa-info-circle"></i> Know About DigiLocker</small>
                  <form-input
                    :label="is_foreign == true ? '(b) Academic Bank Of Credits(ABC) ID' : '(b).Academic Bank Of Credits(ABC)'"
                    :input_data="step_one_form.abc" :errors="step_one_form.errors" :error_attr="'abc'" :required="false"
                    :placeholder="'Academic Bank Of Credits(ABC) ID'"
                    @inputDataChanged="step_one_form.abc = $event"  v-if=" is_foreign == false"></form-input>
                    <small class="text-primary" @click="openPreviewInNewWindowII('https://www.abc.gov.in/')"  v-if=" is_foreign == false"><i
                          class="fa fa-info-circle"></i> Know About Academic Bank Of Credits(ABC)</small>

                  <form-input :label="
                    is_foreign == true
                      ? '6. Passport Number'
                      : '9. Passport Number'
                  " :input_data="step_one_form.passport_number" :errors="step_one_form.errors" :required="true"
                    :error_attr="'passport_number'" :placeholder="'Passport No'" v-if="is_foreign == true"
                    @inputDataChanged="step_one_form.passport_number = $event"></form-input>
                  <!--<form-input :label="
                    is_foreign == true
                      ? '7. Driving License Social security number or equivalent'
                      : '10. Driving License Social security number or equivalent'
                  " :input_data="step_one_form.driving_license_equivalnet_no" :errors="step_one_form.errors" :required="true"
                    :error_attr="'driving_license_equivalnet_no'" :placeholder="
                      'Driving License Social security number or equivalent'
                    " v-if="is_foreign == true" @inputDataChanged="
  step_one_form.driving_license_equivalnet_no = $event
"></form-input>-->

                  <div class="form-group">
                    <label class="col-md-7">
                      <span v-if="is_foreign == true">8</span><span v-else>9</span> . Are you employed? :
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span> <br />
                      <small class="text-primary" v-if="step_one_form.is_employed && this.is_phd_prof == false"><i class="fa fa-info-circle"></i> NOC
                        must be produced
                        if selected.</small>
                      <small class="text-primary" v-if="step_one_form.is_employed && this.is_phd_prof == true"><i class="fa fa-info-circle"></i> Category
                        of Ph.D.</small>
                    </label>
                    <div class="col-md-5">
                      <div class="row">
                        <div class="col-md-12">
                          <label class="radio-inline">
                            <input type="radio" :value="true" v-model="step_one_form.is_employed" id="is_employed" />
                            <span class="text-warning">Yes</span>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" :value="false" v-model="step_one_form.is_employed" id="is_employed" />
                            <span class="text-warning">No</span>
                          </label>
                        </div>
                      </div>
                      <div class="row" v-if="step_one_form.is_employed == true && this.is_phd_prof == false">
                        <div class="col-md-10">
                          <select class="form-control input-sm" v-model="step_one_form.employment_details">
                            <option selected disabled>--SELECT--</option>
                            <option v-for="(employment, index) in employments" :value="employment.name" :key="index"
                              :selected="
                                employment.name ==
                                step_one_form.employment_details
                              ">{{ employment.name }}</option>
                          </select>
                          <small class="text-danger" v-if="step_one_form.errors.employment_details">{{
                            step_one_form.errors.employment_details
                          }}</small>
                        </div>
                      </div>

                      <div class="row" v-if="step_one_form.is_employed == true && this.is_phd_prof == true">
                        <div class="col-md-12">
                          <select class="form-control input-sm" v-model="step_one_form.employment_details">
                            <option selected disabled>--SELECT--</option>
                            <option v-for="(employments_phd_prof, index) in employments_phd_prof" :value="employments_phd_prof.name" :key="index"
                              :selected="
                              employments_phd_prof.name ==
                                step_one_form.employment_details
                              ">{{ employments_phd_prof.name }}</option>
                          </select>
                          <small class="text-danger" v-if="step_one_form.errors.employment_details">{{
                            step_one_form.errors.employment_details
                          }}</small>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-7">
                      <span v-if="is_foreign == true">9</span><span v-else>10</span>. Are you a person with disability
                      (PWD)? :
                    </label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_pwd" id="disablity" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_pwd" id="disablity" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>

                  <form-input :label="'Please specify'" :input_data="step_one_form.pwd_exp" :errors="step_one_form.errors"
                    :error_attr="'pwd_explain'" :placeholder="'Please specify'" :required="false"
                    :label_style="'margin-left:12px;'" :show_text_limit="true"
                    @inputDataChanged="step_one_form.pwd_exp = $event" v-if="step_one_form.is_pwd"></form-input>

                  <form-input :label="'Enter PWD Percentage'" :input_data="step_one_form.pwd_per"
                    :errors="step_one_form.errors" :error_attr="'pwd_percent'" :placeholder="'Please specify'"
                    :required="false" :label_style="'margin-left:12px;'" :show_text_limit="true"
                    @inputDataChanged="step_one_form.pwd_per = $event" v-if="step_one_form.is_pwd"></form-input>

                  <!--<div class="form-group">
                    <label class="col-md-7">
                      <span v-if="is_foreign == true">9</span><span v-else>11</span>. Do you want to apply in defence quota? :
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_ex_serviceman"
                          id="is_ex_serviceman" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_ex_serviceman"
                          id="is_ex_serviceman" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>
                  <div class="form-group" v-if="step_one_form.is_ex_serviceman == true">
                    <label class="col-md-7">
                      <span style="margin-left:14px;">Are you ex-Servicemen or widow/ward of ex-Serviceman ? :</span>
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_aplly_defense_quota"
                          id="is_ex_serviceman" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_aplly_defense_quota"
                          id="is_ex_serviceman" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>-->

                  <div class="form-group" v-if="is_foreign == false">
                    <label class="col-md-7">
                      <span v-if="is_foreign == true">10</span><span v-else>11</span>.
                      Do you want to apply in defence quota ? :</span>
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_aplly_defense_quota"
                          id="is_ex_serviceman" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_aplly_defense_quota"
                          id="is_ex_serviceman" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                    
                    <div class="form-group" v-if="step_one_form.is_aplly_defense_quota == true">
                      <label class="col-md-7">
                        <span style="margin-left:14px;">Are you ex-Servicemen or widow/ward of ex-Serviceman ? :</span>
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-5">
                        <label class="radio-inline">
                          <input type="radio" :value="true" v-model="step_one_form.is_ex_serviceman"
                            id="is_ex_serviceman" />
                          <span class="text-warning">Yes</span>
                        </label>
                        <label class="radio-inline">
                          <input type="radio" :value="false" v-model="step_one_form.is_ex_serviceman"
                            id="is_ex_serviceman" />
                          <span class="text-warning">No</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <form-select :label="'Select Priority'" :options="step_one_form.priorities"
                    :errors="step_one_form.errors" :error_attr="'priority'" :value_attr="'id'" :name_attr="'name'"
                    :required="true" :label_style="'margin-left:12px;'" :old_value="step_one_form.priority"
                    :old_value_attr="'id'" @changedSelect="step_one_form.priority = $event" v-if="
                      step_one_form.is_aplly_defense_quota == true &&
                      step_one_form.is_ex_serviceman == true
                    "></form-select>
                  <div class="form-group" v-if="is_foreign == false">
                    <label class="col-md-7"><span v-if="is_foreign == true">11</span><span>12</span> Are you a Kashmiri
                      Migrant (KM) ?
                      :</label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_km" id="is_km" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_km" id="is_km" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>
                  <form-input :label="'Please specify'" :input_data="step_one_form.km_details"
                    :errors="step_one_form.errors" :error_attr="'km_details'" :placeholder="'Please specify'"
                    :required="false" :label_style="'margin-left:12px;'" :show_text_limit="true"
                    @inputDataChanged="step_one_form.km_details = $event" v-if="step_one_form.is_km"></form-input>
                  <!--<div class="form-group" v-if="is_foreign == false">
                    <label class="col-md-7">13. Are you a student from Jammu & Kashmir?:</label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_jk_student" id="is_jk_student" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_jk_student" id="is_jk_student" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>-->

                  <div class="form-group" v-if="is_foreign == false">
                    <label class="col-md-7">
                      13 . Do you belong to BPL/AAY?:
                      <br />
                      <small class="text-primary" v-if="step_one_form.is_bpl == true"><i class="fa fa-info-circle"></i>
                        BPL/AAY certificate
                        upload is mandatory at the final step</small>
                    </label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_bpl" id="is_bpl" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_bpl" id="is_bpl" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>
                  <div class="form-group" v-if="is_foreign == false">
                    <label class="col-md-7">14. Do you belong to minority community ? :</label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_minority" id="is_minority" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_minority" id="is_minority" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>
                  <form-input :label="'Please specify'" :input_data="step_one_form.minority_details"
                    :errors="step_one_form.errors" :error_attr="'minority_details'" :placeholder="'Please specify'"
                    :required="false" :label_style="'margin-left:12px;'" :show_text_limit="true"
                    @inputDataChanged="step_one_form.minority_details = $event" v-if="step_one_form.is_minority">
                  </form-input>
                  <div class="form-group" v-if="is_phd">
                    <label class="col-md-7">15. Whether passed/appeared the qualification exam?:</label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="'passed'" v-model="step_one_form.passed_or_appeared_qualified_exam"
                          id="passed_or_appeared_qualified_exam" />
                        <span class="text-warning">Passed</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="'appeared'" v-model="step_one_form.passed_or_appeared_qualified_exam"
                          id="passed_or_appeared_qualified_exam" />
                        <span class="text-warning">Appeared</span>
                      </label>
                    </div>
                  </div>
                  <div class="form-group" v-if="is_phd">
                    <label class="col-md-7">16. Whether applying for full time/part time?:</label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_full_time" id="is_full_time" />
                        <span class="text-warning">Full Time</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_full_time" id="is_full_time" />
                        <span class="text-warning">Part Time</span>
                      </label>
                    </div>
                  </div>
                  <form-input v-if="!step_one_form.is_full_time && is_phd"
                    :label="'Furnish Designation with office address of the College/Office wherein he/she is working?'"
                    :input_data="step_one_form.part_time_details" :errors="step_one_form.errors"
                    :error_attr="'part_time_details'" :placeholder="'Please specify'" :required="false"
                    :show_text_limit="true" @inputDataChanged="step_one_form.part_time_details = $event"></form-input>
                  <div class="form-group" v-if="is_phd">
                    <label class="col-md-7">17. Experience related to:</label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="'Research experience'" v-model="step_one_form.academic_experience"
                          id="academic_experience" />
                        <span class="text-warning">Research Experience</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="'Professional'" v-model="step_one_form.academic_experience"
                          id="academic_experience" />
                        <span class="text-warning">Professional</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="'Teaching experience'" v-model="step_one_form.academic_experience"
                          id="academic_experience" />
                        <span class="text-warning">Teaching Experience</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="'NA'" v-model="step_one_form.academic_experience"
                          id="academic_experience" />
                        <span class="text-warning">NA</span>
                      </label>
                    </div>
                  </div>

                  <div class="form-group" v-if="is_phd">
                    <label for="proposed_area_of_research" class="col-md-7">
                      <span>18</span>. Publication Details ( Enter NA if not available)
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-5">
                      <textarea cols="30" rows="2" required v-model="step_one_form.publication_details"
                        class="form-control input-sm" placeholder=".Publication Details"></textarea>
                      <small class="text-info">
                        <!-- <strong>{{
                              publication_detailsTextLeft
                            }}</strong> -->
                      </small>
                      <small class="text-danger" v-if="step_one_form.errors.publication_details">{{
                        step_one_form.errors.publication_details
                      }}</small>
                    </div>
                  </div>
                  <div class="form-group" v-if="is_phd">
                    <label for="proposed_area_of_research" class="col-md-7">
                      <span>19</span> .Statement of Purpose (SOP) of research (maximum 250-300 words)( Enter NA if not available)
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-5">
                      <textarea cols="30" rows="2" required v-model="step_one_form.statement_of_purpose"
                        class="form-control input-sm" placeholder="Statement of purpose"></textarea>
                      <small class="text-info">
                        <!-- <strong>{{
                              publication_detailsTextLeft
                            }}</strong> -->
                      </small>
                      <small class="text-danger" v-if="step_one_form.errors.statement_of_purpose">{{
                        step_one_form.errors.statement_of_purpose
                      }}</small>
                    </div>
                  </div>

                </div>

                <div class="col-md-6">
                  <form-input :label="
                    is_foreign == true
                      ? '13 (a) Father\'s Name'
                      : '16 (a) Father\'s Name'
                  " :input_data="step_one_form.father_name" :errors="step_one_form.errors" :error_attr="'father_name'"
                    :placeholder="'Father Name'" :required="true"
                    @inputDataChanged="step_one_form.father_name = $event"></form-input>

                  <form-input :label="'Occupation'" :input_data="step_one_form.father_occupation"
                    :errors="step_one_form.errors" :error_attr="'father_occupation'" :placeholder="'Occupation'"
                    :required="true" :label_style="'margin-left:12px;'"
                    @inputDataChanged="step_one_form.father_occupation = $event"></form-input>

                  <form-select :label="'Qualification'" :options="parents_qualification" :errors="step_one_form.errors"
                    :error_attr="'father_qualification'" :value_attr="'id'" :name_attr="'name'"
                    :required="true" :old_value="step_one_form.father_qualification" :old_value_attr="'id'"
                    :label_style="'margin-left:12px;'" @changedSelect="step_one_form.father_qualification = $event">
                  </form-select>

                  <form-input :label="'Mobile No'" :input_data="step_one_form.father_mobile"
                    :errors="step_one_form.errors" :error_attr="'father_mobile'" :placeholder="'Mobile'" :required="true"
                    :label_style="'margin-left:12px;'"
                    @inputDataChanged="step_one_form.father_mobile = $event"></form-input>

                  <form-input :label="'Email'" :input_data="step_one_form.father_email" :errors="step_one_form.errors"
                    :error_attr="'father_email'" :placeholder="'Email'" :required="true"
                    :label_style="'margin-left:12px;'" @inputDataChanged="step_one_form.father_email = $event">
                  </form-input>
                  <form-input :label="'(b) Mother\'s Name'" :input_data="step_one_form.mother_name"
                    :errors="step_one_form.errors" :error_attr="'mother_name'" :placeholder="'Mother Name'"
                    :required="true" @inputDataChanged="step_one_form.mother_name = $event"></form-input>

                  <form-input :label="'Occupation'" :input_data="step_one_form.mother_occupation"
                    :errors="step_one_form.errors" :error_attr="'mother_occupation'" :placeholder="'Occupation'"
                    :required="true" :label_style="'margin-left:12px;'"
                    @inputDataChanged="step_one_form.mother_occupation = $event"></form-input>

                  <form-select :label="'Qualification'" :options="parents_qualification" :errors="step_one_form.errors"
                    :error_attr="'mother_qualification'" :value_attr="'id'" :name_attr="'name'"
                    :required="true" :old_value="step_one_form.mother_qualification" :old_value_attr="'id'"
                    :label_style="'margin-left:12px;'" @changedSelect="step_one_form.mother_qualification = $event">
                  </form-select>

                  <form-input :label="'Mobile No'" :input_data="step_one_form.mother_mobile"
                    :errors="step_one_form.errors" :error_attr="'mother_mobile'" :placeholder="'Mobile'" :required="true"
                    :label_style="'margin-left:12px;'"
                    @inputDataChanged="step_one_form.mother_mobile = $event"></form-input>

                  <form-input :label="'Email'" :input_data="step_one_form.mother_email" :errors="step_one_form.errors"
                    :error_attr="'mother_email'" :placeholder="'Email'" :required="true"
                    :label_style="'margin-left:12px;'" @inputDataChanged="step_one_form.mother_email = $event">
                  </form-input>

                  <form-input :label="'(c) Guardian\'s Name'" :input_data="step_one_form.guardian_name"
                    :errors="step_one_form.errors" :error_attr="'guardian_name'" :placeholder="'Guardian Name'"
                    :required="true" @inputDataChanged="step_one_form.guardian_name = $event"></form-input>

                  <form-input :label="'Occupation'" :input_data="step_one_form.guardian_occupation"
                    :errors="step_one_form.errors" :error_attr="'guardian_occupation'" :placeholder="'Occupation'"
                    :required="true" :label_style="'margin-left:12px;'" @inputDataChanged="
                      step_one_form.guardian_occupation = $event
                    "></form-input>

                  <form-input :label="'Mobile No (number must not match the registered one)'" :input_data="step_one_form.guardian_mobile"
                    :errors="step_one_form.errors" :error_attr="'guardian_mobile'" :placeholder="'Mobile'"
                    :required="true" :label_style="'margin-left:12px;'"
                    @inputDataChanged="step_one_form.guardian_mobile = $event"></form-input>

                  <form-input :label="'Email'" :input_data="step_one_form.guardian_email" :errors="step_one_form.errors"
                    :error_attr="'guardian_email'" :placeholder="'Email'" :required="true"
                    :label_style="'margin-left:12px;'" @inputDataChanged="step_one_form.guardian_email = $event">
                  </form-input>
                  <form-select :label="'(d) Family Income'" :options="income_ranges" :errors="step_one_form.errors"
                    :error_attr="'family_income'" :value_attr="'id'" :name_attr="'min'" :name_attr2="'max'"
                    :required="true" :old_value="step_one_form.family_income" :old_value_attr="'id'"
                    :label_style="'margin-left:12px;'" @changedSelect="step_one_form.family_income = $event">
                  </form-select>
                  <div class="form-group">
                    <label class="col-md-7">
                      <span v-if="is_foreign == true">14</span>
                      <span v-else>17 (a)</span>. Do you need hostel accommodation?
                      : <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span><br />
                      <small class="text-primary" v-if="step_one_form.is_accomodation_need == true"><i
                          class="fa fa-info-circle"></i> Subject to
                        availability</small>
                    </label>
                    <div class="col-md-5">
                      <label class="radio-inline">
                        <input type="radio" :value="true" v-model="step_one_form.is_accomodation_need"
                          id="is_accomodation_need" />
                        <span class="text-warning">Yes</span>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" :value="false" v-model="step_one_form.is_accomodation_need"
                          id="is_accomodation_need" />
                        <span class="text-warning">No</span>
                      </label>
                    </div>
                  </div>
                  <label class="col-md-12">
                    (b) Applicant Bank information <span style="font-size:14px;color:#D10000;">(For 1st Semester Fee
                      Refund Purpose)</span>
                  </label>
                  <form-input :label="'A/C Holder Name'" :input_data="step_one_form.account_holder_name"
                    :errors="step_one_form.errors" :error_attr="'account_holder_name'" :placeholder="'A/C Holder Name'"
                    :required="false" :label_style="'margin-left:35px;'"
                    @inputDataChanged="step_one_form.account_holder_name = $event"></form-input>
                  <form-input :label="'Bank A/C No'" :input_data="step_one_form.bank_ac_no" :errors="step_one_form.errors"
                    :error_attr="'bank_ac_no'" :placeholder="'Bank ac no'" :required="false"
                    :label_style="'margin-left:35px;'" @inputDataChanged="step_one_form.bank_ac_no = $event"></form-input>

                  <form-input :label="'Bank Name'" :input_data="step_one_form.bank_name" :errors="step_one_form.errors"
                    :error_attr="'bank_name'" :placeholder="'Bank name'" :required="false"
                    :label_style="'margin-left:35px;'" @inputDataChanged="step_one_form.bank_name = $event">
                  </form-input>

                  <form-input :label="'Branch Name'" :input_data="step_one_form.branch_name"
                    :errors="step_one_form.errors" :error_attr="'branch_name'" :placeholder="'Branch name'"
                    :required="false" :label_style="'margin-left:35px;'"
                    @inputDataChanged="step_one_form.branch_name = $event"></form-input>

                  <form-input :label="'IFSC code'" :input_data="step_one_form.ifsc_code" :errors="step_one_form.errors"
                    :error_attr="'ifsc_code'" :placeholder="'IFSC Code'" :required="false"
                    :label_style="'margin-left:35px;'" @inputDataChanged="step_one_form.ifsc_code = $event">
                  </form-input>

                  <form-input :label="'Mobile No'" :input_data="step_one_form.bank_reg_mobile_no"
                    :errors="step_one_form.errors" :error_attr="'bank_reg_mobile_no'"
                    :placeholder="'Bank Registered Mobile No'" :required="false" :label_style="'margin-left:35px;'"
                    @inputDataChanged="step_one_form.bank_reg_mobile_no = $event"></form-input>
                  <label class="col-md-4">

                  </label>
                  <label class="col-md-5">
                    <small class="text-primary"><i class="fa fa-info-circle"></i> Registered mobile no with bank</small>
                  </label>
                  <label class="col-md-4">
                  </label>
                  <label class="col-md-8">
                    <small class="text-primary"><i class="fa fa-info-circle"></i> You are required to upload the front
                      page of the passbook clearly.</small>
                  </label>

                </div>
              </div>
            </fieldset>
            <div class="row btn-row">
              <div class="col-md-6">
                <button type="button" class="btn btn-warning btn-sm" id="next" @click="goToStepZero"
                  v-if="mode == 'create'">
                  <i class="fa fa-chevron-left"></i>
                  GO BACK
                </button>
                <button type="button" class="btn btn-success btn-sm" id="next" @click="submitStepOne"
                  v-if="mode == 'create' && step_one_form.submitted == false">
                  <section v-if="step_one_form.creating">
                    <i class="fa fa-circle-o-notch fa-spin" style="font-size:14px;color:white;"></i>
                    <span class="text-white">Submitting ..</span>
                  </section>
                  <section v-else>
                    Save & Proceed
                    <i class="fa fa-save"></i>
                  </section>
                </button>
                <button type="button" class="btn btn-success btn-sm" id="next" @click="updateStepOne"
                  v-if="step_one_form.submitted == true || mode == 'edit'">
                  <section v-if="step_one_form.updating">
                    <i class="fa fa-circle-o-notch fa-spin" style="font-size:14px;color:white;"></i>
                    <span class="text-white">Updating ..</span>
                  </section>
                  <section v-else>
                    Update
                    <i class="fa fa-save"></i>
                  </section>
                </button>
                <button type="button" class="btn btn-info btn-sm" id="next" @click="goToStepTwo"
                  v-if="step_one_form.submitted == true">
                  GO NEXT
                  <i class="fa fa-chevron-right"></i>
                </button>
              </div>
              <div class="col-md-6" v-if="step_one_form.success_msg">
                <div class="alert alert-success">
                  {{ step_one_form.success_msg }}
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row" v-if="step == 2">
      <div class="col-md-12">
        <form name="step-one-update" class="form-horizontal form-class application-form">
          <br />
          <div class="step personal_information">
            <fieldset id="personal_information">
              <legend>
                Address & Contact Information 
                <span class="pull-right text-danger">(Step 2)</span>
              </legend>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-md-5">
                      <span class="text-info">1. Correspondence Address:</span>
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                  </div>
                  <form-input :label="'(a) C/O'" :input_data="step_two_form.correspondence.co"
                    :errors="step_two_form.errors" :error_attr="'correspondence_co'" :placeholder="'C/O'"
                    @inputDataChanged="step_two_form.correspondence.co = $event"></form-input>

                  <form-input :label="'(b) House No '" :input_data="step_two_form.correspondence.house_no"
                    :errors="step_two_form.errors" :error_attr="'correspondence_house_no'" :placeholder="'House No'"
                    @inputDataChanged="
                      step_two_form.correspondence.house_no = $event
                    "></form-input>

                  <form-input :label="'(c) Street Name /Locality '" :input_data="
                    step_two_form.correspondence.street_name_locality
                  " :errors="step_two_form.errors" :error_attr="'correspondence_street_name_locality'"
                    :placeholder="'Street Name / locality'" @inputDataChanged="
                      step_two_form.correspondence.street_name_locality = $event
                    "></form-input>

                  <form-input :label="'(d) Vill/ Town '" :input_data="step_two_form.correspondence.vill_town"
                    :errors="step_two_form.errors" :error_attr="'correspondence_vill_town'"
                    :placeholder="'Village / Town'" @inputDataChanged="
                      step_two_form.correspondence.vill_town = $event
                    "></form-input>

                  <form-input :label="'(e) Pin/Zip Code'" :input_data="step_two_form.correspondence.pin_code"
                    :errors="step_two_form.errors" :error_attr="'correspondence_pin_code'" :placeholder="'Pin Code'"
                    @inputDataChanged="
                      step_two_form.correspondence.pin_code = $event
                    "></form-input>

                  <form-input :label="'(f) P.O'" :input_data="step_two_form.correspondence.po"
                    :errors="step_two_form.errors" :error_attr="'correspondence_po'" :placeholder="'P.O'"
                    @inputDataChanged="step_two_form.correspondence.po = $event"></form-input>

                  <form-select :label="'(g) State'" :options="indian_states" :errors="step_two_form.errors"
                    :error_attr="'correspondence_state'" :value_attr="'id'" :name_attr="'name'" :required="true"
                    :old_value="step_two_form.correspondence.state" :old_value_attr="'id'"
                    @changedSelect="($event) => { step_two_form.correspondence.state = $event; loadDistricts($event); }"
                    v-if="!is_foreign"></form-select>
                  <form-input :label="'(g) State / UT'" :input_data="step_two_form.correspondence.state"
                    :errors="step_two_form.errors" :error_attr="'correspondence_state'" :placeholder="'State'"
                    @inputDataChanged="step_two_form.correspondence.state = $event" v-if="is_foreign"></form-input>

                  <!--<form-input :label="'(h) District'" :input_data="step_two_form.correspondence.district"
                    :errors="step_two_form.errors" :error_attr="'correspondence_district'" :placeholder="'District'"
                    @inputDataChanged="
                      step_two_form.correspondence.district = $event
                    "></form-input>-->
                  <form-select :label="'(h) District'" :options="districts" :errors="step_two_form.errors"
                    :error_attr="'correspondence_district'" :value_attr="'id'" :name_attr="'district_name'"
                    :required="true" :old_value="step_two_form.correspondence.district" :old_value_attr="'id'"
                    @changedSelect="($event) => { step_two_form.correspondence.district = $event }"
                    v-if="!is_foreign"></form-select>

                  <form-input :label="'(h) District'" :input_data="step_two_form.correspondence.district"
                    :errors="step_two_form.errors" :error_attr="'correspondence_district'" :placeholder="'District'"
                    @inputDataChanged="step_two_form.correspondence.district = $event" v-if="is_foreign"></form-input>


                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label class="col-md-4">
                          <span class="text-info">2. Permanent Address:</span>
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                        </label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <label class="col-md-12">
                          Is Permanent address same as correspondence address ?
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                        </label>
                      </div>
                      <div class="col-md-4">
                        <section style="float:left;">
                          <label class="radio-inline">
                            <input type="radio" :value="true" v-model="step_two_form.is_permanent_same"
                              @change="isPermanentChanged" id="is-permanent" />

                            <span class="text-warning">Yes</span>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" :value="false" v-model="step_two_form.is_permanent_same"
                              @change="isPermanentChanged" id="is-permanent" />
                            <span class="text-warning">No</span>
                          </label>
                        </section>
                      </div>
                    </div>
                  </div>
                  <section>
                    <form-input :label="'(a) C/O'" :input_data="step_two_form.permanent.co" :errors="step_two_form.errors"
                      :error_attr="'permanent_co'" :placeholder="'C/O'"
                      @inputDataChanged="step_two_form.permanent.co = $event"></form-input>

                    <form-input :label="'(b) House No'" :input_data="step_two_form.permanent.house_no"
                      :errors="step_two_form.errors" :error_attr="'permanent_house_no'" :placeholder="'House No'"
                      @inputDataChanged="
                        step_two_form.permanent.house_no = $event
                      "></form-input>

                    <form-input :label="'(c) Street Name /Locality'"
                      :input_data="step_two_form.permanent.street_name_locality" :errors="step_two_form.errors"
                      :error_attr="'permanent_street_name_locality'" :placeholder="'House Name / locality'"
                      @inputDataChanged="
                        step_two_form.permanent.street_name_locality = $event
                      "></form-input>
                  </section>
                  <section>
                    <form-input :label="'(d) Vill/ Town'" :input_data="step_two_form.permanent.vill_town"
                      :errors="step_two_form.errors" :error_attr="'permanent_vill_town'" :placeholder="'Village / Town'"
                      @inputDataChanged="
                        step_two_form.permanent.vill_town = $event
                      "></form-input>

                    <form-input :label="'(e) Pin/Zip Code'" :input_data="step_two_form.permanent.pin_code"
                      :errors="step_two_form.errors" :error_attr="'permanent_pin_code'" :placeholder="'Pin Code'"
                      @inputDataChanged="
                        step_two_form.permanent.pin_code = $event
                      "></form-input>

                    <form-input :label="'(f) P.O'" :input_data="step_two_form.permanent.po" :errors="step_two_form.errors"
                      :error_attr="'permanent_po'" :placeholder="'P.O'"
                      @inputDataChanged="step_two_form.permanent.po = $event"></form-input>


                    <form-select :label="'(g) State'" :options="indian_states" :errors="step_two_form.errors"
                      :error_attr="'permanent_state'" :value_attr="'id'" :name_attr="'name'" :required="true"
                      :old_value="step_two_form.permanent.state" :old_value_attr="'id'"
                      @changedSelect="($event) => { step_two_form.permanent.state = $event; loadDistrictsPermanent($event); }"
                      v-if="!is_foreign"></form-select>
                    <form-input :label="'(g) State / UT'" :input_data="step_two_form.permanent.state"
                      :errors="step_two_form.errors" :error_attr="'permanent_state'" :placeholder="'State'"
                      @inputDataChanged="step_two_form.permanent.state = $event" v-if="is_foreign"></form-input>
                    <!--<form-input :label="'(h) District'" :input_data="step_two_form.permanent.district"
                      :errors="step_two_form.errors" :error_attr="'permanent_district'" :placeholder="'District'"
                      @inputDataChanged="
                        step_two_form.permanent.district = $event
                      "></form-input>-->
                    <form-select :label="'(h) District'" :options="permanent_districts" :errors="step_two_form.errors"
                      :error_attr="'correspondence_district'" :value_attr="'id'" :name_attr="'district_name'"
                      :required="true" :old_value="step_two_form.permanent.district" :old_value_attr="'id'"
                      @changedSelect="($event) => { step_two_form.permanent.district = $event }"
                      v-if="!is_foreign"></form-select>
                    <form-input :label="'(h) District'" :input_data="step_two_form.permanent.district"
                      :errors="step_two_form.errors" :error_attr="'permanent_district'" :placeholder="'District'"
                      @inputDataChanged="step_two_form.permanent.district = $event" v-if="is_foreign"></form-input>








                  </section>
                </div>
                <div class="clearfix"></div>
                <hr />
                <div class="col-md-6">
                  <form-input :label="'3. Contact No'" :input_data="step_two_form.contact_no"
                    :errors="step_two_form.errors" :error_attr="'contact_no'" :placeholder="'Contact No'" :disabled="true"
                    @inputDataChanged="step_two_form.contact_no = $event"></form-input>

                  <form-input :label="'4. Email'" :input_data="step_two_form.email" :errors="step_two_form.errors"
                    :error_attr="'email'" :placeholder="'Email'" :disabled="true"
                    @inputDataChanged="step_two_form.email = $event"></form-input>

                  <form-input :label="'5. Nationality'" :input_data="step_two_form.nationality"
                    :errors="step_two_form.errors" :error_attr="'nationality'" :placeholder="'Nationality'"
                    :required="true" @inputDataChanged="step_two_form.nationality = $event"></form-input>

                  <!-- <form-input
                    :label="'6. State of Domicile'"
                    :input_data="step_two_form.state_domicile"
                    :errors="step_two_form.errors"
                    :error_attr="'state_domicile'"
                    :placeholder="'State of domicile'"
                    @inputDataChanged="step_two_form.state_domicile = $event"
                  ></form-input>
                  -->
                  <!-- <form-input
                    :label="'6. Place of residence urban/rural'"
                    :input_data="step_two_form.place_residence"
                    :errors="step_two_form.errors"
                    :error_attr="'place_residence'"
                    :placeholder="'Place of residence urban/rural'"
                    :required="true"
                    @inputDataChanged="step_two_form.place_residence = $event"
                  ></form-input> -->
                  <form-select :label="'6. Place of residence'" :options="place_of_residence_dropdown"
                    :errors="step_two_form.errors" :error_attr="'place_residence'" :value_attr="'id'"
                    :name_attr="'place_residence'" :required="true" :old_value="step_two_form.place_residence"
                    :old_value_attr="'id'" @changedSelect="step_two_form.place_residence = $event"></form-select>
                </div>
                <div class="col-md-6" v-if="initial_step.is_phd == false  && initial_step.is_cuet_pg == false && initial_step.is_mba==false && initial_step.is_btech == false && initial_step.via_exam_mdes=='TUEE' && initial_step.is_cuet_ug == false && this.application_type=='TUEE'" >
                  <div class="panel panel-info">
                    <div class="panel-body">
                      <!--<form-select :label="'Exam Center 1'" :options="centers"
                        :errors="step_two_form.errors" :error_attr="'exam_center'" :value_attr="'id'"
                        :name_attr="'center_name'" :required="true" :old_value="step_two_form.center"
                        :old_value_attr="'id'" @changedSelect="step_two_form.center = $event"></form-select>
                      <form-select :label="' Exam Center 2'" :options="centers"
                        :errors="step_two_form.errors" :error_attr="'exam_center1'" :value_attr="'id'"
                        :name_attr="'center_name'" :required="true" :old_value="step_two_form.center1"
                        :old_value_attr="'id'" @changedSelect="step_two_form.center1 = $event"></form-select>
                      <form-select :label="' Exam Center 3'" :options="centers"
                        :errors="step_two_form.errors" :error_attr="'exam_center2'" :value_attr="'id'"
                        :name_attr="'center_name'" :required="true" :old_value="step_two_form.center2"
                        :old_value_attr="'id'" @changedSelect="step_two_form.center2 = $event"></form-select>-->

                      <form-select :label="'Exam Center 1'" :options="centers"
                        :errors="step_two_form.errors" :error_attr="'exam_center'" :value_attr="'id'"
                        :name_attr="'center_name'" :required="true" :old_value="step_two_form.center"
                        :old_value_attr="'id'" @changedSelect="checkUnique('center', $event)"></form-select>                   
                      <form-select :label="'Exam Center 2'" :options="centers"
                        :errors="step_two_form.errors" :error_attr="'exam_center1'" :value_attr="'id'"
                        :name_attr="'center_name'" :required="true" :old_value="step_two_form.center1"
                        :old_value_attr="'id'" @changedSelect="checkUnique('center1', $event)" v-if="is_foreign == false"></form-select>               
                      <form-select :label="'Exam Center 3'" :options="centers"
                          :errors="step_two_form.errors" :error_attr="'exam_center2'" :value_attr="'id'"
                          :name_attr="'center_name'" :required="true" :old_value="step_two_form.center2"
                          :old_value_attr="'id'" @changedSelect="checkUnique('center2', $event)" v-if="is_foreign == false"></form-select>
                      <div v-if="step_two_form.errors.center || step_two_form.errors.center1 || step_two_form.errors.center2">
                        Selected exam centers must be unique.
                      </div>
                    </div>
                  </div>
                </div>                       
              </div>
            </fieldset>
            <div class="row btn-row">
              <div class="col-md-6">
                <button type="button" class="btn btn-warning btn-sm next" id="next" @click="goToStepOne">
                  <i class="fa fa-chevron-left"></i>
                  GO BACK
                </button>
                <button type="button" class="btn btn-success btn-sm next" id="next" @click="submitStepTwo"
                  v-if="mode == 'create' && step_two_form.submitted == false">
                  <section v-if="step_two_form.creating">
                    <i class="fa fa-circle-o-notch fa-spin" style="font-size:14px;color:white;"></i>
                    <span class="text-white">Submitting ..</span>
                  </section>
                  <section v-else>
                    <span v-if="step_two_form.submitted == true">Update</span>
                    <span v-else>Save & Proceed</span>
                    <!-- & Next -->
                    <i class="fa fa-save"></i>
                  </section>
                </button>
                <button type="button" class="btn btn-success btn-sm" id="next" @click="updateStepTwo"
                  v-if="step_two_form.submitted == true || mode == 'edit'">
                  <section v-if="step_two_form.updating">
                    <i class="fa fa-circle-o-notch fa-spin" style="font-size:14px;color:white;"></i>
                    <span class="text-white">Updating ..</span>
                  </section>
                  <section v-else>
                    Update
                    <i class="fa fa-save"></i>
                  </section>
                </button>
                <button type="button" class="btn btn-info btn-sm" id="next" @click="goToStepThree"
                  v-if="step_two_form.submitted == true">
                  GO NEXT
                  <i class="fa fa-chevron-right"></i>
                </button>
              </div>
              <div class="col-md-6" v-if="step_two_form.success_msg">
                <div class="alert alert-success">
                  {{ step_two_form.success_msg }}
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <section v-if="step == 3">
      <div class="row">
        <div class="col-md-12">
          <form name="step-one-update" class="form-horizontal form-class application-form">
            <br />
            <fieldset id="academic_details">
              <legend>
                Academic Details:
                <span class="pull-right text-danger">(Step 3)</span>
              </legend>
              <label>
                <span class="text-info"> 1.Qualifications  Write <span class="text-primary">NA/0</span> if not
                  available</span>
              </label>
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Name of Exam Passed
                        /
                        Stream</th>
                      <th>Name of Board / University / Institute</th>
                      <th>Year of Passing / Appearing</th>
                      <th>Class / Grade / Division</th>
                      <th>
                        Subjects taken (Including Honours / Major, if any)
                      </th>
                      <th>Passed or Appearing</th>
                      <th>Total Marks</th>
                      <th>Marks Obtained</th>
                      <th>SGPA/CGPA</th>
                      <th>Percentage</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th>
                        10
                        <sup>th</sup>
                        <span v-if="is_foreign==true">/Equivalent</span>
                      </th>
                      <td>
                        <input required type="text" v-model="
                          step_three_form.academic10.board_university_name
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic10_board_name">{{
                          step_three_form.errors.academic10_board_name
                        }}</small>
                      </td>
                      <td>
                        <!-- <input
                          required
                          type="number"
                          v-model="step_three_form.academic10.passing_year"
                          class="form-control input-sm input-sm"
                          maxlength="4"
                          minlength="4"
                          min="0"
                        /> -->
                        <select v-model="step_three_form.academic10.passing_year" class="form-control input-sm" required>
                          <option value selected disabled>--SELECT--</option>
                          <option :value="year" :key="`hslc_${year}`" v-for="year in yearRange">{{ year }}</option>
                        </select>

                        <small class="text-danger" v-if="step_three_form.errors.academic10_passing_year">{{
                          step_three_form.errors.academic10_passing_year
                        }}</small>
                      </td>
                      <td>
                        <input required type="text" v-model="step_three_form.academic10.class_grade"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic10_class_grade">{{
                          step_three_form.errors.academic10_class_grade
                        }}</small>
                      </td>
                      <td>
                        <textarea cols="30" rows="2" required v-model="step_three_form.academic10.subjects_taken"
                          class="form-control input-sm" placeholder="Subjects taken"></textarea>
                        <small class="text-info"><strong>{{
                          academic10SubjectsTextLeft
                        }}</strong></small>
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic10_subjects_taken
                        ">{{
  step_three_form.errors.academic10_subjects_taken
}}</small>
                      </td>
                      <td>
                        <label class="radio-inline">
                          <input type="radio" :value="true" v-model="
                            step_three_form.academic10.is_passed_appearing
                          " @change="academic10AppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Passed</span> </label><br />
                        <label class="radio-inline">
                          <input type="radio" :value="false" v-model="
                            step_three_form.academic10.is_passed_appearing
                          " @change="academic10AppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Appearing</span>
                        </label>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic10.academic_10_total_mark"
                          class="form-control input-sm input-sm" @input="calculatePercentage()" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_10_total_mark">{{
                          step_three_form.errors.academic_10_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic10.academic_10_mark_obtained"
                          class="form-control input-sm input-sm" @input="calculatePercentage()" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_10_mark_obtained">{{
                          step_three_form.errors.academic_10_mark_obtained
                        }}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic10.cgpa"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic10_cgpa">{{
                          step_three_form.errors.academic10_cgpa
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic10.marks_percentage"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic10_marks_percentage
                        ">{{
  step_three_form.errors.academic10_marks_percentage
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic10.remarks"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic10_remarks">{{
                          step_three_form.errors.academic10_remarks
                        }}</small>
                      </td>
                    </tr>
                    <tr>
                      <th>
                        12
                        <sup>th</sup>
                        <span v-if="is_foreign==true">/Equivalent</span>
                        <form-select :label="'Stream'" :options="streams" :errors="step_three_form.errors"
                          style="margin-top:12px;" :value_attr="'name'" :name_attr="'name'" :required="false"
                          :is_break_label="true" :old_value="step_three_form.academic12.stream" :old_value_attr="'name'"
                          @changedSelect="
                            step_three_form.academic12.stream = $event
                          "></form-select>
                      </th>
                      <td>
                        <input required type="text" v-model="
                          step_three_form.academic12.board_university_name
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic10_board_name">{{
                          step_three_form.errors.academic10_board_name
                        }}</small>




                        <small class="text-danger" v-if="step_three_form.errors.academic12_stream">{{
                          step_three_form.errors.academic12_stream
                        }}</small>
                      </td>
                      <td>
                        <!-- <input
                          required
                          type="number"
                          v-model="step_three_form.academic12.passing_year"
                          class="form-control input-sm input-sm"
                          maxlength="4"
                          minlength="4"
                          min="0"
                        /> -->
                        <select v-model="step_three_form.academic12.passing_year" class="form-control input-sm" required>
                          <option value selected disabled>--SELECT--</option>
                          <option :value="year" :key="`hs_${year}`" v-for="year in yearRange">{{ year }}</option>
                        </select>
                        <small class="text-danger" v-if="step_three_form.errors.academic12_passing_year">{{
                          step_three_form.errors.academic12_passing_year
                        }}</small>
                      </td>
                      <td>
                        <input required type="text" v-model="step_three_form.academic12.class_grade"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic12_class_grade">{{
                          step_three_form.errors.academic12_class_grade
                        }}</small>
                      </td>
                      <td>
                        <textarea cols="30" rows="2" required v-model="step_three_form.academic12.subjects_taken"
                          class="form-control input-sm" placeholder="Subjects taken"></textarea>
                        <small class="text-info"><strong>{{
                          academic12SubjectsTextLeft
                        }}</strong></small>
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic12_subjects_taken
                        ">{{
  step_three_form.errors.academic12_subjects_taken
}}</small><br />

                        <div v-if="initial_step.is_mca == true">
                          <strong>Marks in mathematics</strong> <input required type="number"
                            v-model="step_three_form.academic12.mca_mathematics_mark"
                            class="form-control input-sm input-sm" maxlength="4" minlength="4" min="0" />

                          <small class="text-danger" v-if="
                            step_three_form.errors.mca_mathematics_mark
                          ">{{
  step_three_form.errors.mca_mathematics_mark
}}</small>
                        </div>
                      </td>
                      <td>
                        <label class="radio-inline">
                          <input type="radio" :value="true" v-model="
                            step_three_form.academic12.is_passed_appearing
                          " @change="academic12AppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Passed</span> </label><br />
                        <label class="radio-inline">
                          <input type="radio" :value="false" v-model="
                            step_three_form.academic12.is_passed_appearing
                          " @change="academic12AppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Appearing</span>
                        </label>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic12.academic_12_total_mark"
                          class="form-control input-sm input-sm" @input="calculate12Percentage()" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_12_total_mark">{{
                          step_three_form.errors.academic_12_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic12.academic_12_mark_obtained"
                          class="form-control input-sm input-sm" @input="calculate12Percentage()" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_12_mark_obtained">{{
                          step_three_form.errors.academic_12_mark_obtained
                        }}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic12.cgpa"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic12_cgpa">{{
                          step_three_form.errors.academic12_cgpa
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic12.marks_percentage"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic12_marks_percentage
                        ">{{
  step_three_form.errors.academic12_marks_percentage
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic12.remarks"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic12_remarks">{{
                          step_three_form.errors.academic12_remarks
                        }}</small>
                      </td>
                    </tr>
                    <!--<tr v-if="show_ug == true">-->
                    <tr
                      v-if="initial_step.is_cuet_pg == true || initial_step.is_phd == true || initial_step.is_mba == true || initial_step.is_mdes == true || is_mbbt==true">
                      <th>
                        <form-select :label="'Select Bachelor\'s Degree'" :options="bachelor_degrees"
                          :errors="step_three_form.errors" :error_attr="'academic_bachelor_degree'" :value_attr="'name'"
                          :name_attr="'name'" :required="true" :is_break_label="true"
                          :old_value="step_three_form.academic_bachelor.degree" :old_value_attr="'name'" @changedSelect="
                            step_three_form.academic_bachelor.degree = $event
                          "></form-select>
                      </th>
                      <td>
                        <input required type="text" v-model="
                          step_three_form.academic_bachelor
                            .board_university_name
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_bachelor_board_name
                        ">{{
  step_three_form.errors.academic_bachelor_board_name
}}</small>
                      </td>
                      <td>
                        <!-- <input
                          required
                          type="number"
                          v-model="
                            step_three_form.academic_bachelor.passing_year
                          "
                          class="form-control input-sm input-sm"
                          maxlength="4"
                          minlength="4"
                          min="0"
                        /> -->
                        <select v-model="step_three_form.academic_bachelor.passing_year" class="form-control input-sm"
                          required>
                          <option value selected disabled>--SELECT--</option>
                          <option :value="year" :key="`bachelor_${year}`" v-for="year in yearRange">{{ year }}</option>
                        </select>
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_bachelor_passing_year
                        ">{{
  step_three_form.errors
    .academic_bachelor_passing_year
}}</small>
                      </td>
                      <td>
                        <input required type="text" v-model="
                          step_three_form.academic_bachelor.class_grade
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_bachelor_class_grade
                        ">{{
  step_three_form.errors.academic_bachelor_class_grade
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic_bachelor.major"
                          class="form-control input-sm input-sm" placeholder="Major if any" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_bachelor_major">{{
                          step_three_form.errors.academic_bachelor_major
                        }}</small>

                        <textarea cols="30" rows="2" required v-model="
                          step_three_form.academic_bachelor.subjects_taken
                        " class="form-control input-sm" placeholder="Subjects taken"
                          style="margin-top:12px;"></textarea>
                        <small class="text-info"><strong>{{
                          academicBachelorSubjectsTextLeft
                        }}</strong></small>
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_bachelor_subjects_taken
                        ">{{
  step_three_form.errors
    .academic_bachelor_subjects_taken
}}</small>
                      </td>
                      <td>
                        <label class="radio-inline">
                          <input type="radio" :value="true" v-model="
                            step_three_form.academic_bachelor
                              .is_passed_appearing
                          " @change="academicBachelorAppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Passed</span> </label><br />
                        <label class="radio-inline">
                          <input type="radio" :value="false" v-model="
                            step_three_form.academic_bachelor
                              .is_passed_appearing
                          " @change="academicBachelorAppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Appearing</span>
                        </label>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic_bachelor.academic_graduation_total_mark"
                          class="form-control input-sm input-sm" @input="calculateGradPercentage" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_graduation_total_mark">{{
                          step_three_form.errors.academic_graduation_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic_bachelor.academic_graduation_mark_obtained"
                          class="form-control input-sm input-sm" @input="calculateGradPercentage" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_graduation_mark_obtained">{{
                          step_three_form.errors.academic_graduation_mark_obtained
                        }}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic_bachelor.cgpa"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_bachelor_cgpa">{{
                          step_three_form.errors.academic_bachelor_cgpa
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="
                          step_three_form.academic_bachelor.marks_percentage
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_bachelor_marks_percentage
                        ">{{
  step_three_form.errors
    .academic_bachelor_marks_percentage
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic_bachelor.remarks"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_bachelor_remarks
                        ">{{
  step_three_form.errors.academic_bachelor_remarks
}}</small>
                      </td>


                    </tr>
                    <!--<tr v-if="show_pg == true">-->


                    <tr v-if="initial_step.is_cuet_pg == true || initial_step.is_phd == true ">
                      <th>
                        <form-select :label="
                          'Select Master\'s degree (for M.Tech. candidate optional )'
                        " :options="post_degrees" :errors="step_three_form.errors" :error_attr="'master_degree'"
                          :value_attr="'name'" :name_attr="'name'" :required="false" :is_break_label="true" :old_value="
                            step_three_form.academic_post_graduate.degree
                          " :old_value_attr="'name'" @changedSelect="
  step_three_form.academic_post_graduate.degree = $event
"></form-select>
                      </th>
                      <td>
                        <input required type="text" v-model="
                          step_three_form.academic_post_graduate
                            .board_university_name
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_post_graduate_board_name
                        ">{{
  step_three_form.errors
    .academic_post_graduate_board_name
}}</small>
                      </td>
                      <td>
                        <!-- <input
                          required
                          type="number"
                          v-model="
                            step_three_form.academic_post_graduate.passing_year
                          "
                          class="form-control input-sm input-sm"
                          maxlength="4"
                          minlength="4"
                          min="0"
                        /> -->
                        <select v-model="step_three_form.academic_post_graduate.passing_year"
                          class="form-control input-sm" required>
                          <option value selected disabled>--SELECT--</option>
                          <option :value="year" :key="`master_${year}`" v-for="year in yearRange">{{ year }}</option>
                        </select>
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_post_graduate_passing_year
                        ">{{
  step_three_form.errors
    .academic_post_graduate_passing_year
}}</small>
                      </td>
                      <td>
                        <input required type="text" v-model="
                          step_three_form.academic_post_graduate.class_grade
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_post_graduate_class_grade
                        ">{{
  step_three_form.errors
    .academic_post_graduate_class_grade
}}</small>
                      </td>
                      <td>
                        <textarea cols="30" rows="2" required v-model="
                          step_three_form.academic_post_graduate
                            .subjects_taken
                        " class="form-control input-sm" placeholder="Subjects taken"></textarea>
                        <small class="text-info"><strong>{{
                          academicPostSubjectsTextLeft
                        }}</strong></small>
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_post_graduate_subjects_taken
                        ">{{
  step_three_form.errors
    .academic_post_graduate_subjects_taken
}}</small>
                      </td>
                      <td>
                        <label class="radio-inline">
                          <input type="radio" :value="true" v-model="
                            step_three_form.academic_post_graduate
                              .is_passed_appearing
                          " @change="academicPostAppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Passed</span> </label><br />
                        <label class="radio-inline">
                          <input type="radio" :value="false" v-model="
                            step_three_form.academic_post_graduate
                              .is_passed_appearing
                          " @change="academicPostAppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Appearing</span>
                        </label>
                      </td>
                      <td>
                        <input type="number"
                          v-model="step_three_form.academic_post_graduate.academic_post_graduation_total_mark"
                          class="form-control input-sm input-sm" @input="calculatePostGradPercentage()" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_post_graduation_total_mark
                        ">{{
  step_three_form.errors.academic_post_graduation_total_mark
}}</small>
                      </td>
                      <td>
                        <input type="number"
                          v-model="step_three_form.academic_post_graduate.academic_post_graduation_mark_obtained"
                          class="form-control input-sm input-sm" @input="calculatePostGradPercentage()" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_post_graduation_mark_obtained
                        ">{{
  step_three_form.errors.academic_post_graduation_mark_obtained
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic_post_graduate.cgpa"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_post_graduate_cgpa
                        ">{{
  step_three_form.errors.academic_post_graduate_cgpa
}}</small>
                      </td>
                      <td>
                        <input type="number" v-model="
                          step_three_form.academic_post_graduate
                            .marks_percentage
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_post_graduate_marks_percentage
                        ">{{
  step_three_form.errors
    .academic_post_graduate_marks_percentage
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="
                          step_three_form.academic_post_graduate.remarks
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_post_graduate_remarks
                        ">{{
  step_three_form.errors
    .academic_post_graduate_remarks
}}</small>
                      </td>
                    </tr>
                    <tr v-if="is_lateral == true">
                      <th>
                        Diploma In Engineering (
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>)
                      </th>
                      <td>
                        <input required type="text" v-model="
                          step_three_form.academic_diploma
                            .board_university_name
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_diploma_board_name
                        ">{{
  step_three_form.errors.academic_diploma_board_name
}}</small>
                      </td>
                      <td>
                        <!-- <input
                          required
                          type="number"
                          v-model="
                            step_three_form.academic_diploma.passing_year
                          "
                          class="form-control input-sm input-sm"
                          maxlength="4"
                          minlength="4"
                          min="0"
                        /> -->
                        <select v-model="step_three_form.academic_diploma.passing_year" class="form-control input-sm"
                          required>
                          <option value selected disabled>--SELECT--</option>
                          <option :value="year" :key="`diploma_${year}`" v-for="year in yearRange">{{ year }}</option>
                        </select>
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_diploma_passing_year
                        ">{{
  step_three_form.errors.academic_diploma_passing_year
}}</small>
                      </td>
                      <td>
                        <input required type="text" v-model="step_three_form.academic_diploma.class_grade"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors.academic_diploma_class_grade
                        ">{{
  step_three_form.errors.academic_diploma_class_grade
}}</small>
                      </td>
                      <td>
                        <textarea cols="30" rows="2" required v-model="
                          step_three_form.academic_diploma.subjects_taken
                        " class="form-control input-sm" placeholder="Subjects taken"></textarea>
                        <small class="text-info"><strong>{{
                          academicDiplomaSubjectsTextLeft
                        }}</strong></small>
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_diploma_subjects_taken
                        ">{{
  step_three_form.errors
    .academic_diploma_subjects_taken
}}</small>
                      </td>
                      <td>
                        <label class="radio-inline">
                          <input type="radio" :value="true" v-model="
                            step_three_form.academic_diploma
                              .is_passed_appearing
                          " @change="academicDiplomaAppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Paased</span> </label><br />
                        <label class="radio-inline">
                          <input type="radio" :value="false" v-model="
                            step_three_form.academic_diploma
                              .is_passed_appearing
                          " @change="academicDiplomaAppearedChanged" id="is_sport_represented" />
                          <span class="text-warning">Appearing</span>
                        </label>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic_diploma.academic_diploma_total_mark"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_diploma_total_mark">{{
                          step_three_form.errors.academic_diploma_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="step_three_form.academic_diploma.academic_diploma_mark_obtained"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_diploma_mark_obtained">{{
                          step_three_form.errors.academic_diploma_mark_obtained
                        }}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic_diploma.cgpa"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_diploma_cgpa">{{
                          step_three_form.errors.academic_diploma_cgpa
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model="
                          step_three_form.academic_diploma.marks_percentage
                        " class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.errors
                            .academic_diploma_marks_percentage
                        ">{{
  step_three_form.errors
    .academic_diploma_marks_percentage
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.academic_diploma.remarks"
                          class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="step_three_form.errors.academic_diploma_remarks">{{
                          step_three_form.errors.academic_diploma_remarks
                        }}</small>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <label>
                <span class="text-info">Pre-Qualifying/ Semester wise/ Year wise/ Any Other Qualification Marks of
                  Qualifying Exam (Please enter information here by clicking Add New button)</span><br />
                <span class="text-danger">a. All M.Tech applicants whether GATE qualified or not, can appear for TUEE 2023-24. M.Tech applicants who are GATE qualified can apply for direct admission to TU by clicking on the ADD NEW button.<br/></span>
                <span class="text-danger">b. Ph.D applicants please enter NET/GATE examination details using the ADD NEW button.<br/></span>
                <span class="text-danger">c. M.Des : CEED / GATE / DAT qualified applicants can apply for Direct admission to Tezpur University.</span>
                  
                
              </label>
              <button type="button" class="btn btn-xs btn-info" @click="addNewQualification" style="float:right;">
                ADD NEW
                <i class="fa fa-plus-circle"></i>
              </button>

              <div class="table-responsive" v-if="
                step_three_form.other_qualifications.qualifications.length > 0
              ">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Name of Exam Passed</th>
                      <th>Name of Board / University / Institute</th>
                      <th>Year of Passing / Appeared</th>
                      <th>Class / Grade / Division</th>
                      <th>
                        Subjects taken (Including Honours / Major, if any)
                      </th>
                      <th>Total Marks</th>
                      <th>Marks obtained</th>
                      <th>SGPA/CGPA</th>
                      <th>Percentage of marks</th>
                      <th>Remarks</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(qual, index) in step_three_form
                      .other_qualifications.qualifications" :key="index">
                      <td>
                        <input required type="text" v-model="qual.exam_name" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors
                            .exam_name
                        ">{{
  step_three_form.other_qualifications.errors
    .exam_name
}}</small>
                      </td>
                      <td>
                        <input required type="text" v-model="qual.board_name" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors
                            .board_name
                        ">{{
  step_three_form.other_qualifications.errors
    .board_name
}}</small>
                      </td>
                      <td>
                        <!-- <input
                          required
                          type="number"
                          v-model="qual.passing_year"
                          class="form-control input-sm input-sm"
                          maxlength="4"
                          minlength="4"
                          min="0"
                        /> -->
                        <select v-model="qual.passing_year" class="form-control input-sm" required>
                          <option value selected disabled>--SELECT--</option>
                          <option :value="year" :key="`diploma_${year}`" v-for="year in yearRange">{{ year }}</option>
                        </select>
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors
                            .passing_year
                        ">{{
  step_three_form.other_qualifications.errors
    .passing_year
}}</small>
                      </td>
                      <td>
                        <input required type="text" v-model="qual.class_grade" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors
                            .class_grade
                        ">{{
  step_three_form.other_qualifications.errors
    .class_grade
}}</small>
                      </td>
                      <td>
                        <textarea cols="30" rows="2" required v-model="qual.subjects_taken"
                          class="form-control input-sm"></textarea>
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors
                            .subjects_taken
                        ">{{
  step_three_form.other_qualifications.errors
    .subjects_taken
}}</small>
                      </td>

                      <td>
                        <input type="text" v-model.number="qual.total_mark" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors.total_mark
                        ">{{
  step_three_form.other_qualifications.errors.total_mark
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model.number="qual.mark_obtained" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors.mark_obtained
                        ">{{
  step_three_form.other_qualifications.errors.mark_obtained
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="qual.cgpa" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors.cgpa
                        ">{{
  step_three_form.other_qualifications.errors.cgpa
}}</small>
                      </td>
                      <td>
                        <input type="number" v-model="qual.marks_percentage" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors
                            .marks_percentage
                        ">{{
  step_three_form.other_qualifications.errors
    .marks_percentage
}}</small>
                      </td>
                      <td>
                        <input type="text" v-model="qual.remarks" class="form-control input-sm input-sm" />
                        <small class="text-danger" v-if="
                          step_three_form.other_qualifications.errors.remarks
                        ">{{
  step_three_form.other_qualifications.errors.remarks
}}</small>
                      </td>
                      <td>
                        <button type="button" class="btn btn-xs btn-warning" @click="removeQualification(qual, index)">
                          Remove
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr />
              <div class="table-responsive qualifing">
                <!-- v-if="(initial_step.is_btech == true || is_integrated) && !is_integrated_mcom" 
                <label>
                  <span class="text-info">Qualifing Marks (if not available enter 0 zero)</span>
                </label>
                <div class="alert alert-info">
                  Note : Please follow the sequence given below while entering the marks
                  <ul>
                    <li>For Mark based system:
                      <ul>

                        <li>Column A : Total Marks in the subject (
                          English/Physics/Chemistry/Mathematics/Biology/Statistics)</li>
                        <li>Column B : Marks secured in the subject (
                          English/Physics/Chemistry/Mathematics/Biology/Statistics)</li>
                        <li>Column C : Percentage in the subject (
                          English/Physics/Chemistry/Mathematics/Biology/Statistics)</li>
                      </ul>

                    </li>
                    <li>For Grade based system:
                      <ul>
                        <li>Column A : Total Marks in the subject (
                          English/Physics/Chemistry/Mathematics/Biology/Statistics)</li>
                        <li>Column B : Grade secured in the subject (
                          English/Physics/Chemistry/Mathematics/Biology/Statistics)</li>
                        <li>Column C : Percentage in the subject (
                          English/Physics/Chemistry/Mathematics/Biology/Statistics) [To be calculated based on the
                          percentage formula set by the Council / Board. Upload the percentile conversion formula in the
                          Upload Document section (Compulsory)]</li>
                      </ul>

                    </li>

                  </ul>
                </div>
                <table class="table table-bordered table-striped marks_columns_min_width" v-if="initial_step.is_mba == false">
                  <thead>
                    <th>Subject</th>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                  </thead>
                  <tbody>
                    <tr>
                      <th>English in 10<sup>th</sup> (Core/Compulsory)</th>
                      <td>
                        <input type="number" v-model.number="step_three_form.qualifing.english_mark_10_total_mark"
                          class="form-control input-sm input-sm" maxlength="4" minlength="2" required />
                        <small class="text-danger" v-if="step_three_form.errors.english_mark_10_total_mark">{{
                            step_three_form.errors.english_mark_10_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.qualifing.english_mark_10_grade"
                          class="form-control input-sm input-sm" maxlength="10" minlength="2" required />
                        <small class="text-danger" v-if="step_three_form.errors.english_mark_10_grade">{{
                            step_three_form.errors.english_mark_10_grade
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model.number="step_three_form.qualifing.english_mark_10"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" required />
                        <small class="text-danger" v-if="step_three_form.errors.english_mark_10">{{
                            step_three_form.errors.english_mark_10
                        }}</small>
                      </td>
                    </tr>
                    <tr>
                      <th>Physics in 10+2</th>
                      <td>
                        <input type="number" v-model.number="step_three_form.qualifing.physics_total_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" required />
                        <small class="text-danger" v-if="step_three_form.errors.physics_total_mark">{{
                            step_three_form.errors.physics_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.qualifing.physics_grade"
                          class="form-control input-sm input-sm" maxlength="3" required />
                        <small class="text-danger" v-if="step_three_form.errors.physics_grade">{{
                            step_three_form.errors.physics_grade
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model.number="step_three_form.qualifing.physics_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" required />
                        <small class="text-danger" v-if="step_three_form.errors.physics_mark">{{
                            step_three_form.errors.physics_mark
                        }}</small>
                      </td>
                    </tr>
                    <tr>

                      <th>Chemistry in 10+2</th>

                      <td>
                        <input type="number" v-model.number="step_three_form.qualifing.chemistry_total_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" required />
                        <small class="text-danger" v-if="step_three_form.errors.chemistry_total_mark">{{
                            step_three_form.errors.chemistry_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="text" v-model="step_three_form.qualifing.chemistry_grade"
                          class="form-control input-sm input-sm" maxlength="3" required />
                        <small class="text-danger" v-if="step_three_form.errors.chemistry_grade">{{
                            step_three_form.errors.chemistry_grade
                        }}</small>
                      </td>
                      <td>
                        <input type="number" v-model.number="step_three_form.qualifing.chemistry_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" required />
                        <small class="text-danger" v-if="step_three_form.errors.chemistry_mark">{{
                            step_three_form.errors.chemistry_mark
                        }}</small>
                      </td>
                    </tr>
                    <tr>
                      <th>Mathematics in 10+2</th>

                      <td>
                        <input type="number" required v-model="step_three_form.qualifing.mathematics_total_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" />
                        <small class="text-danger" v-if="step_three_form.errors.mathematics_total_mark">{{
                            step_three_form.errors.mathematics_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="text" required v-model="step_three_form.qualifing.mathematics_grade"
                          class="form-control input-sm input-sm" maxlength="3" />
                        <small class="text-danger" v-if="step_three_form.errors.mathematics_grade">{{
                            step_three_form.errors.mathematics_grade
                        }}</small>
                      </td>
                      <td>
                        <input type="number" required v-model="step_three_form.qualifing.mathematics_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" />
                        <small class="text-danger" v-if="step_three_form.errors.mathematics_mark">{{
                            step_three_form.errors.mathematics_mark
                        }}</small>
                      </td>
                    </tr>
                    <tr>
                      <th>English in 10+2 (Core/ Compulsory)</th>

                      <td>
                        <input type="number" required v-model.number="step_three_form.qualifing.english_total_mark"
                          class="form-control input-sm input-sm" maxlength="4" />
                        <small class="text-danger" v-if="step_three_form.errors.english_total_mark">{{
                            step_three_form.errors.english_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="text" required v-model="step_three_form.qualifing.english_grade"
                          class="form-control input-sm input-sm" maxlength="5" />
                        <small class="text-danger" v-if="step_three_form.errors.english_grade">{{
                            step_three_form.errors.english_grade
                        }}</small>
                      </td>
                      <td>
                        <input type="number" required v-model.number="step_three_form.qualifing.english_mark"
                          class="form-control input-sm input-sm" maxlength="3" />
                        <small class="text-danger" v-if="step_three_form.errors.english_mark">{{
                            step_three_form.errors.english_mark
                        }}</small>
                      </td>
                    </tr>
                    <tr>
                      <th>Statistics in 10+2</th>

                      <td>
                        <input type="number" required v-model.number="step_three_form.qualifing.statistics_total_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" />
                        <small class="text-danger" v-if="step_three_form.errors.statistics_total_mark">{{
                            step_three_form.errors.statistics_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="text" required v-model="step_three_form.qualifing.statistics_grade"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" />
                        <small class="text-danger" v-if="step_three_form.errors.statistics_grade">{{
                            step_three_form.errors.statistics_grade
                        }}</small>
                      </td>
                      <td>
                        <input type="number" required v-model.number="step_three_form.qualifing.statistics_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" />
                        <small class="text-danger" v-if="step_three_form.errors.statistics_mark">{{
                            step_three_form.errors.statistics_mark
                        }}</small>
                      </td>

                    </tr>
                    <tr>
                      <th>Biology in 10+2</th>

                      <td>
                        <input type="number" required v-model.number="step_three_form.qualifing.biology_total_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" />
                        <small class="text-danger" v-if="step_three_form.errors.biology_total_mark">{{
                            step_three_form.errors.biology_total_mark
                        }}</small>
                      </td>
                      <td>
                        <input type="text" required v-model="step_three_form.qualifing.biology_grade"
                          class="form-control input-sm input-sm" maxlength="3" />
                        <small class="text-danger" v-if="step_three_form.errors.biology_grade">{{
                            step_three_form.errors.biology_grade
                        }}</small>
                      </td>
                      <td>
                        <input type="number" required v-model.number="step_three_form.qualifing.biology_mark"
                          class="form-control input-sm input-sm" maxlength="3" minlength="2" />
                        <small class="text-danger" v-if="step_three_form.errors.biology_mark">{{
                            step_three_form.errors.biology_mark
                        }}</small>
                      </td>
                    </tr>
                  </tbody>
                </table>-->
              </div>

              <div class="row jee" v-if="initial_step.is_btech == true">
                <div class="col-md-12">
                  <label>
                    <span class="text-info">2. JEE Mains</span>
                    <small>(If you donot have the JEE Admit Card or if you have multiple JEE Roll Nos, please enter
                      'NA')</small>
                  </label>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="jee_roll" class="col-md-4 control-label text-right margin-label">
                              (a) JEE Main Roll No:
                              <!-- <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span> -->
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.jee.roll_no"
                                class="form-control input-sm input-sm" />
                              <strong class="text-info small">Enter NA if Roll No not available.</strong>
                              <small class="text-danger" v-if="step_three_form.errors.jee_roll_no">{{
                                step_three_form.errors.jee_roll_no
                              }}</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="jee_form_no" class="col-md-4 control-label text-right margin-label">
                              (b) JEE Form No. / Application no.:
                              <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.jee.form_no"
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.jee_form_no">{{
                                step_three_form.errors.jee_form_no
                              }}</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="jee_year" class="col-md-4 control-label text-right margin-label">
                              (c) Year:
                              <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.jee.year"
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.jee_year">{{
                                step_three_form.errors.jee_year
                              }}</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row jee" v-if="initial_step.is_cuet_ug == true || this.application_type=='CUET' || this.application_type=='CHINESE'" >
                <div class="col-md-12">
                  <label>
                    <span class="text-info"> 2. CUET Details</span><span class="text-danger"
                      style="font-size: 24px; vertical-align: middle;">*</span>
                    <!--v-if="initial_step.is_cuet_ug == true"-->
                    <small>( As Per CUET Form/Admit card/Result Details)</small>
                  </label>

                  <div class="alert alert-danger">
                <strong>Note:</strong> - Attention CUET applicants.<br />
                You are requested to Update exact Percentile Score And Normalised Score as per your NTA Score Card After Decleration of CUET result. 
                </div>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="jee_roll" class="col-md-4 control-label text-right margin-label">
                              (a) CUET Roll No:
                              <span class="text-danger" style="font-size: 24px; vertical-align: middle;"></span>
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.cuet.cuet_roll_no" 
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.cuet_roll_no">{{
                                step_three_form.errors.cuet_roll_no
                              }}</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="cuet_form_no" class="col-md-4 control-label text-right margin-label">
                              (b) CUET Application No:
                              <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.cuet.cuet_form_no"
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.cuet_form_no">{{
                                step_three_form.errors.cuet_form_no
                              }}</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="cuet_year" class="col-md-4 control-label text-right margin-label">
                              (c) Year:
                              <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.cuet.cuet_year"
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.cuet_year">{{
                                step_three_form.errors.cuet_year
                              }}</small>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!--<div class="row">
                        <div class="form-group"> 
                          <div class="col-md-3 control-label text-right margin-label">
                            <div class="row">
                              <div class="col-md-12">
                                <label class="radio-inline">
                                  <input type="radio" :value="false" v-model="step_three_form.is_cuet_qualified"
                                    id="is_cuet_qualified"  @click="emptyCuetDtl" />
                                  <span class="text-warning">Appeared</span>
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" :value="true" v-model="step_three_form.is_cuet_qualified"
                                    id="is_cuet_qualified" @click="addNewCuetDtls" />
                                  <span class="text-warning">Qualified</span>
                                </label>
                              </div>
                            </div>
                          </div>
                       </div>
                      </div> -->

                      <div v-for="(cuet, index) in step_three_form.cuet.subject_wise_dtl" :key="index">
                        <div class="row">
                          <div class="form-group">

                            <div class="col-md-1"></div>
                            <label class="col-md-1" v-if="initial_step.is_cuet_ug == true && index == 0">Language
                              Subjects</label>
                            <label class="col-md-1" v-if="initial_step.is_cuet_ug != true">Test Code</label>
                            <label class="col-md-1" v-if="initial_step.is_cuet_ug == true && index != 0"> Domain
                              Subjects</label>

                            <div class="col-md-2" v-if="index == 0">
                              <select v-model="cuet.subjects" class="form-control input-sm"
                                v-if="initial_step.is_cuet_ug == true" required>
                                <option value selected disabled>--SELECT--</option>
                                <option value="English">English</option>
                              </select>
                              <select v-model="cuet.subjects" class="form-control input-sm"
                                v-if="initial_step.is_cuet_ug != true" @change="subjectChanged(cuet.subjects, index)"
                                required>
                                <option value selected disabled>--SELECT--</option>
                                <option :value="val.subject_name" v-for="val in cuet_subject">{{ val.subject_name }}
                                </option>
                              </select>
                            </div>

                            <div class="col-md-2" v-if="index != 0">
                              <select v-model="cuet.subjects" class="form-control input-sm"
                                @change="subjectChanged(cuet.subjects, index)" required>
                                <option value selected disabled>--SELECT--</option>
                                <option :value="val.subject_name" v-for="val in cuet_subject">{{ val.subject_name }}
                                </option>
                              </select>
                            </div>

                            <label class="col-md-1">CUET Normalised Score(In Figures)</label>
                            <div class="col-md-2">
                              <input required type="text" v-model="cuet.marks" class="form-control input-sm input-sm" />
                            </div>
                            <!--<label class="col-md-1">CUET Percentile Score</label>
                            <div class="col-md-2">
                              <input required type="text" v-model="cuet.percentile"
                                class="form-control input-sm input-sm" />
                            </div>-->

                            <div class="col-md-1">
                              <button type="button" class="btn btn-xs btn-warning" @click="removeCuetDtl(cuet, index)"
                                v-if="index != 0">
                                Remove
                              </button>
                              <button type="button" class="btn btn-xs btn-info" @click="addNewCuetDtls" v-if="index == 0">
                                Add New.
                                <i class="fa fa-plus-circle"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>


              <div class="row jee" v-if="isThisCourseApplied('MTECH') == true">
                <div class="col-md-12">
                  <label>
                    <span class="text-info">*. GATE Details</span>
                    <small>( As Per GATE Form/Admit card/Result Details)</small>
                  </label>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="jee_roll" class="col-md-4 control-label text-right margin-label">
                              (a) GATE Roll No:
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.gate.gate_roll_no"
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.gate_roll_no">{{
                                step_three_form.errors.gate_roll_no
                              }}</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="gate_form_no" class="col-md-4 control-label text-right margin-label">
                              (b) GATE Form No:
                              <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.gate.gate_form_no"
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.gate_form_no">{{
                                step_three_form.errors.gate_form_no
                              }}</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="gate_year" class="col-md-4 control-label text-right margin-label">
                              (c) Year:
                              <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                            </label>
                            <div class="col-md-7">
                              <input type="text" v-model="step_three_form.gate.gate_year"
                                class="form-control input-sm input-sm" />
                              <small class="text-danger" v-if="step_three_form.errors.gate_year">{{
                                step_three_form.errors.gate_year
                              }}</small>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group">
                          <div class="col-md-1"></div>
                          <div class="col-md-3">
                            <div class="row">
                              <div class="col-md-12">
                                <label class="radio-inline">
                                  <input type="radio" :value="false" v-model="step_three_form.is_gate_qualified"
                                    id="is_gate_qualified" />
                                  <span class="text-warning">Appeared</span>
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" :value="true" v-model="step_three_form.is_gate_qualified"
                                    id="is_gate_qualified" />
                                  <span class="text-warning">Qualified</span>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <form-input :label="'CUET score'" :input_data="step_three_form.gate.gate_score"
                              :errors="step_three_form.errors" :error_attr="'gate_score'"
                              :placeholder="'Enter CUET Score'" :required="false" :label_style="'margin-left:12px;'"
                              :show_text_limit="false" v-if="step_three_form.is_gate_qualified == true" @inputDataChanged="
                                step_three_form.gate.gate_score = $event
                              "></form-input>
                          </div>
                          <div class="col-md-4">
                            <form-input :label="'CUET Rank'" :input_data="step_three_form.gate.gate_rank"
                              :errors="step_three_form.errors" :error_attr="'gate_rank'" :placeholder="'Enter CUET Rank'"
                              :required="false" :label_style="'margin-left:12px;'" :show_text_limit="false"
                              v-if="step_three_form.is_gate_qualified == true" @inputDataChanged="
                                step_three_form.gate.gate_rank = $event
                              "></form-input>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="row mba" v-if="isThisCourseApplied('MBA') == true">
                <div class="col-md-12">
                  <label>
                    *. CAT/MAT/ATMA/XAT/GMAT/CMAT Details (only for MBA student) (if not enter <span class="text-info">NA,
                      Zero</span>)
                  </label>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Name of the Exam</th>
                                <th>Registration No.</th>
                                <th>Date of Exam</th>
                                <th>Score Obtained</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(data, index) in step_three_form.mba_exam_data.details" :key="index">
                                <td>
                                  <strong>{{ data.name_of_the_exam }}</strong> 
                                  <span v-if="data.name_of_the_exam=='CAT' || data.name_of_the_exam=='XAT' || data.name_of_the_exam=='GMAT' || data.name_of_the_exam=='CMAT'">(Enter Percentile)</span>
                                  <span v-else>(Enter Composite Score)</span>
                                </td>
                                <td>
                                  <input type="text" v-model="data.registration_no" class="form-control input-sm" value=""
                                    required />
                                  <small class="text-danger"
                                    v-if="step_three_form.errors['mba_exams.' + index + '.registration_no']">{{
                                      step_three_form.errors['mba_exams.' + index + '.registration_no']
                                    }}</small>
                                </td>
                                <td>
                                  <input type="date" class="form-control input-sm" v-model="data.date_of_exam" required />
                                  <small class="text-danger"
                                    v-if="step_three_form.errors['mba_exams.' + index + '.date_of_exam']">{{
                                      step_three_form.errors['mba_exams.' + index + '.date_of_exam']
                                    }}</small>
                                </td>
                                <td>
                                  <input type="number" v-model="data.score_obtained"
                                    class="form-control input-sm text-right" required />
                                  <small class="text-danger"
                                    v-if="step_three_form.errors['mba_exams.' + index + '.score_obtained']">{{
                                      step_three_form.errors['mba_exams.' + index + '.score_obtained']
                                    }}</small>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row is_master_in_design" v-if="is_master_in_design">

              </div>
              <div class="row" v-if="isThisCourseApplied('MBA') == true">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <h3>Work Experience(For MBA Students)(If Any) <button type="button" class="btn btn-primary btn-xs" @click="addMoreExperience()">Add More</button></h3>
                    <table class="table table-bordered">
                      <thead>
                        <th>Organization</th>
                        <th>Designation</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Details</th>
                      </thead>
                      <tbody>

                        <tr v-for="item in step_three_form.work_experience" @key="item.key">
                          <td>
                            <input v-model="item.organization" type="text" name="exp_1" class="form-control input-sm"
                              placeholder="organization name" />
                          </td>
                          <td>
                            <input v-model="item.designation" type="text" name="exp_1" class="form-control input-sm"
                              placeholder="designation" />
                          </td>
                          <td>
                            <input v-model="item.from" type="date" name="exp_1" class="form-control input-sm" />
                          </td>
                          <td>
                            <input v-model="item.to" type="date" name="exp_1" class="form-control input-sm" />
                          </td>
                          <td>
                            <textarea v-model="item.details" class="form-control input-sm"
                              placeholder="detail"></textarea>
                          </td>
                          <td>
                            <button class="btn btn-warning btn-xs" @click="removeWorkExperience(item.key)">Remove</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="row jee">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group" v-if="is_foreign == false">
                        <label for="is_sport_represented" class="col-md-7">
                          <span v-if="initial_step.is_btech == true"></span>
                          <span v-else></span> 3. Are you medalist in any
                          National and International Sports events?
                        </label>
                        <div class="col-md-5">
                          <div class="row">
                            <div class="col-md-12">
                              <label class="radio-inline">
                                <input type="radio" :value="true" v-model="step_three_form.is_sport_represented"
                                  id="is_sport_represented" />
                                <span class="text-warning">Yes</span>
                              </label>
                              <label class="radio-inline">
                                <input type="radio" :value="false" v-model="step_three_form.is_sport_represented"
                                  id="is_sport_represented" />
                                <span class="text-warning">No</span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <form-select :label="'Medal Type'" :options="medals" :errors="step_three_form.errors"
                        :error_attr="'medel_type'" :value_attr="'id'" :name_attr="'name'" :required="true"
                        :old_value="step_three_form.medel_type" :old_value_attr="'id'" @changedSelect="
                          step_three_form.medel_type = $event
                        " v-if="step_three_form.is_sport_represented == true"></form-select>

                      <form-select 
                      :label="'Sporting event'" 
                      :options="sports" :errors="step_three_form.errors" 
                      :error_attr="'sport_played'" 
                      :value_attr="'name'"
                      :name_attr="'name'" :required="true"
                      :old_value="step_three_form.sport_played" 
                      :old_value_attr="'id'" 
                      @changedSelect="
                          step_three_form.sport_played = $event
                        " v-if="step_three_form.is_sport_represented == true"></form-select> 

                      <!--<form-input :label="'Sporting event'" :input_data="step_three_form.sport_played"
                        :errors="step_three_form.errors" :error_attr="'sport_played'" :placeholder="'Please specify'"
                        :required="false" @inputDataChanged="
                          step_three_form.sport_played = $event
                        " v-if="step_three_form.is_sport_represented == true"></form-input>-->

                      <div class="form-group">
                        <label for="is_prizes_distinction" class="col-md-7">
                          <span v-if="initial_step.is_btech == true"></span>
                          <span v-else></span>4 . Academic
                          distinction/Medals/Prizes, <br />
                          if any ?
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                        </label>
                        <div class="col-md-5">
                          <label class="radio-inline">
                            <input type="radio" :value="true" v-model="step_three_form.is_prizes_distinction"
                              id="is_prizes_distinction" />
                            <span class="text-warning">Yes</span>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" :value="false" v-model="step_three_form.is_prizes_distinction"
                              id="is_prizes_distinction" />
                            <span class="text-warning">No</span>
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="other_information" class="col-md-7">
                          <span></span> 5. Other information worth
                          mentioning including publications, if any (If not available enter NA)?
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                        </label>
                        <div class="col-md-5">
                          <textarea cols="30" rows="2" required v-model="step_three_form.other_information"
                            class="form-control input-sm"></textarea>
                          <small class="text-info"><strong>{{
                            academicOtherInfoSubjectsTextLeft
                          }}</strong></small>
                          <small class="text-danger" v-if="step_three_form.errors.other_information">{{
                            step_three_form.errors.other_information
                          }}</small>
                        </div>
                      </div>


                      <form-input :label="'5. GAT-B Score'" :input_data="step_three_form.gat_b_score"
                      :errors="step_three_form.errors" :error_attr="'gat_b_score'" :placeholder="'GAT-B Score'"
                      :required="true" @inputDataChanged="step_three_form.gat_b_score = $event" v-if="is_mbbt"></form-input>


                      <div class="form-group" v-if="initial_step.is_phd == true">
                        <label for="proposed_area_of_research" class="col-md-10">
                          <span v-if="initial_step.is_phd == true"></span>
                          <span v-else></span>7 . Proposed area of research?
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                          <a href="https://www.tezuadmissions.in/public/notifications/2023/Advt-phd-spring-2024.pdf" target="_blank">More about research areas</a>     
                        </label>
                        <div class="col-md-8">
                          <!--<div v-for="(value, index) in step_three_form.proposed_area_of_research" :key="index">
                            <label>Preference {{ index + 1 }}</label>
                            <select  class="form-control input-sm" v-model="value.data">
                              <option v-for="area in area_of_researchs" :value="area.name">{{ area.name }}</option>
                            </select><br/>
                          </div>-->
                              
                          <table>
                            <tr v-for="(value, index) in step_three_form.proposed_area_of_research" :key="index">
                              <td style="padding-right:3px;">{{ index + 1 }}. </td>
                              <td>
                                <select  class="form-control input-sm" v-model="value.data">
                                  <option v-for="area in area_of_researchs" :value="area.name">{{ area.half_name }}</option>
                                </select><br/>
                                <!--<input style="padding-bottom:2px" type="text" v-model="value.data"
                                  class="form-control input-sm" value="" required />-->
                                <small class="text-danger"
                                  v-if="step_three_form.errors['proposed_area_of_research.' + index]">{{
                                    step_three_form.errors['proposed_area_of_research.' + index]
                                  }}</small>

                              </td>
                              <td>
                                <button type="button" @click="removePurposeofResearch(index)"
                                  class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove-sign"></i></button>
                              </td>
                            </tr>
                          </table>
                          <button type="button" class="btn btn-xs btn-info" @click="addMorePurposeofResearch()"><i
                              class="glyphicon glyphicon-plus-sign"></i> Add More </button>
                        </div>
                      </div>
                      <!--<div class="form-group is_master_in _design" v-if="is_master_in_design || (is_bachelor_in_design && this.application_type === 'UCEED')">-->
                      <div class="form-group is_master_in _design" v-if="this.application_type === 'CEED' || (is_bachelor_in_design && this.application_type === 'UCEED')">
                        <!--<div class="form-group is_master_in _design" v-if="is_master_in_design && initial_step.via_exam_mdes!='TUEE'"></div>-->
                        <label for="ceed_score" class="col-md-7" v-if="is_master_in_design">
                          CEED / GATE / DAT score ( if not applicable enter 'NA')
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;"></span>
                        </label>
                        <label for="ceed_score" class="col-md-7" v-if="is_bachelor_in_design">
                          UCEED score 
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;"></span>
                        </label>
                        <div class="col-md-5">
                          <input required v-model="step_three_form.ceed_score" class="form-control input-sm"
                            placeholder="ceed score" />
                          <small class="text-danger" v-if="step_three_form.errors.ceed_score">{{
                            step_three_form.errors.ceed_score
                          }}</small>
                        </div>
                      </div>

                      <div class="form-group" v-if="initial_step.is_phd == true">
                        <label for="proposed_area_of_research" class="col-md-7">
                          <span v-if="initial_step.is_phd == true"></span>
                          <span v-else></span>8 . Qualified National level test?
                        </label>
                        <div class="col-md-5">
                          <select v-model="step_three_form.qualified_national_level_test" class="form-control input-sm"
                            required>
                            <option value selected disabled>--SELECT--</option>
                            <option :value="test_exam" :key="test_exam"
                              v-for="test_exam in qualified_national_level_test">{{ test_exam }}</option>
                          </select>
                          <small class="text-danger" v-if="step_three_form.errors.qualified_national_level_test">{{
                            step_three_form.errors.qualified_national_level_test
                          }}</small>
                        </div>
                      </div>
                      <div class="form-group" v-if="initial_step.is_phd == true">
                        <label for="is_punished" class="col-md-7">
                          9. Qualified national level test mark
                          <small class="text-muted">(If not available enter NA)</small>
                        </label>
                        <div class="col-md-5">
                          <input type="text" v-model="step_three_form.qualified_national_level_test_mark"
                            id="qualified_national_level_test_mark" class="form-control" />
                          <small class="text-danger" v-if="step_three_form.errors.qualified_national_level_test_mark">{{
                            step_three_form.errors.qualified_national_level_test_mark
                          }}</small>
                        </div>
                      </div>

                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="is_debarred" class="col-md-7">
                          <span v-if="initial_step.is_btech == true"></span>
                          <span v-else></span>6. Were you <br />(a) Debarred
                          from any examination (s) ?
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                        </label>
                        <div class="col-md-5">
                          <label class="radio-inline">
                            <input type="radio" :value="true" v-model="step_three_form.is_debarred" id="is_debarred" />
                            <span class="text-warning">Yes</span>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" :value="false" v-model="step_three_form.is_debarred" id="is_debarred" />
                            <span class="text-warning">No</span>
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="is_punished" class="col-md-7">
                          (b) Punished for misconduct ?
                          <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                        </label>
                        <div class="col-md-5">
                          <label class="radio-inline">
                            <input type="radio" :value="true" v-model="step_three_form.is_punished" id="is_punished" />
                            <span class="text-warning">Yes</span>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" :value="false" v-model="step_three_form.is_punished" id="is_punished" />
                            <span class="text-warning">No</span>
                          </label>
                        </div>
                      </div>
                      <div class="form-group" v-if="
                        step_three_form.is_debarred ||
                        step_three_form.is_punished
                      ">
                        <label style="margin-left:14px;">Furnish Details</label>
                        <div class="col-md-12">
                          <textarea cols="50" rows="2" required v-model="step_three_form.furnish_details"
                            class="form-control input-sm"></textarea>
                          <small class="text-info"><strong>{{
                            academicFurnishTextLeft
                          }}</strong></small>
                          <small class="text-danger" v-if="step_three_form.errors.furnish_details">{{
                            step_three_form.errors.furnish_details
                          }}</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="row btn-row">
                <div class="col-md-6">
                  <button type="button" class="btn btn-warning btn-sm next" id="next" @click="goToStepTwo">
                    <i class="fa fa-chevron-left"></i>
                    GO BACK
                  </button>
                  <button type="button" class="btn btn-success btn-sm next" id="next" @click="submitStepThree">
                    <section v-if="step_three_form.creating">
                      <i class="fa fa-circle-o-notch fa-spin" style="font-size:14px;color:white;"></i>
                      <span class="text-white">Submitting ..</span>
                    </section>
                    <section v-else>
                      <span v-if="
                        step_three_form.submitted == true || mode == 'edit'
                      ">Update</span>
                      <span v-else>Save & Proceed</span>
                      <!-- & Next -->
                      <i class="fa fa-save"></i>
                    </section>
                  </button>


                  <button type="button" class="btn btn-info btn-sm" v-if="step_three_form.submitted == true"
                    @click="openPreviewInNewWindow()">
                    Preview This Form
                    <i class="fa fa-chevron-right"></i>
                  </button>


                  <button type="button" class="btn btn-info btn-sm" id="next" @click="goToStepFour"
                    v-if="step_three_form.submitted == true && step_three_form.is_preview_click == true">
                    GO NEXT
                    <i class="fa fa-chevron-right"></i>
                  </button>




                </div>
                <div class="col-md-6" v-if="step_three_form.success_msg">
                  <div class="alert alert-success">
                    {{ step_three_form.success_msg }}
                  </div>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </section>
    <div class="row" v-if="step == 4">
      <div class="col-md-12">
        <form name="step-one-update" class="form-horizontal form-class application-form">
          <br />
          <div class="step personal_information">
            <fieldset id="personal_information">
              <legend>
                Documents Upload
                <span class="pull-right text-danger">(Step 4)</span>
              </legend>
              <div class="alert alert-danger">
                <strong>Note:</strong> - Click on the "Upload" next to each "Heading" to upload the document.<br />
                - Once uploaded the "Upload" button would turn "Green" indicating that document is uploaded.<br />
                - To replace any document re-upload the document under the same heading and then click "Uploaded".
              </div>
              <div class="row">
                <div class="col-md-12"></div>
              </div>
              <div class="clearfix"></div>
              <hr />
              <div class="row" v-if="initial_step.is_mba_edit == false">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="passport" class="col-md-4 margin-label">
                      Passport Size Photo :
                      <strong class="text-danger" style="font-size: 24px; vertical-align: middle;">*</strong>
                    </label>
                    <div class="col-md-7">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="input-group">
                            <input type="file" ref="document_passport_photo" class="form-control input-sm input-sm"
                              @change="passportPhotoFileChanged" accept=".jpg,.jpeg,.png" />
                            <div class="input-group-btn" v-if="isThisUploaded('passport_photo') == true">
                              <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                                @click="uploadSingleFile('document_passport_photo', 'passport_photo')">
                                <i class="fa fa-upload"></i> Uploaded
                              </button>
                            </div>
                            <div class="input-group-btn" v-if="isThisUploaded('passport_photo') == false">
                              <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                                @click="uploadSingleFile('document_passport_photo', 'passport_photo')">
                                <i class="fa fa-upload"></i> Upload
                              </button>
                            </div>
                          </div>

                          <small class="text-info"><strong>Max-size: 100KB | File type: JPEG,JPG, PNG |
                              Height : 250 , Width : 200 Pixels</strong>
                          </small>
                          <strong class="text-danger" style="display:block" v-if="
                            step_four_form.errors2.document_passport_photo
                          ">{{
  step_four_form.errors2.document_passport_photo
}}</strong>
                          <strong class="text-danger" style="display:block" v-if="step_four_form.errors.passport_photo">{{
                            step_four_form.errors.passport_photo
                          }}</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row" v-if="step_four_form.passport_photo_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <img :src="step_four_form.passport_photo_url" alt="Passport Photo" width="100px" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" v-if="initial_step.is_mba_edit == false">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="signature" class="col-md-4 margin-label">
                      Signature :
                      <strong class="text-danger" style="font-size: 24px; vertical-align: middle;">*</strong>
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="document_signature" class="form-control input-sm input-sm"
                          @change="signatureFileChanged" accept=".jpg,.jpeg,.png" />
                        <div class="input-group-btn" v-if="isThisUploaded('signature') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_signature', 'signature')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('signature') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_signature', 'signature')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Height:150px , Width:200px , Max Size : 100KB <br />
                          File Type: PNG/JPEG/JPG
                        </strong></small>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors.signature">{{
                        step_four_form.errors.signature
                      }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.document_signature">{{ step_four_form.errors2.document_signature
                        }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row" v-if="step_four_form.signature_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <img :src="step_four_form.signature_url" alt="Signature" width="100px" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>






              <div class="row">
                <div class="col-md-6">
                  <section v-if="this.step_one_form.caste != '1' && this.step_one_form.caste != '3' && is_foreign == false">
                    <div class="form-group">
                      <label for="document_undertaking" class="col-md-4 margin-label">
                        Undertaking for Category Certificate <strong class="text-danger" v-if="initial_step.is_mba = false"
                          style="font-size: 24px; vertical-align: middle;">*</strong><small class="text-muted"
                          v-if="initial_step.is_mba = false">(for all programmes)</small>
                        :
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="undertaking" class="form-control input-sm input-sm"
                            @change="undertakingFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('undertaking') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('undertaking', 'undertaking')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('undertaking') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('undertaking', 'undertaking')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>
                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block;" v-if="step_four_form.errors.undertaking">{{
                          step_four_form.errors.undertaking
                        }}</strong>
                        <!-- errors2 for create validation -->
                        <strong class="text-danger" style="display:block;" v-if="step_four_form.errors2.undertaking">{{
                          step_four_form.errors2.undertaking
                        }}</strong>
                      </div>

                    </div>
                  </section>
                </div>
                <div class="col-md-4">
                  <div class="row" v-if="step_four_form.undertaking_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <a @click="openImageInNewWindow(step_four_form.undertaking_url)">
                              <img :src="step_four_form.undertaking_url" alt="undertaking" width="100px" /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <section v-if="!is_foreign">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="is_mbbt || initial_step.is_btech">
                      <label for="document_prc" class="col-md-4 margin-label">
                        PRC (for B.Tech and M.Sc in MBBT for North-East quota)
                        <strong class="text-danger" v-if="initial_step.is_btech = true"
                          style="font-size: 24px; vertical-align: middle;"></strong>
                        :
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_prc" class="form-control input-sm input-sm"
                            @change="prcFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('prc_certificate') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_prc', 'prc')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('prc_certificate') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_prc', 'prc')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.prc">{{
                          step_four_form.errors.prc
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.prc_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.prc_url)"><img :src="step_four_form.prc_url"
                                  alt="undertaking" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="isThisCourseApplied('MTECH') == true">
                      <label for="gate_card" class="col-md-4 margin-label">
                        GATE Score card (If applicable):
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_gate_card" class="form-control input-sm input-sm"
                            @change="gateCardFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('gate_score_card') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_gate_score_card', 'gate_score_card')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('gate_score_card') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_gate_score_card', 'gate_score_card')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.gate_score_card">{{
                          step_four_form.errors.gate_score_card
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.gate_score_card_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.gate_score_card_url)"><img
                                  :src="step_four_form.gate_score_card_url" alt="undertaking" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="this.step_one_form.caste != '1' && this.step_one_form.caste != '3'">
                      <label for="caste_certificate" class="col-md-4 margin-label">Category Certificate:</label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="caste_certificate" class="form-control input-sm input-sm"
                            @change="casteCertificateChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('caste_certificate') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('caste_certificate', 'caste_certificate')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('caste_certificate') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('caste_certificate', 'caste_certificate')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors.caste_certificate">{{ step_four_form.errors.caste_certificate
                          }}</strong>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors2.caste_certificate">{{ step_four_form.errors2.caste_certificate
                          }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.caste_certificate_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.caste_certificate_url)"><img
                                  :src="step_four_form.caste_certificate_url" alt="undertaking" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="is_mbbt">
                      <label for="caste_certificate" class="col-md-4 margin-label">GAT-B score card: <strong
                          class="text-danger" style="font-size: 24px; vertical-align: middle;">*</strong></label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="gate_b_score_card" class="form-control input-sm input-sm"
                            @change="gateBScoreCardChange" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('gate_b_score_card') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('gate_b_score_card', 'gate_b_score_card')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('gate_b_score_card') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('gate_b_score_card', 'gate_b_score_card')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 5MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors.gate_b_score_card">{{ step_four_form.errors.gate_b_score_card
                          }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.gate_b_score_card_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.gate_b_score_card_url)"><img
                                  :src="step_four_form.gate_b_score_card_url" alt="undertaking" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <!--<div class="form-group" v-if="is_master_in_design || (is_bachelor_in_design && this.application_type === 'UCEED')">-->
                    <div class="form-group" v-if="this.application_type === 'CEED' || (is_bachelor_in_design && this.application_type === 'UCEED')">
                      <label for="ceed_score_card" class="col-md-4 margin-label" v-if="is_master_in_design">CEED/GATE/DAT score card (only for master of
                        Design) <strong class="text-danger"
                          style="font-size: 24px; vertical-align: middle;"></strong>:</label>
                      <label for="ceed_score_card" class="col-md-4 margin-label" v-if="is_bachelor_in_design">UCEED score card (only for bachelor of
                        Design) <strong class="text-danger"
                          style="font-size: 24px; vertical-align: middle;"></strong>:</label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="ceed_score_card" class="form-control input-sm input-sm"
                            @change="CeedScoreCardChange" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('ceed_score_card') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('ceed_score_card', 'ceed_score_card')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('ceed_score_card') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('ceed_score_card', 'ceed_score_card')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.ceed_score_card">{{
                          step_four_form.errors.ceed_score_card
                        }}</strong>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.ceed_score_card">{{
                          step_four_form.errors2.ceed_score_card
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.ceed_score_card_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.ceed_score_card_url)"><img
                                  :src="step_four_form.ceed_score_card_url" alt="undertaking" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="isThisSubCourseApplied(84) || is_master_in_design || is_bachelor_in_design">
                      <label for="portfolio" class="col-md-4 margin-label">Portfolio (only for master of Design)<strong class="text-danger"
                          style="font-size: 24px; vertical-align: middle;">*</strong> <strong
                          class="text-danger">(Only PDF Allowed)</strong>:</label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="portfolio_file" class="form-control input-sm input-sm"
                            @change="portfolioChange" accept=".pdf,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('portfolio') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('portfolio_file', 'portfolio')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('portfolio') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('portfolio_file', 'portfolio')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 5MB , File type: PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.portfolio">{{
                          step_four_form.errors.portfolio
                        }}</strong>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.portfolio_file">{{
                          step_four_form.errors2.portfolio_file
                        }}</strong>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="step_one_form.is_bpl == true">
                      <label for="document_bpl" class="col-md-4 margin-label">BPL/AAY Card :</label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_bpl" class="form-control input-sm input-sm"
                            @change="bplFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('bpl_card') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_bpl', 'bpl_card')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('bpl_card') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_bpl', 'bpl_card')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.bpl_card">{{
                          step_four_form.errors.bpl_card
                        }}</strong>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.document_bpl">{{
                          step_four_form.errors2.document_bpl
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.bpl_card_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.bpl_card_url)"><img
                                  :src="step_four_form.bpl_card_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="is_ncl == true">
                      <label for="obc_ncl" class="col-md-4 margin-label">OBC(NCL) Certificate:</label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="obc_ncl" class="form-control input-sm input-sm" @change="obcFileChanged"
                            accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('obc_ncl') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('obc_ncl', 'obc_ncl')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('obc_ncl') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('obc_ncl', 'obc_ncl')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.obc_ncl">{{
                          step_four_form.errors.obc_ncl
                        }}</strong>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.obc_ncl">{{
                          step_four_form.errors2.obc_ncl
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.obc_ncl_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.obc_ncl_url)"><img
                                  :src="step_four_form.obc_ncl_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>





              <div class="row">
                <div class="col-md-6">
                  <section v-if="is_foreign">
                    <div class="form-group">
                      <label for="passport" class="col-md-4 margin-label">
                        Passport (first & last page of passport):
                        <strong class="text-danger" style="font-size: 24px; vertical-align: middle;">*</strong>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_passport" class="form-control input-sm input-sm"
                            @change="passportFileChanged" accept=".pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('document_passport') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_passport', 'document_passport')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('document_passport') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_passport', 'document_passport')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.passport">{{
                          step_four_form.errors.passport
                        }}</strong>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors2.document_passport">{{ step_four_form.errors2.document_passport
                          }}</strong>
                      </div>
                    </div>
                  </section>
                </div>
                <!--<div class="col-md-4">
                  <div class="row" v-if="step_four_form.document_passport_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <a @click="openImageInNewWindow(step_four_form.document_passport_url)"><img
                                :src="step_four_form.document_passport_url" alt="bpl" width="100px" /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group" v-if="step_three_form.is_sport_represented == true">
                    <label for="document_sport_representation" class="col-md-4 margin-label">Certificate of winning Medals
                      in any National and International Sports events :
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="document_sport_representation" class="form-control input-sm input-sm"
                          @change="sportCertificateChanged" accept=".jpg,.jpeg,.png,.pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('sport_certificate') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_sport_representation', 'sport_certificate')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('sport_certificate') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_sport_representation', 'sport_certificate')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                      </small>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors.sport_certificate">{{
                        step_four_form.errors.sport_certificate
                      }}</strong>
                      <strong class="text-danger" style="display:block" v-if="
                        step_four_form.errors2.document_sport_representation
                      ">{{
  step_four_form.errors2.document_sport_representation
}}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="row" v-if="this.step_four_form.sport_certificate_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <a @click="openImageInNewWindow(step_four_form.sport_certificate_url)"><img
                                :src="step_four_form.sport_certificate_url" alt="Passport Photo" width="100px" /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group" v-if="step_one_form.is_pwd == true">
                    <label for="document_pwd" class="col-md-4 margin-label">
                      PWD Ceritficate :
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="document_pwd" class="form-control input-sm input-sm"
                          @change="pwdCertificateChanged" accept=".jpg,.jpeg,.png,.pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('pwd_certificate') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_pwd', 'pwd_certificate')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('pwd_certificate') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_pwd', 'pwd_certificate')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                      </small>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors.pwd_certificate">{{
                        step_four_form.errors.pwd_certificate
                      }}</strong>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.document_pwd">{{
                        step_four_form.errors2.document_pwd
                      }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="row" v-if="step_four_form.document_pwd_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <a @click="openImageInNewWindow(step_four_form.document_pwd_url)"><img
                                :src="step_four_form.document_pwd_url" alt="bpl" width="100px" /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group" v-if="step_one_form.is_employed == true || isThisCourseApplied('MBA') == true">
                    <label for="document_noc" class="col-md-4 margin-label">
                      NOC (If Employed):
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;"></span>
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="document_noc" class="form-control input-sm input-sm"
                          @change="nocFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('noc_certificate') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_noc', 'noc_certificate')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('noc_certificate') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('document_noc', 'noc_certificate')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG,PDF</strong>
                      </small>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors.noc_certificate">{{
                        step_four_form.errors.noc_certificate
                      }}</strong>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.document_noc">{{
                        step_four_form.errors2.document_noc
                      }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="row" v-if="step_four_form.document_noc_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <a @click="openImageInNewWindow(step_four_form.document_noc_url)"><img
                                :src="step_four_form.document_noc_url" alt="bpl" width="100px" /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="row" v-if="initial_step.is_mba_edit == false">
                <div class="col-md-6">
                  <section>
                    <div class="form-group">
                      <label for="document_undertaking" class="col-md-4 margin-label">
                        Undertaking for (Passed/Appeared) <strong class="text-danger" v-if="initial_step.is_mba == false"
                          style="font-size: 24px; vertical-align: middle;">*</strong><small class="text-muted"
                          v-if="initial_step.is_mba == false">(for all programmes)</small>
                        :
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="undertaking_pass_appear" class="form-control input-sm input-sm"
                            @change="undertakingPassAppearFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('undertaking_pass_appear') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('undertaking_pass_appear', 'undertaking_pass_appear')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('undertaking_pass_appear') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('undertaking_pass_appear', 'undertaking_pass_appear')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG , PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block;"
                          v-if="step_four_form.errors.undertaking_pass_appear">{{
                            step_four_form.errors.undertaking_pass_appear
                          }}</strong>
                        <!-- errors2 for create validation -->
                      </div>
                    </div>
                  </section>
                </div>
                <div class="col-md-4">
                  <div class="row" v-if="step_four_form.undertaking_pass_appear_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <a @click="openImageInNewWindow(step_four_form.undertaking_pass_appear_url)"><img
                                :src="step_four_form.undertaking_pass_appear_url" alt="bpl" width="100px" /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <section v-if="!is_foreign">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="initial_step.is_btech == true && is_mbbt==false">
                      <label for="document_admit_jee" class="col-md-4 margin-label">
                        Admit Card of JEE (for B.Tech Programme) :
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_admit_jee" class="form-control input-sm input-sm"
                            @change="admiCardChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('jee_admit_card') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_admit_jee', 'jee_admit_card')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('jee_admit_card') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_admit_jee', 'jee_admit_card')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG,PDF</strong>
                        </small><br>
                        <strong class="small text-warning">Please insert undertaking if admit card is not
                          available.</strong>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.jee_admit">{{
                          step_four_form.errors.jee_admit
                        }}</strong>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors2.document_admit_jee">{{ step_four_form.errors2.document_admit_jee
                          }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_admit_jee_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.document_admit_jee_url)"><img
                                  :src="step_four_form.document_admit_jee_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="initial_step.is_cuet_ug == true || this.application_type=='CUET' || this.application_type=='CHINESE'">
                      <label for="cuet_admit_card" class="col-md-4 margin-label">
                        Admit Card of CUET(anyone):
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;"></span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="cuet_admit_card" class="form-control input-sm input-sm"
                            @change="cuetAdmitCardChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('cuet_admit_card') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('cuet_admit_card', 'cuet_admit_card')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('cuet_admit_card') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('cuet_admit_card', 'cuet_admit_card')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small><br>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.cuet_admit_card">{{
                          step_four_form.errors.cuet_admit_card
                        }}</strong>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.cuet_admit_card">{{
                          step_four_form.errors2.cuet_admit_card
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.cuet_admit_card_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.cuet_admit_card_url)"><img
                                  :src="step_four_form.cuet_admit_card_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="initial_step.is_cuet_ug == true || this.application_type=='CUET' || this.application_type=='CHINESE'">
                      <label for="cuet_score_card" class="col-md-4 margin-label">
                        Score Card of CUET :
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;"></span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="cuet_score_card" class="form-control input-sm input-sm"
                            @change="cuetScoreCardChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('cuet_score_card') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('cuet_score_card', 'cuet_score_card')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('cuet_score_card') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('cuet_score_card', 'cuet_score_card')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small><br>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.cuet_score_card">{{
                          step_four_form.errors.cuet_score_card
                        }}</strong>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.cuet_score_card">{{
                          step_four_form.errors2.cuet_score_card
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.cuet_score_card_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.cuet_score_card_url)"><img
                                  :src="step_four_form.cuet_score_card_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!--<div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bank_passbook" class="col-md-4 margin-label">
                        Front Page Of Bank Passbook :
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="bank_passbook" class="form-control input-sm input-sm"
                            @change="bankPassbookChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('bank_passbook')==true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('bank_passbook', 'bank_passbook')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('bank_passbook')==false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('bank_passbook', 'bank_passbook')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small><br>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.bank_passbook">{{
                            step_four_form.errors.bank_passbook
                        }}</strong>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors2.bank_passbook">{{ step_four_form.errors2.bank_passbook
                          }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.bank_passbook_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.bank_passbook_url)"><img :src="step_four_form.bank_passbook_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="initial_step.is_phd == true">
                      <label for="document_net_slet" class="col-md-4 margin-label">
                        NET/GATE/SLET/JRF/MPhil Certificate (if applicable) :
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_net_slet" class="form-control input-sm input-sm"
                            @change="netSletFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('net_slet_certificate') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_net_slet', 'net_slet_certificate')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('net_slet_certificate') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_net_slet', 'net_slet_certificate')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors.net_slet_certificate">{{
                            step_four_form.errors.net_slet_certificate
                          }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_net_slet_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.document_net_slet_url)"><img
                                  :src="step_four_form.document_net_slet_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" v-if="step_one_form.is_ex_serviceman == true">
                      <label for="document_ex_servicement" class="col-md-4 margin-label">
                        Defence Quota Certificate :
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_ex_servicement" class="form-control input-sm input-sm"
                            @change="exservicemanFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('ex_serviceman_certificate') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_ex_servicement', 'ex_serviceman_certificate')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('ex_serviceman_certificate') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_ex_servicement', 'ex_serviceman_certificate')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors.ex_serviceman_certificate">{{
                            step_four_form.errors.ex_serviceman_certificate
                          }}</strong>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors2.document_ex_servicement">{{
                            step_four_form.errors2.document_ex_servicement
                          }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_ex_servicement_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.document_ex_servicement_url)"><img
                                  :src="step_four_form.document_ex_servicement_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>



              <!--NOT Done-->

              <section v-if="is_foreign">
                <!--<div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="document_driving_license" class="col-md-4 margin-label">
                        Driving License (if applicable):
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_driving_license" class="form-control input-sm input-sm"
                            @change="drivingLicenseFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('document_driving_license') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_driving_license', 'document_driving_license')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('document_driving_license') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_driving_license', 'document_driving_license')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.driving_license">{{
                          step_four_form.errors.driving_license
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_driving_license_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.document_driving_license_url)"><img
                                  :src="step_four_form.document_driving_license_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="document_ssn_equivalent" class="col-md-4 margin-label">
                        SSN or equivalent (if applicable):
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_ssn_equivalent" class="form-control input-sm input-sm"
                            @change="ssnFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('document_ssn_equivalent') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_ssn_equivalent', 'document_ssn_equivalent')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('document_ssn_equivalent') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_ssn_equivalent', 'document_ssn_equivalent')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="step_four_form.errors.document_ssn_equivalent">{{
                          step_four_form.errors.document_ssn_equivalent
                        }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_ssn_equivalent_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.document_ssn_equivalent_url)"><img
                                  :src="step_four_form.document_ssn_equivalent_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->

                <!--<div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="document_passing_certificate" class="col-md-4 margin-label">
                        Passing Certificate of qualifying exam:
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_passing_certificate" class="form-control input-sm input-sm"
                            @change="passingCertificateFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('document_passing_certificate') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_passing_certificate', 'document_passing_certificate')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('document_passing_certificate') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_passing_certificate', 'document_passing_certificate')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors.document_passing_certificate">{{
                            step_four_form.errors.document_passing_certificate
                          }}</strong>
                        <strong class="text-danger" style="display:block" v-if="
                          step_four_form.errors2.document_passing_certificate
                        ">{{
  step_four_form.errors2.document_passing_certificate
}}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_passing_certificate_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.document_passing_certificate_url)"><img
                                  :src="step_four_form.document_passing_certificate_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->

                <!--<div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="marksheet" class="col-md-4 margin-label">
                        Mark sheet of qualifying exam:
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="document_marksheet" class="form-control input-sm input-sm"
                            @change="markSheetFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('document_marksheet') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_marksheet', 'document_marksheet')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('document_marksheet') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_marksheet', 'document_marksheet')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors.document_marksheet">{{
                            step_four_form.errors.document_marksheet
                          }}</strong>
                        <strong class="text-danger" style="display:block"
                          v-if="step_four_form.errors2.document_marksheet">{{
                            step_four_form.errors2.document_marksheet
                          }}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_marksheet_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a @click="openImageInNewWindow(step_four_form.document_marksheet_url)"><img
                                  :src="step_four_form.document_marksheet_url" alt="bpl" width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->

                <div class="row" v-if="is_foreign==true">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="marksheet" class="col-md-4 margin-label">
                        Proficiency in English Certificate :
                        <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="english_proficiency_certificate" class="form-control input-sm inpu0t-sm"
                            @change="proficiencyFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                          <div class="input-group-btn" v-if="isThisUploaded('document_english_proficiency_certificate') == true">
                            <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_english_proficiency_certificate', 'document_english_proficiency_certificate')">
                              <i class="fa fa-upload"></i> Uploaded
                            </button>
                          </div>
                          <div class="input-group-btn" v-if="isThisUploaded('document_english_proficiency_certificate') == false">
                            <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                              @click="uploadSingleFile('document_english_proficiency_certificate', 'document_english_proficiency_certificate')">
                              <i class="fa fa-upload"></i> Upload
                            </button>
                          </div>
                        </div>

                        <small class="text-info"><strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                        </small>
                        <strong class="text-danger" style="display:block" v-if="
                          step_four_form.errors
                            .english_proficiency_certificate
                        ">{{
  step_four_form.errors
    .english_proficiency_certificate
}}</strong>
                        <strong class="text-danger" style="display:block" v-if="
                          step_four_form.errors2
                            .document_english_proficiency_certificate
                        ">{{
  step_four_form.errors2
    .document_english_proficiency_certificate
}}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row" v-if="step_four_form.document_english_proficiency_certificate_url">
                      <div class="panel panel-info">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <div class="image">
                              <a
                                @click="openImageInNewWindow(step_four_form.document_english_proficiency_certificate_url)"><img
                                  :src="step_four_form.document_english_proficiency_certificate_url" alt="bpl"
                                  width="100px" /></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>



              <!--<div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="driving_license" class="col-md-4 margin-label">
                      Additional Documents (if applicable):
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="additional_document" class="form-control input-sm input-sm"
                          @change="additionalFileChanged" accept=".jpg,.jpeg,.png,.pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('document_additional')==true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('additional_document', 'document_additional')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('document_additional')==false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('additional_document', 'document_additional')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info">
                        <strong>Max-size: 1MB , File type: JPEG,JPG, PNG, PDF</strong>
                      </small>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.additional_document">{{ step_four_form.errors.additional_document
                        }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="row" v-if="step_four_form.document_additional_url">
                    <div class="panel panel-info">
                      <div class="panel-body">
                        <div class="col-md-12">
                          <div class="image">
                            <a @click="openImageInNewWindow(step_four_form.document_additional_url)"><img :src="step_four_form.document_additional_url" alt="undertaking" width="100px" /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->


              <div class="clearfix"></div>
              <div class="row" v-if="isThisCourseApplied('MBA') == true">
                <div class="col-sm-12">
                  <h3>Only for MBA Student</h3>
                  <hr />
                </div>
                <hr />
                <div v-for="mba_exam in mba_exams" @key="mba_exam + '_document'">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="" class="col-md-4 margin-label">
                          {{ mba_exam }} Score Card :
                          <span class="text-danger">(if applicable)</span>
                        </label>
                        <div class="col-md-7">
                          <div class="input-group">
                            <input type="file" class="form-control input-sm input-sm"
                              @change="anyDcoumentsChange($event, mba_exam)" accept=".jpg,.jpeg,.png,.pdf" />
                            <!-- check doc name for same  -->
                            <div class="input-group-btn">
                              <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                                @click="uploadSingleFile((`${mba_exam}_score_card`).toLowerCase(), (`${mba_exam}_score_card`).toLowerCase())">
                                <i class="fa fa-upload"></i> Upload
                              </button>
                            </div>
                          </div>

                          <small class="text-info"><strong>Max Size : 500KB <br />
                              File Type: PNG/JPEG/JPG
                            </strong>
                          </small>
                          <strong class="text-danger" style="display:block"
                            v-if="step_four_form.errors[mba_exam + '_score_card']">{{
                              step_four_form.errors[mba_exam + '_score_card']
                            }}
                          </strong>
                          <strong class="text-danger" style="display:block"
                            v-if="step_four_form.errors2[mba_exam + '_score_card']">{{
                              step_four_form.errors2[mba_exam + '_score_card']
                            }}
                          </strong>
                        </div>
                      </div>
                    </div>
                    <!--<div class="col-md-4">
                      <div class="row" v-if="step_four_form.document_ex_servicement_url">
                        <div class="panel panel-info">
                          <div class="panel-body">
                            <div class="col-md-12">
                              <div class="image">
                                <a @click="openImageInNewWindow(step_four_form.document_ex_servicement_url)"><img :src="step_four_form.document_ex_servicement_url" alt="bpl" width="100px" /></a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>


              <div class="clearfix"></div>
              <hr>
              </hr>

              <li><strong></strong>Upload Marksheet, Certificate with conversion formula issued by your University/ Board/
                Council.</strong></li>


              <li><strong></strong>Upload This Three Document In One PDF File. </strong></li>
              <div class="row" v-if="isThisCourseApplied('MBA') == false">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="class_x_documents" class="col-md-4 margin-label">
                      Class X Documents :
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="class_x_documents" class="form-control input-sm input-sm"
                          @change="classXChanged" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('class_x_documents') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_x_documents', 'class_x_documents')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('class_x_documents') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_x_documents', 'class_x_documents')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors.class_x_documents">{{
                        step_four_form.errors.class_x_documents
                      }}</strong>
                      <strong class="text-danger" style="display:block" v-if="step_four_form.errors2.class_x_documents">{{
                        step_four_form.errors2.class_x_documents
                      }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="class_XII_documents" class="col-md-4 margin-label">
                      Class XII  / Diploma Documents :
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="class_XII_documents" class="form-control input-sm input-sm"
                          @change="classXIIChanged" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('class_XII_documents') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_XII_documents', 'class_XII_documents')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('class_XII_documents') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_XII_documents', 'class_XII_documents')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.class_XII_documents">{{
                          step_four_form.errors.class_XII_documents
                        }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.class_XII_documents">{{ step_four_form.errors2.class_XII_documents
                        }}</strong>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" v-if="isThisCourseApplied('MBA') == false">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="class_x_grade_conversion" class="col-md-4 margin-label">
                      Class X Grade Conversion Formula :
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="class_x_grade_conversion" class="form-control input-sm input-sm"
                          @change="classXChangedII" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('class_x_grade_conversion') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_x_grade_conversion', 'class_x_grade_conversion')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('class_x_grade_conversion') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_x_grade_conversion', 'class_x_grade_conversion')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.class_x_grade_conversion">{{
                          step_four_form.errors.class_x_grade_conversion
                        }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.class_x_grade_conversion">{{
                          step_four_form.errors2.class_x_grade_conversion
                        }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="class_XII_grade_conversion" class="col-md-4 margin-label">
                      Class XII  / Diploma Grade Conversion Formula :
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="class_XII_grade_conversion" class="form-control input-sm input-sm"
                          @change="classXIIChangedII" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('class_XII_grade_conversion') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_XII_grade_conversion', 'class_XII_grade_conversion')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('class_XII_grade_conversion') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('class_XII_grade_conversion', 'class_XII_grade_conversion')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.class_XII_grade_conversion">{{
                          step_four_form.errors.class_XII_grade_conversion
                        }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.class_XII_grade_conversion">{{
                          step_four_form.errors2.class_XII_grade_conversion
                        }}</strong>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group"
                    v-if="initial_step.is_cuet_pg == true || initial_step.is_phd == true || isThisCourseApplied('MBA') == true || is_master_in_design || is_mbbt== true">
                    <label for="graduation_documents" class="col-md-4 margin-label">
                      Graduation Documents :
                      <span class="text-danger" style="font-size: 24px; vertical-align: middle;">*</span>
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="graduation_documents" class="form-control input-sm input-sm"
                          @change="ugDocChanged" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('graduation_documents') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('graduation_documents', 'graduation_documents')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('graduation_documents') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('graduation_documents', 'graduation_documents')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.graduation_documents">{{
                          step_four_form.errors.graduation_documents
                        }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.graduation_documents">{{ step_four_form.errors2.graduation_documents
                        }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group" v-if="initial_step.is_cuet_pg == true||initial_step.is_phd == true || isThisCourseApplied('MBA') == true">
                    <label for="post_graduation_documents" class="col-md-4 margin-label">
                      Post Graduation Documents (If Applicable):
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="post_graduation_documents" class="form-control input-sm input-sm"
                          @change="pgDocChanged" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('post_graduation_documents') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('post_graduation_documents', 'post_graduation_documents')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('post_graduation_documents') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('post_graduation_documents', 'post_graduation_documents')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.post_graduation_documents">{{
                          step_four_form.errors.post_graduation_documents
                        }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.post_graduation_documents">{{
                          step_four_form.errors2.post_graduation_documents
                        }}</strong>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group"
                    v-if="initial_step.is_cuet_pg == true || initial_step.is_phd == true || initial_step.is_mba == true || is_master_in_design || is_mbbt== true">
                    <label for="graduation_grade_conversion" class="col-md-4 margin-label">
                      Graduation Grade Conversion Formula :
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="graduation_grade_conversion" class="form-control input-sm input-sm"
                          @change="ugDocChangedII" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('graduation_grade_conversion') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('graduation_grade_conversion', 'graduation_grade_conversion')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('graduation_grade_conversion') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('graduation_grade_conversion', 'graduation_grade_conversion')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.graduation_grade_conversion">{{
                          step_four_form.errors.graduation_grade_conversion
                        }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.graduation_grade_conversion">{{
                          step_four_form.errors2.graduation_grade_conversion
                        }}</strong>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group" v-if="initial_step.is_cuet_pg == true || initial_step.is_phd == true">
                    <label for="post_graduation_grade_conversion" class="col-md-4 margin-label">
                      Post Graduation Grade Conversion Formula (If Applicable) :
                    </label>
                    <div class="col-md-7">
                      <div class="input-group">
                        <input type="file" ref="post_graduation_grade_conversion" class="form-control input-sm input-sm"
                          @change="pgDocChangedII" accept=".pdf" />
                        <div class="input-group-btn" v-if="isThisUploaded('post_graduation_grade_conversion') == true">
                          <button type="button" class="btn btn-success btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('post_graduation_grade_conversion', 'post_graduation_grade_conversion')">
                            <i class="fa fa-upload"></i> Uploaded
                          </button>
                        </div>
                        <div class="input-group-btn" v-if="isThisUploaded('post_graduation_grade_conversion') == false">
                          <button type="button" class="btn btn-default btn-sm" :disabled="singleFileUploadingProgress"
                            @click="uploadSingleFile('post_graduation_grade_conversion', 'post_graduation_grade_conversion')">
                            <i class="fa fa-upload"></i> Upload
                          </button>
                        </div>
                      </div>

                      <small class="text-info"><strong>Max-size: 1MB , File type: Pdf only</strong>
                      </small><br>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors.post_graduation_grade_conversion">{{
                          step_four_form.errors.post_graduation_grade_conversion
                        }}</strong>
                      <strong class="text-danger" style="display:block"
                        v-if="step_four_form.errors2.post_graduation_grade_conversion">{{
                          step_four_form.errors2.post_graduation_grade_conversion
                        }}</strong>
                    </div>
                  </div>
                </div>
              </div>

              <legend>
                Miscellaneous Documents Upload
              </legend>

              <div class="row">
                <div class="col-md-3">
                  <li><strong></strong>Upload any other Documents.</strong></li>
                </div>
                <div class="col-md-6">
                  <button type="button" class="btn btn-xs btn-info" @click="addNewDocument">
                    ADD NEW
                  </button>
                </div>
              </div><br />
              <div class="row" v-for="(item, index) in misc_count" :key="index">
                <div :id="`misc${index}`">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label for="misc_documents" class="col-md-4 margin-label">
                        Document Name:
                      </label>
                      <div class="col-md-7">
                        <input type="text" v-model="step_four_form.misc_documents_name[index]" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <label for="misc_documents" class="col-md-4 margin-label">
                        Upload Document:
                      </label>
                      <div class="col-md-7">
                        <div class="input-group">
                          <input type="file" ref="misc_documents[]" class="form-control input-sm input-sm"
                            @change="(event) => ugDocChangedIII(event, index)" accept=".pdf" />

                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2" v-if="step_four_form.mis_is_upload[index]==null">
                    <button type="button" class="btn btn-xs btn-danger" @click="deleteMiscDocument(index)">
                      DELETE
                    </button>
                  </div>
                  <div class="col-md-2" v-if="step_four_form.mis_is_upload[index]!=null">
                    <button type="button" class="btn btn-xs btn-success">
                      UPLOADED
                    </button>
                  </div>
                </div>
              </div>


              <div class="row text-center">
                <div class="col-md-12">
                  <div class="panel panel-info">
                    <div class="panel-body" style="text-align:justify;">
                      <p>
                        I
                        {{
                          step_one_form.first_name +
                          " " +
                          step_one_form.middle_name +
                          " " +
                          step_one_form.last_name
                        }}
                        declare that I shall abide by the Statutes, Ordinances,
                        Rules and Orders etc. of the University that will be in
                        force from time to time. I will submit myself to the
                        disciplinary jurisdiction of the Vice-Chancellor and the
                        authorities of the University who may be vested with
                        such power under the Acts, Statutes, Ordinances and the
                        Rules that have been framed thereunder by the
                        University.
                      </p>
                      <p>
                        I also declare that the information given above is true
                        and complete to the best of my knowledge and belief, and
                        if any of it is found to be incorrect, my admission
                        shall be liable to be cancelled and I shall be liable to
                        such disciplinary action as may be decided by the
                        University.
                      </p>
                      <div class="form-group">
                        <label for="is_ex_serviceman" class="col-md-4 control-label text-right margin-label">I accept
                          :</label>
                        <div class="col-md-7">
                          <label class="radio-inline">
                            <input type="radio" :value="true" v-model="step_four_form.is_accepted"
                              id="is_ex_serviceman" />
                            <span class="text-warning">Yes</span>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" :value="false" v-model="step_four_form.is_accepted"
                              id="is_ex_serviceman" />
                            <span class="text-warning">No</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
            <div class="row btn-row">
              <div class="col-md-6">
                <button type="button" class="btn btn-warning btn-sm next" id="next" @click="goToStepThree">
                  <i class="fa fa-chevron-left"></i>
                  GO BACK
                </button>
                <button type="button" class="btn btn-success btn-sm next" id="next" @click="submitStepFour"
                  v-if="step_four_form.is_accepted == true">
                  <section v-if="step_four_form.uploading">
                    <i class="fa fa-circle-o-notch fa-spin" style="font-size:14px;color:white;"></i>
                    <span class="text-white">Uploading ..</span>
                  </section>
                  <section v-else>
                    <span v-if="mode == 'edit'">Update</span>
                    <span v-if="mode == 'create'">Submit</span>
                    <i class="fa fa-save"></i>
                  </section>
                </button>
              </div>
              <div class="col-md-6">
                <div class="progress">
                  <div class="progress-bar" :class="
                    step_four_form.is_progress_error
                      ? 'progress-bar-danger'
                      : 'progress-bar-success'
                  " role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" :style="progress_style">
                    {{ progress_style.width }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
const axios = require("axios");

import FormInput from "../input/TextInput.vue";
import FormSelect from "../input/Select.vue";
import DatePicker from "../input/DatePicker.vue";


import Vue from 'vue';
import VueCryptojs from 'vue-cryptojs';
Vue.use(VueCryptojs);


export default {
  components: {
    "form-input": FormInput,
    "form-select": FormSelect,
    "date-picker": DatePicker
  },
  props: ["mode", "application_id", "count", "create_app_id", "application_type"],
  data() {
    return {
      base_url: "https://www.tezuadmissions.in/public",
      //base_url: 'https://www.tezuadmissions.in/public',

      // base_url: "http://127.0.0.1:8000",
      // base_url: "http://139.59.46.246/tezpur_admission/public/",
      // base_url: "http://localhost/dec/tezuadmissions_in/public/",
      singleFileUploadingProgress: false,
      preview_url: "www.google.com",
      disabledAfter: new Date(),
      progress_style: {
        width: "0%"
      },
      place_of_residence_dropdown: [
        {
          "place_residence": "Urban",
          "id": "Urban",
        },
        {
          "place_residence": "Rural",
          "id": "Rural"
        },
      ],
      applications_count: "",
      is_60: true,
      is_mba: false,
      is_phd: false,
      is_phd_prof: false,
      phd_course_ids: [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 64, 65, 67, 68, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96],
      limit: 10,
      misc_count: 0,
      misc_limit: 3,
      experience_count: 10,
      mba_exams: [
        "CAT", "MAT", "ATMA", "XAT", "GMAT", "CMAT"
      ],
      
      qualified_national_level_test: [
        "NA",
        "UGCNET/JRF",
        "UGC-CSIR",
        "NET/JRF",
        "UGC-CSIR-NET/JRF",
        "DBT- JRF",
        "ICAR-NET",
        "ICMR-JRF",
        "NETLS",
        "GATE",
        "SLET",
      ],
      states: [
        { name: 'Andaman and Nicobar', },
        { name: 'Andhra Pradesh', },
        { name: 'Arunachal Pradesh', },
        { name: 'Assam', },
        { name: 'Bihar', },
        { name: 'Chandigarh', },
        { name: 'Chhattisgarh', },
        { name: 'Dadra Nagar Haveli', },
        { name: 'Daman and Diu', },
        { name: 'Delhi', },
        { name: 'Goa', },
        { name: 'Gujarat', },
        { name: 'Haryana', },
        { name: 'Himachal Pradesh', },
        { name: 'Jammu and Kashmir', },
        { name: 'Jharkhand', },
        { name: 'Karnataka', },
        { name: 'Kerala', },
        { name: 'Ladakh', },
        { name: 'Lakshadweep', },
        { name: 'Madhya Pradesh', },
        { name: 'Maharashtra', },
        { name: 'Manipur', },
        { name: 'Meghalaya', },
        { name: 'Mizoram', },
        { name: 'Nagaland', },
        { name: 'Odisha', },
        { name: 'Puducherry', },
        { name: 'Punjab', },
        { name: 'Rajasthan', },
        { name: 'Sikkim', },
        { name: 'Tamil Nadu', },
        { name: 'Telangana', },
        { name: 'Tripura', },
        { name: 'Uttar Pradesh', },
        { name: 'Uttarakhand', },
        { name: 'West Bengal', },
      ],
      employments: [
        { name: "Government Sector" },
        { name: "Private Sector" },
        { name: "Self Employed" },
        { name: "Businessman" },
        { name: "Farmer" },
        { name: "Part Time" }
      ],

      employments_phd_prof: [],

      bachelor_degrees: [
        { name: "B.A" },
        { name: "B.Sc" },
        { name: "B.Com" },
        { name: "B.E/B.Tech" },
        { name: "LL.B 3 years" },
        { name: "LL.B 5 years" },
        { name: "Other" }
      ],
      post_degrees: [
        { name: "MA" },
        { name: "MSC" },
        { name: "M.Com" },
        { name: "M.E/M.Tech" },
        { name: "Other" }
      ],
      gender_options: [
        { name: "Male", value: "Male" },
        { name: "Female", value: "Female" },
        { name: "Transgender", value: "Transgender" }
      ],
      streams: [{ name: "ARTS" }, { name: "SCIENCE" }, { name: "COMMERCE" }, { name: "DIPLOMA" }],
      martital_options: [
        { name: "Married", value: "Married" },
        { name: "Unmarried", value: "Unmarried" },
        { name: "Divorced", value: "Divorced" },
        { name: "Widow", value: "Widow" }
      ],
      religions: [
        { name: "Hinduism", value: "Hinduism" },
        { name: "Buddhism", value: "Buddhism" },
        { name: "Christianity", value: "Christianity" },
        { name: "Islam", value: "Islam" },
        { name: "Jainism", value: "Jainism" },
        { name: "Sikhism", value: "Sikhism" },
        { name: "Other", value: "Other" }
      ],
      edit_mode: false,
      application_old: "",
      app_programme: "",
      program_type: "",
      user: "",
      is_foreign: false,
      courses: [],
      departments: [],
      castes: [],
      sports: [],
      cuet_subject: [],
      area_of_researchs: [],
      area_of_researchs_length:"",
      medals: [
        { id: "Gold", name: "Gold" },
        { id: "Silver", name: "Silver" },
        { id: "Bronze", name: "Bronze" },
      ],
      centers: [],
      indian_states: [],
      districts: [],
      permanent_districts: [],
      income_ranges: [],
      parents_qualification: [],
      step: 0,
      stepthreesl: 0,
      cuet_sub_limit: 1,
      initial_step: {
        course_group_23: [],
        program_selection_step: true,
        maximm: 1,
        is_pref_limit_exceeded: false,
        pref_limit: 2,
        course_type: "",
        is_show_eligibility: false,
        is_qualify: false,
        is_btech: false,
        program_name_load: "",
        via_exam_mdes: "",
        course_preference_limit: "",
        is_cuet_ug: false,
        is_cuet_pg: false,
        is_mdes:false,
        is_phd: false,
        // is_phd_prof: false,
        is_mba: false,
        program_name: "",
        is_mca: false,
        branches: [],
        courses: [],
        combined_courses: [],
        sub_combined_courses: [],
        combined_courses_main: [],
        course_types: [],
        course: "",
        b_tech_courses: [],
        preference_error: "",
        ready: true,
        course_alert: "",
        preference_count: false, //initially this if false time to to time it changes
        selection_count: false,
        qualify_check: false,
        is_mba_edit: false,
      },
      step_one_form: {
        driving_license_equivalnet_no: "",
        passport_number: "",
        priorities: [],
        priority: "",
        creating: false,
        updating: false,
        errors: [],
        success_msg: "",
        submitted: false,
        first_name: "",
        middle_name: "",
        last_name: "",
        father_name: "",
        father_occupation: "",
        father_income: "",
        father_mobile: "",
        father_email: "",
        mother_name: "",
        mother_occupation: "",
        mother_income: "",
        mother_mobile: "",
        mother_email: "",
        guardian_name: "",
        guardian_occupation: "",
        guardian_mobile: "",
        guardian_email: "",
        caste: "",
        sub_caste: "NA",
        is_pwd: false,
        pwd_exp: "",
        pwd_per: "",
        is_km: false,
        is_jk_student: false,
        is_ex_serviceman: false,
        adhaar: "",
        nad_id: "",
        abc: "",
        is_bpl: false,
        is_minority: false,
        is_accomodation_need: false,
        is_employed: false,
        is_aplly_defense_quota: false,
        religion: "",
        other: "",
        gender: "",
        marital_status: "",
        dob: "",
        employment_details: "",
        km_details: "",
        minority_details: "",
        part_time_details: "",
        academic_experience: "NA",
        publication_details: "",
        statement_of_purpose: "",
        family_income: "",
        father_qualification: "",
        mother_qualification: "",
        account_holder_name: "",
        bank_ac_no: "",
        bank_name: "",
        branch_name: "",
        ifsc_code: "",
        bank_reg_mobile_no: "",
        pan_no: "",
        is_full_time: true,
        passed_or_appeared_qualified_exam: "passed"
      },
      step_two_form: {
        creating: false,
        updating: false,
        errors: [],
        success_msg: "",
        submitted: false,
        is_permanent_same: false,
        correspondence: {
          co: "",
          house_no: "",
          vill_town: "",
          po: "",
          district: "",
          street_name_locality: "",
          pin_code: "",
          state: ""
        },
        permanent: {
          co: "",
          house_no: "",
          vill_town: "",
          po: "",
          district: "",
          street_name_locality: "",
          pin_code: "",
          state: ""
        },
        contact_no: "",
        email: "",
        nationality: "",
        state_domicile: "",
        place_residence: "",
        center: "",
        center1: "",
        center2: ""

      },
      step_three_form: {
        submitted: false,
        creating: false,
        errors: [],
        success_msg: "",
        sport_played: "",
        medel_type: "",
        work_experience: [],
        is_cuet_qualified: false,
        is_gate_qualified: false,
        academic10: {
          board_university_name: "",
          passing_year: "",
          class_grade: "",
          subjects_taken: "",
          cgpa: 0.0,
          marks_percentage: 0.00,
          remarks: "NA",
          academic_10_exam_type: "",
          academic_10_total_mark: 0.00,
          academic_10_mark_obtained: 0.00,
          is_passed_appearing: true
        },
        academic12: {
          board_university_name: "",
          passing_year: "",
          class_grade: "",
          subjects_taken: "",
          stream: "",
          cgpa: 0.0,
          marks_percentage: 0.00,
          remarks: "NA",
          academic_12_exam_type: "",
          academic_12_total_mark: 0.00,
          academic_12_mark_obtained: 0.0,
          is_passed_appearing: true,
          mca_mathematics_mark: ""
        },
        academic_bachelor: {
          board_university_name: "",
          passing_year: "",
          class_grade: "",
          subjects_taken: "",
          cgpa: 0.0,
          marks_percentage: 0.00,
          remarks: "NA",
          academic_graduation_exam_type: "",
          academic_graduation_total_mark: 0.0,
          academic_graduation_mark_obtained: 0.0,
          major: "",
          is_passed_appearing: true,
          degree: ""
        },
        academic_post_graduate: {
          board_university_name: "",
          passing_year: "",
          class_grade: "",
          subjects_taken: "",
          cgpa: 0.0,
          marks_percentage: 0.00,
          remarks: "NA",
          academic_post_graduation_exam_type: "",
          academic_post_graduation_total_mark: 0.0,
          academic_post_graduation_mark_obtained: 0.0,
          remarks: "",
          is_passed_appearing: true,
          degree: ""
        },
        academic_diploma: {
          board_university_name: "",
          passing_year: "",
          class_grade: "",
          subjects_taken: "",
          cgpa: 0.0,
          marks_percentage: 0.00,
          remarks: "NA",
          academic_diploma_exam_type: "",
          academic_diploma_total_mark: 0.0,
          academic_diploma_mark_obtained: 0.0,
          is_passed_appearing: true
        },
        jee: {
          roll_no: "",
          form_no: "",
          year: ""
        },
        cuet_count: 0,
        cuet: {
          cuet_roll_no: "",
          cuet_form_no: "",
          cuet_year: "",
          cuet_score: "",
          cuet_rank: "",
          subject_wise_dtl: [],
        },
        gate: {
          gate_roll_no: "",
          gate_form_no: "",
          gate_year: "",
          gate_score: "",
          gate_rank: "",
        },
        other_qualifications: {
          qualifications: [],
          errors: []
        },
        qualifing: {
          physics_total_mark: 0.00,
          physics_grade: "NA",
          physics_mark: "",

          chemistry_total_mark: 0.00,
          chemistry_grade: "NA",
          chemistry_mark: "",

          mathematics_total_mark: 0.00,
          mathematics_grade: "NA",
          mathematics_mark: "",

          english_total_mark: 0.00,
          english_grade: "NA",
          english_mark: 0.0,

          statistics_total_mark: 0.00,
          statistics_grade: "NA",
          statistics_mark: "",

          biology_total_mark: 0.00,
          biology_grade: "NA",
          biology_mark: "",

          english_mark_10_total_mark: 0.00,
          english_mark_10_grade: "NA",
          english_mark_10: 0.0,
        },
        mba_exam_data: {
          details: [],
          errors: []
        },

        is_sport_represented: false,
        is_prizes_distinction: false,
        other_information: "NA",
        is_debarred: false,
        is_punished: false,
        furnish_details: "NA",
        ceed_score: "0",
        proposed_area_of_research: [{
          data: ""
        }],
        area_of_research:[],
        qualified_national_level_test: "",
        qualified_national_level_test_mark: "",
        is_preview_click: false,
        gat_b_score: "",
      },
      step_four_form: {
        is_progress_error: false,
        is_accepted: false,
        submitted: false,
        creating: false,
        uploading: false,
        is_valid: true,
        errors: [],
        success_msg: "",
        document_prc: "",
        document_gate_score_card: "",
        caste_certificate: "",
        document_bpl: "",
        obc_ncl: "",
        document_sport_representation: "",
        document_ews: "",
        document_admit_jee: "",
        document_pwd: "",
        document_net_slet: "",
        document_noc: "",
        document_ex_servicement: "",
        document_passport: "",
        passport: "",
        document_driving_license: "",
        document_ssn_equivalent: "",
        document_passing_certificate: "",
        document_marksheet: "",
        document_passport_photo: "",
        document_signature: "",
        document_english_proficiency_certificate: "",
        gate_b_score_card: "",
        document_additional: "",
        undertaking_pass_appear: "",
        cuet_admit_card: "",
        bank_passbook: "",
        passport_photo_url: "",
        document_additional_url: "",
        signature_url: "",
        prc_url: "",
        sport_certificate_url: "",
        undertaking_url: "",
        undertaking_pass_appear_url: "",
        gate_score_card_url: "",
        caste_certificate_url: "",
        gate_b_score_card_url: "",
        ceed_score_card_url: "",
        bpl_card_url: "",
        obc_ncl_url: "",
        document_passport_url: "",
        document_pwd_url: "",
        document_noc_url: "",
        ceed_score_card: "",
        portfolio_file: "",
        document_admit_jee_url: "",
        document_net_slet_url: "",
        document_ex_servicement_url: "",
        document_driving_license_url: "",
        document_ssn_equivalent_url: "",
        document_passing_certificate_url: "",
        document_marksheet_url: "",
        cuet_admit_card_url: "",
        bank_passbook_url: "",
        document_english_proficiency_certificate_url: "",
        cuet_score_card: "",
        cuet_score_card_url: "",
        class_x_documents: "",
        class_XII_documents: "",
        graduation_documents: "",
        post_graduation_documents: "",
        class_x_grade_conversion: "",
        class_XII_grade_conversion: "",
        graduation_grade_conversion: "",
        post_graduation_grade_conversion: "",
        undertaking: "",
        uploaded_documents: [],
        misc_documents_name: [],
        misc_documents: [],
        mis_is_upload: [],
        errors2: {
          document_prc: "",
          document_gate_score_card: "",
          caste_certificate: "",
          document_bpl: "",
          obc_ncl: "",
          document_sport_representation: "",
          document_ews: "",
          document_admit_jee: "",
          document_pwd: "",
          document_net_slet: "",
          document_noc: "",
          document_ex_servicement: "",
          document_passport: "",
          document_ssn_equivalent: "",
          document_passing_certificate: "",
          document_passport_photo: "",
          document_signature: "",
          document_english_proficiency_certificate: "",
          document_additional: "",
          gate_b_score_card: "",
          ceed_score_card: "",
          portfolio_file: "",
          undertaking: "",
          undertaking_pass_appear: "",
          cuet_admit_card: "",
          bank_passbook: "",
          passport: "",
        }
      }
    };
  },
  computed: {




    isCheckedPreferenceSelected() {
      var selected = true;
      var checked_courses = this.initial_step.combined_courses.filter(
        course => course.is_checked == true && course.sub_preference == 0
      );
      checked_courses.forEach((value, index) => {
        if (value.preference == 0) {
          selected = false;
        }
      });
      return selected;
    },


    isNoPreferenceSelected() {
      var selected_none = true;
      this.initial_step.combined_courses.forEach((value, index) => {
        if (value.preference == 1 && value.sub_preference == 0) {
          selected_none = false;
        }
      });
      return selected_none;
    },


    isAllowNext() {
      var flag = true;
      this.initial_step.combined_courses.forEach((value, index) => {
        if (value.is_checked == true && value.preference == 0) {
          flag = false;
          return flag;
        }
      });
      // console.log(flag);
      return flag
    },



    isCheckedNonePreference() {
      var checked_none = true;
      this.initial_step.combined_courses.forEach((value, index) => {
        if (value.is_checked == true && value.preference != 1) {
          checked_none = false;
        }
      });
      return checked_none;
    },
    isCheckedNone() {
      var checked_none = true;
      this.initial_step.combined_courses.forEach((value, index) => {
        if (value.is_checked == true && value.sub_preference == 0) {
          checked_none = false;
        }
      });
      return checked_none;
    },
    filteredCourses() {
      return this.initial_step.courses.filter(course => {
        return !course.sub_combination_id || course.sub_preference
          ? true
          : false;
      });
    },
    is_ncl() {
      if (this.step_one_form.caste == "4") {
        return true;
      } else {
        return false;
      }
    },
    academic10SubjectsTextLeft() {
      var text = this.step_three_form.academic10.subjects_taken;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else {
        return 255;
      }
    },
    // proposed_area_of_researchTextLeft() {
    //   var text = this.step_three_form.proposed_area_of_research;
    //   if (text) {
    //     var char = text.length,
    //       limit = 255;
    //     return limit - char + " / " + limit + " characters remaining";
    //   } else {
    //     return 255;
    //   }
    // },

    academic12SubjectsTextLeft() {
      var text = this.step_three_form.academic12.subjects_taken;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else return 255;
    },
    academicBachelorSubjectsTextLeft() {
      var text = this.step_three_form.academic_bachelor.subjects_taken;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else return 255;
    },
    academicBachelorSubjectsTextLeft() {
      var text = this.step_three_form.academic_bachelor.subjects_taken;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else return 255;
    },
    academicPostSubjectsTextLeft() {
      var text = this.step_three_form.academic_post_graduate.subjects_taken;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else return 255;
    },
    academicDiplomaSubjectsTextLeft() {
      var text = this.step_three_form.academic_diploma.subjects_taken;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else return 255;
    },
    academicOtherInfoSubjectsTextLeft() {
      var text = this.step_three_form.other_information;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else return 255;
    },
    academicFurnishTextLeft() {
      var text = this.step_three_form.furnish_details;
      if (text) {
        var char = text.length,
          limit = 255;
        return limit - char + " / " + limit + " characters remaining";
      } else return 255;
    },
    getPriority() {
      var priority = this.step_one_form.priorities.find(
        priority => priority.id == this.step_one_form.priority
      );
      if (priority) {
        return priority.name;
      }
    },
    getCaste() {
      var caste = this.castes.find(
        caste => caste.id == this.step_one_form.caste
      );
      if (caste) {
        return caste.name;
      }
    },
    showOtherInput() {
      if (this.step_one_form.religion == "Other") {
        return true;
      } else {
        return false;
      }
    },
    is_preference() {
      var step0 = this.initial_step;
      if (step0.course.preference == true) {
        return true;
      } else {
        return false;
      }
    },
    is_sub_preference() {
      var step0 = this.initial_step;
      if (step0.course.sub_preference == true) {
        return true;
      } else {
        return false;
      }
    },
    is_lateral() {
      if (this.initial_step.course_type.code == "LATERAL") {
        return true;
      } else {
        return false;
      }
    },
    isPHD() {
      if (this.initial_step.course_type.code == "PHD") {
        return true;
      } else {
        return false;
      }
    },
    programType() {
      return this.initial_step.course.program;
    },
    // show_ug() {
    //   if (this.mode === "create") {
    //     if (this.programType.type == "PG" || this.programType.type == "PHD" || this.programType.type == "MBA") {
    //       return true;
    //     } else {
    //       return false;
    //     }
    //   } else if (this.mode === "edit") {
    //     if (this.program_type == "PG" || this.program_type == "PHD" || this.program_type == "MBA") {
    //       return true;
    //     } else {
    //       return false;
    //     }
    //   }
    // },
    is_mtech() {
      if (this.initial_step.course_type.code == "MTECH") {
        return true;
      }
      return false;
    },
    is_integrated() {
      if (this.initial_step.course_type.code == "INTEGRATED") {
        return true;
      }
      return false;
    },
    // show_pg() {
    //   if (this.initial_step.course_type.code == "MBA") {
    //     return false;
    //   }
    //   if (this.initial_step.course_type.code == "MTECH") {
    //     return true;
    //   }
    //   if (this.mode === "create") {
    //     if (this.programType.type == "PHD") {
    //       return true;
    //     } else {
    //       return false;
    //     }
    //   } else if (this.mode === "edit") {
    //     if (this.program_type == "PHD") {
    //       return true;
    //     } else {
    //       return false;
    //     }
    //   }
    // },
    is_master_in_design() {
      let course_id = 84;
      if (this.initial_step.course) {
        if (this.initial_step.course.id && this.initial_step.course.id == course_id) {
          return true;
        }
      } else if (this.application_old !== undefined && this.application_old.applied_courses) {
        if (this.application_old.applied_courses.filter(item => item.course_id == course_id).length) {
          return true;
        }
      }
      return false;
    },

    is_bachelor_in_design() {
      let course_id = 108;
      if (this.initial_step.course) {
        if (this.initial_step.course.id && this.initial_step.course.id == course_id) {
          return true;
        }
      } else if (this.application_old !== undefined && this.application_old.applied_courses) {
        if (this.application_old.applied_courses.filter(item => item.course_id == course_id).length) {
          return true;
        }
      }
      return false;
    },

    is_mbbt() {
      let course_id = 35;
      if (this.initial_step.course) {
        if (this.initial_step.course.id && this.initial_step.course.id == course_id) {
          return true;
        }
      } else if (this.application_old !== undefined && this.application_old.applied_courses) {
        if (this.application_old.applied_courses.filter(item => item.course_id == course_id).length) {
          return true;
        }
      }
      return false;
    },
    is_mtech_civil() {
      let course_id = 15;
      if (this.initial_step.course) {
        if (this.initial_step.course.id && this.initial_step.course.id == course_id) {
          return true;
        }
      } else if (this.application_old !== undefined && this.application_old.applied_courses) {
        if (this.application_old.applied_courses.filter(item => item.course_id == course_id).length) {
          return true;
        }
      }
      return false;
    },
    is_integrated_english() {
      let course_id = 6;
      if (this.initial_step.course) {
        if (this.initial_step.course.id && this.initial_step.course.id == course_id) {
          return true;
        }
      } else if (this.application_old !== undefined && this.application_old.applied_courses) {
        if (this.application_old.applied_courses.filter(item => item.course_id == course_id).length) {
          return true;
        }
      }
      return false;
    },
    is_integrated_mcom() {
      let course_id = 7;
      if (this.initial_step.course) {
        if (this.initial_step.course.id && this.initial_step.course.id == course_id) {
          return true;
        }
      } else if (this.application_old !== undefined && this.application_old.applied_courses) {
        if (this.application_old.applied_courses.filter(item => item.course_id == course_id).length) {
          return true;
        }
      }
      return false;
    },
    yearRange() {
      var currentYear = new Date().getFullYear(),
        years = [];
      years.push("NA");
      var startYear = 1980;
      while (startYear <= currentYear) {
        years.push(startYear++);
      }
      return years;
    }
  },


  mounted() {
    this.loadCourses();
    this.fillMBAExams();

    if (this.mode == "edit") {
      this.step = 1;
      this.loadOldApplication(this.application_id);
    } else if (this.mode == "create") {
      if (this.count > 0 && this.count < this.limit) {
        this.loadOldApplication(this.create_app_id);
      } else {
        //this.alertMessage("error", "Sorry we could not load your old record!!");
      }
    }
    
    this.step_three_form.work_experience = [1].map(id => {
      return {
        key: id + 'workexp',
        organization: "",
        designation: "",
        from: "",
        to: "",
        details: "",
      };
    })
    this.loadAreaOfResearch();
    this.loadCuetSubjects();
    
    // this.testforCuet();
    // this.calculateCuetAlreadyApplied();
  },



  methods: {


    checkUnique(centerName, selectedValue) {
      // alert(this.step_two_form.center+','+this.step_two_form.center1+','+this.step_two_form.center2)
      // alert(selectedValue);
      this.step_two_form.errors[centerName] = null;
      if (
        (centerName === 'center' && (selectedValue === this.step_two_form.center1 || selectedValue === this.step_two_form.center2)) ||
        (centerName === 'center1' && (selectedValue === this.step_two_form.center || selectedValue === this.step_two_form.center2)) ||
        (centerName === 'center2' && (selectedValue === this.step_two_form.center || selectedValue === this.step_two_form.center1))
      ) {
        alert("Exam Center is Already Selected");
        event.preventDefault();
        //this.step_two_form.errors[centerName] = 'Selected exam centers must be unique.';
      }
      else{
        if(centerName === 'center'){
          this.step_two_form.center = selectedValue;
        }else if(centerName === 'center1'){
          this.step_two_form.center1 = selectedValue;
        }else if(centerName === 'center2'){
          this.step_two_form.center2 = selectedValue;
        }
      }
    },

    // testforCuet(){
    //   alert("ok");
    //   console.log("cuet"+this.step_three_form.cuet.cuet_roll_no);
    //   console.log("cuet_sub"+this.step_three_form.cuet.subject_wise_dtl);
    //   if(this.step_three_form.cuet.cuet_roll_no != "" && this.step_three_form.cuet.subject_wise_dtl==""){
    //     this.addNewCuetDtls();
    //   }

    // },
    // calculateCuetAlreadyApplied(){
    //   //  this.step_three_form.cuet_count=this.step_three_form .cuet.subject_wise_dtl.length;
    //   var count = this.step_three_form .cuet.subject_wise_dtl.length;
    //   alert(count);
    // },



    calculatePercentage() {
      var total = this.step_three_form.academic10.academic_10_total_mark;
      var obtained = this.step_three_form.academic10.academic_10_mark_obtained;
      var percentage = obtained / total * 100;

      this.step_three_form.academic10.marks_percentage = percentage.toFixed(2);

    },

    calculate12Percentage() {
      var total = this.step_three_form.academic12.academic_12_total_mark;
      var obtained = this.step_three_form.academic12.academic_12_mark_obtained;
      var percentage = obtained / total * 100;
      this.step_three_form.academic12.marks_percentage = percentage.toFixed(2);

    },

    calculateGradPercentage() {
      var total = this.step_three_form.academic_bachelor.academic_graduation_total_mark;
      var obtained = this.step_three_form.academic_bachelor.academic_graduation_mark_obtained;
      var percentage = obtained / total * 100;
      this.step_three_form.academic_bachelor.marks_percentage = percentage.toFixed(2);

    },

    calculatePostGradPercentage() {
      var total = this.step_three_form.academic_post_graduate.academic_post_graduation_total_mark;
      var obtained = this.step_three_form.academic_post_graduate.academic_post_graduation_mark_obtained;
      var percentage = obtained / total * 100;
      this.step_three_form.academic_post_graduate.marks_percentage = percentage.toFixed(2);

    },

    academic10AppearedChanged() {
      if (this.step_three_form.academic10.is_passed_appearing === false) {
        this.step_three_form.academic10.remarks = "Results awaited";
      } else {
        if (this.mode == "edit") {
          this.step_three_form.academic10.remarks = this.application_old.application_academic.academic_10_remarks;
        } else if (this.mode == "create") {
          this.step_three_form.academic10.remarks = "";
        }
      }
    },
    academic12AppearedChanged() {
      if (this.step_three_form.academic12.is_passed_appearing === false) {
        this.step_three_form.academic12.remarks = "Results awaited";
      } else {
        if (this.mode == "edit") {
          this.step_three_form.academic12.remarks = this.application_old.application_academic.academic_12_remarks;
        } else if (this.mode == "create") {
          this.step_three_form.academic12.remarks = "";
        }
      }
    },
    academicBachelorAppearedChanged() {
      if (
        this.step_three_form.academic_bachelor.is_passed_appearing === false
      ) {
        this.step_three_form.academic_bachelor.remarks = "Results awaited";
      } else {
        if (this.mode == "edit") {
          this.step_three_form.academic_bachelor.remarks = this.application_old.application_academic.academic_graduation_remarks;
        } else if (this.mode == "create") {
          this.step_three_form.academic_bachelor.remarks = "";
        }
      }
    },
    academicPostAppearedChanged() {
      if (
        this.step_three_form.academic_post_graduate.is_passed_appearing ===
        false
      ) {
        this.step_three_form.academic_post_graduate.remarks = "Results awaited";
      } else {
        if (this.mode == "edit") {
          this.step_three_form.academic_post_graduate.remarks = this.application_old.application_academic.academic_post_graduation_remarks;
        } else if (this.mode == "create") {
          this.step_three_form.academic_post_graduate.remarks = "";
        }
      }
    },
    academicDiplomaAppearedChanged() {
      if (this.step_three_form.academic12.is_passed_appearing === false) {
        this.step_three_form.academic12.remarks = "Results awaited";
      } else {
        if (this.mode == "edit") {
          this.step_three_form.academic_diploma.remarks = this.application_old.application_academic.academic_diploma_remarks;
        } else if (this.mode == "create") {
          this.step_three_form.academic_diploma.remarks = "";
        }
      }
    },
    removeQualification(qual, index) {
      this.step_three_form.other_qualifications.qualifications.splice(
        index,
        1
      );
    },
    addNewQualification() {
      var item = {
        exam_name: "",
        board_name: "",
        passing_year: "",
        class_grade: "",
        subjects_taken: "",
        cgpa: "",
        marks_percentage: "",
        remarks: ""
      };
      this.step_three_form.other_qualifications.qualifications.push(item);
    },
    
    addNewDocument() {
      if (this.misc_count >= this.misc_limit) {
        alert("You can't add more then three miscellaneous documents");
      } else {
        this.misc_count = this.misc_count + 1;
      }
    },
    ugDocChangedIII(event, index) {
      const files = event.target.files;
      const misc_documents = [...this.step_four_form.misc_documents];
      misc_documents[index] = files[0];
      this.step_four_form.misc_documents = misc_documents;
    },
    deleteMiscDocument(index) {
      this.misc_limit = this.misc_limit + 1;
      $("#misc"+index).empty();
    },

    uploadSinglemiscFile(file_ref, file_name) {
      // // all file ref are from step 4 so no need to mention file param
      // if (!this.step_four_form[file_ref] || this.step_four_form[file_ref] == undefined) {
      //   this.alertMessage("danger", "Please select a file to upload.");
      //   return;
      // }

      // // url student/file-upload/app_id
      // var formdata = new FormData();
      // formdata.append(file_name, this.step_four_form[file_ref]);
      // this.singleFileUploadingProgress = true;
      // axios({
      //   method: "post",
      //   url: "student/application/file-upload/" + this.application_old.id,
      //   data: formdata,
      //   onUploadProgress: function (progressEvent) {
      //     this.progress_style.width =
      //       parseInt(
      //         Math.round((progressEvent.loaded / progressEvent.total) * 100)
      //       ) + "%";
      //   }.bind(this)
      // })
      //   .then(response => {
      //     // console.log(response.data);
      //     var msg = `${file_name} successfully uploaded.`;
      //     this.step_four_form.success_msg = msg.replace("_", " ");

      //     this.step_four_form.uploading = false;
      //     this.step_four_form.submitted = true;
      //     this.step_four_form.is_progress_error = false;

      //     this.step_four_form[file_ref].value = null;
      //     this.step_four_form.errors = [];
      //     this.step_four_form.uploaded_documents= response.data.already_upload;
      //     this.step_four_form.errors2 = [];

      //     this.alertMessage(
      //       "success",
      //       "file successfully uploaded."
      //     );
      //     this.singleFileUploadingProgress = false;
      //     // console.log(this.step_four_form.uploaded_documents);
      //   })
      //   .catch(error => {
      //     this.step_four_form.is_progress_error = true;
      //     this.alertMessage("danger", "Uploading failed.");
      //     this.step_four_form.uploading = false;
      //     if (error.response.status == 422) {
      //       this.step_four_form.errors = error.response.data.errors;
      //     }
      //   })
      //   .then(() => {
      //     this.step_four_form.uploading = false;
      //     this.singleFileUploadingProgress = false;
      //   });
    },

    removeCuetDtl(cuet, index) {
      this.step_three_form.cuet_count--;
      this.step_three_form.cuet.subject_wise_dtl.splice(
        index,
        1
      );
    },
    loadCuetSubjects() {
      // alert("ok");
      axios({
        method: "post",
        url: "student/application/edit/load_cuet_sujects/" + this.application_id,
      }).then(response => {
        console.log(response.data.cuet_subject);
        this.cuet_sub_limit = response.data.cuet_subject.length;
        this.cuet_subject = response.data.cuet_subject;
      });

    },

    loadAreaOfResearch() {
      
      axios({
        method: "get",
        url: "student/application/edit/load_area_of_research/" + this.application_id,
      }).then(response => {
        // alert("ok");
        console.log(response.data);
        this.area_of_researchs = response.data.area_of_researchs;
        this.area_of_researchs_length = this.area_of_researchs.length;
        // for (let i = 0; i < this.area_of_researchs.length; i++) {
        //   this.step_three_form.proposed_area_of_research.push({
        //     data: ""
        //   });
        // // }
        // console.log(response);
        // console.log(area_of_researchs);
      });

    },

    addNewCuetDtls() {
      // console.log(this.step_three_form.cuet.subject_wise_dtl);
      var limit = 2;
      if (this.initial_step.is_cuet_ug == true) {
        var limit = this.cuet_sub_limit;
      }
      if (this.initial_step.is_cuet_pg == true) {
        var limit = this.cuet_sub_limit;
      }
      // console.log(this.application_id);
      // console.log(this.step_three_form.cuet_count);
      this.step_three_form.cuet_count++;
      if (this.step_three_form.cuet_count <= limit) {
        var item = {
          subjects: "",
          marks: "",
          percentile: "",
        };
        this.step_three_form.cuet.subject_wise_dtl.push(item);
      } else {
        if (this.initial_step.is_cuet_ug == true) {
          var msg = this.cuet_sub_limit;
        }
        if (this.initial_step.is_cuet_pg == true) {
          var msg = this.cuet_sub_limit;
        }
        this.step_three_form.cuet_count--;
        if(msg>0){
          alert("You Can't Add More Then " + msg + " Subjects");
        }
        
      }
    },

    emptyCuetDtl() {
      // alert("okk");
      this.step_three_form.cuet.subject_wise_dtl = [];
      this.step_three_form.cuet_count = 0;
    },
    loadOldApplication(id) {
      // alert("KKK");
      axios({
        method: "post",
        url: "student/application/edit/load_old/" + id
      }).then(response => {
        // alert("OOOKKK");
        // console.log(response.data.misc_documents);
        // console.log(response.data.misc_documents.length);
        
        this.misc_count = response.data.misc_documents.length;       
        for (let i = 0; i < response.data.misc_documents.length; i++) {
          this.step_four_form.misc_documents_name[i] = response.data.misc_documents[i].document_name;
          this.step_four_form.mis_is_upload[i] = response.data.misc_documents[i].document_name;
        }  
        
        this.step_four_form.uploaded_documents = response.data.already_upload;
        this.application_old = response.data.application;
        this.app_programme = response.data.program_list;
        var old_programme = this.app_programme;
        if (old_programme.course_id == 37) {
          this.initial_step.is_mca = 1;
        }

        var old_app = this.application_old;
        
        var step_one = this.step_one_form;
        var step_two = this.step_two_form;
        var step_three = this.step_three_form;
        var step_four = this.step_four_form;
        if (this.mode == "edit") {
          this.application_type = old_app.exam_through;
          if (old_app.form_step == 1) {
            this.step = 2;
            step_one.submitted = true;
          }
          if (old_app.form_step == 2) {
            this.step = 3;
            step_one.submitted = true;
            step_two.submitted = true;
          }
          if (old_app.form_step == 3) {
            this.step = 4;
            step_one.submitted = true;
            step_two.submitted = true;
            step_three.submitted = true;
          }
          if (old_app.form_step == 4) {
            step_one.submitted = true;
            step_two.submitted = true;
            step_three.submitted = true;
            step_four.submitted = true;
          }
        } else {
          this.step = 0;
        }
        // alert(old_app.is_editable);
        this.initial_step.is_mba_edit = old_app.is_editable == 3 ? true : false;
        this.initial_step.is_btech = old_app.is_btech == 1 ? true : false;
        this.initial_step.is_cuet_ug = old_app.is_cuet_ug == 1 ? true : false;
        this.initial_step.is_cuet_pg = old_app.is_cuet_pg == 1 ? true : false;
        this.initial_step.is_mdes = old_app.is_mdes ==1 ?true : false;
        this.initial_step.is_phd = old_app.is_phd == 1 ? true : false;
        this.initial_step.is_mba = old_app.is_mba == 1 ? true : false;
        // this.initial_step.is_phd_prof = old_app.is_phd_prof == 1 ? true : false;
        // console.log(this.is_foreign);
        
        // this.is_foreign = old_app.is_foreign == 1 ? true : false;
        // alert(old_app.is_foreign);
        // alert(this.is_foreign);
        // if (old_app.is_foreign == 1) {
        //     this.is_foreign = true;
        //   } else {
        //     this.is_foreign = false;
        //   }
        this.program_type = response.data.program.type;
        this.initial_step.course_type = response.data.course_type;
        step_one.middle_name =
          old_app.middle_name == null ? "" : old_app.middle_name.toUpperCase();
        step_one.first_name =
          old_app.first_name == null ? "" : old_app.first_name.toUpperCase();
        step_one.last_name =
          old_app.last_name == null ? "" : old_app.last_name.toUpperCase();
        step_one.father_name =
          old_app.father_name == "null" ? "" : old_app.father_name;
        step_one.father_occupation =
          old_app.father_occupation == "null" ? "" : old_app.father_occupation;
        step_one.father_income =
          old_app.father_income == "null" ? "" : old_app.father_income;
        step_one.father_mobile =
          old_app.father_mobile == "null" ? "" : old_app.father_mobile;
        step_one.father_email =
          old_app.father_email == "null" ? "" : old_app.father_email;
        this.step_one_form.mother_name =
          old_app.mother_name == "null" ? "" : old_app.mother_name;
        step_one.family_income =
          old_app.family_income == "null" ? "" : old_app.family_income;

        step_one.father_qualification =
          old_app.father_qualification == "null" ? "" : old_app.father_qualification;

        step_one.mother_qualification =
          old_app.mother_qualification == "null" ? "" : old_app.mother_qualification;

        step_one.bank_ac_no =
          old_app.bank_ac_no == "null" ? "" : old_app.bank_ac_no;
        step_one.bank_name =
          old_app.bank_name == "null" ? "" : old_app.bank_name;
        step_one.branch_name =
          old_app.branch_name == "null" ? "" : old_app.branch_name;
        step_one.ifsc_code =
          old_app.ifsc_code == "null" ? "" : old_app.ifsc_code;
        step_one.bank_reg_mobile_no =
          old_app.bank_reg_mobile_no == "null" ? "" : old_app.bank_reg_mobile_no;
        step_one.mother_occupation =
          old_app.mother_occupation == "null" ? "" : old_app.mother_occupation;
        step_one.mother_income =
          old_app.mother_income == "null" ? "" : old_app.mother_income;
        step_one.mother_mobile =
          old_app.mother_mobile == "null" ? "" : old_app.mother_mobile;
        step_one.mother_email =
          old_app.mother_email == "null" ? "" : old_app.mother_email;
        step_one.guardian_name =
          old_app.guardian_name == "null" ? "" : old_app.guardian_name;
        step_one.guardian_occupation =
          old_app.guardian_occupation == "null"
            ? ""
            : old_app.guardian_occupation;
        step_one.guardian_mobile =
          old_app.guardian_phone == "null" ? "" : old_app.guardian_phone;
        step_one.guardian_email =
          old_app.guardian_email == "null" ? "" : old_app.guardian_email;
        if (old_app.caste_id != "null") {
          step_one.caste = old_app.caste_id;
        }
        step_one.sub_caste = old_app.sub_caste;
        // step_one.is_pwd = old_app.is_pwd1 == 1 ? true : false;
        step_one.is_pwd = old_app.is_pwd == 1 ? true : false;
        step_one.pwd_exp =
          old_app.person_with_disablity == "null"
            ? ""
            : old_app.person_with_disablity;
        step_one.pwd_per =
          old_app.pwd_percentage == "null"
            ? ""
            : old_app.pwd_percentage;
        step_one.is_km = old_app.is_km == 1 ? true : false;
        step_one.km_details =
          old_app.km_details == "null" ? "" : old_app.km_details;
        step_one.is_jk_student = old_app.is_jk_student == 1 ? true : false;
        step_one.is_ex_serviceman =
          old_app.is_ex_servicement == 1 ? true : false;
        step_one.is_aplly_defense_quota =
          old_app.is_aplly_defense_quota == 1 ? true : false;
        step_one.priority = old_app.priority_id;
        step_one.adhaar = old_app.adhaar == "null" ? null : old_app.adhaar;
        step_one.nad_id = old_app.nad_id == "null" ? null : old_app.nad_id;
        step_one.abc = old_app.abc == "null" ? null : old_app.abc;
        step_one.is_bpl = old_app.is_bpl == 1 ? true : false;
        step_one.is_minority = old_app.is_minority == 1 ? true : false;
        step_one.minority_details =
          old_app.minority_details == "null" ? "" : old_app.minority_details;
        step_one.is_accomodation_need =
          old_app.is_accomodation_needed == 1 ? true : false;
        step_one.is_employed = old_app.is_employed == 1 ? true : false;
        step_one.employment_details = old_app.employment_details;
        step_one.religion = old_app.religion;
        step_one.gender = old_app.gender;
        step_one.marital_status = old_app.marital_status;
        step_one.dob = old_app.dob;
        step_one.passport_number =
          old_app.passport_number == null ? "" : old_app.passport_number;
        step_one.driving_license_equivalnet_no =
          old_app.driving_license_equivalnet_no == null
            ? ""
            : old_app.driving_license_equivalnet_no;

        step_one.account_holder_name = old_app.account_holder_name;
        var corr = step_two.correspondence;
        var perm = step_two.permanent;
        corr.co =
          old_app.correspondence_co == "null" ? "" : old_app.correspondence_co;
        corr.house_no =
          old_app.correspondence_house_no == "null"
            ? ""
            : old_app.correspondence_house_no;
        corr.vill_town =
          old_app.correspondence_village_town == "null"
            ? ""
            : old_app.correspondence_village_town;
        corr.po =
          old_app.correspondence_po == "null" ? "" : old_app.correspondence_po;
        corr.district =
          old_app.correspondence_district == "null"
            ? ""
            : old_app.correspondence_district;
        corr.street_name_locality =
          old_app.correspondence_street_locality == "null"
            ? ""
            : old_app.correspondence_street_locality;
        corr.pin_code =
          old_app.correspondence_pin == "null"
            ? ""
            : old_app.correspondence_pin;
        corr.state =
          old_app.correspondence_state == "null"
            ? ""
            : old_app.correspondence_state;
        this.loadDistricts(corr.state);
        perm.co = old_app.permanent_co == "null" ? "" : old_app.permanent_co;
        perm.house_no =
          old_app.permanent_house_no == "null"
            ? ""
            : old_app.permanent_house_no;
        perm.vill_town =
          old_app.permanent_village_town == "null"
            ? ""
            : old_app.permanent_village_town;
        perm.po = old_app.permanent_po == "null" ? "" : old_app.permanent_po;
        perm.district =
          old_app.permanent_district == "null"
            ? ""
            : old_app.permanent_district;
        perm.street_name_locality =
          old_app.permanent_street_locality == "null"
            ? ""
            : old_app.permanent_street_locality;
        perm.pin_code =
          old_app.permanent_pin == "null" ? "" : old_app.permanent_pin;
        perm.state =
          old_app.permanent_state == "null" ? "" : old_app.permanent_state;
        this.loadDistrictsPermanent(perm.state);
        step_two.nationality =
          old_app.nationality == "null" ? "" : old_app.nationality;
        step_two.place_residence =
          old_app.place_residence == "null" ? "" : old_app.place_residence;
        step_two.adhaar = old_app.adhaar;
        step_two.nad_id = old_app.nad_id;
        step_two.abc = old_app.abc;
        if(old_app.exam_through=='TUEE'){
          step_two.center =
            old_app.exam_center_id == null ? "" : old_app.exam_center_id;
          step_two.center1 =
            old_app.exam_center_id1 == null ? "" : old_app.exam_center_id1;
          step_two.center2 =
            old_app.exam_center_id2 == null ? "" : old_app.exam_center_id2;
        }       
        var aca10 = step_three.academic10;
        var aca12 = step_three.academic12;
        var aca_bac = step_three.academic_bachelor;
        var aca_post = step_three.academic_post_graduate;
        var aca_jee = step_three.jee;
        var aca_diploma = step_three.academic_diploma;
        var aca_qualifing = step_three.qualifing;
        if (old_app.application_academic) {

          aca10.board_university_name =
            old_app.application_academic.academic_10_board;
          aca10.passing_year = old_app.application_academic.academic_10_year;
          aca10.class_grade = old_app.application_academic.academic_10_grade;
          aca10.subjects_taken = old_app.application_academic.academic_10_subject;
          aca10.cgpa = old_app.application_academic.academic_10_cgpa;
          aca10.academic_10_exam_type = old_app.application_academic.academic_10_exam_type;
          aca10.academic_10_total_mark = old_app.application_academic.academic_10_total_mark;
          aca10.academic_10_mark_obtained = old_app.application_academic.academic_10_mark_obtained;
          aca10.marks_percentage =
            old_app.application_academic.academic_10_percentage;
          aca10.remarks = old_app.application_academic.academic_10_remarks;
          aca10.is_passed_appearing =
            old_app.application_academic.academic_10_is_passed_appearing == "1"
              ? true
              : false;

          aca_diploma.board_university_name =
            old_app.application_academic.academic_diploma_board;
          aca_diploma.passing_year =
            old_app.application_academic.academic_diploma_year;
          aca_diploma.class_grade =
            old_app.application_academic.academic_diploma_grade;
          aca_diploma.subjects_taken =
            old_app.application_academic.academic_diploma_subject;
          aca_diploma.cgpa = old_app.application_academic.academic_diploma_cgpa;
          aca_diploma.academic_diploma_exam_type = old_app.application_academic.academic_diploma_exam_type;
          aca_diploma.academic_diploma_total_mark = old_app.application_academic.academic_diploma_total_mark;
          aca_diploma.academic_diploma_mark_obtained = old_app.application_academic.academic_diploma_mark_obtained;
          aca_diploma.marks_percentage =
            old_app.application_academic.academic_diploma_percentage;
          aca_diploma.remarks =
            old_app.application_academic.academic_diploma_remarks;
          aca_diploma.is_passed_appearing =
            old_app.application_academic.academic_diploma_is_passed_appearing ==
              "1"
              ? true
              : false;

          aca12.board_university_name =
            old_app.application_academic.academic_12_board;
          aca12.passing_year = old_app.application_academic.academic_12_year;
          aca12.class_grade = old_app.application_academic.academic_12_grade;
          aca12.subjects_taken = old_app.application_academic.academic_12_subject;
          aca12.stream = old_app.application_academic.academic_12_stream;
          aca12.cgpa = old_app.application_academic.academic_12_cgpa;
          aca12.academic_12_exam_type = old_app.application_academic.academic_12_exam_type;
          aca12.academic_12_total_mark = old_app.application_academic.academic_12_total_mark;
          aca12.academic_12_mark_obtained = old_app.application_academic.academic_12_mark_obtained;
          aca12.marks_percentage =
            old_app.application_academic.academic_12_percentage;
          aca12.remarks = old_app.application_academic.academic_12_remarks;
          aca12.is_passed_appearing =
            old_app.application_academic.academic_12_is_passed_appearing == "1"
              ? true
              : false;
          aca12.mca_mathematics_mark = old_app.application_academic.mca_mathematics_mark;
          aca_bac.board_university_name =
            old_app.application_academic.academic_graduation_board;
          aca_bac.passing_year =
            old_app.application_academic.academic_graduation_year;
          aca_bac.class_grade =
            old_app.application_academic.academic_graduation_grade;
          aca_bac.subjects_taken =
            old_app.application_academic.academic_graduation_subject;
          aca_bac.cgpa = old_app.application_academic.academic_graduation_cgpa;
          aca_bac.academic_graduation_exam_type = old_app.application_academic.academic_graduation_exam_type;
          aca_bac.academic_graduation_total_mark = old_app.application_academic.academic_graduation_total_mark;
          aca_bac.academic_graduation_mark_obtained = old_app.application_academic.academic_graduation_mark_obtained;
          aca_bac.marks_percentage =
            old_app.application_academic.academic_graduation_percentage;
          aca_bac.remarks =
            old_app.application_academic.academic_graduation_remarks;
          aca_bac.is_passed_appearing =
            old_app.application_academic
              .academic_graduation_is_passed_appearing == "1"
              ? true
              : false;
          aca_bac.major = old_app.application_academic.acadmeic_graduation_major;
          aca_bac.degree = old_app.application_academic.academic_bachelor_degree;

          aca_post.board_university_name =
            old_app.application_academic.academic_post_graduation_board;
          aca_post.passing_year =
            old_app.application_academic.academic_post_graduation_year;
          aca_post.class_grade =
            old_app.application_academic.academic_post_graduation_grade;
          aca_post.subjects_taken =
            old_app.application_academic.academic_post_graduation_subject;
          aca_post.cgpa =
            old_app.application_academic.academic_post_graduation_cgpa;
          aca_post.academic_post_graduation_exam_type = old_app.application_academic.academic_post_graduation_exam_type;
          aca_post.academic_post_graduation_total_mark = old_app.application_academic.academic_post_graduation_total_mark;
          aca_post.academic_post_graduation_mark_obtained = old_app.application_academic.academic_post_graduation_mark_obtained;

          aca_post.marks_percentage =
            old_app.application_academic.academic_post_graduation_percentage;
          aca_post.remarks =
            old_app.application_academic.academic_post_graduation_remarks;
          aca_post.is_passed_appearing =
            old_app.application_academic
              .academic_post_graduation_is_passed_appearing == "1"
              ? true
              : false;
          aca_post.degree =
            old_app.application_academic.academic_post_graduation_degree;

          aca_jee.roll_no = old_app.application_academic.jee_roll_no;
          aca_jee.form_no = old_app.application_academic.jee_form_no;
          aca_jee.year = old_app.application_academic.jee_year;

          aca_qualifing.physics_grade = old_app.application_academic.physics_grade;
          aca_qualifing.physics_total_mark = old_app.application_academic.physics_total_mark;
          aca_qualifing.physics_mark = old_app.application_academic.physics_mark;

          aca_qualifing.chemistry_mark = old_app.application_academic.chemistry_mark;
          aca_qualifing.chemistry_total_mark = old_app.application_academic.chemistry_total_mark;
          aca_qualifing.chemistry_grade = old_app.application_academic.chemistry_grade;

          aca_qualifing.mathematics_mark = old_app.application_academic.mathematics_mark;
          aca_qualifing.mathematics_total_mark = old_app.application_academic.mathematics_total_mark;
          aca_qualifing.mathematics_grade = old_app.application_academic.mathematics_grade;

          aca_qualifing.english_grade = old_app.application_academic.english_grade;
          aca_qualifing.english_total_mark = old_app.application_academic.english_total_mark;
          aca_qualifing.english_mark = old_app.application_academic.english_mark;

          aca_qualifing.statistics_grade = old_app.application_academic.statistics_grade;
          aca_qualifing.statistics_mark = old_app.application_academic.statistics_mark;
          aca_qualifing.statistics_total_mark = old_app.application_academic.statistics_total_mark;

          aca_qualifing.english_mark_10 = old_app.application_academic.english_mark_10;
          aca_qualifing.english_mark_10_total_mark = old_app.application_academic.english_mark_10_total_mark;
          aca_qualifing.english_mark_10_grade = old_app.application_academic.english_mark_10_grade;

          aca_qualifing.biology_mark = old_app.application_academic.biology_mark;
          aca_qualifing.biology_total_mark = old_app.application_academic.biology_total_mark;
          aca_qualifing.biology_grade = old_app.application_academic.biology_grade;

          step_three.other_qualifications.qualifications =
            old_app.other_qualifications;

          step_three.cuet.subject_wise_dtl =
            old_app.cuet_exam_details;

          this.step_three_form.cuet_count = this.step_three_form.cuet.subject_wise_dtl.length;


          step_three.is_sport_represented =
            old_app.application_academic.is_sport_represented == 1 ? true : false;
          step_three.is_cuet_qualified = old_app.application_academic.cuet_qualified == 1 ? true : false;
          step_three.is_gate_qualified = old_app.application_academic.gate_qualified == 1 ? true : false;

          step_three.medel_type = old_app.application_academic.medel_type;
          step_three.sport_played = old_app.application_academic.sport_played;

          step_three.cuet.cuet_score = old_app.application_academic.cuet_score;
          step_three.cuet.cuet_rank = old_app.application_academic.cuet_rank;
          step_three.cuet.cuet_roll_no = old_app.application_academic.cuet_roll_no;
          if (this.step_three_form.cuet.cuet_roll_no != "" && this.step_three_form.cuet.subject_wise_dtl == "") {
            this.addNewCuetDtls();
          }
          step_three.cuet.cuet_form_no = old_app.application_academic.cuet_form_no;
          step_three.cuet.cuet_year = old_app.application_academic.cuet_year;

          step_three.gate.gate_score = old_app.application_academic.gate_score;
          step_three.gate.gate_rank = old_app.application_academic.gate_rank;
          step_three.gate.gate_roll_no = old_app.application_academic.gate_roll_no;
          step_three.gate.gate_form_no = old_app.application_academic.gate_form_no;
          step_three.gate.gate_year = old_app.application_academic.gate_year;
          // console.log(old_app.application_academic);
          step_three.sport_played =
            old_app.application_academic.sport_played == "null"
              ? ""
              : old_app.application_academic.sport_played;
          step_three.is_prizes_distinction =
            old_app.application_academic.is_prizes_distinction == 1
              ? true
              : false;
          step_three.other_information =
            old_app.application_academic.other_information == "null"
              ? ""
              : old_app.application_academic.other_information;
          step_three.is_debarred =
            old_app.application_academic.is_debarred == 1 ? true : false;
          step_three.is_punished =
            old_app.application_academic.is_punished == 1 ? true : false;
          step_three.furnish_details =
            old_app.application_academic.furnish_details == "null"
              ? ""
              : old_app.application_academic.furnish_details;
          step_one.part_time_details = old_app.application_academic.part_time_details;
          step_one.academic_experience = old_app.application_academic.academic_experience;

          step_one.publication_details = old_app.application_academic.publication_details;
          step_one.statement_of_purpose = old_app.application_academic.statement_of_purpose;
          step_one.bank_acc = old_app.application_academic.bank_acc;
          step_one.pan_no = old_app.application_academic.pan_no;
          step_one.ifsc_no = old_app.application_academic.ifsc_no;
          step_one.branch_code = old_app.application_academic.branch_code;
          step_one.is_full_time = old_app.application_academic.is_full_time;
          step_one.passed_or_appeared_qualified_exam = old_app.application_academic.passed_or_appeared_qualified_exam;
          step_three.qualified_national_level_test = old_app.application_academic.qualified_national_level_test;
          step_three.gat_b_score = old_app.application_academic.gat_b_score;
          step_three.ceed_score = old_app.application_academic.ceed_score;
          step_three.qualified_national_level_test_mark = old_app.application_academic.qualified_national_level_test_mark;
          if (old_app.application_academic.proposed_area_of_research) {
            step_three.proposed_area_of_research = old_app.application_academic.proposed_area_of_research.map(function (item, index) {
              return {
                data: item
              }
            });

          }
        }

        if (old_app && old_app.extra_exam_details.length) {
          this.fillMBAExams(old_app.extra_exam_details);
        }
        if (this.is_mba === false) {
          if (this.initial_step.course && this.initial_step.course.id && this.initial_step.course.id == 80) {
            this.is_mba = true;
            return;
          }
          if (old_app.applied_courses) {
            if (old_app.applied_courses.filter(item => item.course_id == 80).length) {
              this.is_mba = true;
            }
          }
        }
        if (this.initial_step.course) {
          if (this.initial_step.course.id && this.phd_course_ids.some(function (course_id) {
            return this.initial_step.course.id == course_id;
          })) {
            this.is_phd = true;
            return;
          }
        } else if (old_app.applied_courses) {
          // console.log("applied courses", old_app.applied_courses);
          // console.log("phd coursed", this.phd_course_ids);
          // if(old_app.applied_courses.filter(item => item.course_id == 80).length){
          // this.is_phd = true;
          // }
          // console.log("true false thisphd", old_app.applied_courses.some(course=> this.phd_course_ids.includes(course.course_id)));
          // old_app.applied_courses.forEach(function(item, key){
          //   console.log("item", item.course_id);
          // });
          this.is_phd = old_app.applied_courses.some(course => this.phd_course_ids.includes(parseInt(course.course_id)));
        }
        var length_of_exp = old_app.work_experiences.length;
        if(length_of_exp>0){
          this.step_three_form.work_experience  = [];
        }
        // alert(old_app.work_experiences.length);
        if (!this.initial_step.course) {          
          for (var i = 0; i < length_of_exp; i++) {
            this.addMoreExperience();
            if (old_app.work_experiences) {
              if (old_app.work_experiences[i] !== undefined) {
                this.step_three_form.work_experience[i].organization = old_app.work_experiences[i].organization;
                this.step_three_form.work_experience[i].designation = old_app.work_experiences[i].designation;
                this.step_three_form.work_experience[i].from = old_app.work_experiences[i].from;
                this.step_three_form.work_experience[i].to = old_app.work_experiences[i].to;
                this.step_three_form.work_experience[i].details = old_app.work_experiences[i].details;
              }
            }
          }
        }
        // console.log(this);
      });
    },
    additionalFileChanged() {
      this.step_four_form.document_additional = this.$refs.additional_document.files[0];
      this.step_four_form.document_additional_url = URL.createObjectURL(
        this.step_four_form.document_document_additional
      );
    },
    proficiencyFileChanged() {
      this.step_four_form.document_english_proficiency_certificate = this.$refs.english_proficiency_certificate.files[0];
      this.step_four_form.document_english_proficiency_certificate_url = URL.createObjectURL(
        this.step_four_form.document_english_proficiency_certificate
      );
    },
    passportPhotoFileChanged() {
      this.step_four_form.document_passport_photo = this.$refs.document_passport_photo.files[0];
      this.step_four_form.passport_photo_url = URL.createObjectURL(
        this.step_four_form.document_passport_photo
      );
    },
    signatureFileChanged() {
      this.step_four_form.document_signature = this.$refs.document_signature.files[0];
      this.step_four_form.signature_url = URL.createObjectURL(
        this.step_four_form.document_signature
      );
    },
    gateBScoreCardChange() {
      this.step_four_form.gate_b_score_card = this.$refs.gate_b_score_card.files[0];
      this.step_four_form.gate_b_score_card_url = URL.createObjectURL(
        this.step_four_form.gate_b_score_card
      );
    },

    CeedScoreCardChange() {
      this.step_four_form.ceed_score_card = this.$refs.ceed_score_card.files[0];

      this.step_four_form.ceed_score_card_url = URL.createObjectURL(
        this.step_four_form.ceed_score_card
      );
    },

    portfolioChange() {
      this.step_four_form.portfolio_file = this.$refs.portfolio_file.files[0];
    },

    anyDcoumentsChange(e, doc_name) {
      var doc_name = (doc_name + "_score_card").toLowerCase();
      this.step_four_form[doc_name] = e.target.files[0];
    },
    prcFileChanged() {
      this.step_four_form.document_prc = this.$refs.document_prc.files[0];
      this.step_four_form.prc_url = URL.createObjectURL(
        this.step_four_form.document_prc
      );
    },
    undertakingFileChanged() {

      this.step_four_form.undertaking = this.$refs.undertaking.files[0];
      this.step_four_form.undertaking_url = URL.createObjectURL(
        this.step_four_form.undertaking
      );
    },

    undertakingPassAppearFileChanged() {
      this.step_four_form.undertaking_pass_appear = this.$refs.undertaking_pass_appear.files[0];
      // console.log(this.step_four_form.undertaking_pass_appear);
      this.step_four_form.undertaking_pass_appear_url = URL.createObjectURL(
        this.step_four_form.undertaking_pass_appear
      );

    },
    gateCardFileChanged() {
      this.step_four_form.document_gate_score_card = this.$refs.document_gate_card.files[0];
      this.step_four_form.gate_score_card_url = URL.createObjectURL(
        this.step_four_form.document_gate_score_card
      );
    },
    casteCertificateChanged() {
      this.step_four_form.caste_certificate = this.$refs.caste_certificate.files[0];

      this.step_four_form.caste_certificate_url = URL.createObjectURL(
        this.step_four_form.caste_certificate
      );
    },
    bplFileChanged() {
      this.step_four_form.document_bpl = this.$refs.document_bpl.files[0];
      this.step_four_form.bpl_card_url = URL.createObjectURL(
        this.step_four_form.document_bpl
      );
    },
    obcFileChanged() {
      this.step_four_form.obc_ncl = this.$refs.obc_ncl.files[0];
      this.step_four_form.obc_ncl_url = URL.createObjectURL(
        this.step_four_form.obc_ncl
      );

    },
    sportCertificateChanged() {
      this.step_four_form.document_sport_representation = this.$refs.document_sport_representation.files[0];
      this.step_four_form.sport_certificate_url = URL.createObjectURL(
        this.step_four_form.document_sport_representation
      );
    },
    ewsFileChanged() {
      this.step_four_form.document_ews = this.$refs.document_ews.files[0];
    },
    admiCardChanged() {
      // console.log(this.$refs.document_admit_jee.files[0]);
      this.step_four_form.document_admit_jee = this.$refs.document_admit_jee.files[0];
      // console.log("ok2");
      this.step_four_form.document_admit_jee_url = URL.createObjectURL(
        this.step_four_form.document_admit_jee
      );
      // console.log(this.step_four_form.document_admit_jee);
      // console.log(this.step_four_form.document_admit_jee_url);
    },
    cuetAdmitCardChanged() {
      this.step_four_form.cuet_admit_card = this.$refs.cuet_admit_card.files[0];
      this.step_four_form.cuet_admit_card_url = URL.createObjectURL(
        this.step_four_form.cuet_admit_card
      );
    },

    cuetScoreCardChanged() {
      this.step_four_form.cuet_score_card = this.$refs.cuet_score_card.files[0];
      this.step_four_form.cuet_score_card_url = URL.createObjectURL(
        this.step_four_form.cuet_score_card
      );
    },

    classXChanged() {
      this.step_four_form.class_x_documents = this.$refs.class_x_documents.files[0];
    },

    classXIIChanged() {
      this.step_four_form.class_XII_documents = this.$refs.class_XII_documents.files[0];
    },

    ugDocChanged() {
      this.step_four_form.graduation_documents = this.$refs.graduation_documents.files[0];
    },

    pgDocChanged() {
      this.step_four_form.post_graduation_documents = this.$refs.post_graduation_documents.files[0];
    },



    classXChangedII() {
      this.step_four_form.class_x_grade_conversion = this.$refs.class_x_grade_conversion.files[0];
    },

    classXIIChangedII() {
      this.step_four_form.class_XII_grade_conversion = this.$refs.class_XII_grade_conversion.files[0];
    },

    ugDocChangedII() {
      this.step_four_form.graduation_grade_conversion = this.$refs.graduation_grade_conversion.files[0];
    },

    pgDocChangedII() {
      this.step_four_form.post_graduation_grade_conversion = this.$refs.post_graduation_grade_conversion.files[0];
    },

    bankPassbookChanged() {
      this.step_four_form.bank_passbook = this.$refs.bank_passbook.files[0];
      this.step_four_form.bank_passbook_url = URL.createObjectURL(
        this.step_four_form.bank_passbook
      );
    },
    pwdCertificateChanged() {
      this.step_four_form.document_pwd = this.$refs.document_pwd.files[0];
      this.step_four_form.document_pwd_url = URL.createObjectURL(
        this.step_four_form.document_pwd
      );
    },
    netSletFileChanged() {
      this.step_four_form.document_net_slet = this.$refs.document_net_slet.files[0];
      this.step_four_form.document_net_slet_url = URL.createObjectURL(
        this.step_four_form.document_net_slet
      );
    },
    nocFileChanged() {
      this.step_four_form.document_noc = this.$refs.document_noc.files[0];
      this.step_four_form.document_noc_url = URL.createObjectURL(
        this.step_four_form.document_noc
      );
    },
    exservicemanFileChanged() {
      this.step_four_form.document_ex_servicement = this.$refs.document_ex_servicement.files[0];
      this.step_four_form.document_ex_servicement_url = URL.createObjectURL(
        this.step_four_form.document_ex_servicement
      );
    },
    passportFileChanged() {
      this.step_four_form.document_passport = this.$refs.document_passport.files[0];
      this.step_four_form.document_passport_url = URL.createObjectURL(
        this.step_four_form.document_passport
      );
    },
    drivingLicenseFileChanged() {
      this.step_four_form.document_driving_license = this.$refs.document_driving_license.files[0];
      this.step_four_form.document_driving_license_url = URL.createObjectURL(
        this.step_four_form.document_driving_license
      );
    },
    ssnFileChanged() {
      this.step_four_form.document_ssn_equivalent = this.$refs.document_ssn_equivalent.files[0];
      this.step_four_form.document_ssn_equivalent_url = URL.createObjectURL(
        this.step_four_form.document_ssn_equivalent
      );
    },
    passingCertificateFileChanged() {
      this.step_four_form.document_passing_certificate = this.$refs.document_passing_certificate.files[0];
      this.step_four_form.document_passing_certificate_url = URL.createObjectURL(
        this.step_four_form.document_passing_certificate
      );
    },
    markSheetFileChanged() {
      this.step_four_form.document_marksheet = this.$refs.document_marksheet.files[0];
      this.step_four_form.document_marksheet_url = URL.createObjectURL(
        this.step_four_form.document_marksheet
      );
    },
    openImageInNewWindow(data) {
      //  console.log(data);
      window.open(data, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
    },

    goToStepZero() {
      // this.checkMBA();
      this.step = 0;
    },
    goToStepOne() {
      this.checkMBA();
      this.checkPHD();
      this.step = 1;
    },
    checkPHD() {
      if (this.initial_step.course) {
        if (this.initial_step.course.id && this.phd_course_ids.some(function (course_id) {
          return this.initial_step.course.id == course_id;
        }, this)) {
          this.is_phd = true;
          return;
        } else {
          this.is_phd = false;
          return;
        }
      } else if (this.old_app !== undefined && this.old_app.applied_courses) {
        this.is_phd = this.old_app.applied_courses.some(course => this.phd_course_ids.includes(parseInt(course.course_id)));
      }
    },
    checkMBA() {
      if (this.is_mba === false) {
        if (this.initial_step.course && this.initial_step.course.id && this.initial_step.course.id == 80) {
          this.is_mba = true;
          return;
        }
        if (this.old_app !== undefined && this.old_app.applied_courses) {
          if (this.old_app.applied_courses.filter(item => item.course_id == 80).length) {
            this.is_mba = true;
          }
        }
      }
    },
    goToStepTwo() {
      this.step = 2;
    },
    goToStepThree() {

      this.step = 3;
      // alert(this.step);
    },
    goToStepFour() {
      // alert(this.is_foreign);
      this.step = 4;
    },

    submitStepOne() {
      this.step_one_form.success_msg = "";
      this.step_one_form.errors = [];
      this.step_one_form.creating = true;
      var formdata = this.createStepOneFormData();
      // console.log(formdata);
      axios({
        method: "post",
        url: "student/step_one_form/submit",
        data: formdata
      })
        .then(response => {
          // console.log(response);
          this.step_one_form.success_msg =
            "Step one has been completed successfully !!";
          this.alertMessage("success", "Step one has been completed successfully !!");

          this.application_old = response.data.application;
          // console.log('this');
          // console.log(this.application_old);
          this.step_one_form.creating = false;
          this.step_one_form.submitted = true;
          this.app_programme = response.data.program_list;
          var old_programme = this.app_programme;
          if (old_programme.course_id == 37) {
            this.initial_step.is_mca = 1;
          }

          this.initial_step.is_btech = response.data.application.is_btech == 1 ? true : false;
          this.initial_step.is_cuet_ug = response.data.application.is_cuet_ug == 1 ? true : false;
          this.initial_step.is_cuet_pg = response.data.application.is_cuet_pg == 1 ? true : false;
          this.initial_step.is_mdes = response.data.application.is_mdes ==1 ? true: false;
          this.initial_step.is_phd = response.data.application.is_phd == 1 ? true : false;
          this.initial_step.is_mba = response.data.application.is_mba == 1 ? true : false;
          this.application_id = response.data.application.id;
          this.loadAreaOfResearch();
        })
        .catch(error => {
          if (error.response.status == 422) {
            this.step_one_form.errors = error.response.data.errors;
          }
          this.step_one_form.creating = false;
          if (error.response.status == 501) {
            this.alertMessage("danger", error.response.message);
          }else{
            this.alertMessage("danger", "Failed to create !! Please check if there any input left.");
          }      
          this.step_one_form.errors = error.response.data.errors;
        })
        .then(() => {
          this.step_one_form.creating = false;
        });
    },
    updateStepOne() {
      this.step_one_form.errors = "";
      this.step_one_form.success_msg = "";
      this.step_one_form.updating = true;
      var formdata = this.createStepOneFormData();
      formdata.append("application_id", this.application_old.id);
      axios({
        method: "post",
        url: "student/step_one_form/update",
        data: formdata
      })
        .then(response => {
          this.step_one_form.success_msg =
            "Step one has been updated successfully !!";
          this.alertMessage("success", "Updated step 1 !!");
          //this.application_old = response.data.application;
          this.step_one_form.updating = false;
        })
        .catch(error => {
          // console.log(error.response.data.message);
          if (error.response.status == 422) {
            this.step_one_form.errors = error.response.data.errors;
          }
          this.step_one_form.updating = false;
          this.step_one_form.errors = error.response.data.errors;
          this.alertMessage("danger", error.response.data.message);
        })
        .then(() => {
          this.step_one_form.updating = false;
        });
    },
    createStepOneFormData() {
      var formdata = new FormData();
      formdata.append("is_preference", this.is_preference);
      formdata.append("is_sub_preference", this.is_sub_preference);
      formdata.append("course_type", this.initial_step.course_type);
      formdata.append("course", this.initial_step.course.id);
      // console.log(this.initial_step.combined_courses);
      // if (this.is_preference == true || this.is_sub_preference) {
      formdata.append(
        "applied_courses",
        JSON.stringify(this.initial_step.combined_courses)
      );
      // }
      formdata.append("application_type", this.application_type);
      formdata.append("first_name", this.step_one_form.first_name);
      formdata.append("middle_name", this.step_one_form.middle_name);
      formdata.append("last_name", this.step_one_form.last_name);
      formdata.append("father_name", this.step_one_form.father_name);
      formdata.append(
        "father_occupation",
        this.step_one_form.father_occupation
      );
      formdata.append("father_income", this.step_one_form.father_income);
      formdata.append("father_mobile", this.step_one_form.father_mobile);
      formdata.append("father_email", this.step_one_form.father_email);
      formdata.append("mother_name", this.step_one_form.mother_name);
      formdata.append(
        "mother_occupation",
        this.step_one_form.mother_occupation
      );
      formdata.append("mother_income", this.step_one_form.mother_income);
      formdata.append("mother_mobile", this.step_one_form.mother_mobile);
      formdata.append("mother_email", this.step_one_form.mother_email);
      formdata.append("guardian_name", this.step_one_form.guardian_name);
      formdata.append(
        "guardian_occupation",
        this.step_one_form.guardian_occupation
      );
      formdata.append("guardian_mobile", this.step_one_form.guardian_mobile);
      formdata.append("guardian_email", this.step_one_form.guardian_email);
      formdata.append("family_income", this.step_one_form.family_income);
      formdata.append("father_qualification", this.step_one_form.father_qualification);
      formdata.append("mother_qualification", this.step_one_form.mother_qualification);
      
      formdata.append("account_holder_name", this.step_one_form.account_holder_name);
      formdata.append("bank_ac_no", this.step_one_form.bank_ac_no);
      formdata.append("bank_name", this.step_one_form.bank_name);
      formdata.append("branch_name", this.step_one_form.branch_name);
      formdata.append("ifsc_code", this.step_one_form.ifsc_code);
      formdata.append("bank_reg_mobile_no", this.step_one_form.bank_reg_mobile_no);


      if (this.showOtherInput == true) {
        formdata.append("religion", this.step_one_form.other);
      } else {
        formdata.append("religion", this.step_one_form.religion);
      }
      formdata.append("gender", this.step_one_form.gender);
      formdata.append("marital_status", this.step_one_form.marital_status);
      if (this.step_one_form.dob == "null") {
        formdata.append("dob", null);
      } else {
        formdata.append("dob", this.step_one_form.dob);
      }
      formdata.append("dob", this.step_one_form.dob);
      if (this.is_foreign == false) {
        formdata.append("caste", this.step_one_form.caste);
      }
      formdata.append("sub_caste", this.step_one_form.sub_caste);
      formdata.append("is_pwd", this.step_one_form.is_pwd);
      formdata.append("pwd_explain", this.step_one_form.pwd_exp);
      formdata.append("pwd_percentage", this.step_one_form.pwd_per);
      formdata.append("is_km", this.step_one_form.is_km);
      formdata.append("is_jk_student", this.step_one_form.is_jk_student);
      formdata.append("is_ex_serviceman", this.step_one_form.is_ex_serviceman);
      formdata.append(
        "is_aplly_defense_quota",
        this.step_one_form.is_aplly_defense_quota
      );
      formdata.append("is_bpl", this.step_one_form.is_bpl);
      formdata.append("is_minority", this.step_one_form.is_minority);
      formdata.append(
        "is_accomodation_need",
        this.step_one_form.is_accomodation_need
      );
      formdata.append("is_employed", this.step_one_form.is_employed);
      if (this.step_one_form.is_aplly_defense_quota == true) {
        formdata.append("priority", this.step_one_form.priority);
      }
      formdata.append("adhaar", this.step_one_form.adhaar);
      formdata.append("nad_id", this.step_one_form.nad_id);
      formdata.append("abc", this.step_one_form.abc);
      if (this.step_one_form.is_employed == true) {
        formdata.append(
          "employment_details",
          this.step_one_form.employment_details
        );
      }
      if (this.step_one_form.is_km == true) {
        formdata.append("km_details", this.step_one_form.km_details);
      }
      if (this.step_one_form.is_minority == true) {
        formdata.append(
          "minority_details",
          this.step_one_form.minority_details
        );
      }
      if (this.is_foreign == true) {
        formdata.append("passport_number", this.step_one_form.passport_number);
        formdata.append(
          "driving_license_equivalnet_no",
          this.step_one_form.driving_license_equivalnet_no
        );
      }
      formdata.append("is_btech", this.initial_step.is_btech);

      formdata.append("program_name", this.initial_step.program_name);
      formdata.append("is_mca", this.initial_step.is_mca);
      // new additions
      formdata.append("part_time_details", this.step_one_form.part_time_details);
      formdata.append("academic_experience", this.step_one_form.academic_experience);
      formdata.append("publication_details", this.step_one_form.publication_details);
      formdata.append("statement_of_purpose", this.step_one_form.statement_of_purpose);
      formdata.append("bank_acc", this.step_one_form.bank_acc);
      formdata.append("ifsc_no", this.step_one_form.ifsc_no);
      formdata.append("branch_name", this.step_one_form.branch_name);
      formdata.append("branch_code", this.step_one_form.branch_code);
      formdata.append("pan_no", this.step_one_form.pan_no);
      formdata.append("is_full_time", this.step_one_form.is_full_time);
      formdata.append("passed_or_appeared_qualified_exam", this.step_one_form.passed_or_appeared_qualified_exam);

      // if (this.initial_step.is_btech == true) {
      //   formdata.append(
      //     "b_tech_courses",
      //     JSON.stringify(this.initial_step.b_tech_courses)
      //   );
      // }
      return formdata;
    },

    loadDistricts(value) {
      axios({
        method: "post",
        url: "student/application/district/load_specific_district/" + value,
      }).then(response => {
        this.districts = response.data.districts;
      });
    },
    loadDistrictsPermanent(value) {
      axios({
        method: "post",
        url: "student/application/district/load_specific_district/" + value,
      }).then(response => {
        this.permanent_districts = response.data.districts;
      });
    },

    isPermanentChanged() {
      var corr = this.step_two_form.correspondence;
      var perm = this.step_two_form.permanent;
      if (this.step_two_form.is_permanent_same == true) {
        // new insertition
        this.permanent_districts = this.districts
        // end
        perm.co = corr.co;
        perm.house_no = corr.house_no;
        perm.vill_town = corr.vill_town;
        perm.po = corr.po;
        perm.district = corr.district;
        perm.street_name_locality = corr.street_name_locality;
        perm.pin_code = corr.pin_code;
        perm.state = corr.state;
      } else {
        // new insertition
        this.permanent_districts = [];
        // end
        perm.co = "";
        perm.house_no = "";
        perm.vill_town = "";
        perm.po = "";
        perm.district = "";
        perm.street_name_locality = "";
        perm.pin_code = "";
        perm.state = "";

      }
    },
    submitStepTwo() {
      
      this.step_two_form.errors = [];
      this.step_two_form.creating = true;
      var formdata = this.createStepTwoFormData();
      formdata.append("mode", "create");
      axios({
        method: "post",
        url: "student/step_two_form/submit",
        data: formdata
      })
        .then(response => {
          this.step_two_form.success_msg =
            "Step two has been completed successfully !!";
          this.alertMessage("success", "Step two has been completed successfully !!");
          this.step_two_form.creating = false;
          this.step_two_form.submitted = true;
          this.cuet_subject = response.data.cuet_subject;
        })
        .catch(error => {
          if (error.response.status == 422) {
            this.step_two_form.errors = error.response.data.errors;
          }
          this.step_two_form.creating = false;
          this.alertMessage("danger", "Failed to create !! Please check if there any input left.");
          this.step_two_form.errors = error.response.data.errors;
        })
        .then(() => {
          this.step_two_form.creating = false;
        });
    },
    updateStepTwo() {
      // alert(this.is_foreign);
      var formdata = this.createStepTwoFormData();
      this.step_two_form.errors = [];
      this.step_two_form.success_msg = "";
      formdata.append("mode", "edit");
      // console.log(formdata);
      axios({
        method: "post",
        url: "student/step_two_form/update",
        data: formdata
      })
        .then(response => {
          // alert(this.initial_step.is_mba_edit);
          if (this.initial_step.is_mba_edit == false) {
            this.step_two_form.success_msg =
              "Step two has been updated successfully !!";
            this.alertMessage("success", "Step two has been updated successfully !!");
            //this.application_old = response.data.application;
            this.step_two_form.updating = false;
            this.step_two_form.submitted = true;
          } else {
            this.alertMessage("danger", "Access Denied. Edit option available for only Academic Details & Document Uploads.");
            this.step_two_form.updating = false;
            this.step_two_form.submitted = true;
          }

        })
        .catch(error => {

          if (error.response.status == 422) {
            this.step_two_form.errors = error.response.data.errors;
          }
          this.step_two_form.updating = false;
          this.alertMessage("danger", error.response.data.message);
          this.step_two_form.errors = error.response.data.errors;
        })
        .then(() => {
          this.step_two_form.updating = false;
        });
    },
    createStepTwoFormData() {
      var formdata = new FormData();

      formdata.append("application_id", this.application_old.id);

      var corr = this.step_two_form.correspondence;
      formdata.append("correspondence_co", corr.co);
      formdata.append("correspondence_house_no", corr.house_no);
      formdata.append("correspondence_vill_town", corr.vill_town);
      formdata.append("correspondence_po", corr.po);
      formdata.append("correspondence_district", corr.district);
      formdata.append(
        "correspondence_street_name_locality",
        corr.street_name_locality
      );
      formdata.append("correspondence_pin_code", corr.pin_code);
      formdata.append("correspondence_state", corr.state);

      var perm = this.step_two_form.permanent;
      formdata.append("permanent_co", perm.co);
      formdata.append("permanent_house_no", perm.house_no);
      formdata.append("permanent_vill_town", perm.vill_town);
      formdata.append("permanent_po", perm.po);
      formdata.append("permanent_district", perm.district);
      formdata.append(
        "permanent_street_name_locality",
        perm.street_name_locality
      );
      formdata.append("permanent_pin_code", perm.pin_code);
      formdata.append("permanent_state", perm.state);

      var step_two = this.step_two_form;
      formdata.append("contact_no", step_two.contact_no);
      formdata.append("email", step_two.email);
      formdata.append("nationality", step_two.nationality);
      formdata.append("place_residence", step_two.place_residence);
      formdata.append("exam_center", step_two.center);
      formdata.append("exam_center1", step_two.center1);
      formdata.append("exam_center2", step_two.center2);

      return formdata;
    },
    submitStepThree() {
      this.step_three_form.errors = [];
      this.step_four_form.success_msg = "";
      var formdata = new FormData();

      formdata.append("application_id", this.application_old.id);
      if (this.step_three_form.other_qualifications.qualifications.length > 0) {
        formdata.append(
          "other_qualifications",
          JSON.stringify(
            this.step_three_form.other_qualifications.qualifications
          )
        );
      }

      if (this.step_three_form.cuet.subject_wise_dtl.length > 0) {
        formdata.append(
          "cuet_details",
          JSON.stringify(
            this.step_three_form.cuet.subject_wise_dtl
          )
        );
      }
      /* formdata.append(
        "mba_exams",
        JSON.stringify(
          this.step_three_form.mba_exam_data.details
        )
      ); */
      if (this.step_three_form.mba_exam_data !== undefined) {
        this.step_three_form.mba_exam_data.details.forEach(function (element, index) {
          formdata.append(`mba_exams[${index}][name_of_the_exam]`, element.name_of_the_exam);
          formdata.append(`mba_exams[${index}][registration_no]`, element.registration_no);
          formdata.append(`mba_exams[${index}][score_obtained]`, element.score_obtained);
          formdata.append(`mba_exams[${index}][date_of_exam]`, element.date_of_exam == null ? "" : element.date_of_exam);
        })
      }
      if (this.step_three_form.work_experience.length) {
        this.step_three_form.work_experience.forEach(function (item, index) {
          formdata.append(`work_experience[${index}][organization]`, item.organization);
          formdata.append(`work_experience[${index}][designation]`, item.designation);
          formdata.append(`work_experience[${index}][from]`, item.to == null ? "" : item.from);
          formdata.append(`work_experience[${index}][to]`, item.to == null ? "" : item.to);
          formdata.append(`work_experience[${index}][details]`, item.details);

        });
      }
      var aca10 = this.step_three_form.academic10;

      formdata.append("academic10_board_name", aca10.board_university_name);
      formdata.append("academic10_passing_year", aca10.passing_year);
      formdata.append("academic10_class_grade", aca10.class_grade);
      formdata.append("academic10_subjects_taken", aca10.subjects_taken);
      formdata.append("academic_10_exam_type", aca10.academic_10_exam_type);
      formdata.append("academic_10_total_mark", aca10.academic_10_total_mark);
      formdata.append("academic_10_mark_obtained", aca10.academic_10_mark_obtained);
      if (aca10.cgpa === null) {
        formdata.append("academic10_cgpa", 0);
      } else {
        formdata.append("academic10_cgpa", aca10.cgpa);
      }
      if (aca10.marks_percentage === null) {
        formdata.append("academic10_marks_percentage", 0);
      } else {
        formdata.append("academic10_marks_percentage", aca10.marks_percentage);
      }
      formdata.append("academic10_remarks", aca10.remarks);

      var aca12 = this.step_three_form.academic12;

      formdata.append("academic12_board_name", aca12.board_university_name);
      formdata.append("academic12_passing_year", aca12.passing_year);
      formdata.append("academic12_class_grade", aca12.class_grade);
      formdata.append("academic12_subjects_taken", aca12.subjects_taken);
      formdata.append("academic_12_exam_type", aca12.academic_12_exam_type);
      formdata.append("academic_12_total_mark", aca12.academic_12_total_mark);
      formdata.append("academic_12_mark_obtained", aca12.academic_12_mark_obtained);
      formdata.append("academic12_stream", aca12.stream);
      if (aca12.cgpa === null) {
        formdata.append("academic12_cgpa", 0);
      } else {
        formdata.append("academic12_cgpa", aca12.cgpa);
      }
      if (aca12.marks_percentage === null) {
        formdata.append("academic12_marks_percentage", 0);
      } else {
        formdata.append("academic12_marks_percentage", aca12.marks_percentage);
      }
      formdata.append("academic12_remarks", aca12.remarks);

      var aca_bach = this.step_three_form.academic_bachelor;

      formdata.append(
        "academic_bachelor_board_name",
        aca_bach.board_university_name
      );
      formdata.append("academic_bachelor_passing_year", aca_bach.passing_year);
      formdata.append("academic_bachelor_class_grade", aca_bach.class_grade);
      formdata.append(
        "academic_bachelor_subjects_taken",
        aca_bach.subjects_taken
      );
      if (aca_bach.cgpa === null) {
        formdata.append("academic_bachelor_cgpa", 0);
      } else {
        formdata.append("academic_bachelor_cgpa", aca_bach.cgpa);
      }
      if (aca_bach.marks_percentage === null) {
        formdata.append("academic_bachelor_marks_percentage", 0);
      } else {
        formdata.append(
          "academic_bachelor_marks_percentage",
          aca_bach.marks_percentage
        );
      }
      formdata.append("academic_bachelor_remarks", aca_bach.remarks);
      formdata.append("academic_graduation_exam_type", aca_bach.academic_graduation_exam_type);
      formdata.append("academic_graduation_total_mark", aca_bach.academic_graduation_total_mark);
      formdata.append("academic_graduation_mark_obtained", aca_bach.academic_graduation_mark_obtained);
      formdata.append("academic_bachelor_major", aca_bach.major);
      formdata.append("academic_bachelor_degree", aca_bach.degree);

      var academic_post_graduate = this.step_three_form.academic_post_graduate;
      formdata.append(
        "academic_post_graduate_board_name",
        academic_post_graduate.board_university_name
      );
      formdata.append(
        "academic_post_graduate_passing_year",
        academic_post_graduate.passing_year
      );
      formdata.append(
        "academic_post_graduate_class_grade",
        academic_post_graduate.class_grade
      );
      formdata.append("master_degree", academic_post_graduate.degree);
      formdata.append("academic_post_graduation_exam_type", academic_post_graduate.academic_post_graduation_exam_type);
      formdata.append("academic_post_graduation_total_mark", academic_post_graduate.academic_post_graduation_total_mark);
      formdata.append("academic_post_graduation_mark_obtained", academic_post_graduate.academic_post_graduation_mark_obtained);
      formdata.append(
        "academic_post_graduate_subjects_taken",
        academic_post_graduate.subjects_taken
      );
      if (academic_post_graduate.cgpa === null) {
        formdata.append("academic_post_graduate_cgpa", 0);
      } else {
        formdata.append(
          "academic_post_graduate_cgpa",
          academic_post_graduate.cgpa
        );
      }
      if (academic_post_graduate.marks_percentage === null) {
        formdata.append("academic_post_graduate_marks_percentage", 0);
      } else {
        formdata.append(
          "academic_post_graduate_marks_percentage",
          academic_post_graduate.marks_percentage
        );
      }

      var aca_diploma = this.step_three_form.academic_diploma;
      formdata.append(
        "academic_diploma_board_name",
        aca_diploma.board_university_name
      );
      formdata.append(
        "academic_diploma_passing_year",
        aca_diploma.passing_year
      );
      formdata.append("academic_diploma_class_grade", aca_diploma.class_grade);
      formdata.append("academic_diploma_exam_type", aca_diploma.academic_diploma_exam_type);
      formdata.append("academic_diploma_total_mark", aca_diploma.academic_diploma_total_mark);
      formdata.append("academic_diploma_mark_obtained", aca_diploma.academic_diploma_mark_obtained);
      formdata.append(
        "academic_diploma_subjects_taken",
        aca_diploma.subjects_taken
      );
      if (aca_diploma.cgpa === null) {
        formdata.append("academic_diploma_cgpa", 0);
      } else {
        formdata.append("academic_diploma_cgpa", aca_diploma.cgpa);
      }
      if (aca_diploma.marks_percentage === null) {
        formdata.append("academic_diploma_marks_percentage", 0);
      } else {
        formdata.append(
          "academic_diploma_marks_percentage",
          aca_diploma.marks_percentage
        );
      }
      formdata.append("academic_diploma_remarks", aca_diploma.remarks);

      var jee = this.step_three_form.jee;
      var step_three = this.step_three_form;
      var qualifing_marks = this.step_three_form.qualifing;
      var cuet = this.step_three_form.cuet;
      var gate = this.step_three_form.gate;
      // console.log(cuet);
      formdata.append("jee_roll_no", jee.roll_no);
      formdata.append("jee_form_no", jee.form_no);
      formdata.append("jee_year", jee.year);

      formdata.append("cuet_roll_no", cuet.cuet_roll_no);
      formdata.append("cuet_form_no", cuet.cuet_form_no);
      formdata.append("cuet_year", cuet.cuet_year);
      formdata.append("cuet_rank", cuet.cuet_rank);
      formdata.append("cuet_score", cuet.cuet_score);
      formdata.append("cuet_qualified", this.step_three_form.is_cuet_qualified);

      formdata.append("gate_roll_no", gate.gate_roll_no);
      formdata.append("gate_form_no", gate.gate_form_no);
      formdata.append("gate_year", gate.gate_year);
      formdata.append("gate_rank", gate.gate_rank);
      formdata.append("gate_score", gate.gate_score);
      formdata.append("gate_qualified", this.step_three_form.is_gate_qualified);

      formdata.append("physics_mark", qualifing_marks.physics_mark);
      formdata.append("physics_total_mark", qualifing_marks.physics_total_mark);
      formdata.append("physics_grade", qualifing_marks.physics_grade);

      formdata.append("chemistry_total_mark", qualifing_marks.chemistry_total_mark);
      formdata.append("chemistry_grade", qualifing_marks.chemistry_grade);
      formdata.append("chemistry_mark", qualifing_marks.chemistry_mark);

      formdata.append("mathematics_total_mark", qualifing_marks.mathematics_total_mark);
      formdata.append("mathematics_grade", qualifing_marks.mathematics_grade);
      formdata.append("mathematics_mark", qualifing_marks.mathematics_mark);

      formdata.append("english_total_mark", qualifing_marks.english_total_mark);
      formdata.append("english_grade", qualifing_marks.english_grade);
      formdata.append("english_mark", qualifing_marks.english_mark);

      formdata.append("english_mark_10_total_mark", qualifing_marks.english_mark_10_total_mark);
      formdata.append("english_mark_10_grade", qualifing_marks.english_mark_10_grade);
      formdata.append("english_mark_10", qualifing_marks.english_mark_10);

      formdata.append("biology_total_mark", qualifing_marks.biology_total_mark);
      formdata.append("biology_grade", qualifing_marks.biology_grade);
      formdata.append("biology_mark", qualifing_marks.biology_mark);

      formdata.append("statistics_total_mark", qualifing_marks.statistics_total_mark);
      formdata.append("statistics_grade", qualifing_marks.statistics_grade);
      formdata.append("statistics_mark", qualifing_marks.statistics_mark);

      formdata.append("is_sport_represented", step_three.is_sport_represented);
      formdata.append("mca_mathematics_mark", step_three.academic12.mca_mathematics_mark);

      formdata.append(
        "is_prizes_distinction",
        step_three.is_prizes_distinction
      );
      formdata.append("other_information", step_three.other_information);
      formdata.append("is_debarred", step_three.is_debarred);
      formdata.append("is_punished", step_three.is_punished);
      formdata.append("furnish_details", step_three.furnish_details);

      if (this.mode == "create") {
        formdata.append("program_type", this.programType);
      } else if (this.mode == "edit") {
        formdata.append("program_type", this.program_type);
      }
      formdata.append("course_type", this.initial_step.course_type.code);

      if (step_three.is_sport_represented == true) {
        formdata.append("sport_played", step_three.sport_played);
        formdata.append("medel_type", step_three.medel_type);
      }
      formdata.append(
        "academic10_is_appeared",
        step_three.academic10.is_passed_appearing
      );
      formdata.append(
        "academic12_is_appeared",
        step_three.academic12.is_passed_appearing
      );
      formdata.append(
        "academic_bachelor_is_appeared",
        step_three.academic_bachelor.is_passed_appearing
      );
      formdata.append(
        "academic_post_graduate_is_appeared",
        step_three.academic_post_graduate.is_passed_appearing
      );
      formdata.append(
        "academic_diploma_is_appeared",
        step_three.academic_diploma.is_passed_appearing
      );
      formdata.append(
        "qualified_national_level_test",
        step_three.qualified_national_level_test
      );
      formdata.append(
        "qualified_national_level_test_mark",
        step_three.qualified_national_level_test_mark
      );
      formdata.append(
        "gat_b_score",
        step_three.gat_b_score
      );
      if (step_three.proposed_area_of_research.length) {
        step_three.proposed_area_of_research.forEach(function (item, index) {
          formdata.append(`proposed_area_of_research[${index}]`, item.data);
        });
      }
      if (this.is_master_in_design) {
        formdata.append(
          "ceed_score",
          step_three.ceed_score
        );
      }

      if (this.is_bachelor_in_design) {
        formdata.append(
          "ceed_score",
          step_three.ceed_score
        );
      }

      axios({
        method: "post",
        url: "student/step_three_form/submit",
        data: formdata
      })
        .then(response => {
          // console.log(response);
          if (this.initial_step.is_mba_edit == false) {
            this.alertMessage("success", "Step three has been completed successfully !!");
            this.step_three_form.success_msg =
              "Step three has been completed successfully !!";
            this.step_three_form.creating = false;
            this.step_three_form.submitted = true;
          } else {
            this.alertMessage("success", "Only Bachelor's Degree field and CAT/MAT/ATMA/XAT Details can be edit in this step..");
            this.step_three_form.success_msg =
              "Only Bachelor's Degree field and CAT/MAT/ATMA/XAT Details can be edit in this step..";
            this.step_three_form.creating = false;
          }
        })
        .catch(error => {
          this.alertMessage("danger", "Failed !! Please check if there any input is left.");
          this.step_three_form.creating = false;
          this.step_three_form.errors = error.response.data.errors;
        })
        .then(() => {
          this.step_three_form.creating = false;
        });
    },
    // submitStepFour() {
    //   alert("test");
    // },

    submitStepFour() {
      var fields = [
        {
          name: "caste_certificate",
          // is_required: this.step_one_form.caste == "1" ? false : true
          is_required: false
        },
        {
          name: "document_bpl",
          // is_required: this.step_one_form.is_bpl == true ? true : false
          is_required: false
        },
        {
          name: "obc_ncl",
          // is_required: this.is_ncl == true ? true : false
          is_required: false
        },
        {
          name: "document_sport_representation",
          // is_required: this.step_three_form.is_sport_represented == true ? true : false
          is_required: false
        },
        {
          name: "document_admit_jee",
          // is_required: this.initial_step.is_btech == true ? true : false
          is_required: false
        },
        {
          name: "document_pwd",
          // is_required: this.step_one_form.is_pwd == true ? true : false
          is_required: false
        },
        {
          name: "document_noc",
          // is_required: this.step_one_form.is_employed == true ? true : false
          is_required: false
        },
        {
          name: "document_ex_servicement",
          // is_required: this.step_one_form.is_ex_serviceman == true ? true : false
          is_required: false
        },
        {
          name: "document_passport",
          // is_required: this.is_foreign == true ? true : false
          is_required: false
        },
        {
          name: "document_passing_certificate",
          // is_required: this.is_foreign == true ? true : false
          is_required: false
        },
        {
          name: "document_marksheet",
          // is_required: this.is_foreign == true ? true : false
          is_required: false
        },
        {
          name: "document_passport_photo",
          // is_required: true 
          is_required: false
        },
        {
          name: "document_signature",
          // is_required: true 
          is_required: false
        },
        {
          name: "document_english_proficiency_certificate",
          // is_required: this.is_foreign == true ? true : false
          is_required: false
        },
        {
          name: "portfolio_file",
          // is_required: this.is_master_in_design == true ? true : false
          is_required: false
        },
        {
          name: "ceed_score_card",
          // is_required: this.is_master_in_design == true ? true : false
          is_required: false
        },
        {
          name: "undertaking",
          // is_required: this.step_one_form.caste == "1" ? false : true
          is_required: false
        },
      ];
      if (this.mode == "create") {
        var is_valid = this.validateFiles(fields);
        if (is_valid == false) {
          this.alertMessage("danger", "There are some errors . Please check");
          return;
        }
      }
      this.step_four_form.uploading = true;
      this.step_four_form.success_msg = "";
      this.step_four_form.errors = [];
      this.progress_style.width = "0%";
      var stepf = this.step_four_form;
      stepf.is_progress_error = false;
      var formdata = new FormData();

      if (stepf.document_additional) {
        formdata.append("additional_document", stepf.document_additional);
      }
      if (stepf.document_english_proficiency_certificate) {
        formdata.append(
          "document_english_proficiency_certificate",
          stepf.document_english_proficiency_certificate
        );
      }
      if (stepf.document_passport_photo) {
        formdata.append("passport_photo", stepf.document_passport_photo);
      }
      if (stepf.document_signature) {
        formdata.append("signature", stepf.document_signature);
      }
      if (stepf.document_prc) {
        formdata.append("prc", stepf.document_prc);
      }
      if (stepf.caste_certificate) {
        formdata.append("caste_certificate", stepf.caste_certificate);
      }
      if (stepf.document_bpl) {
        formdata.append("bpl_card", stepf.document_bpl);
      }
      if (stepf.obc_ncl) {
        formdata.append("obc_ncl", stepf.obc_ncl);
      }
      if (stepf.document_sport_representation) {
        formdata.append(
          "sport_certificate",
          stepf.document_sport_representation
        );
      }
      if (stepf.document_ews) {
        formdata.append("ews_certificate", stepf.document_ews);
      }
      if (stepf.document_admit_jee) {
        formdata.append("jee_admit_card", stepf.document_admit_jee);
      }
      if (stepf.document_pwd) {
        formdata.append("pwd_certificate", stepf.document_pwd);
      }
      if (stepf.document_net_slet) {
        formdata.append("net_slet_certificate", stepf.document_net_slet);
      }
      if (stepf.document_noc) {
        formdata.append("noc_certificate", stepf.document_noc);
      }
      if (stepf.document_ex_servicement) {
        formdata.append(
          "ex_serviceman_certificate",
          stepf.document_ex_servicement
        );
      }
      if (stepf.document_gate_score_card) {
        formdata.append("gate_score_card", stepf.document_gate_score_card);
      }

      if (stepf.document_passport) {
        formdata.append("document_passport", stepf.document_passport);
      }
      if (stepf.document_driving_license) {
        formdata.append("document_driving_license", stepf.document_driving_license);
      }
      if (stepf.document_ssn_equivalent) {
        formdata.append("document_ssn_equivalent", stepf.document_ssn_equivalent);
      }
      if (stepf.document_passing_certificate) {
        formdata.append(
          "document_passing_certificate",
          stepf.document_passing_certificate
        );
      }
      if (stepf.document_marksheet) {
        formdata.append("document_marksheet", stepf.document_marksheet);
      }
      if (stepf.gate_b_score_card) {
        formdata.append("gate_b_score_card", stepf.gate_b_score_card);
      }
      if (stepf.ceed_score_card) {
        formdata.append("ceed_score_card", stepf.ceed_score_card);
      }
      if (stepf.portfolio_file) {
        formdata.append("portfolio", stepf.portfolio_file);
      }
      if (stepf.undertaking) {
        formdata.append("undertaking", stepf.undertaking);
      }
      if (stepf.undertaking_pass_appear) {
        formdata.append("undertaking_pass_appear", stepf.undertaking_pass_appear);
      }
      if (stepf.cuet_admit_card) {
        formdata.append("cuet_admit_card", stepf.cuet_admit_card);
      }
      if (stepf.bank_passbook) {
        formdata.append("bank_passbook", stepf.bank_passbook);
      }




      if (stepf.cuet_score_card) {
        formdata.append("cuet_score_card", stepf.cuet_score_card);
      }

      if (stepf.class_x_documents) {
        formdata.append("class_x_documents", stepf.class_x_documents);
      }

      if (stepf.class_XII_documents) {
        formdata.append("class_XII_documents", stepf.class_XII_documents);
      }

      if (stepf.graduation_documents) {
        formdata.append("graduation_documents", stepf.graduation_documents);
      }

      if (stepf.graduation_documents) {
        formdata.append("post_graduation_documents", stepf.post_graduation_documents);
      }





      if (stepf.class_x_grade_conversion) {
        formdata.append("class_x_grade_conversion", stepf.class_x_grade_conversion);
      }

      if (stepf.class_XII_grade_conversion) {
        formdata.append("class_XII_grade_conversion", stepf.class_XII_grade_conversion);
      }


      if (stepf.misc_documents_name) {
        for (let i = 0; i < stepf.misc_documents_name.length; i++) {
          formdata.append(`misc_documents_name[${i}]`, stepf.misc_documents_name[i]);
        }
      }
      if (stepf.misc_documents) {
        for (let i = 0; i < stepf.misc_documents.length; i++) {
          formdata.append(`misc_documents[${i}]`, stepf.misc_documents[i]);
        }
      }



      if (stepf.graduation_grade_conversion) {
        formdata.append("graduation_grade_conversion", stepf.graduation_grade_conversion);
      }

      if (stepf.graduation_grade_conversion) {
        formdata.append("post_graduation_grade_conversion", stepf.post_graduation_grade_conversion);
      }


      this.mba_exams.forEach(function (name, index) {
        var name = name.toLowerCase() + '_score_card';
        if (stepf[name] !== undefined) {
          formdata.append(name, stepf[name]);
        }
      });
      formdata.append("accept", 1);
      formdata.append("application_id", this.application_old.id);
      axios({
        method: "post",
        url: "student/step_four_form/submit",
        data: formdata,
        onUploadProgress: function (progressEvent) {
          this.progress_style.width =
            parseInt(
              Math.round((progressEvent.loaded / progressEvent.total) * 100)
            ) + "%";
        }.bind(this)
      })
        .then(response => {
          // console.log(response);
          this.step_four_form.success_msg =
            "Final step has been completed successfully !!";
          this.step_four_form.uploading = false;
          this.step_four_form.submitted = true;
          this.step_four_form.is_progress_error = false;
          this.alertMessage(
            "success",
            "You have successfully completed your online application form!!"
          );
          // window.location.href = this.base_url + "/student/application";
          window.location.href = this.base_url + "/student/dashboard";
        })
        .catch(error => {
          this.step_four_form.is_progress_error = true;
          this.alertMessage("danger", "Failed!! please check if there any file left.");
          this.step_four_form.uploading = false;
          if (error.response.status == 422) {
            this.step_four_form.errors = error.response.data.errors;
          }
        })
        .then(() => {
          this.step_four_form.uploading = false;
        });
    },
    checkCourse(course) {
      if (course.is_checked == false) {
        var count = 0;
      } else {
        course.is_checked = false;
        this.checkremovedthis();
        // initial_step.selection_count == true && 
        // initial_step.preference_count == true && 
        // isAllowNext == true && 
        // this.initial_step.program_selection_step == true  
        // console.log(this.initial_step.selection_count);
        // console.log(this.initial_step.preference_count);
        // console.log(this.isAllowNext);
        // console.log(this.initial_step.program_selection_step);



      }
    },

    checkremovedthis() {
      var courses = this.initial_step.combined_courses;
      var selected_length = 0;
      var maximm = 0;
      for (var i = 0; i < courses.length; i++) {
        var cour = courses[i];
        // console.log(cour.preference);
        if (cour.is_checked == true) {
          selected_length++;

          if (maximm < cour.preference) {
            maximm = cour.preference;
          }
        }

      }
      if (selected_length == maximm) {
        this.initial_step.program_selection_step = true;
        if (maximm == 0) {
          this.initial_step.program_selection_step = false;
        }
      }
    },

    checkCourseNew(course) {
      if (course.is_checked == false) {
        var count = 0;
        this.initial_step.combined_courses.forEach((value, index) => {
          if (value.is_checked == true) {
            count++;
          }
        });
        var limit =this.initial_step.pref_limit;
        this.initial_step.course_preference_limit = 2;
        console.log(this.initial_step.program_name_load);
        if(this.initial_step.program_name_load == "BTECH"){
          limit=7;
          this.initial_step.course_preference_limit = 7;
        }
        if(this.initial_step.is_btech){
          limit=7;
          this.initial_step.course_preference_limit = 7;
        }
        
        if (count < limit) {
          this.initial_step.is_pref_limit_exceeded = false;
          course.is_checked = true;
          this.initial_step.preference_count = true;
          this.initial_step.selection_count = true;
          count++;
          if (count >= limit) {
            this.initial_step.is_pref_limit_exceeded = true;
          }
          this.initial_step.program_selection_step = true;

          //2023
          this.is_sub_preference = true;
          if(this.application_type == "TUEE"){
            this.initial_step.course_group_23.push(course.group);
          }else{
            this.initial_step.course_group_23.push(course.cuet_group);
          }
          //console.log(this.initial_step.course_group_23);
          // End
        } else {
          this.initial_step.is_pref_limit_exceeded = true;
        }

      } else {
        course.is_checked = false;
        this.initial_step.is_pref_limit_exceeded = false;
        this.checkremovedthis();
        if(this.application_type == "TUEE"){
          this.initial_step.course_group_23.pop(course.group);
        }else{
          this.initial_step.course_group_23.pop(course.cuet_group);
        }
      }
    },
    courseChanged() {
      // console.log(this.initial_step.combined_courses);
      var step0 = this.initial_step;
      var comb_id = step0.course.combination_id;

      // step0.combined_courses.forEach((course, index) => {
      //   course.preference = 0;
      // });
      step0.is_pref_limit_exceeded = false;
      // if (this.is_preference == true && this.is_sub_preference == false) {
      //   step0.combined_courses = step0.combined_courses_main.filter(course => {
      //     return course.combination_id == comb_id;
      //   });

      //   step0.combined_courses.forEach((course, index) => {
      //     course.preference = 0;
      //     course.is_checked = false;
      //   });
      //   var first_course = step0.combined_courses.find(
      //     course => course.id == step0.course.id
      //   );
      //   first_course.preference = 1;
      //   first_course.is_checked = true;
      // } else if (this.is_sub_preference == true) {
      //   var sub_comb_id = step0.course.sub_combination_id;
      //   step0.combined_courses = step0.sub_combined_courses.filter(course => {
      //     return course.sub_combination_id == sub_comb_id;
      //   });

      //   step0.combined_courses.forEach((course, index) => {
      //     course.preference = 0;
      //   });
      //   var first_course = step0.combined_courses.find(
      //     course => course.id == step0.course.id
      //   );
      //   first_course.preference = 0;
      // }
      // step0.course_alert = "";
      // if (
      //   step0.course.sub_combination_id > 0 &&
      //   step0.course.sub_preference == 0
      // ) {
      //   var optional_course = step0.courses.find(
      //     course =>
      //       course.sub_combination_id == step0.course.sub_combination_id &&
      //       course.sub_preference == 1
      //   );
      //   step0.course_alert =
      //     'You can also choose <span class="text-info text-bold ">' +
      //     optional_course.name +
      //     "</span> from the drop down";
      // } else if (
      //   step0.course.sub_combination_id > 0 &&
      //   step0.course.sub_preference == 1
      // ) {
      //   var optional_courses = step0.courses.filter(
      //     course =>
      //       course.sub_combination_id == step0.course.sub_combination_id &&
      //       course.sub_preference == 0
      //   );
      //   var courses = [];
      //   optional_courses.forEach((value, index) => {
      //     courses.push(value);
      //   });
      //   step0.course_alert =
      //     'You can also choose <span class="text-info text-bold ">' +
      //     courses[0].name +
      //     '</span> Or <span class="text-info text-bold ">' +
      //     courses[1].name +
      //     "</span> from the drop down";
      // }
    },
    preferenceChanged(event, course_id) {
      this.initial_step.program_selection_step = true;
      // console.log(this.initial_step.combined_courses);
      this.initial_step.preference_error = "";
      this.initial_step.ready = true;
      var pref_value = event.target.value;
      var courses = this.initial_step.combined_courses;
      // alert(this.initial_step.maximm);
      // alert(course_id);
      var selected_length = 0;
      for (var i = 0; i < courses.length; i++) {
        var cour = courses[i];
        if (cour.is_checked == true) {
          selected_length++;
        }
      }
      // console.log(selected_length);
      // console.log(pref_value);
      // console.log(this.initial_step.maximm);

      if (/* pref_value!=this.initial_step.maximm || */ pref_value > selected_length) {
        this.initial_step.preference_error =
          // "Preference selection should be in a sequence fdgdfgdf";
          this.alertMessage(
            "danger",
            "Preference selection should be in a sequence !!"
          );

        this.initial_step.program_selection_step = false;
      } else {
        this.initial_step.maximm++;
        for (var i = 0; i < courses.length; i++) {
          var course = courses[i];
          if (course.id == course_id) {
            //  alert(course_id);
            // this.initial_step.maximm--;
            continue;
          }
          // console.log(course);
          if (course.preference != 0) {
            if (course.preference == pref_value && course.is_checked == true) {
              this.initial_step.preference_error =
                "You can not select same preference for multiple courses";
              this.alertMessage(
                "danger",
                "You can't select same preference for multiple courses!!"
              );
              this.initial_step.ready = false;
              this.initial_step.program_selection_step = false;
              break;
            }
          } else {
            this.initial_step.preference_count = true;
            this.initial_step.selection_count = true;
          }
        }
      }
      // console.log(this.initial_step.program_selection_step);
      // if (this.initial_step.preference_error == "") {
      var cour = courses.find(course => course.id == course_id);
      cour.preference = pref_value;
      // }
    },

    subjectChanged(subject_name, index) {
      var subjects = this.step_three_form.cuet.subject_wise_dtl;
      var flag = 0;
      for (var i = 0; i < subjects.length; i++) {
        var subject = subjects[i];
        if (subject.subjects == subject_name) {
          flag++;
        }
        if (flag > 1) {
          alert("You have already selected this subject, please select another.");
          this.removeCuetDtl('cuet', index);
          break;
        }
      }

    },
    courseTypeChanged() {

      if (this.initial_step.course_type.type == "PHD") {
        this.is_phd = true;
      }
      this.initial_step.course = "";
      console.log(this.initial_step.course_type.type);
      this.initial_step.is_pref_limit_exceeded = false;
      this.initial_step.preference_error = "";
      this.initial_step.ready = true;
      this.initial_step.courses = this.initial_step.course_type.courses;
      this.initial_step.is_btech =
        this.initial_step.program_name == "BTECH" ? true : false;
      // this.initial_step.is_cuet_ug = this.initial_step.program_name == "UG" ? true : false;
      // this.initial_step.is_cuet_pg = this.initial_step.program_name == "PG" ? true : false;
      this.initial_step.program_name = this.initial_step.course_type.type;

      this.initial_step.sub_combined_courses = this.initial_step.course_type.courses.filter(
        course =>
          course.sub_preference == true || course.sub_combination_id != null
      );
      this.initial_step.combined_courses_main = this.initial_step.course_type.courses.filter(
        course => course.preference == true
      );
      this.initial_step.combined_courses = this.initial_step.course_type.courses/* .filter(
        course => course.preference == true
      ) */;
      var combined_courses = this.initial_step.combined_courses;
      var sub_combined_courses = this.initial_step.sub_combined_courses;
      var combined_courses_main = this.initial_step.combined_courses_main;
      this.initial_step.combined_courses = combined_courses.map(course => {
        const course_obj = {};
        course_obj.id = course.id;
        course_obj.name = course.name;
        course_obj.code = course.code;
        course_obj.eligibility = course.eligibility;
        course_obj.preference = 0;
        course_obj.combination_id = course.combination_id;
        course_obj.sub_combination_id = course.sub_combination_id;
        course_obj.sub_preference = course.sub_preference;
        course_obj.is_checked = false;
        course_obj.group = course.group;
        course_obj.cuet_group = course.cuet_group;
        return course_obj;
      });
      this.initial_step.sub_combined_courses = sub_combined_courses.map(
        course => {
          const course_obj = {};
          course_obj.id = course.id;
          course_obj.name = course.name;
          course_obj.code = course.code;
          course_obj.eligibility = course.eligibility;
          course_obj.preference = 0;
          course_obj.combination_id = course.combination_id;
          course_obj.sub_combination_id = course.sub_combination_id;
          course_obj.sub_preference = course.sub_preference;
          course_obj.is_checked = false;
          return course_obj;
        }
      );
      this.initial_step.combined_courses_main = combined_courses_main.map(
        course => {
          const course_obj = {};
          course_obj.id = course.id;
          course_obj.name = course.name;
          course_obj.code = course.code;
          course_obj.eligibility = course.eligibility;
          course_obj.preference = 0;
          course_obj.combination_id = course.combination_id;
          course_obj.sub_combination_id = course.sub_combination_id;
          course_obj.sub_preference = course.sub_preference;
          course_obj.is_checked = false;
          return course_obj;
        }
      );
      // var first_course = this.initial_step.combined_courses.find(
      //   course => course.id == this.initial_step.course.id
      // );
      // first_course.preference = 1;

      this.initial_step.is_show_eligibility = true;
    },
    fillMBAExams(application_data) {
      // mba_exam_input_type: [
      //   "enter percentile", "enter composit score", "enter composit score", "enter percentile", "enter percentile", "enter percentile"
      // ],
      this.step_three_form.mba_exam_data.details = this.mba_exams.map((courseName) => {
        var object;
        if (application_data !== undefined && application_data.length) {
          application_data.forEach(function (item, index) {
            if (item.name_of_the_exam == courseName) {
              object = {
                name_of_the_exam: courseName,
                registration_no: item.registration_no,
                date_of_exam: item.date_of_exam,
                score_obtained: item.score_obtained,
              }
              return;
            }
          });
        }
        if (object !== undefined) {
          return object;
        }
        object = {
          name_of_the_exam: courseName,
          registration_no: "NA",
          date_of_exam: null,
          score_obtained: 0.00,
        }
        return object;
      })
    },
    loadCourses() {
      // alert(this.application_type);
      axios({ method: "get", url: "student/application/loadstep1data/"+ this.application_type }).then(
        response => {
          // "ok");
          // console.log("loadstep1data:");
          // console.log(response);
          if (response.data.is_foreign == 1) {
            this.is_foreign = true;
            // alert(this.is_foreign);
          } else if(response.data.is_foreign == 0) {
            this.is_foreign = false;
          }

          // alert(response.data.user.program_name);

          if(response.data.user.program_name=="PHDPROF"){
            this.is_phd_prof = true;
            this.employments_phd_prof = response.data.employements;
            this.step_one_form.is_employed = true;
          }


          
          // alert(this.is_foreign);
          this.initial_step.program_name_load = response.data.program_name;
          this.initial_step.via_exam_mdes = response.data.via_exam;
          this.user = response.data.user;
          this.departments = response.data.departments;
          this.initial_step.course_types = response.data.course_types;
          this.castes = response.data.castes;
          this.sports = response.data.sports;
          //this.cuet_subject = response.data.cuet_subject;
          this.loadCuetSubjects();
          if (this.count == 0) {
            this.step_one_form.first_name = this.user.first_name;
            this.step_one_form.middle_name =
              this.user.middle_name == null ? "" : this.user.middle_name;
            this.step_one_form.last_name =
              this.user.last_name == null ? "" : this.user.last_name;
          } else {
            // console.log("here");
            this.step_one_form.first_name = this.application_old.first_name ?? this.user.first_name;
            this.step_one_form.middle_name =
              this.application_old.middle_name == null
                ? this.user.middle_name
                : this.application_old.middle_name;
            this.step_one_form.last_name =
              this.application_old.last_name == null
                ? this.user.last_name
                : this.application_old.last_name;
          }
          this.step_two_form.contact_no = response.data.user.mobile_no;
          this.step_two_form.email = response.data.user.email;
          this.income_ranges = response.data.income_ranges;
          this.parents_qualification = response.data.parents_qualification;
          this.step_one_form.priorities = response.data.priorities;
          this.centers = response.data.centers;
          this.indian_states = response.data.indian_states;
          // console.log(this.indian_states);
          this.applications_count = response.data.count;
        }
      );
    },
    validateFiles(fields) {
      var step = this.step_four_form;
      step.is_valid = true;
      fields.forEach((value, index) => {
        var name = value.name;
        if (value.is_required == true) {
          if (step[name] == "") {
            step.errors2[name] = name + " " + " is required";
            step.is_valid = false;
          } else {
            step.errors2[name] = "";
          }
        }
      });

      return step.is_valid;
    },
    alertMessage(mode, msg) {
      $.notify(
        {
          message: msg
        },
        {
          type: mode
        }
      );
    },
    addMorePurposeofResearch() {
      // alert("addMorePurposeofResearch");
      if(this.step_three_form.proposed_area_of_research.length < this.area_of_researchs_length){
          this.step_three_form.proposed_area_of_research.push({
            data: ""
          })
      }else{
        alert("No more options available");
      }
      
    },

    addMoreExperience(){
      // alert("ok");
      this.experience_count = this.experience_count+1;
      // alert(this.experience_count);
      // console.log(this.step_three_form.work_experience);       
      this.step_three_form.work_experience.push({
        key: (this.experience_count + 1) + 'workexp',
        organization: "",
        designation: "",
        from: "",
        to: "",
        details: "",
      });
    },

    removeWorkExperience(key){
        const index = this.step_three_form.work_experience.findIndex(exp => exp.key === key);
        if (index !== -1) {
          this.step_three_form.work_experience.splice(index, 1);
        } else {
          alert("Work experience not found");
        }
    },

    removePurposeofResearch(index) {
      if (this.step_three_form.proposed_area_of_research.length === 1) {
        alert("at-least one proposed area of research required.");
        return false;
      }
      // alert("removePurposeofResearch");
      this.step_three_form.proposed_area_of_research.splice(index, 1);
      // delete this.step_three_form.proposed_area_of_research[index];
    },

    openPreviewInNewWindow() {
      this.step_three_form.is_preview_click = true;
      var id = this.application_old.id;
      window.open(
        this.base_url + '/student/applicationii/' + id, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
    },

    openPreviewInNewWindowII(url) {
      this.step_three_form.is_preview_click = true;
      var id = this.application_old.id;
      window.open(
        url);
    },

    openProspectusInNewWindow() {
      // alert("okk");
      if(this.user.program_name == 'MBA'){
        window.open(
        this.base_url + '/notifications/2024/Prospectus_2023_mba.pdf', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
      }else{
        window.open(
        this.base_url + '/notifications/2023/Prospectus_2023.pdf', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
      }
     
    },


    isThisCourseApplied(course_name) {
      // console.log(course_name);
      var all_applied_courses = this.application_old.applied_courses;
      var flag = false;
      all_applied_courses.forEach((value, index) => {
        // console.log(value.course_type);
        if (value.course_type == course_name) {
          flag = true;
          return flag;
        }
      });
      return flag;
    },

    isThisUploaded(doc_name) {
      var uploaded_docs = this.step_four_form.uploaded_documents;
      var flag = false;
      uploaded_docs.forEach((value, index) => {
        // console.log(value.doc_name);
        if (value.doc_name == doc_name) {
          flag = true;
          return flag;
        }
      });
      return flag;
    },

    isThisSubCourseApplied(course_id) {
      var all_applied_courses = this.application_old.applied_courses;
      var flag = false;
      all_applied_courses.forEach((value, index) => {
        // console.log(value.course_id);
        if (value.course_type == course_id) {
          flag = true;
          return flag;
        }
      });
      return flag;
    },




    uploadSingleFile(file_ref, file_name) {
      // all file ref are from step 4 so no need to mention file param
      // console.log(this.step_four_form[file_ref]);
      if (!this.step_four_form[file_ref] || this.step_four_form[file_ref] == undefined) {
        this.alertMessage("danger", "Please select a file to upload.");
        return;
      }

      // url student/file-upload/app_id
      var formdata = new FormData();
      formdata.append(file_name, this.step_four_form[file_ref]);
      this.singleFileUploadingProgress = true;
      axios({
        method: "post",
        url: "student/application/file-upload/" + this.application_old.id,
        data: formdata,
        onUploadProgress: function (progressEvent) {
          this.progress_style.width =
            parseInt(
              Math.round((progressEvent.loaded / progressEvent.total) * 100)
            ) + "%";
        }.bind(this)
      })
        .then(response => {
          // console.log(response.data);
          var msg = `${file_name} successfully uploaded.`;
          this.step_four_form.success_msg = msg.replace("_", " ");

          this.step_four_form.uploading = false;
          this.step_four_form.submitted = true;
          this.step_four_form.is_progress_error = false;

          this.step_four_form[file_ref].value = null;
          this.step_four_form.errors = [];
          this.step_four_form.uploaded_documents = response.data.already_upload;
          this.step_four_form.errors2 = [];

          this.alertMessage(
            "success",
            "file successfully uploaded."
          );
          this.singleFileUploadingProgress = false;
          // console.log(this.step_four_form.uploaded_documents);
        })
        .catch(error => {
          this.step_four_form.is_progress_error = true;
          this.alertMessage("danger", "Uploading failed.");
          this.step_four_form.uploading = false;
          if (error.response.status == 422) {
            this.step_four_form.errors = error.response.data.errors;
          }
        })
        .then(() => {
          this.step_four_form.uploading = false;
          this.singleFileUploadingProgress = false;
        });
    }
  }

};

</script>
<style scoped>
.btn-row {
  margin-top: 24px;
}

.text-bold {
  font-weight: 510;
}

.col-checkbox {
  vertical-align: top;
  width: 10%;
  padding-left: 12px;
  display: inline-block;
}

.col-checkboxii {
  vertical-align: top;
  width: 10%;
  padding-left: 12px;
  display: inline-block;
}

.col-course-name {
  width: 87%;
  /* padding:12px; */
  display: inline-block;
}

.col-course-nameii {
  width: 87%;
  /* padding:12px; */
  display: inline-block;
}

.alert-blue {
  padding: 2px;
  background-color: #4299e1;
  font-weight: 600;
  color: white;
  text-align: center;
  margin-left: 14px;
  margin-right: 16px;
}

.text-notice {
  background: #ff0000b3;
  color: white;
}

.marks_columns_min_width td {
  min-width: 100px;
}

@media screen and (max-width: 700px) {
  div.i_qualify {
    display: none;
  }

  div.choose_preference {
    display: none;
  }
}</style>
