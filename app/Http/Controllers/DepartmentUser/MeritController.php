<?php

namespace App\Http\Controllers\DepartmentUser;

use App\AdmissionCheckedChecklists;
use App\AttendanceTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use App\CourseSeatTypeMaster;
use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\MeritMaster;
use App\Notifications\StudentAnyMail;
use App\Services\MeritListService;
use Carbon\Carbon;
use Config;
use Crypt;
use DB;
use Exception;
use Illuminate\Support\Collection;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Session;
use Validator;
use App\Http\Controllers\CommomMeritController;
use App\Models\User;
use Pusher\Pusher;

class MeritController extends CommomMeritController
{
    //
    // public function index(Request $request)
    // {
    //     //
    //     // dd("ok");
    //     $course_id             = $request->course_id;
    //     $merit_master_id       = $request->merit_master_id;
    //     $application_no        = $request->application_no;
    //     $status                = $request->status;
    //     $tuee_rank             = $request->tuee_rank;
    //     $admission_category_id = $request->admission_category_id;
    //     $merit                 = $request->merit;
    //     $undertaking_status    = $request->undertaking_status;
    //     $courses               = programmes_array();
    //     $admission_categories  = AdmissionCategory::where('status', 1)->get();
    //     $programmes_array = array();
    //     foreach(programmes_array() as $k=>$programme){
    //             array_push($programmes_array,$k);
    //     }
    //     $merit_lists           = MeritList::/* where('status','!=','4')-> */with(
    //         [
    //             'application' => function ($query) {
    //                 $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id");
    //             }, 'meritMaster', 'admissionCategory', 'course',
    //             "undertakings",
    //         ])
    //         ->withCount(["admissionReceipt"]);
    //     $merit_lists->whereIn('course_id', $programmes_array);
    //     if ($course_id) {
    //         $merit_lists->where('course_id', $course_id);
    //     }
    //     if ($merit_master_id) {
    //         $merit_lists->where('merit_master_id', $merit_master_id);
    //     }
    //     if ($application_no) {
    //         $merit_lists->where('application_no', $application_no);
    //     }
    //     if ($admission_category_id) {
    //         $merit_lists->where('admission_category_id', $admission_category_id);
    //     }
    //     if ($status) {
    //         $merit_lists->where('status', $status);
    //     }
    //     if ($tuee_rank) {
    //         $merit_lists->orderBy('tuee_rank', $tuee_rank);
    //     }
    //     if ($merit) {
    //         if ($merit == "merit") {
    //             $merit_lists->where('selected_in_merit_list', 1);
    //         } elseif ($merit == "waiting") {
    //             $merit_lists->where('selected_in_merit_list', 0);
    //         }
    //     }
    //     if ($undertaking_status) {
    //         if ($undertaking_status == "pending") {
    //             $merit_lists->whereHas("active_undertaking");
    //         } elseif ($undertaking_status == "approved") {
    //             $merit_lists->whereHas("approved_undertaking");
    //         } elseif ($undertaking_status == "rejected") {
    //             $merit_lists->whereHas("rejected_undertaking");
    //             $merit_lists->whereDoesnthave("approved_undertaking");
    //             $merit_lists->whereDoesnthave("active_undertaking");
    //         } elseif ($undertaking_status == "not_uploaded") {
    //             $merit_lists->whereDoesnthave("undertakings");
    //         }
    //     }
    //     $merit_lists = $merit_lists->paginate(60);
    //     return view('department-user.merit.index', compact('merit_lists', 'admission_categories'));
    // }

    public function commonMeritFunction($request)
    {
        $course_id             = $request->course_id??0;
        $merit_master_id       = $request->merit_master_id??0;
        $application_no        = $request->application_no;
        $status                = $request->status;
        $tuee_rank             = $request->tuee_rank;
        $admission_category_id = $request->admission_category_id;
        $merit                 = $request->merit;
        $undertaking_status    = $request->undertaking_status;
        $payment_option        = $request->payment_option;
        
        $merit_lists           = MeritList::/* where('status','!=','4')-> */with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id","is_pwd");
                }, 'meritMaster', 'admissionCategory', 'course',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);
           
            if($course_id!=0){
                $course_id=[72,73,74,75,76,77,83];
            }else{
                $course_id=[0];
            }
            // dd($course_id);
            // if ($course_id) {
                $merit_lists->whereIn('course_id', $course_id);
            // }
        // if ($merit_master_id) {
            $merit_lists->where('merit_master_id', $merit_master_id);
        // }
       
        if ($application_no) {
            $merit_lists->where('application_no', $application_no);
        }
        if ($admission_category_id) {
            if($admission_category_id!=7){
                $merit_lists->where('admission_category_id', $admission_category_id);
            }else{
                $merit_lists->where('is_pwd', 1);
            }    
        }

        if ($status) {
            $merit_lists->where('status', $status);
        }
        if (in_array($payment_option, [0,1]) && !is_null($payment_option)) {
            $merit_lists->where('is_payment_applicable', $payment_option);
        }
        if ($tuee_rank) {
            $merit_lists->orderBy('tuee_rank', $tuee_rank);
        }
        if ($merit) {
            if ($merit == "merit") {
                $merit_lists->where('selected_in_merit_list', 1);
            } elseif ($merit == "waiting") {
                $merit_lists->where('selected_in_merit_list', 0);
            }
        }
        if ($undertaking_status) {
            if ($undertaking_status == "pending") {
                $merit_lists->whereHas("active_undertaking");
            } elseif ($undertaking_status == "approved") {
                $merit_lists->whereHas("approved_undertaking");
            } elseif ($undertaking_status == "rejected") {
                $merit_lists->whereHas("rejected_undertaking");
                $merit_lists->whereDoesnthave("approved_undertaking");
                $merit_lists->whereDoesnthave("active_undertaking");
            } elseif ($undertaking_status == "not_uploaded") {
                $merit_lists->whereDoesnthave("undertakings");
            }
        }
        return $merit_lists;
    }

    public function attendence(Request $request)
    {
        // dd("ok");
        $courses               = Course::withTrashed()->get();
        $admission_categories  = AdmissionCategory::where('status', 1)->get();
        $merit_master_id       = $request->merit_master_id??0;

        $program_array=programmes_array();
            $programs=[];
            foreach($program_array as $key=>$prog){
                 if($key!=""){
                    array_push($programs, $key);
                 }
            }
        $merit_lists = $this->commonMeritFunction($request);
        
        
        // // attendence Open Close Maintain
        // $merit_lists->whereHas("meritMaster", function($query) use($merit_master_id) {
        //     return $query->where("allow_attendence", 1);
        // })->with(["meritMaster"=>function($q) use($merit_master_id){
        //     return $q->where("allow_attendence", "1");
        // }]);
        // // ends here
        $merit_lists = $merit_lists->whereIn('course_id',$programs)->paginate(50) ;
        $sms_templates=0;
        $list=MeritMaster::whereIn('course_id',$programs )->get();
        $merit_lists_filter = MeritList::whereIn('course_id',[72,73,74,75,76,77,83,111])->get();
        return view('department.applications.merit-attendence',compact('courses', 'merit_lists', 'admission_categories', "sms_templates","merit_master_id","list","merit_lists_filter"));
    }

    public function attendanceTrans($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            dd("error");
        }
        $merit_list = MeritList::where('id',$decrypted)->first();
        return view('department.applications.attendance-transaction', compact('merit_list'));
    }

    public function presentAbsentCommit(Request $request, $id)
    {
        $decrypted_id = Crypt::decrypt($id);
        if($decrypted_id > 0){
            DB::beginTransaction();
            try{
            $meritmaster=MeritMaster::where('id',$decrypted_id)->first();
            if($meritmaster->allow_attendence==0){
                return redirect()->back()->with("error", "Already Commit Attendence.");
            }
            MeritList::where(['merit_master_id'=>$decrypted_id, 'attendance_flag'=>0])
                                   ->update(['is_hold'=>1,
                                              'attendance_flag'=>2]);
            MeritMaster::where('id',$decrypted_id)->update(['allow_attendence'=>0,
                                                            'allow_admission' =>1,
                                                            'allow_payment'   =>1]);
            }catch (Exception $e) {
                Log::error($e);
                return redirect()->back()
                    ->with("error", "Whoops! something went wrong. Try again later.");
            }
            DB::commit();
        }else{
            return redirect()->back()
                    ->with("error", "Whoops! something went wrong. Try again later.");
        }
        return redirect()->back()->with("success", "Successfully Commit Attendence.");
    }

    public function presentAbsent(Request $request,$id)
    {
        // dd($request->all());
        
        try {
            $decrypted_id = Crypt::decrypt($id);
            $merit_list=MeritList::with('meritMaster')->where('id',$decrypted_id)->first();
            if($merit_list->may_slide==1){
                return redirect()->back()
                ->with("error", "Default Candidate You Can`t process attendence for this student.");
            }
            // if($merit_list->meritMaster->allow_attendence==0){
            //     return redirect()->back()
            //     ->with("error", "You Can't take attendance as you have Commit this process.");
            // }
            $is_admited=MeritList::with('course')->where('student_id',$merit_list->student_id)->where('status',2)->get();
            foreach($is_admited as $check){
                if($check->freezing_floating == "Freezing"){
                    $merit_list->update(['is_hold'=>1]);
                    return redirect()->back()
                    ->with("error", "Can't take admission in another course as applicant freez seat in ".$check->course->name);
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        if($request->flag=='pre'){
            $flag=1;
            $is_hold=0;
        }else{
            $flag=2; 
            $is_hold=1;   
        }

        $merit_details=MeritList::where('id',$decrypted_id)->first();
        if($merit_details->attendance_flag==1 && $flag==2){
            $this->courseSeatdecrement($merit_details->course_id, $merit_details->application->caste_id);
        }
        
        
        // $order_by=$merit_list->tuee_rank;
        // if($request->is_missed){
        //     $max_cmr=MeritList::where('merit_master_id',$merit_list->merit_master_id)->max('cmr')+1;
        //     $flag=0;
        //     // dd($max_cmr);
        //     while($flag < 1){
                
        //         $check=MeritList::where('merit_master_id',$merit_list->merit_master_id)->where('cmr',$max_cmr)->count();
        //         if($check==0){
        //             $flag=1;
        //         }else{
        //             $max_cmr=MeritList::where('merit_master_id',$merit_list->merit_master_id)->max('cmr')+1;
        //         }
        //     }
        //     $order_by=$max_cmr;
        // }
        MeritList::where(['merit_master_id'=>$merit_details->merit_master_id,
                            'student_id'=>$merit_details->student_id])
                            ->update([
                                'attendance_flag'   => $flag,
                                'attendance_coment' => $request->coment?? null,
                                'attendance_time'   => date('Y-m-d h:m:s'),
                                'is_hold'           => $is_hold,
                                // 'cmr'               => $order_by,
                            ]);

        AttendanceTransaction::create([
                                'ml_id' => $decrypted_id,
                                'attendance_time' => date('Y-m-d h:m:s'),
                                'status' => 'Present',
                                'comment' => $request->coment?? null,
                            ]);
        return redirect()->back();

    }

    public function presentAbsentUndo(Request $request,$id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            dd("error");
        }
        $merit_list=MeritList::where('id',$decrypted)->first();
        if($merit_list->status != 0 || $merit_list->attendance_flag==3){
            return redirect()->back()->with('error','Can`t Undo... Some actions are taken by Admission desk.');
        }

        MeritList::where('id',$decrypted)->update([
            'attendance_flag'   => 0,
            'attendance_coment' => null,
            'attendance_time'   => null,
            'cmr'               => $merit_list->tuee_rank,
        ]);

        AttendanceTransaction::create([
            'ml_id' => $decrypted,
            'attendance_time' => date('Y-m-d h:m:s'),
            'status' => 'Undo',
            'comment' => $request->coment?? null,
        ]);

        return redirect()->back();
    }

    // public function shuffleMeritList($course_id)
    // {
    //     $all_Meritians= MeritList::where('course_id',$course_id)->where('attendance_flag',1)->orderby('tuee_rank')->get();
    //     $general  = CourseSeat::where('course_id',$course_id)->where('admission_category_id',1)->first();
    //     $general1  = $general->total_seats;
    //     $count=0;
    //     foreach($all_Meritians as $key=>$meritians){
    //         if(++$key <= $general1){
    //             if($meritians->selection_category!=null){
    //                 // dump($meritians->selection_category);
    //                 $this->courseSeatdecrement($course_id, $meritians->selection_category);
    //             }
    //             $meritians->update(['selection_category'=>1]);
    //         }else{
    //             $is_seat_available=CourseSeat::where('course_id',$course_id)->where('admission_category_id',$meritians->application->caste_id)->first();      
    //             if($is_seat_available->total_seats > $is_seat_available->temp_attendence_occupied){ 
    //                 $meritians->update(['selection_category'=>$meritians->application->caste_id]);
    //                 $is_seat_available->update(['temp_attendence_occupied'=>$is_seat_available->temp_attendence_occupied+1]);
    //             }elseif($meritians->selection_category==null){                  
    //                 $test=$meritians->update(['selection_category'=>0]);
    //             }          
    //         }          
    //     }
    // }

    public function saveChecklist(Request $request)
    {

        // dd($request->all());
        if($request->document==null){
            return redirect()->back()->with('error','Please Check Checkbox');
        }
        DB::beginTransaction();
        try{
            MeritList::where('id',$request->merit_id)->update(['checklist_remarks'=>$request->checklist_remarks]);
            foreach($request->document as $req){
                AdmissionCheckedChecklists::updateOrCreate([
                    'student_id'     => $request->student_id,
                    'application_id' => $request->application_id,
                    'merit_list_id'  => $request->merit_id,
                    'document_id'    => $req
                ]);
            }
            DB::commit();
        }catch( Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Something went wrong!!');
        }
        return redirect()->back()->with('success','Successfully Submitted Checklist');
    }

    public function courseSeatdecrement($course_id, $admission_category_id)
    {
        $is_seat_available=CourseSeat::where('course_id',$course_id)->where('admission_category_id',$admission_category_id)->first();
        $new=$is_seat_available->temp_attendence_occupied-1;
        $is_seat_available->update(['temp_attendence_occupied'=>$new > 0 ? $new : 0]);
    }



    public function payment(Request $request)
    {
        // dd("ok");
        $courses               = Course::withTrashed()->get();
        $admission_categories  = AdmissionCategory::where('status', 1)->get();
        $program_array=programmes_array();
            $programs=[];
            foreach($program_array as $key=>$prog){
                 if($key!=""){
                    array_push($programs, $key);
                 }
            }
        $merit_lists = $this->commonMeritFunction($request);
        $merit_lists = $merit_lists->whereIn('course_id',$programs)/* ->where('is_payment_applicable',1) */->whereIn('status',[8,2])->paginate(60) ;
        $sms_templates=0;
        $branch=Course::where('program_id',7)->withTrashed()->get();
        $admission_categorymodal=AdmissionCategory::where('status',1)->get();
        return view('admin.merit.department-merit', compact('admission_categorymodal','branch','courses', 'merit_lists', 'admission_categories', "sms_templates"));
        // return view('department.applications.merit-payment',compact('courses', 'merit_lists', 'admission_categories', "sms_templates"));
    }

    public function meritMaster(Request $request)
    {
        //
        // dd("ok");
        $courses              = MeritMaster::where('course_id', $request->course_id)->get();
        $course_id            = $request->course_id;
        $admission_categories = AdmissionCategory::with(['CourseSeats' => function ($query) use ($course_id) {
            $query->where('course_id', $course_id);
        }])->where('status', 1)->get();
        return response()->json(['success' => true, 'data' => $courses, 'admission_categories' => $admission_categories]);

    }

    public function meritMasterNew(Request $request)
    {
        //
        $courses              = MeritMaster::where('id', $request->merit_master)->first();
        $course_id            = $courses->course_id;
        $course_seat_type_id = $courses->course_seat_type_id;
        $admission_categories = AdmissionCategory::with(['CourseSeats' => function ($query) use ($course_id, $course_seat_type_id) {
            $query->where('course_id', $course_id)->where('course_seat_type_id', $course_seat_type_id);
        }])->where('status', 1)->get();
        return response()->json(['success' => true, 'data' => $courses, 'admission_categories' => $admission_categories]);

    }

    public function seatPositions(Request $request)
    {
        $type_id = CourseSeatTypeMaster::where('default_in_filter',1)->pluck("id")->toArray();
        $course_id = $request->course_id;
        $programm_ids = array_keys(programmes_array());
        $all_courses = Course::withTrashed()->whereIn("id", $programm_ids)->get();
        $courses = Course::withTrashed()->whereIn("id", $programm_ids)->with('courseSeats.admissionCategory')->orderBy('name');
        if($course_id)
            $courses = $courses->where('id',$course_id);

        $courses =  $courses->get();
        $course_seat_type  = CourseSeatTypeMaster::get();
        $course_seat_type = CourseSeatTypeMaster::whereIn('id',$type_id)->get();
        return view('department-user.vacancy.index',compact('courses','all_courses','course_seat_type'));

    }
    
    public function admissionPaymentReceipt(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list  = MeritList::findOrFail($decrypted_id);
        $receipt     = $merit_list->admissionReceipt->load("collections.feeHead");
        $application = $merit_list->application;
        // dd($application);
        return view("student.admission.single-receipt", compact("application", "merit_list", "receipt"));
    }

    public function admissionSlidePaymentReceipt(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list  = MeritList::findOrFail($decrypted_id);
        $oldmerit_list = MeritList::where(['student_id'=>$merit_list->student_id,
                                           'course_id' =>$merit_list->course_id,
                                           'status'    =>2])->first();
        $receipt     = $oldmerit_list->admissionReceipt->load("collections.feeHead");
        $application = $oldmerit_list->application;
        // dd($application);
        return view("student.admission.single-receipt", compact("application", "merit_list", "receipt"));
    }

    public function vacancySeatBtech()
    {
       
    }

    // public function declineSeatReceipt(Request $request, $encrypted_id)
    // {
    //     try {
    //         $decrypted_id = Crypt::decrypt($encrypted_id);
    //     } catch (Exception $e) {
    //         // dd($e);
    //         Log::error($e);
    //         return redirect()->back()
    //             ->with("error", "Whoops! something went wrong. Try again later.");
    //     }

    //     $merit_list  = MeritList::findOrFail($decrypted_id);
    //     $application = $merit_list->application;
    //     return view("student.admission.seat-decline-recept",compact('merit_list','application'));
    // }

    public function AssignBranch($id){
        // dd(auth('department_user')->id());
        $decr=crypt::decrypt($id);
        // dd($decr);
        $merit_list=MeritList::where('id',$decr)->first();
        //////check is anyone left
            $check_count = MeritList::where('attendance_flag',1)->where('merit_master_id',$merit_list->merit_master_id)->where('status',0)->where('tuee_rank','<',$merit_list->tuee_rank)->count();
            // dd($check_count);
            if($check_count>0){
                return redirect()->back()->with('error','Some Students are Left');
            }

        /////////////////////
        $filter_array=[];
        if(in_array($merit_list->shortlisted_ctegory_id,[1,5])){
            $filter_array=[1];
        }else{
            $filter_array=[1,$merit_list->shortlisted_ctegory_id];
        }

        if($merit_list->is_pwd==1){
            if(in_array($merit_list->shortlisted_ctegory_id,[1,5])){
                $filter_array=[1];
            }else{
                $filter_array=[$merit_list->shortlisted_ctegory_id];
            }
        }
        // $filter_array=[8,10,11];

        $course = Course::with(['course_seats_new'=>function($q) use($filter_array){
            $q->whereIn('admission_category_id',$filter_array);
        }])
        ->where('program_id',7)
        ->where('id','!=',83)
        ->withTrashed()->get();
        
        

        $branch=Course::where('program_id',7)
                        ->where('id','!=',83)
                        ->withTrashed()->get();
        $admission_categorymodal=AdmissionCategory::where('status',1)->where('id','!=',5)->get();
        return view('admin.merit.assign_branch',compact('course','branch','admission_categorymodal','id','merit_list'));
    }

    public function changeBranch(Request $request,$id){
        // dd($request->all());
        try {
            $decrypted = Crypt::decrypt($id);
            $merit_list=MeritList::where('id',$decrypted)->first();
        } catch (\Exception $e) {
            dd("error");
        }
        $validator = Validator::make($request->all(), [
                'branch_name'   => 'required',
            ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error','Check branch.');
        }
        $array = unserialize(urldecode($request->branch_name));
        $branch_name=$array[0];
        $admission_category = $array[1];
        // dd($array);
        //check is seat available
        if(!$request->below_sixty){
            $unreserve_seat= CourseSeat::where('course_id',$branch_name)->where('admission_category_id',1)->first();
            $unreserve_seat_count = $unreserve_seat->total_seats -($unreserve_seat->total_seats_applied+$unreserve_seat->temp_seat_applied);
            if($unreserve_seat_count>0 && $admission_category!=1 && $merit_list->is_pwd==0){
                return redirect()->back()->with('error','Please assign this candidate to Unreserved Category.');
            }
        }
        
        // dd($unreserve_seat_count);

        $course_seat=CourseSeat::where('course_id',$branch_name)->where('admission_category_id',$admission_category)->first();
        $avail_seat = $course_seat->total_seats -($course_seat->total_seats_applied+$course_seat->temp_seat_applied);
        if($avail_seat<=0){
           return redirect()->back()->with('error','seat capacity exceeded !!');
        }
        
        // dd($decrypted);
        if($merit_list->new_status=="branch_assigned"){
            return redirect()
            ->route('department.merit.index', ['course_id' => 83, 'merit_master_id' => $merit_list->merit_master_id])
            ->with('error', 'Branch Already Assigned');
    
        }

        DB::beginTransaction();
        try{
            MeritList::where('id',$decrypted)->update([
                'admission_category_id' =>  $admission_category,
                'course_id'     => $branch_name,
                'new_status'    => 'branch_assigned',
                'status'        => 8,
                'payment_mode'  => $request->online_ofline,
            ]);

            CourseSeat::where('course_id',$branch_name)
                    ->where('admission_category_id',$admission_category)
                    ->increment('temp_seat_applied');
            if($merit_list->is_pwd==1){
                CourseSeat::where('course_id',$branch_name)
                    ->where('admission_category_id',7)
                    ->increment('temp_seat_applied');
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }
        
        return redirect()
            ->route('department.merit.index', ['course_id' => 83, 'merit_master_id' => $merit_list->merit_master_id])
            ->with('success', 'Branch Assigned');
    }


    public function admissionPaymentReceiptNEW(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list  = MeritList::findOrFail($decrypted_id);
        $receipt     = $merit_list->admissionReceipt->load("collections.feeHead");
        $application = $merit_list->application;
        // dd($application);
        return view("student.admission.single-receipt-new", compact("application", "merit_list", "receipt"));
    }

    public function admissionCanceledReceiptNEW(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list  = MeritList::findOrFail($decrypted_id);
        $application = $merit_list->application;
        // dd($application);
        return view("student.admission.cancel-receipt", compact("application", "merit_list"));
    }

    public function admissionCancel(Request $request){

        $merit_list=MeritList::where('id',$request->ml_id)->first();
        // if($merit_list->status!=2){
        //     return redirect()->back()->with('error','can`t cancel as this student yet not take admission');
        // }
        DB::beginTransaction();
        try{
           
            if($request->type=='deny'){
               $status=4;
            }else{
                $status=6;
            }
            MeritList::where('id',$request->ml_id)->update(['status'=>$status,
                                                            'reason_of_cancel'=>$request->reason]);
                if($merit_list->status==2){
                    CourseSeat::where('course_id',$merit_list->course_id)
                            ->where('admission_category_id',$merit_list->admission_category_id)
                            ->decrement('total_seats_applied');
                    if($merit_list->is_pwd==1){
                        CourseSeat::where('course_id',$merit_list->course_id)
                                    ->where('admission_category_id',7)
                                    ->decrement('total_seats_applied');
                }
            }
            
            DB::commit();
            if($merit_list->status==2){
                $options = [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => false
                ];
                $pusher = new Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );
                $course_seat = CourseSeat::where('admission_category_id',$merit_list->admission_category_id)->where('course_id',$merit_list->course_id)->first();
                $user = User::where('id',$merit_list->student_id)->first();
                $course = Course::where('id',$merit_list->course_id)->withTrashed()->first();
                $admission_category = AdmissionCategory::where('id',$merit_list->admission_category_id)->first();
                $display_message = $user->name." has cancelled the admission in ".$course->name." under ".$admission_category->name;
                
                $data['id'] = $course_seat->id;
                $data['course_id'] = $course_seat->course_id;
                $data['admission_category_id'] = $course_seat->admission_category_id;
                $data['total_seats_applied'] = $course_seat->total_seats_applied;
                $data['total_seats'] = $course_seat->total_seats;
                $data['vacant_seats'] = intval($course_seat->total_seats)-intval($course_seat->total_seats_applied);
                $data['display_message'] = $display_message;
                $pusher->trigger('tezu-admission-channel', 'course-seat', ['message' => "A New record inserted",'response'=>$data]);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','something went wrong');
        }
        return redirect()->route('department.merit.index', ['course_id' => 83, 'merit_master_id' => $merit_list->merit_master_id])->with('success','Successfully canceled.');
    }

    public function absentAtAssignBranch($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            dd("error");
        }
        $merit_list=MeritList::where('id',$decrypted)->first();
        //////check is anyone left
            $check_count = MeritList::where('attendance_flag',1)->where('merit_master_id',$merit_list->merit_master_id)->where('status',0)->where('tuee_rank','<',$merit_list->tuee_rank)->count();
            if($check_count>0){
                return redirect()->back()->with('error','Some Students are Left');
            }
        MeritList::where('id',$decrypted)->update(['attendance_flag'=>3]);

        AttendanceTransaction::create([
            'ml_id' => $decrypted,
            'attendance_time' => date('Y-m-d h:m:s'),
            'status' => 'Not Responded',
            'comment' => 'Removed as not responding at admission desk.',
        ]);

        return redirect()->back()->with('success','successfully Removed from attendence desk');
    }

    public function admissionSeatRelease($encrypted_id){
        // dd("ok");
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list = MeritList::findOrFail($decrypted_id);
        DB::beginTransaction();
        try{
                $merit_list->admissionReceipt()->update([
                                                    "status"    => 1
                                                ]);
                $merit_list->status = 3;
                $merit_list->save();
                // if($merit_list->admission_category_id==1){
                //     $this->slidingChanges($merit_list);                  
                // }
                $course_seat = $merit_list->course_seat();
                $application = $merit_list->application;
                if($course_seat){
                    
                    $course_seat->decrement("total_seats_applied");
                }
                // else{
                //     $message = "Application no {$application->application_no} admission Seat released.";
                //     Log::error($message);
                //     try {
                //         saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
                //     } catch (\Throwable $th) {
                //         Log::error("saveLogs Error application no seat released {$application->application_no}");
                //     }
                // }
                // saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission seat released for application no ".$application->application_no);
                DB::commit();
            }catch(\Exception $e){
                dd($e);
                DB::rollBack();
                return redirect()->back()->with('error','Something went wrong');
            }
            return redirect()->back()->with('success','Successfully Released Seat You Can Take Admission to another program.');
    }
}
