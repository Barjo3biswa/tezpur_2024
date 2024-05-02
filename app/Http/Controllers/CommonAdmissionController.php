<?php

namespace App\Http\Controllers;

use App\AdmissionCheckedChecklists;
use App\AdmissionChecklist;
use App\Course;
use App\Helpers\CommonHelper;
use App\Models\AdmissionCategory;
use App\Models\AdmissionReceipt;
use App\Models\Application;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\MeritListUndertaking;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\StudentAnyMail;
use App\Traits\StudentsAdmission;
use App\Traits\VknrlPayment;
use Crypt;
use DB;
use Exception;
use Webpatser\Uuid\Uuid;
use Gate;
use Illuminate\Http\Request;
use Log;
use Mail;
use Pusher\Pusher;

class CommonAdmissionController extends Controller
{
    use StudentsAdmission , VknrlPayment;
    public function bookSeat(Request $request,$encrypted_id)
    {
        // dd("ok");
        try {
            $dec_data = Crypt::decrypt($encrypted_id);
            // dd($dec_data['app_id']);
            $application = Application::find($dec_data['app_id']);
            
            $course_id = $dec_data['course_id'];
            $merit_master_id= $dec_data['merit_master_id'];
        } catch (Exception $e) {
            abort(404, "Invalid Request");
        }
        // if($application->student_id != auth("student")->id() ){
        //     return redirect()
        //         ->back()
        //         ->with("error", "Access denied.");
        // }
        
        if(auth("department_user")->check()){
            // dd("ok");
            $checklist_data=AdmissionChecklist::get();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Visiting Book Seat page with application no ".$application->application_no);
            $application->load(["merit_list.course", "applied_courses.course"]);
            return view("student.admission.boot-seat", compact("application","checklist_data","course_id","merit_master_id"));
        }else{
            return redirect()
                ->back()
                ->with("error", "Access denied.");
        }
        
    }
    
    
    public function uploadingUnderTaking(MeritList $meritList)
    {
        if(!$meritList){
            return redirect()
                ->back()
                ->with("error", "Whoops! something went wrong.");
        }

        $this->validate(request(), $this->getUndertakingRules($meritList));
        $application = $meritList->application;
        if($meritList->undertakings->count() >= MeritListUndertaking::$upload_try_limit){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Undertaking uploading limit crossed. ".$application->application_no);
            return redirect()
                ->back()
                ->with("error", "Uploading limit crossed.");
        }
        if(!Gate::check('access-via-application', $application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission undertaking uploading error for application no ".$application->application_no);
            return redirect()
                ->back()
                ->with("error", "Unauthorized access.");
        }
        $return_data = [];
        $destinationPath = public_path('uploads/' . $application->student_id . "/" . $application->id);
        if (request()->hasFile('undertaking')) {
            $undertaking = request()->file('undertaking');
            $undertaking_name = date('YmdHis') . "_" . rand(4512, 6859) . "-undertaking." . $undertaking->getClientOriginalExtension();
            $undertaking->move($destinationPath . "/", $undertaking_name);
            $return_data = [
                "doc_name" => "undertaking",
                "file_name" => $undertaking_name,
                "original_name" => $undertaking->getClientOriginalName(),
                "mime_type" => $undertaking->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        $bec_uploads_documents = [];
        if (request()->hasFile('marksheet')) {
            $marksheet = request()->file('marksheet');
            $marksheet_name = date('YmdHis') . "_" . rand(4512, 6859) . "-marksheet." . $marksheet->getClientOriginalExtension();
            $marksheet->move($destinationPath . "/", $marksheet_name);
            $bec_uploads_documents[] = [
                "doc_name" => "12_+_2_marksheet",
                "file_name" => $marksheet_name,
                "original_name" => $marksheet->getClientOriginalName(),
                "mime_type" => $marksheet->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('category')) {
            $category = request()->file('category');
            $category_name = date('YmdHis') . "_" . rand(4512, 6859) . "-category." . $category->getClientOriginalExtension();
            $category->move($destinationPath . "/", $category_name);
            $bec_uploads_documents[] = [
                "doc_name" => "category",
                "file_name" => $category_name,
                "original_name" => $category->getClientOriginalName(),
                "mime_type" => $category->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('prc')) {
            $prc = request()->file('prc');
            $prc_name = date('YmdHis') . "_" . rand(4512, 6859) . "-prc." . $prc->getClientOriginalExtension();
            $prc->move($destinationPath . "/", $prc_name);
            $bec_uploads_documents[] = [
                "doc_name" => "PRC",
                "file_name" => $prc_name,
                "original_name" => $prc->getClientOriginalName(),
                "mime_type" => $prc->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        DB::beginTransaction();
        try {
            // for btech candidate same document uploaded for other selection list too.
            // reflection is also reflected on other selected list.
            // which merit list is already admitted thats should be release.
            $inserted_documents    = [];
            if($return_data){
                $inserted_documents[]  = MeritListUndertaking::create([
                    "merit_list_id"    => $meritList->id,
                    "application_id"   => $meritList->application->id,
                    "undertaking_link" => $return_data["file_name"],
                    "destination_path" => $return_data["destination_path"],
                    "doc_name"         => $return_data["doc_name"],
                    "attachment_type"  => $return_data["mime_type"],
                    "status"           => MeritListUndertaking::$pending,
                ]);
            }
            foreach ($bec_uploads_documents as $key => $doc_details) {
                    $inserted_documents[]  = MeritListUndertaking::create([
                    "merit_list_id"    => $meritList->id,
                    "undertaking_link" => $doc_details["file_name"],
                    "application_id"   => $meritList->application->id,
                    "doc_name"         => $doc_details["doc_name"],
                    "destination_path" => $doc_details["destination_path"],
                    "attachment_type"  => $doc_details["mime_type"],
                    "status"           => MeritListUndertaking::$other_pending,
                ]);
            }
            // if btech students.
            if(in_array($meritList->course_id, btechCourseIds())){
                $other_merit_list = MeritList::where("student_id", $meritList->student_id)
                ->where("id", "!=", $meritList->id)
                ->whereIn("course_id", btechCourseIds())
                ->with("application")
                ->where("status", 0)
                ->get();
                // if other merit list also found then
                if($other_merit_list->count()){
                    foreach($other_merit_list as $list){
                        foreach($inserted_documents as $doc){
                            MeritListUndertaking::create([
                                "merit_list_id"    => $list->id,
                                "application_id"   => $list->application->id,
                                "doc_name"         => $doc["doc_name"],
                                "destination_path" => $doc["destination_path"],
                                "attachment_type"  => $doc["attachment_type"],
                                "status"           => MeritListUndertaking::$other_pending,
                                "undertaking_link" => $doc["undertaking_link"],
                            ]);
                        }
                    }
                }
            }
            DB::commit();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Undertaking uploaded. ".$application->application_no);
            return redirect()
                ->back()
                ->with("success", "Undertaking/documents successfully uploaded. Pending for verification.");
            } catch (\Throwable $th) {
                // dd($th);
                Log::error($th);
                DB::rollback();
                return redirect()
                    ->back()
                    ->with("error", "Undertaking/documents uploading failed.");
            }
    }
   private function getUndertakingRules(MeritList $merit_list)
   {
        $rules = [
            "undertaking"    => "required|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024"
        ];
        $btechRules = [
            "marksheet" => "required|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024",
            "prc"       => "nullable|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024",
            "category"  => "nullable|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024",
        ];
        if($merit_list->active_marksheet || $merit_list->approved_marksheet){
            $btechRules["marksheet"] = str_replace("required", "nullable", $btechRules["marksheet"]);
        }
        if($merit_list->active_undertaking || $merit_list->approved_undertaking){
            $rules["undertaking"] = str_replace("required", "nullable", $rules["undertaking"]);
        }
        // checking btech course if btech course then add extra conditions
       return in_array($merit_list->course_id, btechCourseIds()) ? array_merge($rules, $btechRules) : $rules;
   }
    public function undertakingView(MeritList $meritList)
    {
        $meritList->load("undertakings");
        try {
            //code...
            if(!Gate::check('access-via-application', $meritList->application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "student undertaking view unauthorized access ".$meritList->application->application_no);
                return "Unauthorized access";
            }
            return view("common.merit-list.undertaking", compact("meritList"));
        } catch (\Throwable $th) {
            return "<p class='text-danger'>
                Whoops! something went wrong.
            </p>";
        }
    }
    public function reportCounselling(MeritList $merit_list)
    {
        // $checklistdata=AdmissionCheckedChecklists::where('merit_list_id',$merit_list->id)->count();
        // // dd($checklistdata);
        // if($checklistdata==null){
        //     return redirect()->back()->with("error","Please Select Checklist first to continue.");
        // }
        // dd($checklistdata);
        $merit_list_count = MeritList::where("student_id", $merit_list->student_id/* auth("student")->id() */)->where("status", 2)->count();
        if($merit_list_count){
           return redirect()->back()->with("error","Please release previous seat to continue.");
        }

        $user = request()->user();
        saveLogs($user->id, $user->name, "student", "Reporting for merit list application no {$merit_list->application_no}.");
        request()->merge([
            "terms_and_condition"   => returnTermsAndCond($merit_list->application)
        ]);
        $this->validate(request(), [
            "agree_checkbox"    => "required",
            "terms_and_condition"=> "required|max:5000|min:10",
        ]);
        $merit_list->load("application");
        // dd($merit_list);
        // dd("ok");
        // if (!Gate::check('access-via-application', $merit_list->application)) {
        //     // dd("ok");
        //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Accepting merit couselling access denied. Application no " . $merit_list->application->application_no);
        //     return redirect()
        //         ->back()
        //         ->with("error", "Unauthorized access.");
        // }
        $release_seat_applicable=0;
        if( request("seat_allocation")=="Floating"){
            $release_seat_applicable=1;
        }
        $admission_hour_interval = $merit_list->expiry_hour ?: config("vknrl.ADMISSION_EXPIRY_HOUR");
        if($merit_list->expiry_hour && $merit_list->expiry_hour > 0){
            $update_data = [
                "terms_and_conditions" => request("terms_and_condition"),
                "is_payment_applicable"=> in_array($merit_list->course_id, btechCourseIds()) ? 1 : 0,
                // setting payment applicable option to 0 for new candidates default is one
                "reported_at"          => now(),
                "status"               => 8, // reported for admission
                "valid_from"           => now(), // reported for admission
                "valid_till"           => now()->addHour($merit_list->expiry_hour), // reported for admission
                "freezing_floating"    => request("seat_allocation"),
                "release_seat_applicable" => $release_seat_applicable,
            ];
        }else{
            $update_data = [
                "terms_and_conditions" => request("terms_and_condition"),
                "is_payment_applicable"=> in_array($merit_list->course_id, btechCourseIds()) ? 1 : 0,
                "reported_at"          => now(),
                "status"               => 8, // reported for admission
                "freezing_floating"    => request("seat_allocation"),
                "release_seat_applicable" => $release_seat_applicable,
            ];
        }
        $merit_list->update($update_data);
        // $this->shuffleMeritList($merit_list->course_id);
        saveLogs($user->id, $user->name, "student", "Reported successfull for merit list application no {$merit_list->application_no}.");
        $sms = "Your application is successfully reported for counseling. Please wait for for your seat confirmation notification via SMS/E-Mail to book your provisional seat.";
        // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
        try {
            // sendSMS($merit_list->application->student->mobile_no, $sms, "1207162521403708453");
            $merit_list->application->student->notify(new StudentAnyMail($sms));
        } catch (\Throwable $th) {
            Log::error($th);
        }
        $course_id       = $merit_list->course_id;
        $merit_master_id = $merit_list->merit_master_id;
        return redirect()
            ->back()
            // ->route('department.merit.index',['course_id'=>$course_id,'merit_master_id'=>$merit_master_id])
            ->with("success", "Successfully reported.");
    }

    // public function shuffleMeritList($course_id)
    // {
    //     $all_Meritians= MeritList::where('course_id',$course_id)/* ->where('attendance_flag',1) */->where('status',8)->orderby('tuee_rank')->get();
    //     $general  = CourseSeat::where('course_id',$course_id)->where('admission_category_id',1)->first();
    //     $general1  = $general->total_seats;
    //     $count=0;
    //     foreach($all_Meritians as $key=>$meritians){
    //         if(++$key <= $general1){
    //             if($meritians->selection_category!=null){
    //                 // dump($meritians->selection_category);
    //                 $this->courseSeatdecrement($course_id, $meritians->selection_category);
    //             }
    //             $meritians->update(['selection_category'=>1,'admission_category_id'=>1]);
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

    public function courseSeatdecrement($course_id, $admission_category_id)
    {
        $is_seat_available=CourseSeat::where('course_id',$course_id)->where('admission_category_id',$admission_category_id)->first();
        $new=$is_seat_available->temp_attendence_occupied-1;
        $is_seat_available->update(['temp_attendence_occupied'=>$new > 0 ? $new : 0]);
    }







    public function declineSeat(MeritList $merit_list)
    {
        // dd(request()->all());
        // $user = request()->user();
        // saveLogs($user->id, $user->name, "student", "Declining merit list seat for application no {$merit_list->application_no}.", true);
        $this->validate(request(), [
            // "otp"               => "required",
            "reason_from_list"  => "required|in:". implode(",", CommonHelper::admission_decline_rules()),
            "reason"            => "required|max:1000|min:10",
            "decline_by"        => "required",
        ]);
        $merit_list->load("application");
        // if (!Gate::check('access-via-application', $merit_list->application)) {
        //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Accepting merit couselling access denied. Application no " . $merit_list->application->application_no);
        //     return redirect()
        //         ->back()
        //         ->with("error", "Unauthorized access.");
        // }
        // if($merit_list->declined_otp != request("otp")){
        //     saveLogs($user->id, $user->name, "student", "Declining merit list seat for application no {$merit_list->application_no}. OTP validation FAILED.");
        //     return redirect()
        //         ->back()
        //         ->with("error", "OTP doesn't matched.")
        //         ->withInput(request()->all());
        // }
        
        $merit_list->update([
            "declined_at"     => now(),
            "declined_text"   => request("reason_from_list"), // reported for admission
            "declined_remark" => request("reason"), // reported for admission
            "status"          => 9, // reported for admission
            "seat_declined_by"=> request("decline_by"),
        ]);
        // saveLogs($user->id, $user->name, "student", "Declined successfull merit list seat for application no {$merit_list->application_no}.");
        return redirect()
            ->back()
            ->with("success", "Your seat is declined.");
    }
    public function withdrawSeat(MeritList $merit_list)
    {
        $user = request()->user();
        saveLogs($user->id, $user->name, "student", "Withdrawing merit list seat for application no {$merit_list->application_no}.");
        $this->validate(request(), [
            "otp"              => "required",
            "reason_from_list" => "required|in:" . implode(",", CommonHelper::admission_decline_rules()),
            "reason"           => "required|max:1000|min:10",
            'dob'              => "required|date_format:Y-m-d",
            'bank_account'     => "required|string|max:100",
            'holder_name'      => "required|string|max:100",
            'bank_name'        => "required|string|max:100",
            'branch_name'      => "required|string|max:100",
            'ifsc_code'        => "required|string|max:50",
        ]);
        $merit_list->load("application");
        if (!Gate::check('access-via-application', $merit_list->application)) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Accepting merit couselling access denied. Application no " . $merit_list->application->application_no);
            return redirect()
                ->back()
                ->with("error", "Unauthorized access.");
        }
        if($merit_list->declined_otp != request("otp")){
            saveLogs($user->id, $user->name, "student", "Withdrawing merit list seat for application no {$merit_list->application_no}. OTP validation FAILED.");
            return redirect()
                ->back()
                ->with("error", "OTP doesn't matched.")
                ->withInput(request()->all());
        }
        DB::beginTransaction();
        try {
            WithdrawalRequest::create([
                "merit_list_id"    => $merit_list->id,
                "application_id"   => $merit_list->application->id,
                "student_id"       => $merit_list->student_id,
                "reason_from_list" => request("reason_from_list"),
                "reason"           => request("reason"),
                'dob'              => request("dob"),
                'bank_account'     => request("bank_account"),
                'holder_name'      => request("holder_name"),
                'bank_name'        => request("bank_name"),
                'branch_name'      => request("branch_name"),
                'ifsc_code'        => request("ifsc_code"),
                "status"           => "approved",
                "by_id"            => $user->id,
                "by_type"          => get_class($user),
            ]);
            $merit_list->convertToSeatWithdrawal();
            $merit_list->course_seat()->decrement("total_seats_applied");
            $cc = ["acad@tezu.ernet.in", "tuee2021@tezu.ernet.in"];
            if(in_array($merit_list->course_id, btechCourseIds())){
                $cc[] = "bssc2021@tezu.ac.in";
            }
            Mail::to($user->email)
            ->bcc("tuee2021@gmail.com")
            ->cc($cc)->send(new \App\Mail\SeatWithdrawalRequest($merit_list));
        } catch (\Throwable $th) {
            report($th);
            saveLogs($user->id, $user->name, "student", "Withdrawal requesed for application no {$merit_list->application_no} is failed.");
            return redirect()
            ->back()
            ->with("error", "Request failed. Try again later or contact helpline no.");
        }

        saveLogs($user->id, $user->name, "student", "Withdrawal requesed for application no {$merit_list->application_no} is sent.");
        DB::commit();
        return redirect()
            ->back()
            // ->with("success", "Your seat is withdraw request is sent. Please wait for department approval.");
            ->with("success", "Your seat is withdrew successfully.");
    }
    public function sendOTP(MeritList $merit_list)
    {
        $merit_list->load("application", "application");
        if(!Gate::check('access-via-application', $merit_list->application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "OTP verificaiton unauthorized access for declining merit list. For application no ".$merit_list->application->application_no);
            abort(401, "unauthorized access.");
        }
        $otp = rand(1020, 9899);
        $merit_list->update([
            "declined_otp"   => $otp
        ]);
        // $message = "{$otp} is your OTP. Tezpur University";
        $message = "Dear Applicant, your OTP for decline seat is {$otp} .Tezpur University";
        sendSMS($merit_list->student->mobile_no, $message, "1207163247700372856");
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "OTP for declining Merit List success for application no ".$merit_list->application_no);
        return [
            "message"   => "OTP sent"
        ];
    }


    // public function admissionSeatSlide($encrypted_id)
    // {
    //     try {
    //         $dec_data = Crypt::decrypt($encrypted_id);          
    //         $MeritListDetailsNew=MeritList::find($dec_data['merit_list_id']);
    //         $course_id = $dec_data['course_id'];
    //         $merit_master_id= $dec_data['merit_master_id'];
    //     } catch (Exception $e) {
    //         abort(404, "Invalid Request");
    //     }
    //     $previousCounseling=MeritList::where(['student_id'=> $MeritListDetailsNew->student_id,
    //                                           'status'    => 2,
    //                                           'course_id' => $MeritListDetailsNew->course_id,])->first();
    //     if($previousCounseling!=null){

    //         if($previousCounseling->admission_category_id==1){
    //             return redirect()->back()->with('error','Can`t Slide Admission category. As Student is already in Open category');
    //         }
    //         $seatMatrix=CourseSeat::where(['course_id'=>$course_id,'admission_category_id'=>1])->first();
    //         if(checkOpenAvailability($course_id)==false){
    //             return redirect()->back()->with('error','Can`t Slide Admission category. As General Category is already Filled UP..');
    //         }
    //         DB::beginTransaction();
    //         try{
    //             $is_slideable=$this->CheckOrderByCMR($course_id,$MeritListDetailsNew->merit_master_id,$MeritListDetailsNew->cmr);
                
    //             if($is_slideable!=null){
    //                 return redirect()->back()->with('error','Can`t Slide Admission category.'.$is_slideable->application_no.'is in Higher rank');
    //             }
    //             // dd($is_slideable);
    //             $seatMatrix->increment('total_seats_applied');
    //             CourseSeat::where(['course_id'=>$course_id,'admission_category_id'=>$previousCounseling->admission_category_id])->decrement('total_seats_applied');
    //             $previousCounseling->update([/* 'admission_category_id'=>1, */"may_slide"=>3]);
    //             $MeritListDetailsNew->update([
    //                                 'admission_category_id'=>1,
    //                                 'status'=>14,
    //                                 "may_slide"=>2]);  
    //             $admission_receipt=AdmissionReceipt::where(
    //                                                     [   'student_id'   => $previousCounseling->student_id,
    //                                                         'course_id'    => $previousCounseling->course_id,
    //                                                         'merit_list_id'=> $previousCounseling->id
    //                                                         ])->first();  
    //             $admit = $admission_receipt->toArray();
    //             $admit['created_at'] = $admission_receipt->getOriginal('created_at'); 

    //             //$admission_receipt['created_at'] = $admission_receipt->getOriginal('created_at');  
    //             //dd($admit);                   
    //             // dd($admission_receipt = $admission_receipt->toArray());
    //             DB::table('admission_receipts_log')->insert($admit);
    //             AdmissionReceipt::where('id',$admission_receipt->id)->update(['category_id'=>1,'slide_date'=>date('Y-m-d H:m:s')]);
                
    //             DB::commit();
    //         }catch(Exception $e){
    //              dd($e);
    //              DB::rollBack();
    //         }
    //         return redirect()->back()->with('success','Admission category is successfully slided'); 
    //     }else{
    //         return redirect()->back()->with('error','Can`t Slide Admission category. As it is his first..');
    //     }
    // }

    // public function CheckOrderByCMR($course_id, $merit_master_id, $cmr)
    // {
    //     return $availability=MeritList::where(['course_id'=> $course_id,'merit_master_id'=> $merit_master_id,'may_slide'=>1])
    //                             ->where('cmr','<', $cmr)
    //                             ->first();
    // }


    public function chooseHostel($merit_list_id)
    { 
        try {
            $id = Crypt::decrypt($merit_list_id);
            
            $merit_list = MeritList::with(["application"])->findOrFail($id);
            // dd($merit_list);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Whoops! something went wrong. try again later.");
        }

        $check_old = MeritList::where('student_id',$merit_list->student_id)->where('status',2)->count();
        if($check_old>0){
            return redirect()->back()->with("error", "Please release previous seat to continue.");
        }
        
        // dd("ok");
        // if(!Gate::check('access-via-application', $merit_list->application)){
        //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Choose hostel access denied for application no ".$merit_list->application->application_no);
        //     return redirect()
        //         ->back()
        //         ->with("error", "Unauthorized access.");
        // }
        $merit_list->update([
            "hostel_name"=>request("hostel_name"),
            "room_no"    =>request("room_no"),
            "hostel_required" => (request("hostel_required") === "yes"),
            "freezing_floating"    => "Floating",
            "release_seat_applicable" => 1,
        ]);
        // dd("Ok11");
        // $merit_list->refresh();
        // dd("ok");
        $this->changeApplicationAnyDetailsWithoutPayment($merit_list->application,$merit_list);
        // return redirect()
        //     ->route("department.admission.proceed", $merit_list_id);// ecnrypted id
        return redirect()->route('department.merit.index',['course_id'=>83,'merit_master_id'=>$merit_list->merit_master_id])->with('success','Admission Successfull');
    }

    private function changeApplicationAnyDetailsWithoutPayment(Application $application, MeritList $merit_list)
    {
        // dd("okkkkk");
        $was_hold=0;
        if($merit_list->new_status=="time_extended"){
            $was_hold=1;
        }
        DB::beginTransaction();
        try{
        
        
            $merit_list->update(["status" => "2",
                                            "new_status" => "admitted"]);
            
            // if(!isset($online_payment->id) || !$online_payment->id){
            //     $online_payment = OnlinePaymentSuccess::create([
            //         "application_id"    => $application->id,
            //         "student_id"        => $application->student_id,
            //         "order_id"          => uniqid(),
            //         "amount"            => 0.0,
            //         "amount_in_paise"   => 0.0,
            //         "response_amount"   => 0.0,
            //         "currency"          => "INR",
            //         "merchant_order_id" => "ZERO".uniqid(),
            //         "payment_id"        => "ZERO".uniqid(),
            //         "payment_signature" => null,
            //         "is_error"          => false,
            //         "error_message"     => null,
            //         "biller_status"     => "captured",
            //         "biller_response"   => null,
            //         "payment_type"      => "admission",
            //         "course_id"         => $merit_list->course_id,
            //         "merit_list_id"     => $merit_list->id,
            //         "status"            => 1,
            //     ]);
            // }
            // $this->setFeeStructure($merit_list);
            // [$amount, $currency_value, $last_receipt] = $this->getAdmissionAmount($application);
            // $fees = $this->fee_structure;
            $admission_receipt_data = [
                "uuid"              => Uuid::generate()->string,
                "receipt_no"        => date("Y"),
                "student_id"        => $merit_list->student_id,
                "application_id"    => $application->id,
                "course_id"         => $merit_list->course_id,
                "category_id"       => $merit_list->admission_category_id,
                "online_payment_id" => 0,
                "pay_method"        => "NA", 
                "transaction_id"    => "NA",
                "type"              => "admission",
                "total"             => 0,
                "status"            => 0,
                "year"              => date("Y"),
                "merit_list_id"     => $merit_list->id,
                "previous_receipt_id" => 0,
                "previous_received_amount" => 0.00,
                'hostel_name'  => $merit_list->hostel_name,
                "room_no"      => $merit_list->room_no
            ];
            
            $admission_receipt = AdmissionReceipt::create($admission_receipt_data);
            // dd(Uuid::generate()->string);
            // dd($admission_receipt);
            $admission_collections = [];
            for($i=0;$i<1;$i++){
                $admission_collections[] = [
                    "receipt_id"     => $admission_receipt->id,
                    "student_id"     => $merit_list->student_id,
                    "application_id" => $application->id,
                    "fee_id"         => 0,
                    "fee_head_id"    => 0,
                    "amount"         => 0.00,
                    "free_amount"    => 0.00,
                    "is_free"        => 0.00,
                ];
            }
            // dd("ok");
            $admission_receipt->collections()->createMany($admission_collections);
           
            // prev count logic
            $prev_count = AdmissionReceipt::where("id", "<=", $admission_receipt->id)->withTrashed()->count();
            $receipt_no = "TU".date("Y").$prev_count;
            // prev count logic end
            $admission_receipt->receipt_no = $receipt_no;
            $admission_receipt->roll_number = $admission_receipt->generateRollNumber();
            $admission_receipt->save();
            $application->student()->update([
                "roll_number"   => $admission_receipt->roll_number
            ]);
            $course_seat = $merit_list->course_seat();
            if($course_seat){
                $course_seat->increment("total_seats_applied");
                $course_seat->decrement("temp_seat_applied");
                if($merit_list->is_pwd){
                    // $shortlisted_course_seat = CourseSeat::courseFilter($merit_list->course_id)->pwdFilter()->first();
                    $shortlisted_course_seat = CourseSeat::courseFilter($merit_list->course_id)->pwdFilter()->first();
                    if($shortlisted_course_seat){
                       
                        $shortlisted_course_seat->increment("total_seats_applied");
                        $shortlisted_course_seat->decrement("temp_seat_applied");
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
                        $user = User::where('id',$application->student_id)->first();
                        $course = Course::where('id',$course_seat->course_id)->withTrashed()->first();
                        $admission_category = AdmissionCategory::where('id',$course_seat->admission_category_id)->first();
                        $display_message = $user->name." has taken admission in ".$course->name." under ".$admission_category->name;
                        $data['id'] = $course_seat->id;
                        $data['course_id'] = $course_seat->course_id;
                        $data['admission_category_id'] = $course_seat->admission_category_id;
                        $data['total_seats_applied'] = $course_seat->total_seats_applied;
                        $data['total_seats'] = $course_seat->total_seats;
                        $data['vacant_seats'] = intval($course_seat->total_seats)-intval($course_seat->total_seats_applied);
                        $data['display_message'] = $display_message;
                        $pusher->trigger('tezu-admission-channel', 'course-seat', ['message' => "A New record inserted",'response'=>$data]);
                    }
                    // dd($shortlisted_course_seat);
                    // if($shortlisted_course_seat->total_seats_applied==$shortlisted_course_seat->total_seats){
                    //     $shortlisted_course_seat->update(['invitation_flag'=>1,'admission_flag'=>'close']);
                    // }
                }
                if($course_seat->total_seats_applied==$course_seat->total_seats){
                    $course_seat->update(['invitation_flag'=>1,'admission_flag'=>'close']);
                }

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
                $user = User::where('id',$application->student_id)->first();
                $course = Course::where('id',$course_seat->course_id)->withTrashed()->first();
                $admission_category = AdmissionCategory::where('id',$course_seat->admission_category_id)->first();
                $display_message = $user->name." has taken admission in ".$course->name." under ".$admission_category->name;
                $data['id'] = $course_seat->id;
                $data['course_id'] = $course_seat->course_id;
                $data['admission_category_id'] = $course_seat->admission_category_id;
                $data['total_seats_applied'] = $course_seat->total_seats_applied;
                $data['total_seats'] = $course_seat->total_seats;
                $data['vacant_seats'] = intval($course_seat->total_seats)-intval($course_seat->total_seats_applied);
                $data['display_message'] = $display_message;
                $pusher->trigger('tezu-admission-channel', 'course-seat', ['message' => "A New record inserted",'response'=>$data]);
            
            }else{
                $message = "Application no {$application->application_no} admission taken without seat details";
                Log::error($message);
                try {
                    saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
                } catch (\Throwable $th) {
                    Log::error("saveLogs Error application no {$application->application_no}");
                }
            }
            DB::commit();
        }catch(\Exception $e){
            dd($e);
            DB::rollback();
            return redirect()->back()->with('error','Something went wrong...');
        }
    }
}
