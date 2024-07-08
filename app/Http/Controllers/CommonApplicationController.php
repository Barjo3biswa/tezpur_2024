<?php

namespace App\Http\Controllers;

use App\AppliedCourse;
use App\Course;
use App\DepartmentAssignedUser;
use App\GroupMaster;
use App\Http\Controllers\Controller;
use App\Models\AdmitCard;
use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Models\Caste;
use App\Models\CuetExamDetail;
use App\Models\ExamCenter;
use App\Models\ExtraExamDetail;
use App\Models\Program;
use App\Models\Session;
use App\Notifications\EmailVerification;
use App\SubExamCenter;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Validator;
use App\Traits\VknrlPayment;
use App\Traits\ApplicationExport;
use App\Traits\ApplicationDownloader;
use App\TueeResult;
use Auth;
use CreedScore;
use Illuminate\Database\Eloquent\Builder;
use PDF;

class CommonApplicationController extends Controller
{
    use VknrlPayment, ApplicationExport, ApplicationDownloader;
    public $guard;
    public $user;
    public function __construct()
    {
        // $this->guard = get_guard();
        // $this->user = auth()->guard($this->guard)->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function applicationStatusFilter(Request $request, Builder $application)
    {

        $program_array=programmes_array();
        // dd($program_array);
        $programstest=[];
        foreach($program_array as $key=>$prog){
                if($key!=""){
                array_push($programstest, $key);
                }
        }
        
        if($request->has("status") && $request->get("status") != ""){
            if($request->get("status") != "all"){
                if(in_array($request->get("status"), ["application_submitted", "payment_pending"])){                  
                    $application->whereIn("status", ["application_submitted", "payment_pending"]);
                }elseif($request->get("status") === "admitted_student"){
                    $application->whereHas("admission_receipt", function($query){
                        return $query->where("status", 0);
                    });
                }elseif($request->get("status") === "accepted"){
                        $application = $application->whereHas("applied_courses", function($query) use($programstest) {
                            return $query->where("status", "accepted")->whereIn("course_id", $programstest);
                        })->with(["applied_courses"=>function($q) use($programstest){
                            return $q->where("status", "accepted")->whereIn("course_id", $programstest);
                    }]);
                }elseif($request->get("status") === "rejected"){
                    $application = $application->whereHas("applied_courses", function($query) use($programstest){
                        return $query->where("status", "rejected")->whereIn("course_id", $programstest);
                    })->with(["applied_courses"=>function($q) use($programstest){
                        return $q->where("status", "rejected")->whereIn("course_id", $programstest);
                    }]);
                }elseif($request->get("status") === "on_hold"){
                    // dd($request->all());
                    $application = $application->whereHas("applied_courses", function($query) use($programstest){
                        return $query->where("status", "on_hold")->whereIn("course_id", $programstest);
                    })->with(["applied_courses"=>function($q) use($programstest){
                        return $q->where("status", "on_hold")->whereIn("course_id", $programstest);
                    }]);
                }else{
                    $application->where("status", $request->get("status"));
                }
                    
            }
            if(auth("department_user")->check()){
                // dump($application->count());
                $depptt_user_id = auth("department_user")->id();
                $department_id = DB::table("department_assigned_users")->where('department_user_id',$depptt_user_id)->pluck("department_id");
                if($department_id[0]!=20){
                    $application->whereNotIn("status", ["application_submitted","payment_pending"]);
                }
                // dd($application->count());
            }
        }else{
            if(auth("admin")->check() || auth("department_user")->check()){
                $application->whereIn("status", Application::$statuses_for_admin);
                // dump("here2");
            }
        }
    }


    public function index(Request $request)
    {
        // dd("ok");
        // dd($request->all());
        $application = Application::with("caste", "attachments", "student", "admit_card_published", "rePaymentReceipt", "applied_courses", "applied_courses.course", "merit_list");
        $program=0;

        if($request->get("ug_pg")){
            
            if($request->get("ug_pg") == "is_cuet_ug"){
                // dd("ug");
                $application->where('is_cuet_ug',1);
            }elseif($request->get("ug_pg") == "is_cuet_pg"){
                $application->where('is_cuet_pg',1);
            }elseif($request->get("ug_pg") == "is_phd"){
                $application->where('is_phd',1);
            }
            // dd("ok");
        }

        if(isset($request->CUET)){
            // if($request->CUET=="UG"){
            //     $application->where('is_cuet_ug',1);
            // }elseif($request->CUET=="PG"){
            //     $application->where('is_cuet_pg',1);
            // }
            if($request->CUET == "UG"){
                $application->where('is_cuet_ug',1);
            }elseif($request->CUET == "PG"){
                $application->where('is_cuet_pg',1);
            }elseif($request->CUET == "B.Tech"){
                $application->where('is_btech',1);
            }elseif($request->CUET == "B.Tech Lateral"){
                $application->where('is_laterall',1);
            }elseif($request->CUET == "Ph.D."){
                $application->where('is_phd',1)->where('is_visves',0);
            }elseif($request->CUET =="BDES"){
                if($request->EXAM_THROUGH == "JEE"){
                    $application->where('is_btech',1);
                }else{
                    $application->where('is_bdes',1);
                }  
            }elseif($request->CUET =="MDES"){
                $application->where('is_mdes',1);
            }elseif($request->CUET =="Visvesvaraya"){
                $application->where('is_visves',1);
            }
        }
        $exam_type = [$request->EXAM_THROUGH];
        if($request->EXAM_THROUGH == 'TUEE'){
            $exam_type = ['TUEE','GATE'];
        }else if($request->EXAM_THROUGH == 'JEE'){
            $exam_type = ['JEE'];
        }

        if(isset($request->EXAM_THROUGH)){
           $application->whereIn('exam_through', $exam_type);
        }
        
        $this->applicationStatusFilter($request, $application);
        // dd($application->get());
        // dd(auth("department_user")->id());
        


        // dd($application->count());
        // $program_array=programmes_array();
        // $programstest=[];
        // foreach($program_array as $key=>$prog){
        //         if($key!=""){
        //         array_push($programstest, $key);
        //         }
        // }
        // $application = $application->whereHas("applied_courses", function($query) use ($programstest){
        //     $query->whereIn("course_id", $programstest);
        //     })->with(["applied_courses"=>function($q) use($programstest){
        //         return $q->whereIn("course_id", $programstest);
        // }]);


        if($request->get("country")){
            $application = $application->whereHas("student", function($query){
                return $query->where("country_id", "=", request("country"));
            });
        }
        if($request->has("caste") && $request->get("caste") != ""){
            $application = $application->where("caste_id", $request->get("caste"));
        }
        if($request->has("application_id") && $request->get("application_id") != ""){
            $application = $application->where("application_no",$request->get("application_id"));
        }
        if($request->has("registration_no") && $request->get("registration_no")){
            $application = $application->where("student_id", $request->get("registration_no"));
        }
        if($request->has("gender") && $request->get("gender")){
            $application = $application->where("gender", $request->get("gender"));
        }

        if($request->has("center_id") && $request->get("center_id")){
            $application = $application->where("exam_center_id", $request->get("center_id"));
        }

        if($request->has("session") && $request->get("session")){
            $application = $application->where("session_id", $request->get("session"));
        }

        if($request->has("type") && $request->get("type")=="TUEE"){
            $application = $application->where("exam_through", "TUEE")->whereIn('net_jrf',[0,2]);
        }

        if($request->has("type") && $request->get("type")=="QNLT"){
            $application = $application->where("exam_through", "TUEE")->whereIn('net_jrf',[1]);
        }


        if($request->has("doc_uploaded")){
            if($request->get("doc_uploaded") === "uploaded"){
                $application = $application->where("is_extra_doc_uploaded", 1);
            }
            if ($request->get("doc_uploaded") === "not_uploaded") {
                $application = $application->where("is_extra_doc_uploaded", 0);
            }

        }
        if($request->has("department") && $request->get("department")){
            $application->whereHas("courses", function($course_query){
                return $course_query->where("department_id", request("department"));
            });
        }
        
        $statusarr=['accepted','on_hold','rejected','NULL'];
        if($request->has("status")&& $request->get("status") == "accepted" || $request->get("status") == "on_hold" || $request->get("status") == "rejected"){
            $statusarr=[$request->get("status")];
        }
        // dd($statusarr);
        
        if($request->has("program") && $request->get("program")){
            $program = request("program");
            
            if($program==83){
                $arr=[72,73,74,75,76,77];
                $application = $application->whereHas("applied_courses", function($query) use ($arr,$statusarr,$request){
                    $query->whereIn("course_id", $arr)/* ->whereIn('status',$statusarr) */
                        ->when($request->has("status") && $request->get("status")=='accepted' || $request->get("status")=='rejected' || $request->get("status")=='on_hold', function ($qu) use ($request) {
                            return $qu->where('status', $request->status);
                                
                        });
                })->with(["applied_courses"=>function($q) use($arr,$request){
                return $q->whereIn("course_id", $arr)/* ->whereIn('status',$statusarr) */
                    ->when($request->has("status") && $request->get("status")=='accepted' || $request->get("status")=='rejected' || $request->get("status")=='on_hold', function ($qu) use ($request) {
                        return $qu->where('status', $request->status);
                            
                    });
                }]);
            }else{
                $arr=[$program];
                $application = $application->whereHas("applied_courses", function($query) use ($arr,$statusarr,$request){
                    $query->whereIn("course_id", $arr)/* ->whereIn('status',$statusarr) */
                        ->when($request->has("status") && $request->get("status")=='accepted' || $request->get("status")=='rejected' || $request->get("status")=='on_hold', function ($qu) use ($request) {
                            return $qu->where('status', $request->status);
                                
                        });
                })->with(["applied_courses"=>function($q) use($arr,$request){
                return $q->whereIn("course_id", $arr)/* ->whereIn('status',$statusarr) */
                    ->when($request->has("status") && $request->get("status")=='accepted' || $request->get("status")=='rejected' || $request->get("status")=='on_hold', function ($qu) use ($request) {
                        return $qu->where('status', $request->status);
                            
                    });
                }]);

            }
            
        }
        if($request->has("exam_center_id")){
            if($request->exam_center_id!=null){
                $application = $application->where("exam_center_id",$request->exam_center_id);
            }          
        }
        /////////////////

        if($request->has("is_phd")){        
            $application = $application->where("is_phd",1);                 
        } 
        if($request->has("is_btech")){        
            $application = $application->where("is_btech",1);                 
        }
        if($request->has("is_laterall")){        
            $application = $application->where("is_laterall",1);                 
        }
        if($request->has("is_mdes")){        
            $application = $application->where("is_mdes",1);                 
        }
        if($request->has("is_phd_prof")){        
            $application = $application->where("is_phd_prof",1);                 
        }
        if($request->has("is_bdes")){        
            $application = $application->where("is_bdes",1);                 
        }
        if($request->has("is_chinese")){        
            $application = $application->where("is_chinese",1);                 
        }

        ////////////////
        

        if($request->get("payment_date_from")){
            $application = $application->whereHas("online_payments_succeed", function($query) use ($request){
                return $query->whereDate("created_at",">=", dateFormat($request->payment_date_from, "Y-m-d"));
            });
        }
        if($request->get("payment_date_to")){
            $application = $application->whereHas("online_payments_succeed", function($query) use ($request){
                return $query->whereDate("created_at","<=", dateFormat($request->payment_date_to, "Y-m-d"));
            });
        }
        $application = applicant_global_filter($application);
        if (auth("student")->check()) {
            $applications = $application->where("student_id", auth("student")->id());
        }
        
        if (auth("department_user")->check()) {
            $applications = application_dept_filters($application);

            //this block is inserted 07-04-23 coz there are applications of two sessions 
            if(!$request->has("session") && !$request->get("session")){
                $ses = Session::where('is_active',1)->first()->id;           
                if(in_array(auth("department_user")->id(), [168, 170, 171,172])){
                    // $session=[$ses,$ses-1];
                    $session=[$ses];
                }else{
                    // $session=[$ses,$ses-1];
                    $session=[$ses];
                }
                $applications = $applications->whereIn('session_id',$session);
            }
            
            //ends
        }
        dd($application->get());
        $applications = $application->whereNull("deleted_at");
        // $this->applicationStatusFilter($request, $application);
        $castes = Caste::all();
        $sessions = Session::whereHas("applications")->pluck("name",  "id");

        // if($request->get("visvesvaraya")){
            
        //     $application = $application->whereDate("created_at",">=", "2023-09-14")->where('session_id',11);
        //     // dd($application->get());
        // }

        if($request->get("Qualified")){
            $application->whereIn('net_jrf',[1]);
            // if ($request->get("Qualified")) {
            //     $application->whereHas('isNetJrfQualified', function ($query) {
            //         $query->where(function ($query) {
            //             $query->where('exam_name', 'like', '%NET%')
            //                   ->orWhere('exam_name', 'like', '%JRF%')
            //                   ->orWhere('exam_name', 'like', '%net%')
            //                   ->orWhere('exam_name', 'like', '%jrf%');
            //         });
            //     })
            //     ->orWhereHas('isNetJrfQualifiedSecond', function ($query) {
            //         $query->whereIn('qualified_national_level_test', [
            //             "UGCNET/JRF",
            //             "UGC-CSIR",
            //             "NET/JRF",
            //             "UGC-CSIR-NET/JRF",
            //             "DBT- JRF",
            //             "ICAR-NET",
            //             "ICMR-JRF",
            //             "NETLS",
            //             "GATE",
            //             "SLET"
            //         ]);
            //     });
            // }
        }

        
        
        if($request->has("export-data")){
            // $applications = $application->get();
            return $this->ExportApplicationData($applications, $request);
        }elseif($request->get("export") == "zip"){
            return $this->ExportApplicationAsZip($applications);
        }else
            $applications = $application->paginate(100);
        // dd($applications);
        return view($this->getIndexView(), compact("applications", "castes", "sessions","program"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        $application_type=Crypt::decrypt($request->id);
        // dd($application_type);
        $active_session = getActiveSession();
        $active_session_application = getActiveSessionApplication();
        if ($active_session->name == "NA") {
            return redirect()->route("student.home")->with("error", "Online Application is not started for the current session.");
        }
        // if($active_session_application){
        //     return redirect()->route("student.home")->with("error", "Application is already submited. <a target='_blank' href='".route("student.application.show", Crypt::encrypt($active_session_application->id))."'>Click Here to View.</a>");
        // }
        $castes = Caste::all();
        $mode  = ['mode' => 'create'];
        $count = Auth::user()->application()->whereNull("deleted_at")->count();
        $application_id = 0;
        if($count > 0 && $count<3){
           $application = Auth::user()->application()->orderBy('id','desc')->first();
           $application_id = $application->id;
        }else{
            if($count>=20){
                return redirect()->back()->with('error','Maximum applications limit is three.');
            }
        }

        // is closed validation
        $prog_name = Auth::user()->program_name;
        if($prog_name == "PHD"){
            $application_type = 'NET_JRF';
        }/* else if($prog_name == "PG"){
            $application_type = 'GATE';
        } */
        if($prog_name=="PHDPROF" || $prog_name == "VISVES"){
            $prog_name = "PHD";
        }

        if($prog_name=="JOSSA"){
            $prog_name = "BTECH";
        }

        if($prog_name!="FOREIGN"){
            $flag = Program::where('type',$prog_name)->first();
            // dd($application_type);
            if($flag->$application_type==0){
                return redirect()->back()->with('error','Application Process is already closed.');
            }
        }
        
        // end
        return view("student.application.create", compact("active_session", "castes","mode","count","application_id","application_type"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $active_session = getActiveSession();
        $active_session_application = getActiveSessionApplication();
        if ($active_session->name == "NA") {
            return redirect()->route("student.home")->with("error", "Online Application is not started for the current session.");
        }
        if ($active_session_application) {
            return redirect()->route("student.home")->with("error", "Application is already submited. <a target='_blank' href='" . route("student.application.show", Crypt::encrypt($active_session_application->id)) . "'>Click Here to View.</a>");
        }
        $rules = $this->getRules("personal_information");
        if((Int)$request->get("anm_or_lhv")){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm_or_lhv");
        }
        $validator = Validator::make($request->all(), $rules);

        DB::beginTransaction();
        try {
            if ($validator->fails()) {
                Log::error($validator->errors());
                // dd($validator->errors());
                return redirect()->route("student.application.create")->withInput($request->all())->withErrors($validator);
            }
            $lowerLimit = Application::$applicant_lower_age_limit;
            $upperLimit = Application::$applicant_upper_age_limit;

            $Lower_limit_extended_dob = strtotime($request->dob . "+ {$lowerLimit} years");
            $upper_limit_extended_dob = strtotime($request->dob . "+ {$upperLimit} years");

            $limit_date = strtotime(Application::$dob_compare_to);

            if ($Lower_limit_extended_dob > $limit_date) {
                $validator->errors()->add('dob', "Age minimum limit is {$lowerLimit} years.");
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }

            if ($upper_limit_extended_dob < $limit_date) {
                $validator->errors()->add('dob', "Age maximum limit is {$upperLimit} years.");
                return redirect()->route("student.application.create")->withInput($request->all())->withErrors($validator);
            }

            // dump($validator->errors());
            

            $application_data = [
                "fullname"          => $request->get("fullname"),
                "gender"            => $request->get("gender"),
                "student_id"        => auth()->guard("student")->user()->id,
                "father_name"       => $request->get("father_name"),
                "father_occupation" => $request->get("father_occupation"),

                "mother_name"       => $request->get("mother_name"),
                "mother_occupation" => $request->get("mother_occupation"),
                "marital_status"    => $request->get("maritial_status"),
                "religion"          => $request->get("religion"),
                "nationality"       => $request->get("nationality"),
                "dob"               => dateFormat($request->get("dob"), "Y-m-d"),
                "caste_id"          => $request->get("caste"),
                "session_id"        => $active_session->id,
                "form_step"         => 1,
                "person_with_disablity"         => $request->get("disablity"),
                "anm_or_lhv"         => $request->get("anm_or_lhv"),
                "anm_or_lhv_registration"         => $request->get("anm_or_lhv_registration"),
                // "bpl"               => $request->get("bpl"),
            ];
            $application = Application::create($application_data);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()->withInput($request->all())->with("error", "Whoops! something went wrong.");
        }
        DB::commit();
        return redirect()->route("student.application.edit", Crypt::encrypt($application->id))->with("success", "Step 1 form saved successfully.");
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOld(Request $request)
    {
        $active_session = getActiveSession();
        $active_session_application = getActiveSessionApplication();
        if ($active_session->name == "NA") {
            return redirect()->route("student.home")->with("error", "Online Application is not started for the current session.");
        }
        if ($active_session_application) {
            return redirect()->route("student.home")->with("error", "Application is already submited. <a target='_blank' href='" . route("student.application.show", Crypt::encrypt($active_session_application->id)) . "'>Click Here to View.</a>");
        }
        $rules = Application::$rules;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->route("student.application.create")->withInput($request->all())->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $application_data = [
                "fullname" => $request->get("fullname"),
                "gender" => $request->get("gender"),
                "student_id" => auth()->guard("student")->user()->id,
                "father_name" => $request->get("father_name"),
                "father_occupation" => $request->get("father_occupation"),

                "mother_name" => $request->get("mother_name"),
                "mother_occupation" => $request->get("mother_occupation"),
                "marital_status" => $request->get("maritial_status"),
                "religion" => $request->get("religion"),
                "nationality" => $request->get("nationality"),
                "dob" => dateFormat($request->get("dob"), "Y-m-d"),
                "ncl_valid_upto" => $request->get("ncl_valid_upto"),
                "caste_id" => $request->get("caste"),
                "session_id" => $active_session->id,
                // address
                "permanent_village_town" => $request->get("permanent_vill_town"),
                "permanent_po" => $request->get("permanent_po"),
                "permanent_ps" => $request->get("permanent_ps"),
                "permanent_state" => $request->get("permanent_state"),
                "permanent_district" => $request->get("permanent_district"),
                "permanent_pin" => $request->get("permanent_pin"),
                "permanent_contact_number" => $request->get("permanent_contact"),

                "correspondence_village_town" => $request->get("correspondence_vill_town"),
                "correspondence_po" => $request->get("correspondence_po"),
                "correspondence_ps" => $request->get("correspondence_ps"),
                "correspondence_state" => $request->get("correspondence_state"),
                "correspondence_district" => $request->get("correspondence_district"),
                "correspondence_pin" => $request->get("correspondence_pin"),
                "correspondence_contact_number" => $request->get("correspondence_contact"),
                // academic
                "other_qualification" => $request->get("other_qualification"),
                "english_mark_obtain" => $request->get("english_mark_obtained"),
                "academic_10_stream" => $request->get("academic_10_stream"),
                "academic_10_year" => $request->get("academic_10_year"),
                "academic_10_board" => $request->get("academic_10_board"),
                "academic_10_school" => $request->get("academic_10_stream"),
                "academic_10_subject" => $request->get("academic_10_subject"),
                "academic_10_mark" => $request->get("academic_10_mark"),
                "academic_10_percentage" => $request->get("academic_10_percentage"),

                "academic_12_stream" => $request->get("academic_12_stream"),
                "academic_12_year" => $request->get("academic_12_year"),
                "academic_12_board" => $request->get("academic_12_board"),
                "academic_12_school" => $request->get("academic_12_school"),
                "academic_12_subject" => $request->get("academic_12_subject"),
                "academic_12_mark" => $request->get("academic_12_mark"),
                "academic_12_percentage" => $request->get("academic_12_percentage"),

                "academic_voc_stream" => $request->get("academic_voc_stream"),
                "academic_voc_year" => $request->get("academic_voc_year"),
                "academic_voc_board" => $request->get("academic_voc_board"),
                "academic_voc_school" => $request->get("academic_voc_school"),
                "academic_voc_subject" => $request->get("academic_voc_subject"),
                "academic_voc_mark" => $request->get("academic_voc_mark"),
                "academic_voc_percentage" => $request->get("academic_voc_percentage"),

                "academic_anm_stream" => $request->get("academic_anm_stream"),
                "academic_anm_year" => $request->get("academic_anm_year"),
                "academic_anm_board" => $request->get("academic_anm_board"),
                "academic_anm_school" => $request->get("academic_anm_school"),
                "academic_anm_subject" => $request->get("academic_anm_subject"),
                "academic_anm_mark" => $request->get("academic_anm_mark"),
                "academic_anm_percentage" => $request->get("academic_anm_percentage"),
            ];
            $academic_details = [];
            $application = Application::create($application_data);
            $uploaded_docs = $this->storeDocs($request, $application);
            // $table->unsignedInteger("application_id");
            // $table->string("doc_name");
            // $table->string("file_name");
            // $table->string("original_name");
            // $table->string("mime_type");
            // $table->string("destination_path");
            $attachment_data = [];
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
                }
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }
        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->back()->withInput($request->all())->with("error", "Whoops! something went wrong.");
        }
        DB::commit();
        return redirect()->route("student.home")->with("success", "Application is Successfully Submitted. <a href='" . route("student.application.show", Crypt::encrypt($application->id)) . "'>Click Here</a> to view.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $encrypted_id)
    {
        // dd("ok");
        // return "Under Construction";
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments","applied_courses",'cuet_exam_details',"fatherQualification","applicationMaster")->whereId($id);
            
            if (auth()->guard("student")->check()) {
                $application = $application->where("student_id", auth()->guard("student")->user()->id);
            }
            $application = $application->first();
            // dd($application->master_id);
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route("student.home")->with("error", "Whoops! Something went wrong.");
        }
        if($request->has("download-pdf")){
            return $this->downloadApplicationAsPDF($application);
        }
        // dd($application);
        return view($this->getApplicationView(), compact("application"));
    }

    public function showii(Request $request, $encrypted_id)
    {
        // return "Under Construction";
        // dd("ok");
        $user_id=auth()->guard("student")->user()->id;
        try {
            $id = $encrypted_id;
            // $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments")->whereId($id);
            if (auth()->guard("student")->check()) {
                $application = $application->where("student_id", auth()->guard("student")->user()->id);
            }
            $application = $application->first();
            // dd($application);

        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route("student.home")->with("error", "Whoops! Something went wrong.");
        }
        if($request->has("download-pdf")){
            return $this->downloadApplicationAsPDF($application);
        }
        // dd($application);
        return view($this->getApplicationView(), compact("application"));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
        }

        // dd($decrypted_id);
        // dd("Halt Here");
        try {
            $application = Application::with("caste", "attachments", "session")->find($decrypted_id);
            if(get_guard() == "student"){
                $active_session_application = getActiveSessionApplication();
            }
            // dd($application);
            $castes = Caste::all();
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route(get_guard().".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        if(!applicatinEditPermission($application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step One permission denied. Application id {$application->id}");
            return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if(!isEditAvailable($application)){            
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
            return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
        }
        if($application->form_step >=4 ){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application NO {$application->id}.");
        }else
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Inserting Application NO {$application->id} Step {$application->form_step}.");
        return view($this->getApplicationEditView(), compact("application", "active_session_application", "castes"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
        }

        // dd($decrypted_id);
        // dd("Halt Here");
        try {
            $application = Application::with("caste", "attachments", "session")->find($decrypted_id);
            // dd($application);
            $castes = Caste::all();
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route(get_guard().".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        if(!applicatinEditPermission($application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step One permission denied. Application id {$application->id}");
            return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if(!isDeleteAvailable($application)){            
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Delete option no longer available Application no {$application->id}, Status: {$application->status}");
            return redirect()->route(get_guard().".home")->with("error", "Access Denied. Delete option not available.");
        }
        $application->attachments()->delete();
        $application->applied_courses()->delete();
        $application->delete();
        return redirect()->route(get_guard().".home")->with("error", "Application Deleted Successfully.");
    }

    public function storeDocs($request, $application)
    {
        $destinationPath = public_path('uploads/' . $application->student_id . "/" . $application->id);
        // $passport_name = '';
        // $sign_name = '';
        // $marksheet_name = '';
        // $pass_certificate_name = '';
        // $caste_certificate_name = '';
        $return_data = [];
        if (request()->hasFile('passport_photo')) {
            $passport_photo = request()->file('passport_photo');
            $passport_photo_name = date('YmdHis') . "_" . rand(4512, 6859) . "-passport_photo." . $passport_photo->getClientOriginalExtension();
            $passport_photo->move($destinationPath . "/", $passport_photo_name);
            $return_data[] = [
                "doc_name" => "passport_photo",
                "file_name" => $passport_photo_name,
                "original_name" => $passport_photo->getClientOriginalName(),
                "mime_type" => $passport_photo->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('signature')) {
            $signature = request()->file('signature');
            $signature_name = date('YmdHis') . "_" . rand(4512, 6859) . "-signature." . $signature->getClientOriginalExtension();
            $signature->move($destinationPath . "/", $signature_name);
            $return_data[] = [
                "doc_name" => "signature",
                "file_name" => $signature_name,
                "original_name" => $signature->getClientOriginalName(),
                "mime_type" => $signature->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('obc_ncl')) {
            $obc_ncl = request()->file('obc_ncl');
            $obc_ncl_name = date('YmdHis') . "_" . rand(4512, 6859) . "-obc_ncl." . $obc_ncl->getClientOriginalExtension();
            $obc_ncl->move($destinationPath . "/", $obc_ncl_name);
            $return_data[] = [
                "doc_name" => "obc_ncl",
                "file_name" => $obc_ncl_name,
                "original_name" => $obc_ncl->getClientOriginalName(),
                "mime_type" => $obc_ncl->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('caste_certificate')) {
            $caste_certificate = request()->file('caste_certificate');
            $caste_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-caste_certificate." . $caste_certificate->getClientOriginalExtension();
            $caste_certificate->move($destinationPath . "/", $caste_certificate_name);
            $return_data[] = [
                "doc_name" => "caste_certificate",
                "file_name" => $caste_certificate_name,
                "original_name" => $caste_certificate->getClientOriginalName(),
                "mime_type" => $caste_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('category_certificate')) {
            $category_certificate = request()->file('category_certificate');
            $category_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-category_certificate." . $category_certificate->getClientOriginalExtension();
            $category_certificate->move($destinationPath . "/", $category_certificate_name);
            $return_data[] = [
                "doc_name" => "category_certificate",
                "file_name" => $category_certificate_name,
                "original_name" => $category_certificate->getClientOriginalName(),
                "mime_type" => $category_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        
        if (request()->hasFile('sport_certificate')) {
            $sport_certificate = request()->file('sport_certificate');
            $sport_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-sport_certificate." . $sport_certificate->getClientOriginalExtension();
            $sport_certificate->move($destinationPath . "/", $sport_certificate_name);
            $return_data[] = [
                "doc_name" => "sport_certificate",
                "file_name" => $sport_certificate_name,
                "original_name" => $sport_certificate->getClientOriginalName(),
                "mime_type" => $sport_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('ews_certificate')) {
            $ews_certificate = request()->file('ews_certificate');
            $ews_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-ews_certificate." . $ews_certificate->getClientOriginalExtension();
            $ews_certificate->move($destinationPath . "/", $ews_certificate_name);
            $return_data[] = [
                "doc_name" => "ews_certificate",
                "file_name" => $ews_certificate_name,
                "original_name" => $ews_certificate->getClientOriginalName(),
                "mime_type" => $ews_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('jee_admit_card')) {
            // dd("ok");
            $jee_admit = request()->file('jee_admit_card');
            $jee_admit_name = date('YmdHis') . "_" . rand(4512, 6859) . "-jee_admit." . $jee_admit->getClientOriginalExtension();
            $jee_admit->move($destinationPath . "/", $jee_admit_name);
            $return_data[] = [
                "doc_name" => "jee_admit_card",
                "file_name" => $jee_admit_name,
                "original_name" => $jee_admit->getClientOriginalName(),
                "mime_type" => $jee_admit->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('net_slet_certificate')) {
            $net_slet_certificate = request()->file('net_slet_certificate');
            $net_slet_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-net_slet_certificate." . $net_slet_certificate->getClientOriginalExtension();
            $net_slet_certificate->move($destinationPath . "/", $net_slet_certificate_name);
            $return_data[] = [
                "doc_name" => "net_slet_certificate",
                "file_name" => $net_slet_certificate_name,
                "original_name" => $net_slet_certificate->getClientOriginalName(),
                "mime_type" => $net_slet_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('noc_certificate')) {
            $noc_certificate = request()->file('noc_certificate');
            $noc_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-noc_certificate." . $noc_certificate->getClientOriginalExtension();
            $noc_certificate->move($destinationPath . "/", $noc_certificate_name);
            $return_data[] = [
                "doc_name" => "noc_certificate",
                "file_name" => $noc_certificate_name,
                "original_name" => $noc_certificate->getClientOriginalName(),
                "mime_type" => $noc_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('ex_serviceman_certificate')) {
            $ex_serviceman_certificate = request()->file('ex_serviceman_certificate');
            $ex_serviceman_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-ex_serviceman_certificate." . $ex_serviceman_certificate->getClientOriginalExtension();
            $ex_serviceman_certificate->move($destinationPath . "/", $ex_serviceman_certificate_name);
            $return_data[] = [
                "doc_name" => "ex_serviceman_certificate",
                "file_name" => $ex_serviceman_certificate_name,
                "original_name" => $ex_serviceman_certificate->getClientOriginalName(),
                "mime_type" => $ex_serviceman_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('gate_score_card')) {
            $gate_score_card = request()->file('gate_score_card');
            $gate_score_card_name = date('YmdHis') . "_" . rand(4512, 6859) . "-gate_score_card." . $gate_score_card->getClientOriginalExtension();
            $gate_score_card->move($destinationPath . "/", $gate_score_card_name);
            $return_data[] = [
                "doc_name" => "gate_score_card",
                "file_name" => $gate_score_card_name,
                "original_name" => $gate_score_card->getClientOriginalName(),
                "mime_type" => $gate_score_card->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('document_driving_license')) {
            $document_driving_license = request()->file('document_driving_license');
            $document_driving_license_name = date('YmdHis') . "_" . rand(4512, 6859) . "-document_driving_license." . $document_driving_license->getClientOriginalExtension();
            $document_driving_license->move($destinationPath . "/", $document_driving_license_name);
            $return_data[] = [
                "doc_name" => "document_driving_license",
                "file_name" => $document_driving_license_name,
                "original_name" => $document_driving_license->getClientOriginalName(),
                "mime_type" => $document_driving_license->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        // if (request()->hasFile('passing_certificate')) {
        //     $passing_certificate = request()->file('passing_certificate');
        //     $passing_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-passing_certificate." . $passing_certificate->getClientOriginalExtension();
        //     $passing_certificate->move($destinationPath . "/", $passing_certificate_name);
        //     $return_data[] = [
        //         "doc_name" => "passing_certificate",
        //         "file_name" => $passing_certificate_name,
        //         "original_name" => $passing_certificate->getClientOriginalName(),
        //         "mime_type" => $passing_certificate->getClientMimeType(),
        //         "destination_path" => $destinationPath,
        //     ];
        // }

        if (request()->hasFile('document_passing_certificate')) {
            $document_passing_certificate = request()->file('document_passing_certificate');
            $document_passing_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-document_passing_certificate." . $document_passing_certificate->getClientOriginalExtension();
            $document_passing_certificate->move($destinationPath . "/", $document_passing_certificate_name);
            $return_data[] = [
                "doc_name" => "document_passing_certificate",
                "file_name" => $document_passing_certificate_name,
                "original_name" => $document_passing_certificate->getClientOriginalName(),
                "mime_type" => $document_passing_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('document_marksheet')) {
            $marksheet = request()->file('document_marksheet');
            $marksheet_name = date('YmdHis') . "_" . rand(4512, 6859) . "-marksheet." . $marksheet->getClientOriginalExtension();
            $marksheet->move($destinationPath . "/", $marksheet_name);
            $return_data[] = [
                "doc_name" => "document_marksheet",
                "file_name" => $marksheet_name,
                "original_name" => $marksheet->getClientOriginalName(),
                "mime_type" => $marksheet->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('prc')) {
            $prc_certificate = request()->file('prc');
            $prc_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-prc_certificate." . $prc_certificate->getClientOriginalExtension();
            $prc_certificate->move($destinationPath . "/", $prc_certificate_name);
            $return_data[] = [
                "doc_name" => "prc_certificate",
                "file_name" => $prc_certificate_name,
                "original_name" => $prc_certificate->getClientOriginalName(),
                "mime_type" => $prc_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('age_proof_certificate')) {
            $age_proof_certificate = request()->file('age_proof_certificate');
            $age_proof_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-age_proof_certificate." . $age_proof_certificate->getClientOriginalExtension();
            $age_proof_certificate->move($destinationPath . "/", $age_proof_certificate_name);
            $return_data[] = [
                "doc_name" => "age_proof_certificate",
                "file_name" => $age_proof_certificate_name,
                "original_name" => $age_proof_certificate->getClientOriginalName(),
                "mime_type" => $age_proof_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('12_admit_card')) {
            $admit_card_12 = request()->file('12_admit_card');
            $admit_card_12_name = date('YmdHis') . "_" . rand(4512, 6859) . "-admit_card_12." . $admit_card_12->getClientOriginalExtension();
            $admit_card_12->move($destinationPath . "/", $admit_card_12_name);
            $return_data[] = [
                "doc_name" => "admit_card_12",
                "file_name" => $admit_card_12_name,
                "original_name" => $admit_card_12->getClientOriginalName(),
                "mime_type" => $admit_card_12->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('12_marksheet')) {
            $marksheet_12 = request()->file('12_marksheet');
            $marksheet_12_name = date('YmdHis') . "_" . rand(4512, 6859) . "-marksheet_12." . $marksheet_12->getClientOriginalExtension();
            $marksheet_12->move($destinationPath . "/", $marksheet_12_name);
            $return_data[] = [
                "doc_name" => "marksheet_12",
                "file_name" => $marksheet_12_name,
                "original_name" => $marksheet_12->getClientOriginalName(),
                "mime_type" => $marksheet_12->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('document_mentioning_name_of_the_school_class_10')) {
            $school_mentioned_certificate = request()->file('document_mentioning_name_of_the_school_class_10');
            $school_mentioned_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-school_mentioned_certificate." . $school_mentioned_certificate->getClientOriginalExtension();
            $school_mentioned_certificate->move($destinationPath . "/", $school_mentioned_certificate_name);
            $return_data[] = [
                "doc_name" => "document_mentioning_name_of_the_school_class_10",
                "file_name" => $school_mentioned_certificate_name,
                "original_name" => $school_mentioned_certificate->getClientOriginalName(),
                "mime_type" => $school_mentioned_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('anm_registration')) {
            $anm_registration = request()->file('anm_registration');
            $anm_registration_name = date('YmdHis') . "_" . rand(4512, 6859) . "-anm_registration." . $anm_registration->getClientOriginalExtension();
            $anm_registration->move($destinationPath . "/", $anm_registration_name);
            $return_data[] = [
                "doc_name" => "anm_registration",
                "file_name" => $anm_registration_name,
                "original_name" => $anm_registration->getClientOriginalName(),
                "mime_type" => $anm_registration->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('anm_marksheet')) {
            $anm_marksheet = request()->file('anm_marksheet');
            $anm_marksheet_name = date('YmdHis') . "_" . rand(4512, 6859) . "-anm_marksheet." . $anm_marksheet->getClientOriginalExtension();
            $anm_marksheet->move($destinationPath . "/", $anm_marksheet_name);
            $return_data[] = [
                "doc_name" => "anm_marksheet",
                "file_name" => $anm_marksheet_name,
                "original_name" => $anm_marksheet->getClientOriginalName(),
                "mime_type" => $anm_marksheet->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('bpl_card')) {
            $bpl_document = request()->file('bpl_card');
            $bpl_document_name = date('YmdHis') . "_" . rand(4512, 6859) . "-bpl_document." . $bpl_document->getClientOriginalExtension();
            $bpl_document->move($destinationPath . "/", $bpl_document_name);
            $return_data[] = [
                "doc_name" => "bpl_card",
                "file_name" => $bpl_document_name,
                "original_name" => $bpl_document->getClientOriginalName(),
                "mime_type" => $bpl_document->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('pwd_certificate')) {
            $pwd_certificate = request()->file('pwd_certificate');
            $pwd_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-pwd_certificate." . $pwd_certificate->getClientOriginalExtension();
            $pwd_certificate->move($destinationPath . "/", $pwd_certificate_name);
            $return_data[] = [
                "doc_name" => "pwd_certificate",
                "file_name" => $pwd_certificate_name,
                "original_name" => $pwd_certificate->getClientOriginalName(),
                "mime_type" => $pwd_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('document_passport')) {
            $passport_document = request()->file('document_passport');
            $passport_document_name = date('YmdHis') . "_" . rand(4512, 6859) . "-passport_document." . $passport_document->getClientOriginalExtension();
            $passport_document->move($destinationPath . "/", $passport_document_name);
            $return_data[] = [
                "doc_name" => "document_passport",
                "file_name" => $passport_document_name,
                "original_name" => $passport_document->getClientOriginalName(),
                "mime_type" => $passport_document->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('document_ssn_equivalent')) {
            $document_ssn_equivalent = request()->file('document_ssn_equivalent');
            $document_ssn_equivalent_name = date('YmdHis') . "_" . rand(4512, 6859) . "-document_ssn_equivalent." . $document_ssn_equivalent->getClientOriginalExtension();
            $document_ssn_equivalent->move($destinationPath . "/", $document_ssn_equivalent_name);
            $return_data[] = [
                "doc_name" => "document_ssn_equivalent",
                "file_name" => $document_ssn_equivalent_name,
                "original_name" => $document_ssn_equivalent->getClientOriginalName(),
                "mime_type" => $document_ssn_equivalent->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('additional_document')) {
            $additional_document = request()->file('additional_document');
            $additional_document_name = date('YmdHis') . "_" . rand(4512, 6859) . "-additional_document." . $additional_document->getClientOriginalExtension();
            $additional_document->move($destinationPath . "/", $additional_document_name);
            $return_data[] = [
                "doc_name" => "additional_document",
                "file_name" => $additional_document_name,
                "original_name" => $additional_document->getClientOriginalName(),
                "mime_type" => $additional_document->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('document_english_proficiency_certificate')) {
            $english_proficiency = request()->file('document_english_proficiency_certificate');
            $english_proficiency_name = date('YmdHis') . "_" . rand(4512, 6859) . "-english_proficiency." . $english_proficiency->getClientOriginalExtension();
            $english_proficiency->move($destinationPath . "/", $english_proficiency_name);
            $return_data[] = [
                "doc_name" => "document_english_proficiency_certificate",
                "file_name" => $english_proficiency_name,
                "original_name" => $english_proficiency->getClientOriginalName(),
                "mime_type" => $english_proficiency->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('gate_b_score_card')) {
            $gate_b_score_card = request()->file('gate_b_score_card');
            $gate_b_score_card_name = date('YmdHis') . "_" . rand(4512, 6859) . "-gate_b_score_card." . $gate_b_score_card->getClientOriginalExtension();
            $gate_b_score_card->move($destinationPath . "/", $gate_b_score_card_name);
            $return_data[] = [
                "doc_name" => "gate_b_score_card",
                "file_name" => $gate_b_score_card_name,
                "original_name" => $gate_b_score_card->getClientOriginalName(),
                "mime_type" => $gate_b_score_card->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('ceed_score_card')) {
            $ceed_score_card = request()->file('ceed_score_card');
            $ceed_score_card_name = date('YmdHis') . "_" . rand(4512, 6859) . "-ceed_score_card." . $ceed_score_card->getClientOriginalExtension();
            $ceed_score_card->move($destinationPath . "/", $ceed_score_card_name);
            $return_data[] = [
                "doc_name" => "ceed_score_card",
                "file_name" => $ceed_score_card_name,
                "original_name" => $ceed_score_card->getClientOriginalName(),
                "mime_type" => $ceed_score_card->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('portfolio')) {
            $portfolio = request()->file('portfolio');
            $portfolio_name = date('YmdHis') . "_" . rand(4512, 6859) . "-portfolio." . $portfolio->getClientOriginalExtension();
            $portfolio->move($destinationPath . "/", $portfolio_name);
            $return_data[] = [
                "doc_name" => "portfolio",
                "file_name" => $portfolio_name,
                "original_name" => $portfolio->getClientOriginalName(),
                "mime_type" => $portfolio->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('undertaking')) {
            $undertaking = request()->file('undertaking');
            $undertaking_name = date('YmdHis') . "_" . rand(4512, 6859) . "-undertaking." . $undertaking->getClientOriginalExtension();
            $undertaking->move($destinationPath . "/", $undertaking_name);
            $return_data[] = [
                "doc_name" => "undertaking",
                "file_name" => $undertaking_name,
                "original_name" => $undertaking->getClientOriginalName(),
                "mime_type" => $undertaking->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('undertaking_pass_appear')) {
            $undertaking_pass_appear = request()->file('undertaking_pass_appear');
            $undertaking_pass_appear_name = date('YmdHis') . "_" . rand(4512, 6859) . "-undertaking_pass_appear." . $undertaking_pass_appear->getClientOriginalExtension();
            $undertaking_pass_appear->move($destinationPath . "/", $undertaking_pass_appear_name);
            $return_data[] = [
                "doc_name" => "undertaking_pass_appear",
                "file_name" => $undertaking_pass_appear_name,
                "original_name" => $undertaking_pass_appear->getClientOriginalName(),
                "mime_type" => $undertaking_pass_appear->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('cuet_admit_card')) {
            // dd("okk");
            $cuet_admit_card = request()->file('cuet_admit_card');
            $cuet_admit_card_name = date('YmdHis') . "_" . rand(4512, 6859) . "-cuet_admit_card." . $cuet_admit_card->getClientOriginalExtension();
            $cuet_admit_card->move($destinationPath . "/", $cuet_admit_card_name);
            $return_data[] = [
                "doc_name" => "cuet_admit_card",
                "file_name" => $cuet_admit_card_name,
                "original_name" => $cuet_admit_card->getClientOriginalName(),
                "mime_type" => $cuet_admit_card->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('bank_passbook')) {
            $bank_passbook = request()->file('bank_passbook');
            $bank_passbook_name = date('YmdHis') . "_" . rand(4512, 6859) . "-bank_passbook." . $bank_passbook->getClientOriginalExtension();
            $bank_passbook->move($destinationPath . "/", $bank_passbook_name);
            $return_data[] = [
                "doc_name" => "bank_passbook",
                "file_name" => $bank_passbook_name,
                "original_name" => $bank_passbook->getClientOriginalName(),
                "mime_type" => $bank_passbook->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        
        if (request()->hasFile('cuet_score_card')) {
            $cuet_score_card = request()->file('cuet_score_card');
            $cuet_score_card_name = date('YmdHis') . "_" . rand(4512, 6859) . "-cuet_score_card." . $cuet_score_card->getClientOriginalExtension();
            $cuet_score_card->move($destinationPath . "/", $cuet_score_card_name);
            $return_data[] = [
                "doc_name" => "cuet_score_card",
                "file_name" => $cuet_score_card_name,
                "original_name" => $cuet_score_card->getClientOriginalName(),
                "mime_type" => $cuet_score_card->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        
        if (request()->hasFile('class_x_documents')) {
            //return response()->json(['ss'=>$request->class_x_documents]);
            $class_x_documents = request()->file('class_x_documents');
            $class_x_documents_name = date('YmdHis') . "_" . rand(4512, 6859) . "-class_x_documents." . $class_x_documents->getClientOriginalExtension();
            $class_x_documents->move($destinationPath . "/", $class_x_documents_name);
            $return_data[] = [
                "doc_name" => "class_x_documents",
                "file_name" => $class_x_documents_name,
                "original_name" => $class_x_documents->getClientOriginalName(),
                "mime_type" => $class_x_documents->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        
        if (request()->hasFile('class_XII_documents')) {
            $class_XII_documents = request()->file('class_XII_documents');
            $class_XII_documents_name = date('YmdHis') . "_" . rand(4512, 6859) . "-class_XII_documents." . $class_XII_documents->getClientOriginalExtension();
            $class_XII_documents->move($destinationPath . "/", $class_XII_documents_name);
            $return_data[] = [
                "doc_name" => "class_XII_documents",
                "file_name" => $class_XII_documents_name,
                "original_name" => $class_XII_documents->getClientOriginalName(),
                "mime_type" => $class_XII_documents->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('graduation_documents')) {
            $graduation_documents = request()->file('graduation_documents');
            $graduation_documents_name = date('YmdHis') . "_" . rand(4512, 6859) . "-graduation_documents." . $graduation_documents->getClientOriginalExtension();
            $graduation_documents->move($destinationPath . "/", $graduation_documents_name);
            $return_data[] = [
                "doc_name" => "graduation_documents",
                "file_name" => $graduation_documents_name,
                "original_name" => $graduation_documents->getClientOriginalName(),
                "mime_type" => $graduation_documents->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('post_graduation_documents')) {
            $post_graduation_documents = request()->file('post_graduation_documents');
            $post_graduation_documents_name = date('YmdHis') . "_" . rand(4512, 6859) . "-post_graduation_documents." . $post_graduation_documents->getClientOriginalExtension();
            $post_graduation_documents->move($destinationPath . "/", $post_graduation_documents_name);
            $return_data[] = [
                "doc_name" => "post_graduation_documents",
                "file_name" => $post_graduation_documents_name,
                "original_name" => $post_graduation_documents->getClientOriginalName(),
                "mime_type" => $post_graduation_documents->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }


        if (request()->hasFile('class_x_grade_conversion')) {
            //return response()->json(['ss'=>$request->class_x_grade_conversion]);
            $class_x_grade_conversion = request()->file('class_x_grade_conversion');
            $class_x_grade_conversion_name = date('YmdHis') . "_" . rand(4512, 6859) . "-class_x_grade_conversion." . $class_x_grade_conversion->getClientOriginalExtension();
            $class_x_grade_conversion->move($destinationPath . "/", $class_x_grade_conversion_name);
            $return_data[] = [
                "doc_name" => "class_x_grade_conversion",
                "file_name" => $class_x_grade_conversion_name,
                "original_name" => $class_x_grade_conversion->getClientOriginalName(),
                "mime_type" => $class_x_grade_conversion->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        
        if (request()->hasFile('class_XII_grade_conversion')) {
            $class_XII_grade_conversion = request()->file('class_XII_grade_conversion');
            $class_XII_grade_conversion_name = date('YmdHis') . "_" . rand(4512, 6859) . "-class_XII_grade_conversion." . $class_XII_grade_conversion->getClientOriginalExtension();
            $class_XII_grade_conversion->move($destinationPath . "/", $class_XII_grade_conversion_name);
            $return_data[] = [
                "doc_name" => "class_XII_grade_conversion",
                "file_name" => $class_XII_grade_conversion_name,
                "original_name" => $class_XII_grade_conversion->getClientOriginalName(),
                "mime_type" => $class_XII_grade_conversion->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('graduation_grade_conversion')) {
            $graduation_grade_conversion = request()->file('graduation_grade_conversion');
            $graduation_grade_conversion_name = date('YmdHis') . "_" . rand(4512, 6859) . "-graduation_grade_conversion." . $graduation_grade_conversion->getClientOriginalExtension();
            $graduation_grade_conversion->move($destinationPath . "/", $graduation_grade_conversion_name);
            $return_data[] = [
                "doc_name" => "graduation_grade_conversion",
                "file_name" => $graduation_grade_conversion_name,
                "original_name" => $graduation_grade_conversion->getClientOriginalName(),
                "mime_type" => $graduation_grade_conversion->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }

        if (request()->hasFile('post_graduation_grade_conversion')) {
            $post_graduation_grade_conversion = request()->file('post_graduation_grade_conversion');
            $post_graduation_grade_conversion_name = date('YmdHis') . "_" . rand(4512, 6859) . "-post_graduation_grade_conversion." . $post_graduation_grade_conversion->getClientOriginalExtension();
            $post_graduation_grade_conversion->move($destinationPath . "/", $post_graduation_grade_conversion_name);
            $return_data[] = [
                "doc_name" => "post_graduation_grade_conversion",
                "file_name" => $post_graduation_grade_conversion_name,
                "original_name" => $post_graduation_grade_conversion->getClientOriginalName(),
                "mime_type" => $post_graduation_grade_conversion->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }


        
        
        $mba_exam_names = getMBAExams();
        foreach ($mba_exam_names as $key => $value) {
            if (request()->hasFile($value)) {
                $file = request()->file($value);
                $file_name = date('YmdHis') . "_" . rand(4512, 6859) . "-" .$value . $file->getClientOriginalExtension();
                $file->move($destinationPath . "/", $file_name);
                $return_data[] = [
                    "doc_name" => $value,
                    "file_name" => $file_name,
                    "original_name" => $file->getClientOriginalName(),
                    "mime_type" => $file->getClientMimeType(),
                    "destination_path" => $destinationPath,
                ];
            }
        }
        return $return_data;
    }

    public function stepOneUpdate(Request $request, $encrypted_id)
    {
        $application_form = "personal_information";
        $rules = $this->getRules($application_form);
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step One permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 1.";
            if($application->form_step == 2){
                $message = "Updating Application No {$application->id} Step 1.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()->with("error", "Whoops! Lookes like you have mess something.");
        }
        if((Int)$request->get("anm_or_lhv")){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm_or_lhv");
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()->to(url()->previous() . "#step-one-update")
            ->withErrors($validator)->withInput($request->all());
        }
        $lowerLimit = Application::$applicant_lower_age_limit;
        $upperLimit = Application::$applicant_upper_age_limit;

        $Lower_limit_extended_dob = strtotime($request->dob . "+ {$lowerLimit} years");
        $upper_limit_extended_dob = strtotime($request->dob . "+ {$upperLimit} years");

        $limit_date = strtotime(Application::$dob_compare_to);

        if ($Lower_limit_extended_dob > $limit_date) {
            $validator->errors()->add('dob', "Age minimum limit is {$lowerLimit} years.");
            return redirect()
            ->to(url()->previous() . "#step-one-update")
            ->withInput($request->all())->withErrors($validator);
        }

        if ($upper_limit_extended_dob < $limit_date) {
            $validator->errors()->add('dob', "Age maximum limit is {$upperLimit} years.");
            return redirect()
            ->to(url()->previous() . "#step-one-update")
            ->withInput($request->all())->withErrors($validator);
        }
        DB::beginTransaction();
        $old_application_form_step = $application->form_step;
        try {
            // Personal Information
            $application->fullname  = $request->get("fullname");
            $application->gender    = $request->get("gender");

            $application->father_name       = $request->get("father_name");
            $application->father_occupation = $request->get("father_occupation");
            
            $application->mother_name       = $request->get("mother_name");
            $application->mother_occupation = $request->get("mother_occupation");

            $application->marital_status = $request->get("maritial_status");
            $application->religion       = $request->get("religion");
            $application->caste_id       = $request->get("caste");
            $application->dob            = dateFormat($request->get("dob"), "Y-m-d");

            $application->person_with_disablity = $request->get("disablity");
            $application->anm_or_lhv         = $request->get("anm_or_lhv");
            $application->anm_or_lhv_registration         = $request->get("anm_or_lhv_registration");
            if(!$request->get("anm_or_lhv")){
                $application->academic_anm_stream       = null;
                $application->academic_anm_year         = null;
                $application->academic_anm_board        = null;
                $application->academic_anm_school       = null;
                $application->academic_anm_subject      = null;
                $application->academic_anm_mark         = null;
                $application->academic_anm_percentage   = null;
                // attachments also delte.
                // $application->attachments()->where("doc_name", "anm")->delete();
            }
            // $application->bpl                   = $request->get("bpl");
            // below form_step not required due to first step already completed.
            if($application->form_step == 0){
                $application->form_step = 1;
            }
            // dump($application->getChanges());
            // dd($application);
            $application->save();
        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-two-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 1 Successfully Updated.";
        if($old_application_form_step == 1){
            $success_message = "Step 1 Successfully saved.";
        }
        return redirect()->back()
        ->with("success", $success_message);
    }
    public function stepTwoUpdate(Request $request, $encrypted_id)
    {
        $application_form = "address_information";
        $rules = $this->getRules($application_form);
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step two permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 2.";
            if($application->form_step == 1){
                $message = "Updating Application No {$application->id} Step 2.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing or Inserting Application {$application->id} Step 2.");
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
            ->to(url()->previous() . "#step-two-update")
            ->with("error", "Whoops! Lookes like you have missed something.");
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()
            ->to(url()->previous() . "#step-two-update")
            ->withErrors($validator)->withInput($request->all());
        }
        
        DB::beginTransaction();
        try {
            $old_application = clone $application;
            // Correspondence Address
            $application->correspondence_village_town = $request->get("correspondence_vill_town");
            $application->correspondence_po = $request->get("correspondence_po");
            $application->correspondence_ps = $request->get("correspondence_ps");
            $application->correspondence_state = $request->get("correspondence_state");
            $application->correspondence_district = $request->get("correspondence_district");
            $application->correspondence_pin = $request->get("correspondence_pin");
            $application->correspondence_contact_number = $request->get("correspondence_contact");
            // permanent Address
            $application->permanent_village_town = $request->get("permanent_vill_town");
            $application->permanent_po = $request->get("permanent_po");
            $application->permanent_ps = $request->get("permanent_ps");
            $application->permanent_state = $request->get("permanent_state");
            $application->permanent_district = $request->get("permanent_district");
            $application->permanent_pin = $request->get("permanent_pin");
            $application->permanent_contact_number = $request->get("permanent_contact");
            $application->same_address = $request->get("same_address");
            if($application->form_step == 1){
                $application->form_step = 2;
            }
            $application->save();
        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-two-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 2 Successfully Updated.";
        $url = url()->previous()."#step-three-update";
        if($old_application->form_step == 1){
            $success_message = "Step 2 Successfully Saved.";
            $url = str_replace("#step-three-update", "", $url); 
        }
        return redirect()->to($url)
        ->with("success", $success_message);
    }
    public function stepThreeUpdate(Request $request, $encrypted_id)
    {
        $application_form = "academic_information";
        $rules = $this->getRules($application_form);
        // dd($rules);
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step three permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 3.";
            if($application->form_step == 2){
                $message = "Updating Application No {$application->id} Step 3.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
            ->to(url()->previous() . "#step-three-update")
            ->with("error", "Whoops! Lookes like you have mess something.");
        }
        if($this->checkAnmDataEntered($request, $rules) || $application->anm_or_lhv){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm");
        }
        if($this->checkVocationalDataEntered($request, $rules)){
            $rules = $this->ConvertNullableToRequired($rules, $string = "voc");
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()
            ->to(url()->previous() . "#step-three-update")
            ->withErrors($validator)->withInput($request->all());
        }
        DB::beginTransaction();
        $old_application_form_step = $application->form_step;
        try {
            // Academic Details
            // 10th class inforamtion
            $application->academic_10_stream = "NA";
            $application->academic_10_year = $request->get("academic_10_year");
            $application->academic_10_board = $request->get("academic_10_board");
            $application->academic_10_school = $request->get("academic_10_school");
            $application->academic_10_subject = $request->get("academic_10_subject");
            $application->academic_10_mark = $request->get("academic_10_mark");
            $application->academic_10_percentage = $request->get("academic_10_percentage");

            // 12th class inforamtion
            $application->academic_12_stream = $request->get("academic_12_stream");
            $application->academic_12_year = $request->get("academic_12_year");
            $application->academic_12_board = $request->get("academic_12_board");
            $application->academic_12_school = $request->get("academic_12_school");
            $application->academic_12_subject = $request->get("academic_12_subject");
            $application->academic_12_mark = $request->get("academic_12_mark");
            $application->academic_12_percentage = $request->get("academic_12_percentage");

            // Vocational class inforamtion
            $application->academic_voc_stream = $request->get("academic_voc_stream");
            $application->academic_voc_year = $request->get("academic_voc_year");
            $application->academic_voc_board = $request->get("academic_voc_board");
            $application->academic_voc_school = $request->get("academic_voc_school");
            $application->academic_voc_subject = $request->get("academic_voc_subject");
            $application->academic_voc_mark = $request->get("academic_voc_mark");
            $application->academic_voc_percentage = $request->get("academic_voc_percentage");

            // Vocational class inforamtion
            $application->academic_anm_stream = $request->get("academic_anm_stream");
            $application->academic_anm_year = $request->get("academic_anm_year");
            $application->academic_anm_board = $request->get("academic_anm_board");
            $application->academic_anm_school = $request->get("academic_anm_school");
            $application->academic_anm_subject = $request->get("academic_anm_subject");
            $application->academic_anm_mark = $request->get("academic_anm_mark");
            $application->academic_anm_percentage = $request->get("academic_anm_percentage");

            $application->other_qualification = $request->get("other_qualification");
            $application->english_mark_obtain = $request->get("english_mark_obtained");
            if($application->form_step == 2){
                $application->form_step = 3;
            }
            // dump($application->getChanges());
            // dd($application);
            $application->save();
        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-three-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 3 Successfully Updated.";
        $url = url()->previous() . "#step-four-update";
        if($old_application_form_step == 2){
            $success_message = "Step 3 process is completed.";
            $url = str_replace("#step-four-update", "", $url);
        }
        return redirect()->to($url)
        ->with("success", $success_message);
    }
    public function stepFinalUpdate(Request $request, $encrypted_id)
    {
        $application_form = "attachment_information";
        $rules = $this->getRules($application_form);
        // dump($request->all());
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments")->find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step three permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()
                ->to(url()->previous() . "#step-four-update")
                ->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 3.";
            if($application->form_step == 2){
                $message = "Updating Application No {$application->id} Step 3.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()
            ->to(url()->previous() . "#step-four-update")
            ->with("error", "Whoops! Lookes like you have mess something.");
        }
        // check if anm academic details found update rules n add required field to attachment too
        /* if($this->checkAnmDataEntered($request, $rules)){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm");
        } */
        if($this->checkAnmDataEntered($request, $rules) || $application->anm_or_lhv){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm");
        }
        if($application->bpl){
            $rules["bpl_document"] = str_replace("nullable", "required", $rules["bpl_document"]);
        }
        if($application->person_with_disablity){
            $rules["disablity_certificate"] = str_replace("nullable", "required", $rules["disablity_certificate"]);
        }
        // check if files are already uploaded required is not compulsory
        $application->attachments->each(function($attachment, $key) use (&$rules){
            if($attachment->doc_name == "marksheet_12"){
                $rules["12_marksheet"] = str_replace("required", "nullable", $rules["12_marksheet"]);
            }
            if(isset($rules[$attachment->doc_name])){
                $rules[$attachment->doc_name] = str_replace("required", "nullable", $rules[$attachment->doc_name]);
            }
        });
        // dd($rules);
        // Validation
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()
            ->to(url()->previous() . "#step-four-update")
            ->withErrors($validator)->withInput($request->all());
        }
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
            if($deleted_condition){
                $application->attachments()->whereIn("doc_name", $deleted_condition)->delete();
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }
            //Declaration Accepted
            $application->diclaration_accept = $request->get("accept");

            if($application->form_step == 3){
                $application->form_step = 4;
            }
            $application->save();
        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-four-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 4 Successfully Updated.";
        if($old_application_form_step == 3){
            $success_message = "Step 4 Successfully saved. Please review and accept for payment process.";
        }
        return redirect()->route(get_guard().".application.index")
        ->with("success", $success_message);
    }
    public function finalSubmit(Request $request, $encrypted_id) {
        
        // dd("ok");
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $total_cuet=CuetExamDetail::where('application_id',$decrypted_id)->count();

            $is_phd=Application::where('id',$decrypted_id)->pluck('is_phd');
            $is_btech=Application::where('id',$decrypted_id)->pluck('is_btech');
            $is_mba = Application::where('id',$decrypted_id)->pluck('is_mba');
            $is_cuet_pg = Application::where('id',$decrypted_id)->pluck('is_cuet_pg');
            $is_cuet_ug = Application::where('id',$decrypted_id)->pluck('is_cuet_ug');
            $is_laterall = Application::where('id',$decrypted_id)->pluck('is_laterall');
            $is_mdes = Application::where('id',$decrypted_id)->pluck('is_mdes');
            $is_mbbt = Application::where('id',$decrypted_id)->pluck('is_mbbt');
            $is_bdes = Application::where('id',$decrypted_id)->pluck('is_bdes');
            $is_phd_prof = Application::where('id',$decrypted_id)->pluck('is_bdes');
            $is_chinese = Application::where('id',$decrypted_id)->pluck('is_chinese');
            // dd($decrypted_id);
            // dd($is_phd);
            // dd("ok");
            //is closed validation
            $application = Application::where('id',$decrypted_id)->first();
            $application_type = Application::where('id',$decrypted_id)->first()->exam_through;
            $prog_name = Auth::user()->program_name;
            if($prog_name == "PHD" && $application->net_jrf){
                $application_type = 'NET_JRF';
            }/* else if($prog_name == "PG" && $application->is_gate){
                $application_type = 'GATE';
            } */
            if($prog_name=="PHDPROF" || $prog_name=="VISVES"){
                $prog_name = "PHD";
            }
            if($prog_name=="JOSSA"){
                $prog_name = "BTECH";
            }
            if($prog_name!="FOREIGN"){
                $flag = Program::where('type',$prog_name)->first();
                $student_id = Auth::user()->id;
                $is_avail=DB::table('zzz_payment_allowed_students')->where('student_id',$student_id)->count();
                if($flag->$application_type==0 && $is_avail==0){
                    return redirect()->back()->with('error','Application Process is already closed.');
                }

                if($application_type == "GATE"){
                    $applied_courses = AppliedCourse::where('application_id',$decrypted_id)->get();
                    foreach($applied_courses as $course){
                        if(!in_array($course->course_id,[14,15,16,17,18,19,20,21,104,105])){
                            return redirect()->back()->with('error','Application Process is already closed..');
                        }
                    }
                }
            }
            //end
            

            $check=1;
            // Exam center validate
            $valid_application = Application::where('id',$decrypted_id)->first();
            if($valid_application->exam_through == "TUEE"){
                if($valid_application->is_cuet_pg==1 || $valid_application->is_phd==1 || $valid_application->is_laterall || $valid_application->is_mdes || $valid_application->is_bdes){
                    if(!$valid_application->ExamCenter->center_name){
                        return redirect()->route(get_guard().".home")->with("error", "Update Exam Center details in STEP-2");
                    }
                }
            }
            // $is_tuee=1;
            // if($is_mdes[0]==1 && Auth::user()->exam_through!='TUEE'){
            //     $is_tuee=0;
            // }elseif($is_btech[0]==1||$is_cuet_ug[0]==1 || $is_mbbt[0]==1){
            //     $is_tuee=0;
            // }else{
            //     // $is_center_filled=Application::where('id',$decrypted_id)->pluck('exam_center_id');
            //     // if($is_center_filled[0]==null){
            //     //     return redirect()->route(get_guard().".home")->with("error", "Update Exam Center details in STEP-2");
            //     // }
            // }
            
            // if($is_cuet_ug[0]==1 && Auth::user()->cuet_verified==0){
            //     return redirect()->route(get_guard().".home")->with("error", "Please Verify Your CUET University Preference");
            // }
            // 
            
            if($total_cuet>=$check || $is_phd[0]==1 || $is_btech[0]==1 || $is_mba[0]==1 || $is_cuet_pg[0]==1 || $is_laterall[0]==1 || $is_mdes[0]==1 || $is_mbbt[0]==1 || $is_cuet_ug[0]==1 || $is_bdes[0]==1 || $is_phd_prof[0]==1 || $is_chinese[0]==1){       
                
                // $check_percentile=CuetExamDetail::where('application_id',$decrypted_id)->get();
                // dd($check_percentile);
                // foreach($check_percentile as $per){
                //     if(!isset($per->percentile)){
                //         return redirect()->route(get_guard().".home")->with("error", "Add CUET percentile details in STEP-3");
                //     }
                // }
                // dd("ok");
                $application = Application::with("attachments", "caste", "applied_courses")->find($decrypted_id);
                if(!applicatinEditPermission($application)){
                    saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step three permission denied. Application id {$application->id}");
                    return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
                }
                if($application->form_step == 4 && $application->status == "application_submitted"){
                }else{
                    return redirect()->route(get_guard().".home")->with("error", "Final Submit option not available at this time.");
                }
                $message = "Final Submission Application No {$application->id}.";
            }else{
                
                return redirect()->route(get_guard().".home")->with("error", "Update Your CUET details in STEP-3");

            }

            $application = Application::where('id',$decrypted_id)->first();
            foreach($application->applied_courses as $course){
                $check_is_available = DB::table('courses')->where('id',$course->course_id)->whereNotNull('deleted_at')->first();
                if($check_is_available){
                    return redirect()->route(get_guard().".home")->with("error", "Something went wrong please contact Technical Support");
                }
            }
            //check for master info 
            $master  = Application::where('student_id',Auth::User()->id)->where('is_master',1)->first();
            if($master){
                $new_application = Application::where('id',$decrypted_id)->first();
                if($master->caste_id != $new_application->caste_id){
                    return redirect()->route(get_guard().".home")->with("error", "You Can`t change caste as you have applied with another caste in previous application");
                }
                if( $master->gender != $new_application->gender){
                    return redirect()->route(get_guard().".home")->with("error", "You Can`t change  gender as you have applied with another gender in previous application");
                }
            }
            // dd("ok");
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()->with("error", "Whoops! Lookes like you have mess something.");
        }
        if($application->form_step !== 4 && $application->status !== "application_submitted" && !$application->is_eligibility_critaria_fullfilled){
            return redirect()->back()->with("error", "Whoops! Lookes like you have not eligible or eligible checking is not done yet.");
        }
        // Validation check
        // Caste uploaded (non General)
        $errors = [];
        if(!$application->attachments->where("doc_name", "passport_photo")->count()){
            $errors[] = "Passport Photo is required";
        }
        if(!$application->attachments->where("doc_name", "signature")->count()){
            $errors[] = "Signature is required";
        }
        // Disablity Certificate
        if($application->is_pwd){
            if(!$application->attachments->where("doc_name", "pwd_certificate")->count()){
                $errors[] = "Disability Certificate Required.";
            }
        }
        // Ex-Serviceman certificate
        if($application->is_ex_servicement){
            if(!$application->attachments->where("doc_name", "ex_serviceman_certificate")->count()){
                $errors[] = "Ex-Serviceman Certificate Required.";
            }
        }
        // is-employee certificate
        if($application->is_employed){
            if(!$application->attachments->where("doc_name", "noc_certificate")->count()){
                $errors[] = "NOC Certificate Required.";
            }
        }
        // BPL/ AAY certificate
        if($application->is_bpl){
            if(!$application->attachments->where("doc_name", "bpl_card")->count()){
                $errors[] = "BPL/ AAY Document Required.";
            }
        }
        // Sport Certificate
        if($application->application_academic->is_sport_represented){
            if(!$application->attachments->where("doc_name", "sport_certificate")->count()){
                $errors[] = "Sport Certificate Required.";
            }
        }
        // for Master in Design Application
        if(isMasterInDesign($application)){
            if(!$application->attachments->where("doc_name", "portfolio")->count()){
                $errors[] = "Portfolio is required.";
            }
            
            if(!in_array($application->application_academic->ceed_score,['NA','na','0','',null] ) && $application->exam_through=="CEED"){
                if(!$application->attachments->where("doc_name", "ceed_score_card")->count()){
                    $errors[] = "CEED score card is required.";
                }
            }
        }
        // Foreign Student Checking
        if($application->is_foreign){
            if(!$application->attachments->where("doc_name", "document_passport")->count()){
                $errors[] = "Passport Certificate Required.";
            }
            if(!$application->attachments->where("doc_name", "document_english_proficiency_certificate")->count()){
                $errors[] = "English Proficiency Certificate Required.";
            }
            // if(!$application->attachments->where("doc_name", "document_passing_certificate")->count()){
            //     $errors[] = "Passing Certificate Required.";
            // }
            // if(!$application->attachments->where("doc_name", "document_marksheet")->count()){
            //     $errors[] = "Qualifying Marksheet Certificate Required.";
            // }
        }else{  
            //   for indian student.only 
            if($application->caste_id != 1  && $application->caste_id != 3){
                if(!$application->attachments->where("doc_name", "caste_certificate")->count()){
                    $errors[] = "Category Certificate Required For {$application->caste->name}";
                }
                if(!$application->attachments->where("doc_name", "undertaking")->count() && $is_mba[0]==0){
                    $errors[] = "Undertaking is required. Please upload at step 4.";
                }
            }
            // if($application->is_btech || (isMBBT($application) && $application->isNorthEastCandidate())){
            //     if(!$application->attachments->where("doc_name", "prc_certificate")->count()){
            //         $errors[] = "PRC certificate is required. Please upload at step 4.";
            //     }
            // }
        }

        if($errors){
            $error_string = implode("<br>", $errors);
            return redirect()->back()->withError($errors)->with("error","<strong>Please fullfil the required criteria to final submit</strong>.<br>".$error_string);
            dd($errors);
        }
        // 
        DB::beginTransaction();
        try {
            $application->status = "payment_pending";
            if($application->is_mbbt==1 || $application->exam_through=='JOSSA'){
                $application->is_free_reg=1;
            }
            if($application->is_mba==1){
                $application->is_editable=3;
            }
            $application->save();
            $message = "Final Review completed Application No : {$application->id}";
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            DB::rollback();
            return redirect()->back()->with("error", "Something went wrong.");
        }
        DB::commit();
        return redirect()->back()->with("success", "Final review completed. Please go for payment.");
    }
    public function getIndexView()
    {
        $this->guard = get_guard();
        if ($this->guard == "admin") {
            return "admin.applications.index";
        } elseif ($this->guard == "student") {
            return "student.application.index";
            // return "student.dashboard";
        } elseif ($this->guard == "department_user") {
            return "department.applications.index";
        } else {
            return null;
        }


    }
    public function getApplicationView()
    {
        $this->guard = get_guard();
        if ($this->guard == "admin") {
            return "admin.applications.show";
        } elseif ($this->guard == "student") {
        return "student.application.show";
        } elseif ($this->guard == "department_user") {
            return "department.applications.show";
        } else {
            return null;
        }

    }
    public function getApplicationEditView()
    {
        $this->guard = get_guard();
        if ($this->guard == "admin") {
            return "admin.applications.edit";
        } elseif ($this->guard == "student") {
            return "student.application.edit";
        } else {
            return null;
        }
    }
    public function getRules($index)
    {
        if (isset(Application::$rules[$index])) {
            return Application::$rules[$index];
        }
        throw new \Exception("Validation Rules Not Found.", 1);
    }
    public function checkAnmDataEntered($request) {
        if($request->get("academic_anm_stream")){
            return true;
        }elseif($request->get("academic_anm_year")){
            return true;
        }elseif($request->get("academic_anm_board")){
            return true;
        }elseif($request->get("academic_anm_school")){
            return true;
        }elseif($request->get("academic_anm_subject")){
            return true;
        }elseif($request->get("academic_anm_mark")){
            return true;
        }elseif($request->get("academic_anm_percentage")){
            return true;
        }
        return false;
    }
    public function checkVocationalDataEntered($request) {
        if($request->get("academic_voc_stream")){
            return true;
        }elseif($request->get("academic_voc_year")){
            return true;
        }elseif($request->get("academic_voc_board")){
            return true;
        }elseif($request->get("academic_voc_school")){
            return true;
        }elseif($request->get("academic_voc_subject")){
            return true;
        }elseif($request->get("academic_voc_mark")){
            return true;
        }elseif($request->get("academic_voc_percentage")){
            return true;
        }
        return false;
    }
    public function ConvertNullableToRequired($rules, $search_string) {
        foreach($rules as $field_name => $validation_rule){
            if(stripos($field_name, $search_string) !== FALSE){
                // dump($field_name);
                // dump($validation_rule);
                $rules[$field_name] = str_replace("nullable", "required", $validation_rule);
            }
        }
        return $rules;
    }
    public function checkBirthDayLowerLimit($request)
    {
        return false;
        $lowerLimit = Application::$applicant_lower_age_limit;
        $extended_dob = strtotime($request->dob . "+ {$lowerLimit} years");
        dump(date("d-m-Y", $extended_dob));
        $limit_date = strtotime(Application::$dob_compare_to);
        if ($extended_dob < $limit_date) {
            return true;
        }
        return false;
    }
    public function resubmitDocument($encrypted_id)
    {
        // dd("ok");
        $message = "Application Resubmit Option Not Available.";
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments")->whereId($id);
            $application = $application->first();
            if(!$application->resubmit_allow /* || $application->status != "on_hold" */){
                throw new Exception($message, 100);
                
            }
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route("student.home")->with("error", $message);
        }
        
        return view("student.application.resubmit", compact("application"));
    }
    

    public function editSubPref($id)
    {
        $id = Crypt::decrypt($id);
        dd($id);
    }

    public function editMBAScore(Request $request,$encrypted_id)
    {
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments","applied_courses",'cuet_exam_details')->whereId($id);
            if (auth()->guard("student")->check()) {
                $application = $application->where("student_id", auth()->guard("student")->user()->id);
            }
            $application = $application->first();
            // dump($application);

        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route("student.home")->with("error", "Whoops! Something went wrong.");
        }
        return view('admin.students.mba-edit-scoreboard',compact('application'));
    }

    public function updateMBAScore(Request $request, $encrypted_id)
    {
        // dd($request->all());
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments","applied_courses",'cuet_exam_details')->whereId($id);
            if (auth()->guard("student")->check()) {
                $application = $application->where("student_id", auth()->guard("student")->user()->id);
            }
            $application = $application->first();
            $mba_exam=['CAT','MAT','XAT','GMAT','CMAT'];
            foreach($mba_exam as $exam){
                if($request->date_of_exam[$exam]!=null){
                    ExtraExamDetail::updateOrCreate(
                        [
                            'application_id'   => $application->id,
                            'student_id'       => $application->student_id,
                            'name_of_the_exam' => $exam,
                        ],
                        [
                            'application_id'  => $application->id,
                            'student_id'       => $application->student_id,
                            'name_of_the_exam' => $exam,
                            'date_of_exam'     => $request->date_of_exam[$exam],
                            'registration_no'  => $request->reg_no[$exam],
                            'score_obtained'   => $request->score[$exam],
                        ]
                    );
                }
            }
            return redirect()->back()->with('success','Successfully Updated MBA Details');
        } catch (\Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route("student.home")->with("error", "Whoops! Something went wrong.");
        }
        
    }

    public function downloadPdfAdmin(Request $request, $encrypted_id) {
        // dd("ok");
        // dd($encrypted_id);
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            // dd($decrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.admit-card.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $admit_card = AdmitCard::with(["active_application.caste","active_application.attachments", "exam_center","tuee_result"])->findOrFail($decrypted_id);
            // if(!$admit_card->publish){
            //    return  redirect()->back()->with("error", "Admit card is not published yet.");
            // }
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()
                ->route("admin.admit-card.index")
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        // $result=TueeResult::get();
        // dd($admit_card);
        if (auth("student")->check()) {
            
            AdmitCard::where('id',$decrypted_id)->increment('is_downloaded');
        }
        // dd($decrypted_id);
        // return view("common/application/admit_card/admit_card_download", compact("admit_card"));
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
        $pdf = PDF::loadView("common/application/admit_card/admit_card_download", compact("admit_card"));
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Admit-card-".$admit_card->roll_no.'.pdf');
    }


    public function downloadInvitationAdmin(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.admit-card.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $application = Application::where('id',$decrypted_id)->where('net_jrf',1)->where('is_invited',1)->first();
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        $flag = 0;
        foreach($application->applied_courses as $applied){
            // dump()
            if($applied->admitcard){
                $flag =$flag+1;
            }
        }
        if($flag == $application->applied_courses->count()){
            return redirect()->back()->with("error", "Whoops! Something went wrong please contact technical support.");
        }

        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$application->application_no}.");
        $pdf = PDF::loadView("common/application/admit_card/invitation_card_download", compact("application"));
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Provisional-Selection-Card-".$application->application_no.'.pdf');
    }



    public function attendenceSheet(Request $request)
    {

        $sessions = Session::all();
        $center = ExamCenter::get();
        // dd($center);
        $active_session = Session::active()->first();
        if(request("session")){
            $active_session = Session::where("id", request("session"))->first();
        }
        if(!$active_session){
            $active_session = $sessions->sortByDesc("id")->first();
        }




        $center_id=$request->center_name??1;
        $exam_centers = ExamCenter::all();


        $categories = AppliedCourse::whereHas('application', function ($query) use ($active_session,$center_id) {
                return $query->where('is_mba', 0)->where('is_btech',0)->whereIn('net_jrf',[0,2])->where('exam_through','TUEE')
                    ->where('is_cuet_ug',0)
                    ->where('session_id',$active_session->id)
                    ->whereNotNull('application_no')
                    ->when(request('center_name'), function ($q,$center_id) {
                        $q->where('exam_center_id', $center_id);
                    });
            })->select('course_id',DB::raw('count(applied_courses.id) as count'))
            // ->whereNotIn('status',['rejected'])
            ->where(function ($query) {
                $query->whereNotIn('status', ['rejected'])
                    ->orWhereNull('status');
            })
            ->groupBy('course_id')
            ->join('courses','courses.id','=','course_id')
            ->orderBy('courses.name')
            ->get();

        // $categories = DB::table('applied_course')
        // dd($categories);

        return view("admin.admit_card_new.attendence-index",compact('exam_centers','categories','center_id'));
    }

    public function printViewAttendenceSheet(Request $request)
    {
        $center_name=ExamCenter::where('id',$request->center_id)->first();
        $course_name=Course::where('id',$request->course_id)->withTrashed()->first();
        $querry = AdmitCard::where('exam_center_id',$request->center_id)->where('course_id',$request->course_id);
        $count = $querry->count();
        $attendence = $querry->get();
        return view('admin.admit_card_new.attendence_print_view',compact('attendence','center_name','course_name','count'));
    }

    public function printViewAdmitTwo(Request $request)
    {
        set_time_limit(100000);
        $centername=ExamCenter::where('id',$request->center_id)->first();
        $course_name=Course::where('id',$request->course_id)->withTrashed()->first();
        $admit_cards=AdmitCard::where('exam_center_id',$request->center_id)->where('course_id',$request->course_id)->get();
        // return view("common/application/admit_card/admit-card-mass-download", compact("admit_cards"));
        // saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
        $pdf = PDF::loadView("common/application/admit_card/admit-card-mass-download", compact("admit_cards"));
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Admit-card-".$centername->center_name.'('.$course_name->name.').pdf');
        // return view('admin.admit_card_new.admit-card-mass-print',compact('admit_cards'));
    }

    public function downloadScoreCard($encrypted)
    {
        $id=Crypt::decrypt($encrypted);
        // dd($id);
        // $score_card=TueeResult::with('admit_card')->where('id',$id)->where('publish',1)->first();

        $admit_card = AdmitCard::where('id',$id)->first();

        if (auth("student")->check()) {
            
            TueeResult::where('id',$admit_card->tuee_result->id)->increment('is_download');
        }
        // dd($score_card);
        // saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
        $pdf = PDF::loadView("common/application/admit_card/score-card", compact("admit_card"));
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Score-card-".$admit_card->roll_no.'.pdf');
    }

    public function reasonOf(Request $request){
        $reason=AppliedCourse::where('id',$request->course_id)->first();
        return response()->json(['success' => true, 'data' => $reason]);
    }

    public function attendenceSheetNew(Request $request){
        $exam_centers = ExamCenter::get();
        $center_id = $request->center_name??0;
        
        $exam_center = ExamCenter::where('id',$center_id)->get();
        // dd($exam_center);
        $group = GroupMaster::get();
        return view('admin.admit_card_new.attendence-new-index',compact('exam_centers','exam_center','group'));
    }

    public function attendenceSheetViewNew(Request $request){
        $center_name=SubExamCenter::where('id',$request->cen_id)->first();
        // $course_name=Course::where('id',$request->course_id)->withTrashed()->first();
        $groups = GroupMaster::where('group_name',$request->group)->first();
        $querry = AdmitCard::where('sub_exam_center_id',$request->cen_id)->where('exam_group',$request->group);
        $count = $querry->count();
        $attendence = $querry->get();
        return view('admin.admit_card_new.attendence_print_view_new',compact('attendence','center_name','count','groups'));
    }
}
