<?php

namespace App\Http\Controllers\common;

use App\Course;
use App\Http\Controllers\Controller;
use App\MailAndMessage;
use App\Models\AdmissionCategory;
use App\Models\CourseSeat;
use App\Models\CourseSeats;
use App\Models\MeritList;
use App\Models\MeritMaster;
use App\Models\User;
use App\Notifications\AdmissionCallEmail;
use App\Notifications\CancelEmail;
use App\Notifications\HoldExtensionEmail;
use App\Notifications\InvitationEmail;
use App\Notifications\WarningMail;
use App\TimeExtendedTran;
use Carbon\Carbon;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Log;
use Session;
use Validator;

class newAdmissionController extends Controller
{
    public function index(Request $request)
    {
        $course_id            = $request->course_id ?? 0;
        $merit_master_id      = $request->merit_master_id ?? 0;
        $courses              = Course::withTrashed()->get();
        $admission_categories = AdmissionCategory::where('status', 1)->get();
        $merit_lists          = MeritList::with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id", "is_pwd");
                }, 'meritMaster', 'admissionCategory', 'course', 'selectionCategory',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);
        $merit_lists = $merit_lists->where('course_id', $course_id);
        if ($request->merit_master_id) {
            $merit_lists = $merit_lists->where('merit_master_id', $merit_master_id);
        }
        if ($request->admission_cat) {
            if (Session::has('success') || Session::has('error')) {
                /////
            } else {
                if ($request->submit == "Process") {
                    $merit_master = MeritMaster::where('id',$merit_master_id)->first();
                    if($merit_master->semi_auto==0){
                        $this->automateProcess($request->admission_cat, $request->merit_master_id);
                    }else{
                        // dd('semi auto');
                        $this->semiAutomateProcess($request->admission_cat, $request->merit_master_id);
                    }
                    
                }
            }
            if ($request->admission_cat == 7) {
                $merit_lists = $merit_lists->whereIn('new_status', ['can_call', 'called', 'cancel', 'admitted', 'declined', 'time_extended'])->where('is_pwd', 1);
            } else {
                $merit_lists = $merit_lists->whereIn('new_status', ['can_call', 'called', 'cancel', 'admitted', 'declined', 'time_extended'])->where('admission_category_id', $request->admission_cat);
            }

        }

        $list=MeritMaster::where('course_id',$course_id )->get();
        $admission_cat = CourseSeat::with('admissionCategory')
            ->where('course_id', $course_id)->orderBy('admission_order')->get();


        $merit_lists = $merit_lists->get();
        if (auth("admin")->check()) {
            $layot = 'admin.layout.auth';
        }else{      
            $layot = 'department-user.layout.auth';
        }
        return view('onlineAdmission.index', compact('courses', 'admission_categories', 'merit_lists','admission_cat','list','layot'));
    }


    
    public function semiAutomateProcess($admission_cat, $merit_master_id)
    {
        $merit_master = MeritMaster::where('id', $merit_master_id)->first();
        $course_id    = $merit_master->course_id;
        $course_seat  = CourseSeats::where('course_id', $course_id)->where('admission_category_id', $admission_cat)->first();
       
        $currentDate = Carbon::now()->format('Y-m-d');
        $admissionDate = Carbon::parse($course_seat->admission_date)->format('Y-m-d');
        if ($currentDate !== $admissionDate) {
            return redirect()->back()->with('error', 'Please wait till Admission Date for this admission category.');
        }

        if ($course_seat->admission_flag == "close") {
            // return 0;
            return redirect()->back()->with('error', 'This Admission Category is over.');
        }

        $available_seat = $course_seat->total_seats - ($course_seat->total_seats_applied + $course_seat->temp_seat_applied);
        $status = ['invited'];
        if ($admission_cat == 7) { //pull the eligible candidates
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->where('is_pwd', 1)->orderBy('tuee_rank')->get();
        } elseif ($admission_cat == 1) {
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->where('selection_category', $admission_cat)->orderBy('tuee_rank')->get();
        } else {
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->where('selection_category', $admission_cat)->orderBy('tuee_rank')->get();
        }

        if ($course_seat->admission_flag == "open") {
            if ($admission_cat == 7) {
                $check_count = MeritList::where('is_pwd', 1)->where('merit_master_id', $merit_master_id)->whereIn('new_status', ['can_call', 'called'])->count();
            } else {
                $check_count = MeritList::where('admission_category_id', $admission_cat)->where('merit_master_id', $merit_master_id)->whereIn('new_status', ['can_call', 'called'])->count();
            }
            // if ($check_count > 0) {
            //     return redirect()->back()->with('error', 'Some students are already in the admission queue cancel them or let them finished first.');
            // }
        }
        

        // if ($available_seat == 0 || $eligible_students->count() == 0) { //close if already over
        //     $check_if_some_are_not_invited=$this->CheckIsAllAreInvited($admission_cat,$merit_master_id);
        //     if($check_if_some_are_not_invited && $eligible_students->count() == 0){
        //         return redirect()->back()->with('error', 'Students are not invited yet, can`t proceed. ');
        //     }
        //     // if()
        //     // CourseSeats::where('course_id', $course_id)->where('admission_category_id', $admission_cat)->update(['admission_flag' => 'close', 'is_selection_active' => 0]);
        //     // return redirect()->back()->with('error', 'This admission category is Closed.');
        // }
        
        DB::beginTransaction();
        try {
            if ($available_seat > $eligible_students->count()) {$available_seat = $eligible_students->count();} //handle list index out of range
            // dd($available_seat);
            for ($i = 0; $i < $available_seat; $i++) {
                $merit_list_id = $eligible_students[$i]->id;
                    $merit_list=MeritList::where('id',$merit_list_id)->first();
                    // dd($merit_list);
                    MeritList::where('id', $merit_list_id)->update(['new_status' => 'can_call',
                                                                    'admission_category_id' => $merit_list->selection_category,
                ]);
            }
            CourseSeats::where('course_id', $course_id)->where('admission_category_id', $admission_cat) /* ->where('admission_flag',null) */->update(['admission_flag' => 'open', 'is_selection_active' => 1]);
            DB::commit();
        } catch (\Exception $e) {DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Oopps Something went wrong.');
        }
        return redirect()->back()->with('success', 'Successfull.');
    }


    public function automateProcess($admission_cat, $merit_master_id)
    {
        $merit_master = MeritMaster::where('id', $merit_master_id)->first();
        $course_id    = $merit_master->course_id;
        $course_seat  = CourseSeats::where('course_id', $course_id)->where('admission_category_id', $admission_cat)->first();
        // dd($course_seat);
        //validate serialization
        // $course_status=CourseSeat::
        // dd(date('d-m-Y h:s:a',  strtotime(now())));
        
        if($merit_master->sliding_possibility==1 && $admission_cat==1){
            $total_seat_applied=CourseSeat::where('course_id',$merit_master->course_id)
                    ->where('admission_category_id','!=',1)
                    ->sum('total_seats_applied');
            
            $pwd_seat_applied= CourseSeat::where('course_id',$merit_master->course_id)
                    ->where('admission_category_id',7)
                    ->sum('total_seats_applied');
            $count=$total_seat_applied-$pwd_seat_applied;
            // dd($count);
            if($count>0){
                return redirect()->back()->with('error','Its a case of sliding please contact System Admin');
            } 
           
        }

        $admission_order = $course_seat->admission_order;
        if ($admission_order != 1) {
            for ($j = 1; $j < $admission_order; $j++) {
                $status = CourseSeats::where('course_id', $course_id)->where('admission_order', $j)->first();
                if ($status->admission_flag != 'close') {
                    // return 0;
                    return redirect()->back()->with('error', 'Please maintain the Admission Order.');
                }
            }
        }
        //Ends
        if ($course_seat->admission_flag == "close") {
            // return 0;
            return redirect()->back()->with('error', 'This Admission Category is over.');
        }

        $available_seat = $course_seat->total_seats - ($course_seat->total_seats_applied + $course_seat->temp_seat_applied);
        $status = ['invited'];
        if ($admission_cat == 7) { //pull the eligible candidates
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->where('is_pwd', 1)->orderBy('tuee_rank')->get();
        } elseif (in_array($admission_cat,[1,8,10,11] )) {
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->orderBy('tuee_rank')->get();
        } else {
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->where('shortlisted_ctegory_id', $admission_cat)->orderBy('tuee_rank')->get();
        }

        // if ($course_seat->admission_flag == "open") {
        //     if ($admission_cat == 7) {
        //         $check_count = MeritList::where('is_pwd', 1)->where('merit_master_id', $merit_master_id)->whereIn('new_status', ['can_call'])->count();
        //     } else {
        //         $check_count = MeritList::where('admission_category_id', $admission_cat)->where('merit_master_id', $merit_master_id)->whereIn('new_status', ['can_call'])->count();
        //     }
        //     if ($check_count > 0) {
        //         return redirect()->back()->with('error', 'Some students are already in the admission queue cancel them or let them finished first.');
        //     }
        // }
        

        if ($available_seat == 0 || $eligible_students->count() == 0) { //close if already over
            $check_if_some_are_not_invited=$this->CheckIsAllAreInvited($admission_cat,$merit_master_id);
            if($check_if_some_are_not_invited && $eligible_students->count() == 0){
                return redirect()->back()->with('error', 'Students are not invited yet, can`t proceed. ');
            }
            if($course_seat->temp_seat_applied==0){
                CourseSeats::where('course_id', $course_id)->where('admission_category_id', $admission_cat)->update(['admission_flag' => 'close', 'is_selection_active' => 0]);
                return redirect()->back()->with('error', 'This admission category is Closed.');
            }else{
                return redirect()->back()->with('error', 'Students are processing there admission process please wait.');
            }
        }

        //////new condition
        $processing_student=MeritList::where('course_id', $merit_master->course_id)
                                    ->where('admission_category_id', $admission_cat)
                                    ->whereIn('new_status', ['can_call','called'])->count();
        $new_seat_avail=$course_seat->total_seats-$course_seat->total_seats_applied;
        // dump($new_seat_avail);
        $new_seat_avail= $new_seat_avail-$processing_student;
        // dump($processing_student);
        if($new_seat_avail<=0){
            return redirect()->back()->with('error', 'Students are processing there admission process please Wait.');
        }
        /////ends

        $currentDate = Carbon::now()->format('Y-m-d');
        $admissionDate = Carbon::parse($course_seat->admission_date)->format('Y-m-d');
        if ($currentDate !== $admissionDate) {
            return redirect()->back()->with('error', 'Please wait till Admission Date for this admission category.');
        }
        
        DB::beginTransaction();
        try {
            if ($available_seat > $eligible_students->count()) {$available_seat = $eligible_students->count();} //handle list index out of range
            // dd($available_seat);
            for ($i = 0; $i < $available_seat; $i++) {
                $merit_list_id = $eligible_students[$i]->id;
                if ($admission_cat == 7) {
                    if($eligible_students[$i]->shortlisted_ctegory_id==5){
                        $admission_catagory = 1;
                    }else{
                        $admission_catagory = $eligible_students[$i]->shortlisted_ctegory_id;
                    }   
                } else {
                    $admission_catagory = $admission_cat;
                }
                MeritList::where('id', $merit_list_id)->update(['new_status' => 'can_call',
                                                                'admission_category_id' => $admission_catagory
                ]);
            }
            CourseSeats::where('course_id', $course_id)->where('admission_category_id', $admission_cat) /* ->where('admission_flag',null) */->update(['admission_flag' => 'open', 'is_selection_active' => 1]);
            DB::commit();
        } catch (\Exception $e) {DB::rollback();
            // dd($e);
            return redirect()->back()->with('error', 'Oopps Something went wrong.');
        }
        return redirect()->back()->with('success', 'Successfull.');
    }

    public function CheckIsAllAreInvited($admission_cat,$merit_master_id){
        $status = ['queue'];
        if ($admission_cat == 7) { //pull the eligible candidates
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->where('is_pwd', 1)->orderBy('tuee_rank')->get();
        } elseif ($admission_cat == 1) {
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->orderBy('tuee_rank')->get();
        } else {
            $eligible_students = MeritList::where('merit_master_id', $merit_master_id)->whereIn('attendance_flag', [0,1])->whereIn('new_status', $status)->where('shortlisted_ctegory_id', $admission_cat)->orderBy('tuee_rank')->get();
        }
        if($eligible_students->count()>0){
            return true;
        }else{
            return false;
        }    
    }
    
    public function trackProcess(Request $request)
    {
        // dd($request->all());
        $course_id            = $request->course_id ?? 0;
        $merit_master_id      = $request->merit_master_id ?? 0;
        $courses              = Course::withTrashed()->get();
        $admission_categories = AdmissionCategory::where('status', 1)->get();
        $merit_lists          = MeritList::with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id", "is_pwd");
                }, 'meritMaster', 'admissionCategory', 'course', 'selectionCategory',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);
        $merit_lists = $merit_lists->where('course_id', $course_id);
        if ($request->merit_master_id) {
            $merit_lists = $merit_lists->where('merit_master_id', $merit_master_id);
        }
        if($request->application_no){
            $merit_lists = $merit_lists->where('application_no', $request->application_no);
        }
        if($request->social_category_id){
            if($request->social_category_id == '7'){
                $merit_lists = $merit_lists->where('is_pwd', 1);
            }else{
                $merit_lists = $merit_lists->where('shortlisted_ctegory_id', $request->social_category_id);
            }
    
        }
        if($request->admission_category_id){
            $merit_lists = $merit_lists->where('admission_category_id', $request->admission_category_id);
        }

        if ($request->status) {
            // dd($request->status);
            if($request->status == 'reported'){
                $merit_lists = $merit_lists->where('attendance_flag',1);
            } elseif($request->status == 'denied'){
                $merit_lists = $merit_lists->where('attendance_flag', 2);
            } else{
                $merit_lists = $merit_lists->where('new_status',$request->status);
            }
        }

        if($request->preference){
            $merit_lists = $merit_lists->where('preference',$request->preference);
        }




        $merit_lists = $merit_lists->paginate(200);

        $list=MeritMaster::where('course_id',$course_id )->get();
        if (auth("admin")->check()) {
            $layot = 'admin.layout.auth';
        }else{      
            $layot = 'department-user.layout.auth';
        }
        return view('onlineAdmission.track-view', compact('courses', 'admission_categories', 'merit_lists','list','layot'));
    }

    public function loadCategory(Request $request)
    {

        $admission_categories = CourseSeat::with('admissionCategory')
            ->where('course_id', $request->course_id)
        /* ->where('admission_flag','!=','Closed') */->orderBy('admission_order')->get();
        return response()->json(['success' => true, 'data' => $admission_categories]);

    }


    public function callForAdmission(Request $request)
    {
        $rules = [
            "date_from"    => "required",
            "closing_time" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error','please fill all the field');
        }
        if($request->merit_list_ids==null){
            return redirect()->back()->with('error','please select student');
        }

        // $currentDate = Carbon::now()->format('Y-m-d');
        // $admissionDate = Carbon::parse($course_seat->admission_date)->format('Y-m-d');
        // if ($currentDate !== $admissionDate) {
        //     return redirect()->back()->with('error', 'Please wait till Admission Date for this admission category.');
        // }

        if ($request->closing_time == 1) {
            $opening_time = date('Y-m-d', strtotime($request->date_from)) . ' 10:00:00';
            $closing_time = date('Y-m-d', strtotime($request->date_from)) . ' 13:00:00';
        } elseif ($request->closing_time == 2) {
            $opening_time = date('Y-m-d', strtotime($request->date_from)) . ' 14:00:00';
            $closing_time = date('Y-m-d', strtotime($request->date_from)) . ' 17:00:00';
        } elseif ($request->closing_time == 3) {
            $opening_time = date('Y-m-d', strtotime($request->date_from)) . ' 10:00:00';
            $closing_time = date('Y-m-d', strtotime('2024-01-16')) . ' 24:00:00';
        }

        DB::beginTransaction();
        try{
          
            foreach ($request->merit_list_ids as $key => $id) {
                MeritList::where('id', $id)->update([
                    'status'     => 7,
                    'new_status' => 'called',
                    'valid_from' => $opening_time,
                    'valid_till' => $closing_time,
                ]);

                $ml=MeritList::where('id',$id)->first();


                $course_seat=CourseSeat::where('course_id',$ml->course_id)->where('admission_category_id',$ml->admission_category_id)->first();
                $course_seat->increment("temp_seat_applied");
                
                if($ml->is_pwd==1){
                    $course_seat_pws=CourseSeat::where('course_id',$ml->course_id)->where('admission_category_id',7)->first();
                    $course_seat_pws->increment("temp_seat_applied");
                }

                $user = User::where('id',$ml->student_id)->first();
                $full_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                $time_period=date('h:s:a', strtotime($ml->valid_from)).' of '.date('d-m-Y', strtotime($ml->valid_from)) .' to '. date('h:s:a', strtotime($ml->valid_till)).' of '.date('d-m-Y', strtotime($ml->valid_till));
                $course_name= $ml->course->name;
                $user->notify(new AdmissionCallEmail($user,$time_period,$full_name,$course_name));
               
                $from_time=date('h:s:a', strtotime($ml->valid_from));
                $to_time= date('h:s:a', strtotime($ml->valid_till));
                $from_date=date('d-m-Y', strtotime($ml->valid_from));
                $to_date=date('d-m-Y', strtotime($ml->valid_till));
                $message="Dear candidate, You are advised to visit TU website and make the payment to book your seat for the {$course_name} between {$from_time} of {$from_date} to {$to_time} of {$to_date} through the admission portal https://tezuadmissions.in. -Tezpur University";
                
                  
                sendSMSNew($user->mobile_no, $message, "1107168812465758782");
                MailAndMessage::create([
                    'student_id'=> $id,
                    'merit_list_id'=> $ml->student_id,
                    'message'=>	$message,
                    'mail'=> 'send',
                ]);
            }
            DB::commit();
        }catch(\Exception $e){
            // dd($e);
            DB::rollBack();
            return redirect()->back()->with('error','something went wrong');
        }
        return redirect()->back()->with('success', 'Successfully called students');
    }

    public function cancelForAdmission(Request $request)
    {
        
        DB::beginTransaction();
        try{
            MeritList::where('id', $request->merit_list_id)->update(['new_status' => 'cancel', 
            'status' => 4,
            'reason_of_cancel' => $request->reason,
            'message_to_candidate' => $request->message]);
            //Mail to student---
            $ml=MeritList::where('id',$request->merit_list_id)->first();

            $course_seat=CourseSeat::where('course_id',$ml->course_id)->where('admission_category_id',$ml->admission_category_id)->first();
            // dd($course_seat);
            $course_seat->decrement("temp_seat_applied");
            
            if($ml->is_pwd==1){
                $course_seatii=CourseSeat::where('course_id',$ml->course_id)->where('admission_category_id',7)->first();
                $course_seatii->decrement("temp_seat_applied");
            }

            if($ml->admission_category_id==1){
                if($ml->may_slide==1){
                    $course_seat->decrement('temp_attendence_occupied');
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }

        $user = User::where('id',$ml->student_id)->first();
        $full_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
        $time_period=date('d-m-Y h:s:a', strtotime($ml->valid_from)) .' to '. date('d-m-Y h:s:a', strtotime($ml->valid_till));
        $course_name= $ml->course->name;
        $message= $request->message;
        if($message != null){
            $user->notify(new CancelEmail($user, $time_period, $full_name, $course_name, $message));
        }
        return redirect()->back()->with('success', 'Successfully cancelled the student');
    }



    public function reportingBefore(Request $request)
    {
        // dump("ok");
        $course_id            = $request->course_id ?? 0;
        $merit_master_id      = $request->merit_master_id ?? 0;
        $courses              = Course::withTrashed()->get();
        $admission_categories = AdmissionCategory::where('status', 1)->get();
        $merit_lists          = MeritList::with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id", "is_pwd");
                }, 'meritMaster', 'admissionCategory', 'course',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);
        $merit_lists = $merit_lists->where('course_id', $course_id);
        if ($request->merit_master_id) {
            $merit_lists = $merit_lists->where('merit_master_id', $merit_master_id);
        }
        if ($request->has('admission_cat')) {
            $course_seat    = CourseSeats::where('course_id', $course_id)->where('admission_category_id', $request->admission_cat)->first();
            if ($request->submit == "Process" && $course_seat->invitation_flag==0) {         
                $available_seat = $course_seat->total_seats - $course_seat->total_seats_applied;
                $count          = $request->ratio * $available_seat;
                if ($request->admission_cat == 7) { //for pwd
                    $merit_lists = $merit_lists->where('is_pwd', 1)->whereIn('new_status',['queue','invited'])->take($count)->orderBy('tuee_rank');
                } elseif (in_array($request->admission_cat,[1,8,10,11] )) { //for general
                    $merit_lists = $merit_lists->whereIn('new_status',['queue','invited'])->take($count)->orderBy('tuee_rank');
                } else { //others
                    $merit_lists = $merit_lists->where('shortlisted_ctegory_id', $request->admission_cat)->whereIn('new_status',['queue','invited'])->take($count)->orderBy('tuee_rank');
                }
            }
            // elseif($request->submit == "Invited But Not Responded"){
            //     if ($request->admission_cat == 7) { //for pwd
            //         $merit_lists = $merit_lists->where('is_pwd', 1)->whereIn('new_status',['invited'])->where('attendance_flag',0)->where('valid_till','<=',now())->orderBy('tuee_rank');
            //     } elseif (in_array($request->admission_cat,[1,8,10,11] )) { //for general
            //         $merit_lists = $merit_lists->whereIn('new_status',['invited'])->where('attendance_flag',0)->where('valid_till','<=',now())->orderBy('tuee_rank');
            //     } else { //others
            //         $merit_lists = $merit_lists->where('shortlisted_ctegory_id', $request->admission_cat)->whereIn('new_status',['invited'])->where('attendance_flag',0)->where('valid_till','<=',now())->orderBy('tuee_rank');
            //     }
            //     // dd($merit_lists->get());
            // }    
            else{
                
                // return redirect()->back()->with('error','Inviation Process is closed.');
                if ($request->admission_cat == 7) { //for pwd
                    $merit_lists = $merit_lists->where('is_pwd', 1)->where('new_status','invited')->orderBy('tuee_rank');
                } elseif ($request->admission_cat == 1) { //for general
                    $merit_lists = $merit_lists->where('new_status','invited')->orderBy('tuee_rank');
                } else { //others
                    $merit_lists = $merit_lists->/* where('shortlisted_ctegory_id', $request->admission_cat)-> where('new_status','invited')->*/orderBy('tuee_rank');
                }
            }
        }
        $list=MeritMaster::where('course_id',$course_id )->get();
        $admission_cat = CourseSeat::with('admissionCategory')
            ->where('course_id', $course_id)->orderBy('admission_order')->get();
        $merit_lists = $merit_lists->get();

        if (auth("admin")->check()) {
            $layot = 'admin.layout.auth';
        }else{      
            $layot = 'department-user.layout.auth';
        }
        
        return view('onlineAdmission.reporting-control', compact('courses', 'admission_categories', 'merit_lists','list','admission_cat','layot'));
        // return view('onlineAdmission.reporting-control', compact('courses', 'admission_categories', 'merit_lists'));
    }



    public function inviteForAdmission(Request $request){
        $rules = [
            "date_from"    => "required",
            "date_to" => "required",
        ];
       
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error','please select date from and date to');
        }
        if($request->merit_list_ids==null){
            return redirect()->back()->with('error','please select students');
        }
        $check_is_already_invided= CourseSeat::where('course_id', $request->course)->where('admission_category_id', $request->category_id)->first();
        if($check_is_already_invided->invitation_flag==1){
            return redirect()->route('department.merit.invite-for-admission')->with('error',' Some students are already invited for this category, can`t invite more.');
        }
        DB::beginTransaction();
        try{

            foreach ($request->merit_list_ids as $key => $id) {
                MeritList::where('id', $id)->update([
                   'new_status' => 'invited',
                   'valid_from' => $request->date_from,
                   'valid_till' => $request->date_to,
                ]);


                $ml=MeritList::where('id',$id)->first();
                $user = User::where('id',$ml->student_id)->first();
                $full_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                $time_period=date('h:s:a', strtotime($ml->valid_from)).' of '.date('d-m-Y', strtotime($ml->valid_from)) .' to '. date('h:s:a', strtotime($ml->valid_till)).' of '.date('d-m-Y', strtotime($ml->valid_till));
                $course_name= $ml->course->name;
                $user->notify(new InvitationEmail($user,$time_period,$full_name,$course_name));
                // dd("ok");
                $from_time=date('h:s:a', strtotime($ml->valid_from)). ' of ' . date('d-m-Y', strtotime($ml->valid_from));
                $to_time= date('h:s:a', strtotime($ml->valid_till)). ' of ' . date('d-m-Y', strtotime($ml->valid_till));
                // $from_date=date('d-m-Y', strtotime($ml->valid_from));
                // $to_date=date('d-m-Y', strtotime($ml->valid_till));
                // $message="Dear candidate, You are advised to visit TU website and report to book your seat for the {$course_name} between {$from_time} of {$from_date} to {$to_time} of {$to_date} through the admission portal https://tezuadmissions.in. -Tezpur University";
                $message="Dear candidate, You are advised to visit Tezpur Univ website and report for the {$course_name} between {$from_time} to {$to_time} through the admission portal http://tezuadmission.in. -Tezpur University";
                sendSMSNew($user->mobile_no, $message, "1107168897180701420");
                MailAndMessage::create([
                    'student_id'=> $id,
                    'merit_list_id'=> $ml->student_id,
                    'message'=>	$message,
                    'mail'=> 'send',
                ]);

            }

            DB::commit();
        }catch(\Exception $e){
            dd($e);
            DB::rollBack();
        }
        
        return redirect()->route('department.merit.invite-for-admission')->with('success', 'Successfully Invited students for reporting');
    }

    public function holdSeatOnline($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error','somthing went wrong');
        }
        DB::beginTransaction();
        try{
            $valid_ml=MeritList::where('id',$decrypted)->first();
            $valid_till=Carbon::parse($valid_ml->valid_till);
            // dd($valid_till);
            $date_to      = $valid_till->copy()->addHours(4);
            MeritList::where('id',$decrypted)->update(['new_status'=>'time_extended',
                                                        'valid_till'=>$date_to, ]);
            $course_seat=CourseSeat::where('course_id',$valid_ml->course_id)->where('admission_category_id',$valid_ml->admission_category_id)->first();
            // $course_seat->increment("temp_seat_applied");

            $ml=MeritList::where('id',$decrypted)->first();
            $user = User::where('id',$ml->student_id)->first();
            $full_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
            $time_period=date('d-m-Y h:s:a', strtotime($ml->valid_from)) .' to '. date('d-m-Y h:s:a', strtotime($ml->valid_till));
            $course_name= $ml->course->name;

            TimeExtendedTran::create([  'merit_list_id' => $valid_ml->id,
                                        'student_id' =>	$valid_ml->student_id,
                                        'previous_time' => $valid_ml->valid_from.' to '.$valid_ml->valid_till,
                                        'new_time' => $ml->valid_from.' to '.$ml->valid_till ,
                                    ]);
                                    
            // $user->notify(new HoldExtensionEmail($user,$time_period,$full_name,$course_name));


            DB::commit();
        }catch(\Exception $e){
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error','Something went wrong');
        }
        
        return redirect()->back()->with('success','Successfully Hold seat and extended time for completion');
    }

    public function AdmissionControl(){
        
        $courses = Course::withTrashed()->get();
        if (auth("admin")->check()) {
            $layot = 'admin.layout.auth';
        }else{      
            $layot = 'department-user.layout.auth';
        }
        return view('onlineAdmission.admission-control', compact('courses', 'layot'));
    }

    public function AdmissionControlSave($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            dd($e);
        }
        $old=Course::where('id',$decrypted)->withTrashed()->first();
        // dd($decrypted);
        if($old->admission_status==1){
            $new_status=0;
        }else{
            $new_status=1;
        }
        Course::where('id',$decrypted)->withTrashed()->update(['admission_status'=>$new_status]);
        return redirect()->back()->with('success','successfull....');
    }

    public function assignNewTime(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try{
            $merit_list=MeritList::where('id',$request->merit_list_id_new_time)->first();
            MeritList::where('id',$request->merit_list_id_new_time)->update([
                                                                        'valid_from'=> $request->new_date_from,
                                                                        'valid_till'=> $request->new_date_to,
                                                                    ]);    
            TimeExtendedTran::create([  'merit_list_id' => $merit_list->id,
                                        'student_id' =>	$merit_list->student_id,
                                        'previous_time' => $merit_list->valid_from.' to '.$merit_list->valid_till,
                                        'new_time' => $request->new_date_from.' to '.$request->new_date_to ,
                                    ]);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','something went wrong');
        }
        return redirect()->back()->with('success','Time is extended successfully');
    }


    public function sendWarning(Request $request)
    {
        MeritList::where('id', $request->merit_list_id)->update([
            'warning_message' => $request->message,
            'is_warning_send' => 1,
        ]);

        $ml = MeritList::where('id', $request->merit_list_id)->first();
        $user = User::where('id', $ml->student_id)->first();
        $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
        $time_period = date('d-m-Y h:s:a', strtotime($ml->valid_from)) . ' to ' . date('d-m-Y h:s:a', strtotime($ml->valid_till));
        $course_name = $ml->course->name;
        $message = $request->message;
        $user->notify(new WarningMail($user, $time_period, $full_name, $course_name, $message));
        return redirect()->back()->with('success', 'Warning Has Been Sent');
    }

    

    public function vacancy(Request $request)
    {

        $course_id = $request->course_id;
        $all_courses = Course::where('program_id',7)->where('id','!=',83)->withTrashed()->get();
        $courses = Course::where('program_id',7)->with('courseSeats.admissionCategory')->withTrashed()->orderBy('name');
        if($course_id)
            $courses = $courses->where('id',$course_id);
        $courses =  $courses->get();
        return view('admin.vacancy.index',compact('courses','all_courses'));

    }


    public function hostelPaymentReceipt(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        // dd("ok");
        $merit_list  = MeritList::findOrFail($decrypted_id);
        $receipt     = $merit_list->hostelReceipt->load("collections.feeHead");
        $application = $merit_list->application;
        
        // dd($merit_list->admissionReceipt->admission_category->name);
        return view("department-user.hostel.receipt", compact("application", "merit_list", "receipt"));
    }


    public function hostelRePaymentReceipt(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        // dd("ok");
        $merit_list  = MeritList::findOrFail($decrypted_id);
        $receipt     = $merit_list->hostelReceiptRepayment->load("collections.feeHead");
        
        $application = $merit_list->application;
        // dd($receipt);
        // dd($merit_list->admissionReceipt->admission_category->name);
        return view("additional.receipt", compact("application", "merit_list", "receipt"));
    }

    public function cancelByAdmin($encrypted_id){
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }

        try{
            MeritList::where('id', $decrypted_id)->update(['new_status' => 'cancel', 
            'status' => 4,
            'reason_of_cancel' => 'Canceled By Admin']);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }

        return redirect()->back()->with('success', 'Successfully cancelled the student');
    }

    public function ARF($encrypted_id){
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
        // dd($receipt);
        // $hostel_receipt = $merit_list->hostelReceipt->load("collections.feeHead");
        $application = $merit_list->application;
        return view("department-user.hostel.arf", compact("application", "merit_list", "receipt"));
        
    }
}
