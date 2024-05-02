<?php

namespace App\Http\Controllers\Student;

use App\ApplicationAcademic;
use App\AppliedCourse;
use App\AreaOfResearch;
use App\Country;
use App\Course;
use App\CourseType;
use App\Department;
use App\District;
use App\Http\Controllers\CommonApplicationController;
use App\IncomeRange;
use App\MDesExam;
use App\MiscDocument;
use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Models\Caste;
use App\Models\CuetExamDetail;
use App\Models\CuetSubject;
use App\Models\ExamCenter;
use App\Models\ExtraDocument;
use App\Models\Program;
use App\Models\Session;
use App\Models\User;
use App\OtherQualification;
use App\Models\ExtraExamDetail;
use App\ParentsQualification;
use App\PhdEmployeement;
use App\Priority;
use App\State;
use Auth;
use Crypt;
use DB;
use Exception;
use Gate;
use Illuminate\Http\Request;
use Log;
use PDF;
use Validator;

class ApplicationController extends CommonApplicationController
{

    public function __construct()
    {
        $this->middleware("admissionController", ["except" => ["show", "index", "paymentReceipt", "uploadExtraDocumentShow"]]);
    }
    public function editForm($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
        }

        try {
            $application = Application::with("caste", "attachments", "session")->find($decrypted_id);
            $application_type = $application->exam_through;
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route(get_guard() . ".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        if (!applicatinEditPermission($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step One permission denied. Application id {$application->id}");
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if (!isEditAvailable($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option not available.");
        }

        $active_session = getActiveSession();
        $active_session_application = getActiveSessionApplication();
        if ($active_session->name == "NA") {
            return redirect()->route("student.home")->with("error", "Online Application has not started for the current session.");
        }
        // if ($active_session_application) {
        //     return redirect()->route("student.home")->with("error", "Application has been submitted. <a target='_blank' href='" . route("student.application.show", Crypt::encrypt($active_session_application->id)) . "'>Click Here to View.</a>");
        // }
        $mode = ['mode' => 'edit'];
        $id = Crypt::decrypt($encrypted_id);
        return view("student.application.create", compact("mode", "id","application_type"));
    }
    public function submitStepThree(Request $request)
    {
        // dd($request->all());
        // return response()
        //     ->json($request->get("proposed_area_of_research"), 401);
        try {
            $application = Application::with("caste", "attachments", "session")->find($request->application_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Whoops! Something went wrong. Please try again later.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        if (!applicatinEditPermission($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step three permission denied. Application id {$application->id}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Editing Application Step three permission denied",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if (!isEditAvailable($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Step three Option no longer available Application no {$application->id}, Status: {$application->status}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Edit Step three Option no longer available Application",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option not available.");
        }
        $student = $application->student;
        if ($student->id != Auth::id()) {
            return response()->json("Page Not Found", 404);
        }
        $request->validate([
            'academic10_board_name' => 'required|max:255',
            'academic10_passing_year' => 'required|max:255',
            'academic10_class_grade' => 'required|max:255',
            'academic10_subjects_taken' => 'required|max:250',
            'academic10_cgpa' => 'nullable|numeric|max:255',
            'academic_10_total_mark' => 'nullable|numeric|max:9999',
            'academic_10_mark_obtained' => 'nullable|numeric|max:9999',
            'academic10_marks_percentage' => 'nullable|numeric|max:100',
            'academic10_remarks' => 'nullable|max:255',

            'academic12_board_name' => 'nullable|max:255',
            'academic12_passing_year' => 'nullable|max:255',
            'academic12_class_grade' => 'nullable|max:255',
            'academic12_subjects_taken' => 'nullable|max:250',
            'academic12_cgpa' => 'nullable|numeric|max:255',
            'academic_12_total_mark' => 'nullable|numeric|max:9999',
            'academic_12_mark_obtained' => 'nullable|numeric|max:9999',
            'academic12_marks_percentage' => 'nullable|numeric|max:100',
            'academic12_remarks' => 'nullable|max:255',

            'jee_roll_no' => 'nullable|max:255',
            'jee_form_no' => 'nullable|max:255',
            'jee_year' => 'nullable|max:255',
            'other_information' => 'nullable|max:255',
            'furnish_details' => 'nullable|max:255',
            // 'cuet_roll_no' => 'required|max:255',
            // 'cuet_form_no' => 'required|max:255',
            // 'cuet_year' => 'required|max:255',
        ]);
        
        if($application->exam_through=='CUET' && $application->is_cuet_pg==1){
            $request->validate([
                'cuet_roll_no' => 'required|max:255',
                'cuet_form_no' => 'required|max:255',
                'cuet_year' => 'required|max:255',
            ]);
        }

        $program_list = AppliedCourse::where('application_id', $application->id)->first();
        $is_mca = $program_list->course_id;
        // if ($is_mca == 37) {
        //     $this->validate($request, [
        //         'mca_mathematics_mark' => 'numeric|between:30,100',
        //     ], [], [
        //         "mca_mathematics_mark"  => "mathematics mark"
        //     ]);
        // }
        if (in_array($request->program_type, ['PHD', 'PG','MDES','MBA'])) {
            $request->validate([
                'academic_bachelor_board_name' => 'required|max:255',
                'academic_bachelor_passing_year' => 'required|max:255',
                'academic_bachelor_class_grade' => 'required|max:255',
                'academic_bachelor_subjects_taken' => 'required|max:250',
                'academic_bachelor_major' => 'nullable|max:255',
                'academic_bachelor_cgpa' => 'nullable|numeric|max:255',
                'academic_graduation_total_mark' => 'nullable|numeric|max:9999',
                'academic_graduation_mark_obtained' => 'nullable|numeric|max:9999',
                'academic_bachelor_marks_percentage' => 'nullable|numeric|max:100',
                'academic_bachelor_remarks' => 'nullable|max:255',
                'academic_bachelor_degree' => 'required',
            ]);
        }
        if ($request->program_type == 'PHD') {
            $request->validate([
                'academic_post_graduate_board_name' => 'required|max:255',
                'academic_post_graduate_passing_year' => 'required|max:255',
                'academic_post_graduate_class_grade' => 'required|max:255',
                'academic_post_graduate_subjects_taken' => 'required|max:250',
                'academic_post_graduate_cgpa' => 'nullable|numeric|max:255',
                'academic_post_graduation_total_mark' => 'nullable|numeric|max:9999',
                'academic_post_graduation_mark_obtained' => 'nullable|numeric|max:9999',
                'academic_post_graduate_marks_percentage' => 'nullable|numeric|max:100',
                'academic_post_graduate_remarks' => 'nullable|max:255',
                'master_degree' => 'required',
                // 'proposed_area_of_research' => 'required',
            ]);
        }
        if ($request->course_type == 'LATERAL') {
            $request->validate([
                'academic_diploma_board_name' => 'required|max:255',
                'academic_diploma_passing_year' => 'required|max:255',
                'academic_diploma_class_grade' => 'required|max:255',
                'academic_diploma_subjects_taken' => 'required|max:250',
                'academic_diploma_cgpa' => 'nullable|numeric|max:255',
                'academic_diploma_total_mark' => 'nullable|numeric|max:9999',
                'academic_diploma_mark_obtained' => 'nullable|numeric|max:9999',
                'academic_diploma_marks_percentage' => 'nullable|numeric|max:100',
                'academic_diploma_remarks' => 'nullable|max:255',
            ]);
        }
        if ($request->course_type != 'LATERAL') {
            $request->validate([
                'academic12_board_name' => 'required|max:255',
                'academic12_passing_year' => 'required|max:255',
                // 'academic12_class_grade' => 'required|max:255',
                'academic12_subjects_taken' => 'required|max:250',
                "academic12_stream" => 'required|max:255',
            ]);
        }
        if ($request->course_type == 'MBBT') {
            $request->validate([
                'gat_b_score' => 'required|max:255',
            ]);
        }
        
        if (/* isMasterInDesign($application) && Auth::user()->exam_through!='TUEE' */$application->exam_through == 'UCEED' || $application->exam_through == 'CEED') {
            $request->validate([
                'ceed_score' => 'required|max:10'
            ]);
        }
        if(isMbaStudent($application)){
            $request->validate([
                "mba_exams" => "array|required",
                'mba_exams.*.name_of_the_exam' => 'required|max:50',
                'mba_exams.*.registration_no'  => 'required|max:100',
                'mba_exams.*.score_obtained'  => 'required|max:100',
                'mba_exams.*.date_of_exam'  => 'nullable|date_format:Y-m-d',
            ]);
        }
        
        // if($request->cuet_qualified=='true'){  
        //     $request->validate([  
        //         'cuet_score' => 'required|max:50',
        //         'cuet_rank'  => 'required|max:50',
        //     ]);
        // }

        if ($request->is_sport_represented == 'true') {
            $is_sport_represented = true;
        } else {
            $is_sport_represented = false;
        }
        if ($is_sport_represented == true) {
            $request->validate([
                'sport_played' => 'required|max:255',
                'medel_type' => 'required|max:255',
            ]);
        }
        
        $is_prizes_distinction = $request->is_prizes_distinction == 'true' ? true : false;

        if ($request->is_debarred == 'true') {
            $is_debarred = true;
        } else {
            $is_debarred = false;
        }
        if ($request->is_punished == 'true') {
            $is_punished = true;
        } else {
            $is_punished = false;
        }

        $academic_10_is_passed_appearing = $request->academic10_is_appeared == 'true' ? true : false;
        $academic_12_is_passed_appearing = $request->academic12_is_appeared == 'true' ? true : false;
        $academic_graduation_is_passed_appearing = $request->academic_bachelor_is_appeared == 'true' ? true : false;
        $academic_post_graduation_is_passed_appearing = $request->academic_post_graduate_is_appeared == 'true' ? true : false;
        $academic_diploma_is_passed_appearing = $request->academic_diploma_is_appeared == 'true' ? true : false;

        $is_btech = $application->is_btech;
        if ($is_btech) {
            $request->validate([
                'jee_roll_no' => 'required|max:255',
                'jee_form_no' => 'required|max:255',
                'jee_year' => 'required|max:255',
                // 'physics_mark' => 'required|numeric|between:30,100',
                // 'chemistry_mark' => 'required|numeric|between:30,100',
                // 'mathematics_mark' => 'required|numeric|between:30,100',
                // 'english_mark' => 'required|numeric|between:30,100',
            ]);
        }
        // if ($request->course_type == 'INTEGRATED' && !isIntegratedMCOM($application)) {
        //     if(isIntegratedEnglish($application)){
        //         $request->validate([
        //             'english_mark' => 'required|numeric|between:0,100',
        //             'english_mark_10' => 'required|numeric|between:0,100',
        //         ]);
        //     }else{
        //         $request->validate([
        //             'physics_mark' => 'required|numeric|between:0,100',
        //             'chemistry_mark' => 'required|numeric|between:0,100',
        //             'mathematics_mark' => 'required|numeric|between:0,100',
        //             'english_mark' => 'required|numeric|between:0,100',
        //             'biology_mark' => 'required|numeric|between:0,100',
        //             'statistics_mark' => 'required|numeric|between:0,100',
        //         ]);
        //     }
        // }

        if ($request->program_type == 'PHD') {
            if($request->proposed_area_of_research[0]==null){
                // dd("ok");
                return response()->json(["message" => "Fill This Input Area", "status" => false], 500);
            }
        }
        if($application->is_editable!=3){
            $academic = ApplicationAcademic::updateOrCreate(['application_id' => $request->application_id], [
                'application_id' => $request->application_id,
                'academic_graduation_board' => $request->academic_bachelor_board_name,
                'academic_graduation_year' => $request->academic_bachelor_passing_year,
                'academic_graduation_grade' => $request->academic_bachelor_class_grade,
                'academic_graduation_subject' => $request->academic_bachelor_subjects_taken,
                'acadmeic_graduation_major' => $request->academic_bachelor_major,
                'academic_graduation_cgpa' => $request->academic_bachelor_cgpa,
                'academic_graduation_total_mark' => $request->academic_graduation_total_mark,
                'academic_graduation_mark_obtained' => $request->academic_graduation_mark_obtained,
                'academic_graduation_percentage' => $request->academic_bachelor_marks_percentage,
                'academic_bachelor_degree' => $request->academic_bachelor_degree,
                'academic_graduation_remarks' => $request->academic_bachelor_remarks,
                'academic_graduation_is_passed_appearing' => $academic_graduation_is_passed_appearing,

                'academic_diploma_board' => $request->academic_diploma_board_name,
                'academic_diploma_year' => $request->academic_diploma_passing_year,
                'academic_diploma_grade' => $request->academic_diploma_class_grade,
                'academic_diploma_subject' => $request->academic_diploma_subjects_taken,
                'academic_diploma_cgpa' => $request->academic_diploma_cgpa,
                'academic_diploma_total_mark' => $request->academic_diploma_total_mark,
                'academic_diploma_mark_obtained' => $request->academic_diploma_mark_obtained,
                'academic_diploma_percentage' => $request->academic_diploma_marks_percentage,
                'academic_diploma_remarks' => $request->academic_diploma_remarks,
                'academic_diploma_is_passed_appearing' => $academic_diploma_is_passed_appearing,

                'academic_post_graduation_board' => $request->academic_post_graduate_board_name,
                'academic_post_graduation_year' => $request->academic_post_graduate_passing_year,
                'academic_post_graduation_grade' => $request->academic_post_graduate_class_grade,
                'academic_post_graduation_subject' => $request->academic_post_graduate_subjects_taken,
                'academic_post_graduation_cgpa' => $request->academic_post_graduate_cgpa,
                'academic_post_graduation_total_mark' => $request->academic_post_graduation_total_mark,
                'academic_post_graduation_mark_obtained' => $request->academic_post_graduation_mark_obtained,
                'academic_post_graduation_percentage' => $request->academic_post_graduate_marks_percentage,
                'academic_post_graduation_remarks' => $request->academic_post_graduate_remarks,
                'academic_post_graduation_degree' => $request->master_degree,
                'academic_post_graduation_is_passed_appearing' => $academic_post_graduation_is_passed_appearing,
                
                'is_sport_represented' => $is_sport_represented,
                'sport_played' => $request->sport_played,
                'medel_type'   => $request->medel_type,
                'is_debarred'  => $is_debarred,
                'is_academic_prizes' => $is_prizes_distinction,
                'is_punished' => $is_punished,
                'furnish_details' => $request->furnish_details,
                'other_information' => $request->other_information,
                'jee_roll_no' => $request->jee_roll_no,
                'jee_form_no' => $request->jee_form_no,
                'jee_year' => $request->jee_year,

                'cuet_roll_no' => $request->cuet_roll_no,
                'cuet_form_no' => $request->cuet_form_no,
                'cuet_year' => $request->cuet_year,
                'cuet_qualified' =>$request->cuet_qualified=='true'? 1: 0,
                // 'cuet_score' =>$request->cuet_score,
                // 'cuet_rank' =>$request->cuet_rank,

                'gate_roll_no' => $request->gate_roll_no ?? null,
                'gate_form_no' => $request->gate_roll_no ?? null,
                'gate_year' => $request->gate_roll_no ?? null,
                'gate_qualified' =>$request->gate_qualified=='true'? 1: 0 ?? null,
                'gate_score' =>$request->gate_score ?? null,
                'gate_rank' =>$request->gate_rank ?? null,

                'academic_10_board' => $request->academic10_board_name,
                'academic_10_year' => $request->academic10_passing_year,
                'academic_10_grade' => $request->academic10_class_grade,
                'academic_10_subject' => $request->academic10_subjects_taken,
                'academic_10_cgpa' => $request->academic10_cgpa,
                'academic_10_total_mark' => $request->academic_10_total_mark,
                'academic_10_mark_obtained' => $request->academic_10_mark_obtained,
                'academic_10_percentage' => $request->academic10_marks_percentage,
                'academic_10_remarks' => $request->academic10_remarks,
                'academic_10_is_passed_appearing' => $academic_10_is_passed_appearing,
                
                'academic_12_board' => $request->academic12_board_name,
                'academic_12_year' => $request->academic12_passing_year,
                'academic_12_grade' => $request->academic12_class_grade,
                'academic_12_subject' => $request->academic12_subjects_taken,
                'academic_12_cgpa' => $request->academic12_cgpa,
                'academic_12_total_mark' => $request->academic_12_total_mark,
                'academic_12_mark_obtained' => $request->academic_12_mark_obtained,
                'academic_12_percentage' => $request->academic12_marks_percentage,
                'academic_12_remarks' => $request->academic12_remarks,
                'academic_12_stream' => $request->academic12_stream,
                'academic_12_is_passed_appearing' => $academic_12_is_passed_appearing,
                
                'physics_mark' => $request->physics_mark,
                'physics_total_mark' => $request->physics_total_mark ?? 0,
                'physics_grade' => $request->physics_grade ?? "NA",
                
                'chemistry_mark' => $request->chemistry_mark,
                'chemistry_total_mark' => $request->chemistry_total_mark ?? 0,
                'chemistry_grade' => $request->chemistry_grade ?? "NA",

                'mathematics_mark' => $request->mathematics_mark,
                'mathematics_total_mark' => $request->mathematics_total_mark ?? 0,
                'mathematics_grade' => $request->mathematics_grade ?? "NA",
                
                'english_mark' => $request->english_mark,
                'english_total_mark' => $request->english_total_mark,
                'english_grade' => $request->english_grade ?? "NA",

                'english_mark_10' => $request->english_mark_10 ?? 0,
                'english_mark_10_total_mark' => $request->english_mark_10_total_mark ?? 0,
                'english_mark_10_grade' => $request->english_mark_10_grade ?? "NA",

                'statistics_mark' => $request->statistics_mark ?? 0,
                'statistics_grade' => $request->statistics_grade ?? 0,
                'statistics_mark' => $request->statistics_mark ?? 0,

                'biology_mark' => $request->biology_mark ?? 0,
                'biology_total_mark' => $request->biology_total_mark ?? 0,
                'biology_grade' => $request->biology_grade ?? 0,
                
                'mca_mathematics_mark' => $request->mca_mathematics_mark,
                'proposed_area_of_research' => json_encode($request->proposed_area_of_research),
                'qualified_national_level_test' => $request->qualified_national_level_test,
                'qualified_national_level_test_mark' => $request->qualified_national_level_test_mark,
                'gat_b_score'  =>$request->gat_b_score,
                'ceed_score' => $request->ceed_score,
            ]);
            if ($application->other_qualifications()) {
                $application->other_qualifications()->delete();
            }
            if ($request->has('other_qualifications')) {
                $qualifications = json_decode($request->other_qualifications);
                for ($i = 0; $i < count($qualifications); $i++) {

                    $quali = OtherQualification::create([
                        'application_id' => $application->id,
                        'exam_name' => $qualifications[$i]->exam_name,
                        'board_name' => $qualifications[$i]->board_name,
                        'passing_year' => $qualifications[$i]->passing_year,
                        'class_grade' => $qualifications[$i]->class_grade,
                        'subjects_taken' => $qualifications[$i]->subjects_taken,
                        'total_mark' => $qualifications[$i]->total_mark ?? null,
                        'mark_obtained' => $qualifications[$i]->mark_obtained ?? null,
                        'cgpa' => $qualifications[$i]->cgpa,
                        'marks_percentage' => $qualifications[$i]->marks_percentage,
                        'remarks' => $qualifications[$i]->remarks,
                    ]);
                }
            }
            
            if ($application->cuet_exam_details()) {
                $application->cuet_exam_details()->delete();
            }


            if ($request->has('cuet_details') /* &&  $application->is_cuet_ug==1 || */ /* $application->is_cuet_pg==1 */ ) {
                $cuet_per_sub = json_decode($request->cuet_details);
                for ($i = 0; $i < count($cuet_per_sub); $i++) {
                    $quali = CuetExamDetail::create([
                        'application_id' => $application->id,
                        'student_id' => $application->student_id,
                        'subjects' => $cuet_per_sub[$i]->subjects,
                        'marks' => $cuet_per_sub[$i]->marks,
                        // 'marks' => number_format($cuet_per_sub[$i]->marks, 7, '.', ''),
                        // 'percentile' => number_format($cuet_per_sub[$i]->percentile, 7, '.', ''),
                    ]);
                }
                Application::where('id',$application->id)->update(['cuet_status'=>1]);
                // $application->update(['cuet_status'=>1]);
            }


            if ($application->form_step == 2) {
                $application->form_step = 3;
                $application->save();
            }
            if($request->has("mba_exams")){
                $application->extraExamDetails()->delete();
                foreach($request->get("mba_exams") as $exam_details){
                    if(($exam_details["registration_no"]  == "null" ? null : $exam_details["registration_no"]) && ($exam_details["registration_no"]  == "null" ? null : $exam_details["registration_no"]) != "NA"){
                        $application->extraExamDetails()->create([
                            "student_id"        => auth("student")->id(),
                            "name_of_the_exam"  => $exam_details["name_of_the_exam"] == "null" ? null : $exam_details["name_of_the_exam"],
                            "date_of_exam"      => $exam_details["date_of_exam"]     == "null" ? null : $exam_details["date_of_exam"],
                            "registration_no"   => $exam_details["registration_no"]  == "null" ? null : $exam_details["registration_no"],
                            "score_obtained"    => $exam_details["score_obtained"]   == "null" ? null : $exam_details["score_obtained"],
                        ]);
                    }
                }
            }
            if($request->has("work_experience")){
                $application->work_experiences()->delete();
                foreach($request->get("work_experience") as $work_exp){
                    if(($work_exp["organization"]  == "null" ? null : $work_exp["organization"]) && ($work_exp["designation"]  == "null" ? null : $work_exp["designation"]) != "NA"){
                        $application->work_experiences()->create([
                            // "student_id"        => auth("student")->id(),
                            "organization"      => $work_exp["organization"] == "null" ? null : $work_exp["organization"],
                            "designation"       => $work_exp["designation"]     == "null" ? null : $work_exp["designation"],
                            "from"              => $work_exp["from"]  == "null" ? null : $work_exp["from"],
                            "to"                => $work_exp["to"]   == "null" ? null : $work_exp["to"],
                            "details"           => $work_exp["details"]   == "null" ? null : $work_exp["details"],
                        ]);
                    }
                }
            }
            if($application->is_editable==1){
                $this->removeIsEditable($application->id);
            }
        }else{
            $academic = ApplicationAcademic::updateOrCreate(['application_id' => $request->application_id], [
                'application_id' => $request->application_id,
                'academic_graduation_board' => $request->academic_bachelor_board_name,
                'academic_graduation_year' => $request->academic_bachelor_passing_year,
                'academic_graduation_grade' => $request->academic_bachelor_class_grade,
                'academic_graduation_subject' => $request->academic_bachelor_subjects_taken,
                'acadmeic_graduation_major' => $request->academic_bachelor_major,
                'academic_graduation_cgpa' => $request->academic_bachelor_cgpa,
                'academic_graduation_total_mark' => $request->academic_graduation_total_mark,
                'academic_graduation_mark_obtained' => $request->academic_graduation_mark_obtained,
                'academic_graduation_percentage' => $request->academic_bachelor_marks_percentage,
                'academic_bachelor_degree' => $request->academic_bachelor_degree,
                'academic_graduation_remarks' => $request->academic_bachelor_remarks,
                'academic_graduation_is_passed_appearing' => $academic_graduation_is_passed_appearing,
            ]);

            if($request->has("mba_exams")){
                $application->extraExamDetails()->delete();
                foreach($request->get("mba_exams") as $exam_details){
                    if(($exam_details["registration_no"]  == "null" ? null : $exam_details["registration_no"]) && ($exam_details["registration_no"]  == "null" ? null : $exam_details["registration_no"]) != "NA"){
                        $application->extraExamDetails()->create([
                            "student_id"        => auth("student")->id(),
                            "name_of_the_exam"  => $exam_details["name_of_the_exam"] == "null" ? null : $exam_details["name_of_the_exam"],
                            "date_of_exam"      => $exam_details["date_of_exam"]     == "null" ? null : $exam_details["date_of_exam"],
                            "registration_no"   => $exam_details["registration_no"]  == "null" ? null : $exam_details["registration_no"],
                            "score_obtained"    => $exam_details["score_obtained"]   == "null" ? null : $exam_details["score_obtained"],
                        ]);
                    }
                }
            }
        }
        return response()->json(200);

    }
    public function submitStepTwo(Request $request)
    {
        // dd("ok");
        // return response()->json(['application' => $request->all()]);

        try {
            $application = Application::with("caste", "attachments", "session")->find($request->application_id);
        } catch (Exception $e) {
            Log::error($e);
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Whoops! Something went wrong. Please try again later.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        if (!applicatinEditPermission($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step two permission denied. Application id {$application->id}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. You don't have the permission to edit other application.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if (!isEditAvailable($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Step two Option no longer available Application no {$application->id}, Status: {$application->status}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. Edit Step two Option no longer available Application",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option not available.");
        }
        if ($application->is_editable==3) {
           
            // saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Step one Option no longer available Application no {$application->id}, Status: {$application->status}");
            if ($request->ajax()) {
                // dd("ok");
                return response()->json([
                    // "status" => false,
                    "message" => "Access Denied. Edit option available for only Academic Details & Document Uploads.",
                ], 500);
            }
            // return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option available for only Academic Details & Document Uploads.");
        }
        $via_exam= Auth::User()->exam_through;
        //$isMbaOrBtech = ($application->is_mba == 0 && $application->is_btech == 0 && $application->is_cuet_ug == 0);
        $isCenterRequired = ($application->is_mba == 0 && $application->is_btech == 0 && $application->exam_through=="TUEE");
        $isCenterRequiredii = ($application->is_foreign==0 && $application->is_mba == 0 && $application->is_btech == 0 && $application->exam_through=="TUEE");
        $validation_rule = [
            'correspondence_co' => "required|max:255",
            'correspondence_house_no' => "required|max:255",
            'correspondence_vill_town' => "required|max:255",
            'correspondence_po' => "required|max:255",
            'correspondence_district' => "required|max:255",
            'correspondence_street_name_locality' => "required|max:255",
            'correspondence_pin_code' => "required|max:255",
            'correspondence_state' => "required|max:255",
            'permanent_co' => "required|max:255",
            'permanent_house_no' => "required|max:255",
            'permanent_vill_town' => "required|max:255",
            'permanent_po' => "required|max:255",
            'permanent_district' => "required|max:255",
            'permanent_street_name_locality' => "required|max:255",
            'permanent_pin_code' => "required|max:255",
            'permanent_state' => "required|max:255",
            'nationality' => "required|max:255",
            'place_residence' => "required|max:255",
            'exam_center' => $isCenterRequired ? 'required' : '',
            'exam_center1' => $isCenterRequiredii ? 'required' : '',
            'exam_center2' => $isCenterRequiredii ? 'required' : '',
        ];
        // if(isMbaStudent($application)){
        //     $validation_rule["exam_center"] = str_replace("required", "nullable", $validation_rule["exam_center"]);
        // }
        $request->validate($validation_rule);

        $application->correspondence_co = $request->correspondence_co;

        

        $application->correspondence_house_no = $request->correspondence_house_no;
        $application->correspondence_village_town = $request->correspondence_vill_town;
        $application->correspondence_po = $request->correspondence_po;
        $application->correspondence_district = $request->correspondence_district;
        $application->correspondence_street_locality = $request->correspondence_street_name_locality;
        $application->correspondence_pin = $request->correspondence_pin_code;
        $application->correspondence_state = $request->correspondence_state;
        $application->permanent_co = $request->permanent_co;
        $application->permanent_house_no = $request->permanent_house_no;
        $application->permanent_village_town = $request->permanent_vill_town;
        $application->permanent_po = $request->permanent_po;
        $application->permanent_district = $request->permanent_district;
        $application->permanent_street_locality = $request->permanent_street_name_locality;
        $application->permanent_pin = $request->permanent_pin_code;
        $application->permanent_state = $request->permanent_state;
        $application->nationality = $request->nationality;
        $application->place_residence = $request->place_residence;
        $application->exam_center_id = $request->exam_center;
        $application->exam_center_id1 = $request->exam_center1;
        $application->exam_center_id2 = $request->exam_center2;



        if ($application->form_step == 1) {$application->form_step = 2;}

        $application->save();




        // $application=Application::where('id',$id)->first();
        if($application->is_cuet_ug==1){
            $type="UG";
        }elseif($application->is_cuet_pg==1){
            $type="PG";
        }elseif($application->is_phd==1){
            $type="PHD";
        }elseif($application->is_btech==1){
            $type="BTECH";
        }elseif($application->is_mba==1){
            $type="MBA";
        }else{
            $type="MBA";
        }
        $cuet_subject = CuetSubject::where('status',1)
                                    ->where('subject_name','!=','English')
                                    ->where('course_type',$type)
                                    // ->whereNotIN('subject_name',$already_selected)
                                    ->get();
        // return response()->json(['cuet_subject' => $cuet_subject]);

        return response()->json(['cuet_subject' => $cuet_subject], 200);
    }
    public function submitStepOne(Request $request)
    {
        // dd("ok");
        $user_table_session = User::where('id',Auth::id())->first()->session_id;
        $active_session = getActiveSession();
        if($user_table_session != getActiveSession()->id){
            return response()->json(["message" => "You have registered in a old session. Please contact TU-technical support (+91 8399894076).", "status" => false], 501);
        }
        Validator::extend('different_user_mobile', function ($attribute, $value, $parameters, $validator) {
            $user_mobile = User::where('id', auth()->id())->value('mobile_no');
            return $value != $user_mobile;
        });
        
        $messages = [
            'guardian_mobile.different_user_mobile' => 'The :attribute must be different from the user\'s mobile number.',
        ];
        $rules = [
            "application_type" => "required|max:255",
            "first_name" => "required|max:255",
            "middle_name" => "nullable|max:255",
            "last_name" => "nullable|max:255",
            "father_name" => "required|max:255",
            "father_occupation" => "required|max:255",
            "father_mobile" => "required|min:10|max:15",
            "father_email" => "required|email|max:255",
            "mother_name" => "required|max:255",
            "mother_occupation" => "required|max:255",
            "mother_mobile" => "required|min:10|max:15",
            "mother_email" => "required|email|max:255",
            "guardian_name" => "required|max:255",
            "guardian_occupation" => "required|max:255",
            "guardian_mobile" => "required|min:10|max:15|different_user_mobile",
            "guardian_email" => "required|email|max:255",
            "marital_status" => "required",
            "religion" => "required|max:255",
            "gender" => "required|max:255",
            "dob" => "required|date_format:Y-m-d",
            "is_pwd" => "required",
            "pwd_explain" => "nullable|max:255",
            "pwd_percentage" => "nullable|max:255",
            "adhaar" => "nullable|size:12",
            "is_pwd" => "required",
            "family_income" => "required",
            // "nad_id" => "required",
            // "bank_ac_no" => "required",
            // "ifsc_code" => "required",
            // "bank_reg_mobile_no" => "required",
            // "account_holder_name" => "required|max:100",
            // "bank_name" => "required|max:100",
            // "ifsc_no" => "max:100",
            // "branch_name" => "required|max:100",
            // "branch_code" => "nullable|max:100",
            // "pan_no" => "nullable|max:100",
        ];
        
        if(isPHDCourse($request->course_id)){
            $rules = array_merge($rules, [
                "part_time_details" => "max:500",
                "academic_experience" => "max:100",
                "publication_details" => "max:100",
                "statement_of_purpose" => "max:100",
                "is_full_time" => "boolean",
            ]);
        }
        
        $request->validate($rules, $messages);
        $student_user = Auth::User();
        
        $country = Country::find($student_user->country_id);

        $is_foreign = $country->code == 'IN' ? false : true;
        
        // return response()->json(['application' => $is_foreign,'program_list'=>$is_foreign], 200);
        // dd($student_user);
        if ($is_foreign == false) {
            $request->validate([
                "caste" => "required",
                "sub_caste" => "required|max:255",
            ]);
        }
        if ($is_foreign == true) {
            $request->validate([
                "passport_number" => "required|max:255",
                // "driving_license_equivalnet_no" => "required|max:255",
            ]);
        }
        
        $is_btech = $request->is_btech == 'true' ? true : false;
        $is_kmigrant = $request->is_km == 'true' ? true : false;
        $is_jk_student = $request->is_jk_student == 'true' ? true : false;
        $is_ex_serviceman = $request->is_ex_serviceman == 'true' ? true : false;
        $is_bpl = $request->is_bpl == 'true' ? true : false;
        $is_minority = $request->is_minority == 'true' ? true : false;
        $is_accomodation_need = $request->is_accomodation_need == 'true' ? true : false;
        $is_employed = $request->is_employed == 'true' ? true : false;
        $is_preference = $request->is_preference == 'true' ? true : false;
        $is_aplly_defense_quota = $request->is_aplly_defense_quota == 'true' ? true : false;
        $is_sub_preference = $request->is_sub_preference == 'true' ? true : false;
        $is_pwd = $request->is_pwd == 'true' ? true : false;
        
        if ($is_pwd == true) {
            $request->validate([
                'pwd_explain' => 'required|max:255',
                "pwd_percentage"=> 'required|max:255',
            ]);
        }
        if ($is_ex_serviceman == true) {
            $request->validate([
                'priority' => 'required',
            ]);
        }
        if ($is_kmigrant == true) {
            $request->validate([
                'km_details' => 'required|max:255',
            ]);
        }
        // dd($is_employed);
        if(Auth::user()->program_name == "PHDPROF" && $is_employed == false){
            return response()->json(["message" => "You have registered in Ph.D Professional program, Are you employed field must be yes", "status" => false], 501);
        }

        if ($is_employed == true) {
            $request->validate([
                'employment_details' => 'required|max:255',
            ]);
        }

        if ($is_minority == true) {
            $request->validate([
                'minority_details' => 'required|max:255',
            ]);
        }
        
        
        DB::beginTransaction();
        try {
            $active_session = getActiveSession();
            if(isPHDCourse($request->course)){
                $active_session = Session::find(10);
            }
            $is_direct=Auth::User()->qualifying_exam!=null?1:0;
            $application = Application::create([
                // "session_id" => $request->program_name == "MBA"?10:$active_session->id,
                "session_id" => $active_session->id,
                "exam_through" => $request->application_type,
                "first_name" => $request->first_name,
                "middle_name" => $request->middle_name,
                "last_name" => $request->last_name,
                "father_name" => $request->father_name,
                "father_occupation" => $request->father_occupation,
                "father_qualification" => $request->father_qualification,
                "father_mobile" => $request->father_mobile,
                "father_email" => $request->father_email,
                "mother_name" => $request->mother_name,
                "mother_occupation" => $request->mother_occupation,
                "mother_qualification" => $request->mother_qualification,
                "mother_mobile" => $request->mother_mobile,
                "mother_occupation" => $request->mother_occupation,
                "mother_email" => $request->mother_email,
                "guardian_name" => $request->guardian_name,
                "guardian_occupation" => $request->guardian_occupation,
                "guardian_phone" => $request->guardian_mobile,
                "guardian_email" => $request->guardian_email,
                "marital_status" => $request->marital_status,
                "religion" => $request->religion,
                "gender" => $request->gender,
                "dob" => $request->dob,
                "caste_id" => $request->caste,
                "sub_caste" => $request->sub_caste,
                "is_btech" => $is_btech,
                "is_kmigrant" => $is_kmigrant,
                "is_jk_student" => $is_jk_student,
                "is_ex_servicement" => $is_ex_serviceman,
                "is_bpl" => $is_bpl,
                "is_minority" => $is_minority,
                "is_accomodation_needed" => $is_accomodation_need,
                "is_employed" => $is_employed,
                "student_id" => Auth::id(),
                "is_foreign" => $is_foreign,
                "priority_id" => $request->priority,
                "adhaar" => $request->adhaar,
                "nad_id" => $request->nad_id,
                "is_aplly_defense_quota" => $is_aplly_defense_quota,
                "km_details" => $request->km_details,
                "employment_details" => $request->employment_details,
                "minority_details" => $request->minority_details,
                "is_pwd" => $is_pwd,
                "person_with_disablity" => $request->pwd_explain,
                "pwd_percentage"  =>$request->percentage,
                "passport_number" => $request->passport_number,
                "driving_license_equivalnet_no" => $request->driving_license_equivalnet_no,
                "form_step" => 1,
                "family_income" => $request->family_income,
                "bank_ac_no" => $request->bank_ac_no,
                "bank_name" => $request->bank_name,
                "branch_name" => $request->branch_name,
                "ifsc_code" => $request->ifsc_code,
                "bank_reg_mobile_no" => $request->bank_reg_mobile_no,
                "account_holder_name" => $request->account_holder_name,
                "is_cuet_ug"  => $request->program_name == "UG"? 1 : 0,
                "is_cuet_pg" => $request->program_name == "PG"? 1 : 0,
                "is_phd" => $request->program_name == "PHD"? 1 : 0,
                "is_phd_prof" => Auth::user()->program_name == "PHDPROF"?1:0,
                "is_btech" => $request->program_name == "BTECH"? 1 : 0,
                "is_mba"   => $request->program_name == "MBA"?1:0,
                "is_laterall" => $request->program_name == "LATERAL"?1:0,
                "is_mdes" => $request->program_name == "MDES"?1:0,  
                "is_bdes" => $request->program_name == "BDES"?1:0,  
                'is_mbbt' => $request->program_name =="MBBT"?1:0,
                'is_chinese' => $request->program_name =="CHINESE"?1:0,
                "is_direct" => $is_direct,             
            ]);
            // dd("ok1");
            //if ($is_preference == true || $is_sub_preference == true) {
                $b_courses = json_decode($request->applied_courses);
                //return response()->json(['xxx'=>$request->applied_courses]);
                for ($i = 0; $i < count($b_courses); $i++) {
                    if ($b_courses[$i]->is_checked == 'true') {
                        $course_type_id=Course::where('id',$b_courses[$i]->id)->pluck('course_type_id');
                        $course_type=CourseType::where('id',$course_type_id)->pluck('code');
                        $applied_course = AppliedCourse::create([
                            'application_id' => $application->id,
                            'student_id' => $student_user->id,
                            'course_id' => $b_courses[$i]->id,
                            'preference' => $b_courses[$i]->preference,
                            'is_btech_' => $is_btech,
                            'course_type_id' => $course_type_id[0],
                            'course_type' => $course_type[0],
                        ]);
                    }
                }
                
            // new addition 07-01-2021
            $academic_information = $application->application_academic;
            
            $is_full_time = $request->is_full_time;
            if(is_string($is_full_time)){
                $is_full_time = $is_full_time == "true";
            }
            $academic_data = [
                "is_full_time"         => $is_full_time,
                "part_time_details"    => !$is_full_time ? $request->part_time_details : null,
                "academic_experience"  => $request->academic_experience,
                "publication_details"  => $request->publication_details,
                "statement_of_purpose" => $request->statement_of_purpose,
                "bank_acc"             => $request->bank_acc,
                "pan_no"               => $request->pan_no,
                "ifsc_no"              => $request->ifsc_no,
                "branch_code"          => $request->branch_code,
                "passed_or_appeared_qualified_exam"          => $request->passed_or_appeared_qualified_exam,
            ];
            if($academic_information){
                $academic_information->update($academic_data);
            }else{
                $application->application_academic()->create($academic_data);
            }

            if($application->is_editable==1){
                $this->removeIsEditable($application->id);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return response()->json(["message" => $th->getMessage(), "status" => false], 500);
        }
        DB::commit();
        /*  try {
        generateApplicationNo($application);
        } catch (\Throwable $th) {
        $application->applied_courses()->delete();
        $application->auditTrail()->delete();
        $application->forceDelete();
        Log::error($th);
        return response()->json(['message' => "Whoops! something went wrong. try again later."], 500);
        } */
        $program_list = AppliedCourse::where('application_id', $application->id)->first();
        $applicationget=Application::where('id', $application->id)->with(['other_qualifications',"application_academic", "extraExamDetails", "applied_courses", "work_experiences"])->first();
        return response()->json(['application' => $applicationget,'program_list'=>$program_list], 200);
    }
    public function updateStepTwo(Request $request)
    {

        $this->submitStepTwo($request);
    }
    public function updateStepOne(Request $request)
    {
        // return response()->json($request->all(), 401);
        // $user_mobile = User::where('id',Auth::id())->first()->mobile_no;
        Validator::extend('different_user_mobile', function ($attribute, $value, $parameters, $validator) {
            $user_mobile = User::where('id', auth()->id())->value('mobile_no');
            return $value != $user_mobile;
        });
        $messages = [
            'guardian_mobile.different_user_mobile' => 'The :attribute must be different from the user\'s mobile number.',
        ];
        $rules = [
            "first_name" => "required|max:255",
            "middle_name" => "nullable|max:255",
            "last_name" => "nullable|max:255",
            "father_name" => "nullable|max:255",
            "father_occupation" => "nullable|max:255",
            "father_mobile" => "nullable|min:10|max:15",
            "father_email" => "nullable|email|max:255",
            "mother_name" => "nullable|max:255",
            "mother_occupation" => "nullable|max:255",
            "mother_mobile" => "nullable|min:10|max:15",
            "mother_email" => "nullable|email|max:255",
            "guardian_name" => "required|max:255",
            "guardian_occupation" => "required|max:255",
            "guardian_mobile" => "required|min:10|max:15|different_user_mobile",
            "guardian_email" => "required|email|max:255",
            "marital_status" => "required",
            "religion" => "required|max:255",
            "gender" => "required|max:255",
            "dob" => "required|date_format:Y-m-d",
            "is_pwd" => "required",
            "pwd_explain" => "nullable|max:255",
            "pwd_percentage" => "nullable|max:255",
            "adhaar" => "nullable|size:12",
            // "bank_ac_no" => "required",
            "family_income" => "required",
            // "ifsc_code" => "required",
            // "bank_reg_mobile_no" => "required",
        ];
        if(isPHDCourse($request->course_id)){
            $rules = array_merge($rules, [
                "part_time_details" => "max:500",
                "academic_experience" => "max:100",
                "publication_details" => "max:100",
                "statement_of_purpose" => "max:100",
                "bank_acc" => "max:100",
                "ifsc_no" => "max:100",
                "branch_name" => "max:100",
                "branch_code" => "max:100",
                "pan_no" => "max:100",
                "is_full_time" => "boolean",
            ]);
        }
        $request->validate($rules , $messages);
        try {
            $application = Application::with("caste", "attachments", "session")->find($request->application_id);
        } catch (Exception $e) {
            Log::error($e);
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Whoops! Something went wrong. Please try again later.",
                    "errors"=>$e->getMessage()
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        if (!applicatinEditPermission($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step one permission denied. Application id {$application->id}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. You don't have the permission to edit other application.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if (!isEditAvailable($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Step one Option no longer available Application no {$application->id}, Status: {$application->status}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. Edit option not available.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option not available.");
        }

        if ($application->is_editable==3) {
            // saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Step one Option no longer available Application no {$application->id}, Status: {$application->status}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. Edit option available for only Academic Details & Document Uploads.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option available for only Academic Details & Document Uploads.");
        }
        $student_user = User::find($application->student_id);
        $country = Country::find($student_user->country_id);
        $is_foreign = $country->code == 'IN' ? false : true;

        if ($is_foreign == false) {
            $request->validate([
                "caste" => "required",
                "sub_caste" => "required|max:255",
            ]);
        }

        if ($is_foreign == true) {
            $request->validate([
                "passport_number" => "required|max:255",
                // "driving_license_equivalnet_no" => "required|max:255",
            ]);
        }

        $is_btech = $request->is_btech == 'true' ? true : false;
        $is_kmigrant = $request->is_km == 'true' ? true : false;
        $is_jk_student = $request->is_jk_student == 'true' ? true : false;
        $is_ex_serviceman = $request->is_ex_serviceman == 'true' ? true : false;
        $is_bpl = $request->is_bpl == 'true' ? true : false;
        $is_minority = $request->is_minority == 'true' ? true : false;
        $is_accomodation_need = $request->is_accomodation_need == 'true' ? true : false;
        $is_employed = $request->is_employed == 'true' ? true : false;
        $is_preference = $request->is_preference == 'true' ? true : false;
        $is_aplly_defense_quota = $request->is_aplly_defense_quota == 'true' ? true : false;
        $is_pwd = $request->is_pwd == 'true' ? true : false;

        if ($is_pwd == true) {
            $request->validate([
                'pwd_explain' => 'required|max:255',
                'pwd_percentage' => 'required|max:255',
            ]);
        }

        if(Auth::user()->program_name == "PHDPROF" && $is_employed == false){
            return response()->json(["message" => "You have registered in Ph.D Professional program, Are you employed field must be yes.", "status" => false], 501);
        }

        if ($is_employed == true) {
            $request->validate([
                'employment_details' => 'required|max:255',
            ]);
        }

        if ($is_ex_serviceman == true) {
            $request->validate([
                'priority' => 'required',
            ]);
        }

        if ($is_kmigrant == true) {
            $request->validate([
                'km_details' => 'required|max:255',
            ]);
        }

        if ($is_employed == true) {
            $request->validate([
                'employment_details' => 'required|max:255',
            ]);
        }

        if ($is_minority == true) {
            $request->validate([
                'minority_details' => 'required|max:255',
            ]);
        }
        $application->first_name = $request->first_name;
        $application->middle_name = $request->middle_name;
        $application->last_name = $request->last_name;
        $application->father_name = $request->father_name;
        $application->father_occupation = $request->father_occupation;
        $application->father_qualification = $request->father_qualification;
        $application->father_income = $request->father_income;
        $application->father_mobile = $request->father_mobile;
        $application->father_email = $request->father_email;
        $application->mother_name = $request->mother_name;
        $application->mother_occupation = $request->mother_occupation;
        $application->mother_qualification = $request->mother_qualification;
        $application->mother_income = $request->mother_income;
        $application->mother_mobile = $request->mother_mobile;
        $application->mother_email = $request->mother_email;
        $application->guardian_name = $request->guardian_name;
        $application->guardian_occupation = $request->guardian_occupation;
        $application->guardian_phone = $request->guardian_mobile;
        $application->guardian_email = $request->guardian_email;
        $application->marital_status = $request->marital_status;
        $application->religion = $request->religion;
        $application->gender = $request->gender;
        $application->dob = $request->dob;
        if ($is_foreign == false) {
            $application->caste_id = $request->caste;
        }
        $application->sub_caste = $request->sub_caste;
        $application->is_kmigrant = $is_kmigrant;
        $application->is_jk_student = $is_jk_student;
        $application->is_ex_servicement = $is_ex_serviceman;
        $application->is_bpl = $is_bpl;
        $application->is_minority = $is_minority;
        $application->is_accomodation_needed = $is_accomodation_need;
        $application->is_employed = $is_employed;
        $application->student_id = Auth::id();
        $application->is_foreign = $is_foreign;
        $application->priority_id = $request->priority;
        $application->adhaar = $request->adhaar;
        $application->nad_id = $request->nad_id;
        $application->abc = $request->abc;
        $application->is_aplly_defense_quota = $is_aplly_defense_quota;
        $application->is_pwd = $is_pwd;
        $application->person_with_disablity = $request->pwd_explain;
        $application->pwd_percentage = $request->pwd_percentage;
        $application->bank_ac_no = $request->bank_ac_no;
        $application->family_income = $request->family_income;
        $application->bank_name = $request->bank_name;
        $application->branch_name = $request->branch_name;
        $application->ifsc_code = $request->ifsc_code;
        $application->bank_reg_mobile_no = $request->bank_reg_mobile_no;
        

        if ($is_kmigrant == true) {
            $application->km_details = $request->km_details;
        }
        if ($is_employed == true) {
            $application->employment_details = $request->employment_details;
        }
        if ($is_minority == true) {
            $application->minority_details = $request->minority_details;
        }
        if ($is_foreign == true) {
            $application->passport_number = $request->passport_number;
            $application->driving_license_equivalnet_no = $request->driving_license_equivalnet_no;
        }
        $application->save();

        $academic_information = $application->application_academic;
        // new addition 07-01-2021
        $academic_information = $application->application_academic;
            
        $is_full_time = $request->is_full_time;
        if(is_string($is_full_time)){
            $is_full_time = $is_full_time == "true";
        }
        $academic_data = [
            "is_full_time"         => $is_full_time,
            "part_time_details"    => !$is_full_time ? $request->part_time_details : null,
            "academic_experience"  => $request->academic_experience,
            "publication_details"  => $request->publication_details,
            "statement_of_purpose" => $request->statement_of_purpose,
            "bank_acc"             => $request->bank_acc,
            "pan_no"               => $request->pan_no,
            "branch_name"          => $request->branch_name,
            "ifsc_no"              => $request->ifsc_no,
            "branch_code"          => $request->branch_code,
            "passed_or_appeared_qualified_exam"          => $request->passed_or_appeared_qualified_exam,
        ];
        if($academic_information){
            $academic_information->update($academic_data);
        }else{
            $application->application_academic()->create($academic_data);
        }
        if($application->is_editable==1){
            $this->removeIsEditable($application->id);
        }
        // Application::generateApplicationNo($application);
        $applicationget = Application::where('id', $application->id)->with(['other_qualifications',"application_academic", "extraExamDetails", "applied_courses", "work_experiences"])->first();
        return response()->json(['application' => $applicationget], 200);
    }

    public function loadOldApplication($id)
    {
        $application = Application::where('id', $id)->with(['other_qualifications','cuet_exam_details',"application_academic", "extraExamDetails", "applied_courses", "work_experiences"])->first();
        $program = AppliedCourse::where('application_id', $application->id)->first()->course->program;
        $course_type = AppliedCourse::where('application_id', $application->id)->first()->course->course_type;
        $course_typeII = AppliedCourse::where('application_id', $application->id)->get();
        $program_list = AppliedCourse::where('application_id', $application->id)->first();
        $already_upload=ApplicationAttachment::where('application_id',$application->id)->get();
        $misc_documents = MiscDocument::where('application_id',$id)->get();
        return response()->json(['application' => $application, 'program' => $program, 'course_type' => $course_type,'program_list'=>$program_list,'course_typeII' => $course_typeII,'already_upload'=>$already_upload,'misc_documents'=>$misc_documents]);
    }
    
    public function loadDistricts($id)
    {
        $districts = District::where('state_id',$id)->get();
        return response()->json(['districts'=>$districts]);
    }

    public function loadCuetSubjects(Request $request,$id)
    {
        $application=Application::where('id',$id)->first();
        $type="UG";
        if($application->is_cuet_ug==1){
            $type="UG";
        }elseif($application->is_cuet_pg==1){
            $type="PG";
        }
        $applied_course = [];
        foreach($application->applied_courses as $appp){
            array_push($applied_course, $appp->course_id);
        }
        if($application->exam_through=='CHINESE'){
            $cuet_subject = CuetSubject::where('status',1)
                            // ->where('subject_name','!=','English')
                            ->where('course_type',$type)
                            ->whereIn('course_id',$applied_course)
                            ->get();
        }else{
            $cuet_subject = CuetSubject::where('status',1)
                            ->where('subject_name','!=','English')
                            ->where('course_type',$type)
                            ->whereIn('course_id',$applied_course)
                            ->get();
        }

        return response()->json(['cuet_subject' => $cuet_subject]);
    }

    public function loadAreaOfResearch(Request $request,$id)
    {
        $application=Application::where('id',$id)->first();
        if($application->application_academic->proposed_area_of_research == null){
            $area_of_researchs = AreaOfResearch::where('course_id',$application->applied_courses[0]->course_id)
            ->get();
            return response()->json(['area_of_researchs' => $area_of_researchs]);
        }else{
            $area_of_researchs = AreaOfResearch::where('course_id',$application->applied_courses[0]->course_id)
            ->get();
            return response()->json(['area_of_researchs' => $area_of_researchs]);
        }
        
        
    }

    public function loadStep1Data($type)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        // $course_types = CourseType::orderBy('name', 'asc')->with(['courses' => function ($query) {
        //     $query->orderBy('name', 'asc');
        // }, "courses.program"])->get();
        $user = Auth::User();
        $program_filter = $user->program_name;
        if($user->is_mba==1){
            $program_filter = "MBA";
        }
        
        if($user->program_name=="FOREIGN"){         
            if($type=="TUEE"){
                $program_array = ['PG','PHD','BDES','MDES'];
            }
        }
        else if(in_array($user->program_name,["BTECH","UG"])){         
            // if($type=="TUEE"){
                $program_array = ['BTECH','UG'];
            // }
        }
        else if($user->program_name=="PHDPROF"){         
            if($type=="TUEE"){
                $program_array = ['PHD'];
            }
        }else{
            $program_array = explode(",", $user->program_name);
        }
        

        $course_types =Program::with(['courses' => function ($query) {
            $query->orderBy('name', 'asc');
        }, "courses.program"])->where("publish_status",1)->whereIn('type',$program_array)
        /* ->where('type',$program_filter) */->get();

        $castes = Caste::orderBy('order_id', 'asc')->get();
        $sports = DB::table('sports')->get();
        $cuet_subject = CuetSubject::where('status',1)->where('subject_name','!=','English')->get();
        
        $country = Country::find($user->country_id);
        $centers = ExamCenter::select('id', DB::raw("CONCAT(center_name, ' (', state, ')') AS center_name"));
        if ($country->code == 'IN') {
            $is_foreign = '0';
            // $centers->where("center_code", "<", 200); 
            $centers->whereNotIn("id", ["31", "32", "47", "48", "49"]);
        } else {
            $is_foreign = '1';
            $centers->whereIn("id", ["31", "32", "47", "48", "49"]);
            // if ($country->code == 'BAN') {
            //     $centers->whereIn("center_code", ["201", "202", "203", "122", "101", "128", "113", "114"]);
            // } elseif ($country->code == 'BHU') {
            //     $centers->whereIn("center_code", ["201", "202", "203", "122", "129", "113", "114", "108"]);
            // } elseif ($country->code == 'NEP') {
            //     $centers->whereIn("center_code", ["201", "202", "203", "122", "129", "113", "114", "108"]);
            // }
        }

        $income_ranges = IncomeRange::orderBy('id', 'asc')->get();
        if(Auth::User()->program_name=='MBA'){
            $parents_qualification=ParentsQualification::where('is_mba',1)->get();
        }else{
            $parents_qualification=ParentsQualification::where('is_mba',0)->get();
        }
        
        $priorities = Priority::orderBy('orderby', 'asc')->get();
        $centers = $centers->orderBy('center_name', 'asc')->get();
        //$centers = ExamCenter::where('id', 35)->withTrashed()->get();
        // $centers->push($centers_na);
        $count = Auth::user()->application->count();
        $indian_states = State::get();
        $via_exam = $user->exam_through;
        $employements = PhdEmployeement::select('name')->get();
        return response()->json(['course_types' => $course_types, 'departments' => $departments, 'castes' => $castes, 'sports' => $sports, 'is_foreign' => $is_foreign, 'user' => $user, 'income_ranges' => $income_ranges, 'priorities' => $priorities, 'centers' => $centers, 'count' => $count,'cuet_subject' =>$cuet_subject,'indian_states' =>$indian_states,'parents_qualification'=>$parents_qualification,'program_name'=>$program_filter,'via_exam'=>$via_exam, 'employements'=>$employements]);
    }

    public function downloadAdmitCard($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
        }
        try {
            $application = Application::with("caste", "attachments", "session", "admit_card_published")->find($decrypted_id);
            if (get_guard() == "student") {
                $active_session_application = getActiveSessionApplication();
            }
            if (!$application->admit_card_published) {
                abort(404);
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route(get_guard() . ".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        $admit_card = $application->admit_card_published;
        // return view("common/application/admit_card/admit_card_download", compact("admit_card"));
        $pdf = PDF::loadView("common/application/admit_card/admit_card_download", compact("admit_card"));
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Admit-card-" . $admit_card->application->id . '.pdf');
    }

    public function submitStepFour(Request $request)
    {

        // return response()->json([
        //     "status" => false,
        //     "message" => $request->all(),
        // ], 500);
        try {
            $decrypted_id = $request->application_id;
            $application = Application::with("attachments")->find($decrypted_id);
            $message = "Editing Application No {$application->id} Step 4.";
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return response()->json([
                "status" => false,
                "message" => "APPLICATION ID NOT FOUND",
            ], 500);
        }



        // New addition
        if($request->misc_documents!=null){
            $destinationPath = public_path('uploads/' . $application->student_id . "/" . $application->id);
            foreach($request->misc_documents as $key=>$misc){
                $passport_photo = $misc;
                $file_name = $request->misc_documents_name[$key];
                $passport_photo_name = date('YmdHis') . "_" . rand(4512, 6859) . $file_name .".". $passport_photo->getClientOriginalExtension();
                $passport_photo->move($destinationPath . "/", $passport_photo_name);
                MiscDocument::updateOrCreate([
                                        'student_id'=> $application->student_id,	
                                        'application_id'=> $application->id,	
                                        "document_sl" => $key,
                                    ],['student_id'   => $application->student_id,	
                                      'application_id'=> $application->id,	
                                      "document_sl" => $key,
                                      'document_name'=>	$request->misc_documents_name[$key],
                                      'document_path'=> $destinationPath,
                                      'file_name'=> $passport_photo_name]);
            } 
        }
          
        // Ends



        if (!applicatinEditPermission($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step four permission denied. Application id {$application->id}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. You don't have the permission to edit other application.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if (!isEditAvailable($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Step four Option no longer available Application no {$application->id}, Status: {$application->status}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. Edit option not available.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option not available.");
        }
        // $validator = Validator::make($request, $this->giveMeFilesRule($application), $this->giveMeFilesValidationMessage());
        $this->validate($request, $this->giveMeFilesRule($application), $this->giveMeFilesValidationMessage());
        // dd($rules);
        DB::beginTransaction();
        $old_application_form_step = $application->form_step;
        try {
            $uploaded_docs = $this->storeDocs($request, $application);
            $attachment_data = [];
            $deleted_condition = [];
            if ($uploaded_docs) {
                foreach ($uploaded_docs as $index => $doc) {
                    $attachment_data[] = [
                        "application_id" => $application->id,
                        "doc_name" => $doc["doc_name"],
                        "file_name" => $doc["file_name"],
                        "original_name" => $doc["original_name"],
                        "mime_type" => $doc["mime_type"],
                        "destination_path" => $doc["destination_path"],
                        "created_at" => current_date_time(),
                        "updated_at" => current_date_time(),
                    ];
                    $deleted_condition[] = $doc["doc_name"];
                }
            }
            if ($deleted_condition) {
                $application->attachments()->whereIn("doc_name", $deleted_condition)->delete();
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }
            //Declaration Accepted
            $application->diclaration_accept = $request->get("accept") ? 1 : 0;

            if ($application->form_step == 3) {
                $application->form_step = 4;
            }
            $application->save();
            if($application->is_editable==1){
                $this->removeIsEditable($application->id);
            }


            
        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return response()->json([
                "status" => false,
                "message" => "Whoops! something went wrong.",
            ], 500);
        }
        DB::commit();
        $success_message = "Step 4 Successfully Updated.";
        if ($old_application_form_step == 3) {
            $success_message = "Step 4 Successfully saved. Please review and accept for payment process.";
        }
        return response()->json([
            "status" => false,
            "message" => $success_message,
        ]);
    }
    public function singleFileUpload(Request $request, Application $application)
    {
        if(!Gate::check('access-via-application', $application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "single image upload access denied application id ".$application->id);
            abort(403);
        }
        if (!applicatinEditPermission($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step four permission denied. Application id {$application->id}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. You don't have the permission to edit other application.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if (!isEditAvailable($application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Step four Option no longer available Application no {$application->id}, Status: {$application->status}");
            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => "Access Denied. Edit option not available.",
                ], 500);
            }
            return redirect()->route(get_guard() . ".home")->with("error", "Access Denied. Edit option not available.");
        }
        $this->validate($request, $this->giveMeFilesRule($application, true), $this->giveMeFilesValidationMessage());
        DB::beginTransaction();
        try {
            $uploaded_docs = $this->storeDocs($request, $application);
            $attachment_data = [];
            $deleted_condition = [];
            if ($uploaded_docs) {
                foreach ($uploaded_docs as $index => $doc) {
                    $attachment_data[] = [
                        "application_id" => $application->id,
                        "doc_name" => $doc["doc_name"],
                        "file_name" => $doc["file_name"],
                        "original_name" => $doc["original_name"],
                        "mime_type" => $doc["mime_type"],
                        "destination_path" => $doc["destination_path"],
                        "created_at" => current_date_time(),
                        "updated_at" => current_date_time(),
                    ];
                    $deleted_condition[] = $doc["doc_name"];
                }
            }
            if ($deleted_condition) {
                $application->attachments()->whereIn("doc_name", $deleted_condition)->delete();
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }
        } catch (Exception $e) {
            DB::rollback();
            // dd($e);
            Log::emergency($e);
            return response()->json([
                "status" => false,
                "message" => "Whoops! something went wrong.",
            ], 500);
        }
        DB::commit();
        $already_upload=ApplicationAttachment::where('application_id',$application->id)->get();
        $success_message = "Document uploaded successfully.";
        return response()->json([
            "status" => false,
            "message" => $success_message,
            "already_upload"  => $already_upload,
        ]);
    }
    public function giveMeFilesRule($application, $withOutExtraChecking = false)
    {
        //  $required = "required";
        $required = "nullable";
        if ($application->form_step >= 4) {
            $required = "nullable";
        }

        // if ($application->is_cuet_pg ==1 && $application->form_step == 4) {
        //     $pg_required = "required";
        // }

        $rules = [
            'passport_photo' => "{$required}|image|mimes:jpeg,png|max:100"/* |dimensions:max_width=200,max_height=250" */,
            'signature' => "{$required}|image|mimes:jpeg,png|max:100|dimensions:max_width=200,max_height=150",
            "caste_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "sport_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "undertaking" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "jee_admit_card" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "net_slet_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "noc_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "ex_serviceman_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "category_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "gate_score_card" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "document_driving_license" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "document_passing_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "document_marksheet" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "prc" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "bpl_card" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "ssn_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "additional_document" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "document_english_proficiency_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "ews_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "document_passport" => "{$required}|mimes:pdf|max:1024",
            "pwd_certificate" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "ceed_score_card" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "gate_b_score_card" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            // "bpl_aay_document" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "cuet_admit_card" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "cuet_score_card"     => "{$required}|mimes:jpeg,png,pdf|max:1024",
            "portfolio" => "{$required}|mimes:pdf|max:5120",
            "undertaking_pass_appear" => "{$required}|mimes:jpeg,png,pdf|max:1024",
            'class_x_documents'          => "{$required}|mimes:pdf|max:1024",    
            'class_XII_documents'        => "{$required}|mimes:pdf|max:1024",    
            'graduation_documents'       => "{$required}|mimes:pdf|max:1024",   
            'post_graduation_documents'  => "{$required}|mimes:pdf|max:1024",
        ];
        if($withOutExtraChecking){
            return $rules;
        }
        // Validation checkFshow
        // Caste uploaded (non General)
        $errors = [];
        if(!$application->attachments->where("doc_name", "passport_photo")->count()){
            $rules["passport_photo"] = str_replace("nullable", "required",$rules["passport_photo"]);
        }
        if(!$application->attachments->where("doc_name", "signature")->count()){
            $rules["signature"] = str_replace("nullable", "required",$rules["signature"]);
        }


        if(!$application->attachments->where("doc_name", "class_x_documents")->count() && $application->is_mba==0){
            $rules["class_x_documents"] = str_replace("nullable", "required",$rules["class_x_documents"]);
        }
        if(!$application->attachments->where("doc_name", "class_XII_documents")->count() && $application->is_mba==0 && $application->is_cuet_ug==0 && $application->is_laterall==0){
            // if($application->application_academic->academic_12_is_passed_appearing && $application->is_cuet_ug)
            $rules["class_XII_documents"] = str_replace("nullable", "required",$rules["class_XII_documents"]);
        }
        
        if($application->is_btech){
            if(!$application->attachments->where("doc_name", "prc_certificate")->count()){
                $rules["prc"] = str_replace("nullable", "required",$rules["prc"]);
            }

            if(!$application->attachments->where("doc_name", "jee_admit_card")->count()){
                $rules["jee_admit_card"] = str_replace("nullable", "required",$rules["prc"]);
            }
        }
        

        if(/* $application->is_cuet_pg ==1 || */ $application->is_phd ==1){
            if(!$application->attachments->where("doc_name", "graduation_documents")->count()){
                $rules["graduation_documents"] = str_replace("nullable", "required",$rules["graduation_documents"]);
            }
        }


        if($application->is_phd ==1){
            if(!$application->attachments->where("doc_name", "post_graduation_documents")->count()){
                $rules["post_graduation_documents"] = str_replace("nullable", "required",$rules["post_graduation_documents"]);
            }
        }

        if($application->exam_through =='CUET' && $application->is_cuet_pg){
            if(!$application->attachments->where("doc_name", "cuet_admit_card")->count()){
                $rules["cuet_admit_card"] = str_replace("nullable", "required",$rules["cuet_admit_card"]);
            }
            if(!$application->attachments->where("doc_name", "cuet_score_card")->count()){
                $rules["cuet_score_card"] = str_replace("nullable", "required",$rules["cuet_score_card"]);
            }
        }
        // Disablity Certificate
        if($application->is_pwd){
            if(!$application->attachments->where("doc_name", "pwd_certificate")->count()){
                $rules["pwd_certificate"] = str_replace("nullable", "required",$rules["pwd_certificate"]);
            }
        }

        // Ex-Serviceman certificate
        if($application->is_ex_servicement){
            if(!$application->attachments->where("doc_name", "ex_serviceman_certificate")->count()){
                $rules["ex_serviceman_certificate"] = str_replace("nullable", "required",$rules["ex_serviceman_certificate"]);
            }
        }

        // is-employee certificate
        // if($application->is_employed){
        //     if(!$application->attachments->where("doc_name", "noc_certificate")->count()){
        //         $rules["noc_certificate"] = str_replace("nullable", "required",$rules["noc_certificate"]);
        //     }
        // }
        // BPL/ AAY certificate
        if($application->is_bpl){
            if(!$application->attachments->where("doc_name", "bpl_card")->count()){
                $rules["bpl_card"] = str_replace("nullable", "required",$rules["bpl_card"]);
            }
        }

        // Sport Certificate
        if($application->application_academic->is_sport_represented){
            if(!$application->attachments->where("doc_name", "sport_certificate")->count()){
                $rules["sport_certificate"] = str_replace("nullable", "required",$rules["sport_certificate"]);
            }
        }
        
        // for Master in Design Application
        if(isMasterInDesign($application)){
            if(!$application->attachments->where("doc_name", "portfolio")->count()){
                $rules["portfolio"] = str_replace("nullable", "required",$rules["portfolio"]);
            }
            if(Auth::user()->exam_through=='CEED'){
                if(!$application->attachments->where("doc_name", "ceed_score_card")->count()){
                $rules["ceed_score_card"] = str_replace("nullable", "required",$rules["ceed_score_card"]);
            }
            }
            
        }
        // Foreign Student Checking
        if($application->is_foreign){
            if(!$application->attachments->where("doc_name", "document_passport")->count()){
                $rules["document_passport"] = str_replace("nullable", "required",$rules["document_passport"]);
            }
            if(!$application->attachments->where("doc_name", "document_english_proficiency_certificate")->count()){
                $rules["document_english_proficiency_certificate"] = str_replace("nullable", "required",$rules["document_english_proficiency_certificate"]);
            }
            // if(!$application->attachments->where("doc_name", "document_passing_certificate")->count()){
            //     $rules["document_passing_certificate"] = str_replace("nullable", "required",$rules["document_passing_certificate"]);
            // }
            // if(!$application->attachments->where("doc_name", "document_marksheet")->count()){
            //     $rules["document_marksheet"] = str_replace("nullable", "required",$rules["document_marksheet"]);
            // }
        }else{  
            //   for indian student.only 
            if($application->caste_id != 1 && $application->caste_id != 3){
                if(!$application->attachments->where("doc_name", "caste_certificate")->count()){
                    $rules["caste_certificate"] = str_replace("nullable", "required",$rules["caste_certificate"]);
                }
                if(!$application->attachments->where("doc_name", "undertaking")->count() && $application->is_mba==0){
                    $rules["undertaking"] = str_replace("nullable", "required",$rules["undertaking"]);
                }
            }
            if(/* $application->is_btech || */ (isMBBT($application) && $application->isNorthEastCandidate())){
                if(!$application->attachments->where("doc_name", "prc_certificate")->count()){
                    $rules["prc"] = str_replace("nullable", "required",$rules["prc"]);
                }
            }
            if(!$application->attachments->where("doc_name", "undertaking_pass_appear")->count() && $application->is_mba==0){
                $rules["undertaking_pass_appear"] = str_replace("nullable", "required",$rules["undertaking_pass_appear"]);
            }
        }

        if($errors){
            $error_string = implode("<br>", $errors);
            return redirect()->back()->withError($errors)->with("error","<strong>Please fullfil the required criteria to final submit</strong>.<br>".$error_string);
            // dd($errors);
        }
        return $rules;
    }
    public function giveMeFilesValidationMessage()
    {
        return [
            "caste_certificate.image" => "Category Certificate must be an image.",
            "caste_certificate.max" => "Category Certificate max 1MB Allowed.",
        ];
    }

    public function resubmitDocumentsPost(Request $request)
    {
        

        try {
            $decrypted_id = $request->application_id;
            $application = Application::with("attachments")->find($decrypted_id);
            $message = "Document Resubmitting. On Hold application {$application->id}.";
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                "status" => false,
                "message" => "Document Resubmitting Permission Denied.",
            ], 500);
        }
        if (!$application->resubmit_allow || $application->status != "on_hold") {
            $message = "Document Resubmitting Permission Denied for Application no {$application->id}";
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
            return response()->json([
                "status" => false,
                "message" => "Document Resubmitting Permission Denied.",
            ], 500);
        }
        // $validator = Validator::make($request, $this->giveMeFilesRule($application), $this->giveMeFilesValidationMessage());
        $this->validate($request, $this->giveMeFilesRule($application), $this->giveMeFilesValidationMessage());
        DB::beginTransaction();
        try {
            $uploaded_docs = $this->storeDocs($request, $application);
            $attachment_data = [];
            $deleted_condition = [];
            if ($uploaded_docs) {
                foreach ($uploaded_docs as $index => $doc) {
                    $attachment_data[] = [
                        "application_id" => $application->id,
                        "doc_name" => $doc["doc_name"],
                        "file_name" => $doc["file_name"],
                        "original_name" => $doc["original_name"],
                        "mime_type" => $doc["mime_type"],
                        "destination_path" => $doc["destination_path"],
                        "created_at" => current_date_time(),
                        "updated_at" => current_date_time(),
                    ];
                    $deleted_condition[] = $doc["doc_name"];
                }
            }
            if ($deleted_condition) {
                $application->attachments()->whereIn("doc_name", $deleted_condition)->delete();
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }
            //Declaration Accepted
            $application->status = "document_resubmitted";
            $application->resubmit_allow = 0;
            $application->save();
        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return response()->json([
                "status" => false,
                "message" => "Whoops! something went wrong.",
            ], 500);
        }
        DB::commit();
        $success_message = "Document Successfully Submitted.";
        return response()->json([
            "status" => true,
            "message" => $success_message,
        ]);
    }
    public function uploadExtraDocumentGet($encryptedValue)
    {
        
        try {
            $decrypted = Crypt::decrypt($encryptedValue);
            $application = Application::find($decrypted);
           
        } catch (\Throwable $th) {
            Log::error($th);
            abort(404, "Application not found.");
        }
        saveLogs(auth(get_guard())->id(),
                 auth(get_guard())->user()->name,
                 get_guard(), "Uploading extra documents.");
                //  dd("ok");
        if(!isAvailableForFileUpload($application)){
           
            abort(404);
            return redirect()
                ->route("student.application.index")
                ->with("error","Access denied.");
               
        }
        // if($this->isExtraDocUploaded($application)){
        //     return redirect()
        //         ->route("student.application.index")
        //         ->with("error", "Documents already uploaded.");
        // }
        // dd($application);
        return view("student.application.upload-extra-doc", compact("application"));
    }
    public function uploadExtraDocumentShow($encryptedValue)
    {
        try {
            $decrypted = Crypt::decrypt($encryptedValue);
            $application = Application::with("extra_documents")->find($decrypted);
        } catch (\Throwable $th) {
            Log::error($th);
            abort(404, "Application not found.");
        }

        return view("student.application.upload-extra-doc-view", compact("application"));
    }
    public function uploadExtraDocumentPost(Request $request, $encryptedValue)
    {
        // dd($request);
        try {
            $decrypted = Crypt::decrypt($encryptedValue);
            $application = Application::find($decrypted);
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()
                ->back()
                ->with("error", "Whoops! something went wrong try again later.");
        }

        $rules = $this->extraDocumentUploadingRules($application);
        $files = $request->docs;
        if($files){
            foreach ($files as $index => $request_file) {
                $additionalDeviceRules = [
                    'remarks.' .$index => 'required|min:10|max:500',
                ];
                $rules = array_merge($rules, $additionalDeviceRules);
            }
        }

        $validator = Validator::make(request()->all(), $rules);
        if($validator->fails()){
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all())
                ->with("error", "Please fix the issues and try again later.");
        }
        
        DB::beginTransaction();
        try {
            $destinationPath = public_path('uploads/' . $application->student_id . "/" . $application->id);
            $docs = $request->file('docs');
            $manyData = [];
            $counter = 1;
            if($docs){
                foreach ($docs as $key => $file) {
                    $file_name = date('YmdHis') . "_" . rand(0, 6859) . "-file_".$key."." . $file->getClientOriginalExtension();
                    $file->move($destinationPath . "/", $file_name);
                    $manyData[] = new ExtraDocument([
                        "doc_name"         => "File ".$counter,
                        "file_name"        => $file_name,
                        "original_name"    => $file->getClientOriginalName(),
                        "mime_type"        => $file->getClientMimeType(),
                        "destination_path" => $destinationPath,
                        "remark"           => $request->remarks[$key],
                    ]);
                    $counter++;
                }
                $application->extra_documents()->saveMany($manyData);
            }
            if(!$request->get("partial_upload")){
                $application->is_extra_doc_uploaded = true;
                $application->save();
            }
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Extra documents uploading failed.");
            return redirect()
                ->back()
                ->with("error", "Uploading failed.");
        }
        DB::commit();
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Extra documents uploaded.");

        return redirect()
            ->route("student.application.index")
            ->with("success", "Successfully uploaded.");
    }
    //Edit MBA form
    public function edit_mba_Form($encrypted_id){
        // dd("ok");
        $decrypted_id = Crypt::decrypt($encrypted_id);
        $data = ExtraExamDetail::where('application_id',$decrypted_id)->get();

        return View('student.application.edit-mba-form',compact('data'));

    }
    public function update_mba_Form(Request $request ){
    
        $count = count($request->id);
        for ($i = 0; $i < $count; $i++) {
            $id = $request->id[$i];

            $data = ExtraExamDetail::where('id', $id)->delete();

        }
        $count = count($request->application_id);

        for ($i = 0; $i < $count; $i++) {

            $data = [
                'application_id'   => $request->application_id[$i],
                'student_id'       => $request->student_id[$i],
                'name_of_the_exam' => $request->name_of_exam[$i],
                'score_obtained'   => $request->score_obtained[$i],
                'date_of_exam'     => $request->date_of_exam[$i],
                'registration_no'  => $request->registration_no[$i],
            ];
            ExtraExamDetail::create($data);
            Application::find($request->application_id[$i])->update(['is_mba_scorecard_update' => 1]);
        }

        return redirect('student/application');
    }
    
    private function extraDocumentUploadingRules(Application $application){
        $count = $application->extra_documents->count();
        $required = $count ? "nullable" : "required";
        $rules = [
            "remarks"   => "array|{$required}|min:1",
            "docs"      => "array|{$required}|min:1",
            "docs.1"    => "{$required}|file|mimes:pdf,jpg,png,jpeg|bail|verify_corrupted|max:1024",
            "remarks.1" => "{$required}|min:10|max:500|bail",
            "remarks.*" => "nullable|max:500",
            "docs.*"    => "nullable|file|mimes:pdf,jpg,png,jpeg|verify_corrupted|max:1024",
        ];
        return $rules;
        // checking for M.Design
        if(isMasterInDesign($application)){
            $rules = [
                "remarks"   => "array|required|min:1",
                "docs"      => "array|required|min:1",
                "docs.0"    => "required|file|mimes:pdf,jpg,png,jpeg|bail|verify_corrupted|max:5120",
                "remarks.0" => "required|min:10|max:500|bail",
                "docs.5"    => "required|file|mimes:pdf,jpg,png,jpeg|bail|verify_corrupted|max:5120",
                "remarks.5" => "required|min:10|max:500|bail",
                "docs.1"    => "required|file|mimes:pdf,jpg,png,jpeg|bail|verify_corrupted|max:5120",
                "remarks.1" => "required|min:10|max:500|bail",
                "remarks.*" => "nullable|max:500",
                "docs.*"    => "nullable|file|mimes:pdf,jpg,png,jpeg|verify_corrupted|max:5120",
            ];
        }
        return $rules;
    }

    public function loadSports(Request $request)
    {
        return response()->json(['data'=>'allapplication']);
    }

    public function removeIsEditable($id)
    {
        // $application= Application::where('id',$id)->update(['is_editable'=>0]);
        AppliedCourse::where(['application_id'=>$id, 'status'=>"on_hold"])->update(['updation_date'=>date('Y-m-d H:i:s'),
                                                                                    'is_updated'   =>1]);
    }

    public function changeExamCenter($encrypted_id)
    {
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments","applied_courses",'cuet_exam_details',"fatherQualification")->whereId($id);           
            if (auth()->guard("student")->check()) {
                $application = $application->where("student_id", auth()->guard("student")->user()->id);
            }
            $application = $application->first();           
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route("student.home")->with("error", "Whoops! Something went wrong.");
        }
        // dd($application);
        $user = Auth::User();
        $country = Country::find($user->country_id);
        $centers = ExamCenter::query();
        if ($country->code == 'IN') {
            $is_foreign = '0';
            $centers->where("center_code", "<", 200);
        } else {
            $is_foreign = '1';
            if ($country->code == 'BAN') {
                $centers->whereIn("center_code", ["201", "202", "203", "122", "101", "128", "113", "114"]);
            } elseif ($country->code == 'BHU') {
                $centers->whereIn("center_code", ["201", "202", "203", "122", "129", "113", "114", "108"]);
            } elseif ($country->code == 'NEP') {
                $centers->whereIn("center_code", ["201", "202", "203", "122", "129", "113", "114", "108"]);
            }
        }       
        $centers = $centers->orderBy('center_name', 'asc')->get();
        $centers_na = ExamCenter::where('id', 35)->withTrashed()->first();
        $centers->push($centers_na);
        return view('student.change-center',compact('application','centers'));
        // dd($application->exam_center_id);
    }

    public function updateExamCenter(Request $request,$encrypted_id)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments","applied_courses",'cuet_exam_details',"fatherQualification")->whereId($id);           
            if (auth()->guard("student")->check()) {
                $application = $application->where("student_id", auth()->guard("student")->user()->id);
            }
            $application = $application->first();           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route("student.home")->with("error", "Whoops! Something went wrong.");
        }
        Application::where('id',$id)->update(['exam_center_id'=>$request->center_id]);
        DB::commit();
        return redirect()->back()->with('success','Successfully Updated Examination Center');
        // dd("ok");
    }

    public function changePreference($encrypted_id)
    {
        $id = Crypt::decrypt($encrypted_id);
        $applied_course=AppliedCourse::where('application_id',$id)->orderBy('preference')->get();
        $max=AppliedCourse::where('application_id',$id)->count();
        // dd($applied_course);
        return view('student.change-preference',compact('applied_course','max'));
        // dd("ok");
    }

    public function savePreferenceChange(Request $request)
    {
        $new_preference=$request->new_preference;
        $set= array_unique($new_preference);
        $old_count=count($new_preference);
        $new_count=count($set);
        $max_pref=max($new_preference);
        $min_pref=min($new_preference);
        if($old_count>$new_count || $min_pref!=1 || $max_pref != $old_count){
            return redirect()->back()->with('error','Something went wrong!! You can`t enter same preference for two courses. Try again carefully');
        }
        foreach($request->applied_course_id as $key=>$course_id){
            // dump($course_id);
            AppliedCourse::where('id',$course_id)->update(['preference'=>$request->new_preference[$key]]);
        }
        return redirect()->back()->with('success','Successfully changed preference');
    }

    public function CUETForm(Request $request, $id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error','Something went wrong!!');
        }
        $application = Application::where('id',$decrypted)->first();
        $flag = 0;
        if($application->is_cuet_ug==1){
            $flag=DB::table('programs')->where('id',1)->first()->cuet_marks;
        }elseif($application->is_cuet_pg==1){
            $flag=DB::table('programs')->where('id',2)->first()->cuet_marks;
        }

        if($application->cuet_status==1){
            return redirect()->back()->with('error','Already Updated');
        }
        if ($application->exam_through =="CUET" && $flag){
            return view("common.application.cuet-marks", compact('application'));
        }else{
            return redirect()->back()->with('error','Something went wrong!!');
        }
    }  
    
    public function CUETSubmit(Request $request, $id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error','Something went wrong!!');
        }
         
        $request->validate([
            'cuet_app_no' => 'required|max:255',
            'cuet_roll_no' => 'required|max:255',
            'cuet_year' => 'required|max:255',
            "cuet_admit_card" => "required|mimes:jpeg,png,pdf|max:1024",
            "cuet_score_card"     => "required|mimes:jpeg,png,pdf|max:1024",
        ]);

        // $flag = 0;
        // $application =  Application::where('id',$decrypted)->first();       
        // foreach($application->applied_courses as $key=>$cour){
        //     for ($i = 0; $i < count($request->course_code); $i++) {
        //         if($request->marks[$i] || $request->percentile[$i]){
        //             $sub_name = CuetSubject::where('id',$request->course_code[$i])->first();
        //             if($sub_name->course_id == $cour->course_id){
        //                 $flag=1;
        //             }
        //         }
        //     }
        //     if($flag==0){
        //         dd("got it");
        //     }
        // }

        // dd("ok");

        DB::beginTransaction();
        try{
            $application =  Application::where('id',$decrypted)->first(); 
            $application->update([
                'cuet_roll_no' => $request->cuet_roll_no,
                'cuet_form_no' => $request->cuet_app_no,
                'cuet_year' => $request->cuet_year,
            ]);
                    

            if ($application->cuet_exam_details()) {
                $application->cuet_exam_details()->delete();
            }

            for ($i = 0; $i < count($request->course_code); $i++) {
                $sub_name = CuetSubject::where('id',$request->course_code[$i])->first()->subject_name;
                if($request->marks[$i] || $request->percentile[$i]){
                    // if ($request->marks[$i]) {
                    //     $validationRules = [
                    //         'marks.'.$i => ['regex:/^\d+\.\d{7}$/'],
                    //         // 'percentile.'.$i => ['regex:/^\d+\.\d{7}$/'],
                    //     ];
                    //     $validationMessages = [
                    //         'marks.'.$i.'.regex' => 'Each mark must have exactly 7 digits after the decimal point.',
                    //         // 'percentile.'.$i.'.regex' => 'Each percentile must have exactly 7 digits after the decimal point.',
                    //     ];
                    //     $validator = Validator::make($request->all(), $validationRules, $validationMessages);
            
                    //     if ($validator->fails()) {
                    //         return redirect()->back()->withInput()->withErrors($validator);
                    //     }
                    // }
                    $quali = CuetExamDetail::create([
                        'application_id' => $decrypted,
                        'student_id' => $application->student_id,
                        'subjects' => $sub_name,
                        'marks' => $request->marks[$i],
                        // 'percentile' => $request->percentile[$i],
                    ]);
                }
                
            }

            $uploaded_docs = $this->storeDocs($request, $application);
            $attachment_data = [];
            $deleted_condition = [];
            if ($uploaded_docs) {
                foreach ($uploaded_docs as $index => $doc) {
                    $attachment_data[] = [
                        "application_id" => $application->id,
                        "doc_name" => $doc["doc_name"],
                        "file_name" => $doc["file_name"],
                        "original_name" => $doc["original_name"],
                        "mime_type" => $doc["mime_type"],
                        "destination_path" => $doc["destination_path"],
                        "created_at" => current_date_time(),
                        "updated_at" => current_date_time(),
                    ];
                    $deleted_condition[] = $doc["doc_name"];
                }
            }
            if ($deleted_condition) {
                $application->attachments()->whereIn("doc_name", $deleted_condition)->delete();
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }

            $application->update(['cuet_status'=>1]);

            DB::commit();
        }catch(\Exception $e){
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error','Something went wrong');
        }
        
        return redirect()->route('student.home')->with('success','Successfully updated CUET score');

        // dd($request->all());
    }
}
