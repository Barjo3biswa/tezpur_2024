<?php

namespace App\Http\Controllers\Student;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionReceipt;
use App\Models\Application;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\MeritMaster;
use App\Models\WithdrawalRequest;
use App\Notifications\StudentAnyMail;
use Auth;
use Carbon\Carbon;
use Crypt;
use DB;
use Log;

class NewAdmissionController extends Controller
{
    
    public function process($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404, "Invalid Request");
        }
        $merit_list = MeritList::where('student_id',$decrypted)->whereIn('new_status',['called','admitted','invited','can_call','time_extended','denied','declined','withdraw'])->get();
        // dd($merit_list);
        return view('student.new_admission.admission-process',compact('merit_list'));
    }
    // pu
    public function reportCounsellingOnline($id)
    {
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404, "Invalid Request");
        }
        $flag=$this->validateIsTimeAvailable($decrypted);
        if($flag == 'over'){
            return redirect()->back()->with('error','Your Time is over.');
        }
        if($flag == 'wait'){
            return redirect()->back()->with('error','Wait for your turn.');
        }
        $merit_list_count = MeritList::where("student_id",  auth("student")->id())->where("status", 2)->count();
        if($merit_list_count){
           return redirect()->back()->with("error","Please release previous seat to continue.");
        }


        $merit_list= MeritList::where('id',$decrypted)->first();
        if($merit_list->course->admission_status==0){
            abort(404, "Invalid Request");
        }
        if($merit_list->status!=7 && $merit_list->status!=0){
            return redirect()->back()->with("error","You are not allowed to proceed.");
        }
        // dd("ok");
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
        

        $release_seat_applicable=0;
        if( request("seat_allocation")=="Floating"){
            $release_seat_applicable=1;
        }

        $admission_hour_interval = $merit_list->expiry_hour ?: config("vknrl.ADMISSION_EXPIRY_HOUR");
        
        $update_data = [
            "terms_and_conditions" => request("terms_and_condition"),
            "is_payment_applicable"=> in_array($merit_list->course_id, btechCourseIds()) ? 1 : 0,
            "reported_at"          => now(),
            "status"               => 8, // reported for admission
            "freezing_floating"    => request("seat_allocation"),
            "release_seat_applicable" => /* $release_seat_applicable */0,
        ];

        
        $merit_list->update($update_data);
        // $sms = "Your application is successfully reported for counseling. Please continuue with payment process to book your provisional seat.";

        // // $sms = "Your application is successfully reported for counseling. Please wait for for your seat confirmation notification via SMS/E-Mail to book your provisional seat.";
        // try {
        //     $merit_list->application->student->notify(new StudentAnyMail($sms));
        // } catch (\Throwable $th) {
        //     Log::error($th);
        // }
        $course_id       = $merit_list->course_id;
        $merit_master_id = $merit_list->merit_master_id;
        return redirect()->back()->with('success',"Successfully reported.");
    }

    public function chooseHostel($merit_list_id)
    {  
        // dd("ok");
        try {
            $id = Crypt::decrypt($merit_list_id);
            
            $merit_list = MeritList::with(["application"])->findOrFail($id);
            
            // dd($merit_list);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Whoops! something went wrong. try again later.");
        }


        $merit_list_count = MeritList::where("student_id",  auth("student")->id())->where("status", 2)->count();
        if($merit_list_count){
           return redirect()->back()->with("error","Please release previous seat to continue.");
        }

        


        // if($merit_list->course->admission_status==0){
        //     abort(404, "Invalid Request");
        // }
        $flag=$this->validateIsTimeAvailable($id);
        if($flag == 'over'){
            return redirect()->back()->with('error','Your Time is over.');
        }
        if($flag == 'wait'){
            return redirect()->back()->with('error','Wait for your turn.');
        }
        
        $merit_list->update([
            "hostel_name"=>request("hostel_name"),
            "room_no"    =>request("room_no"),
            "hostel_required" => (request("hostel_required") === "yes")
        ]);
        // dd("Ok11");
        // $merit_list->refresh();
        // dd("ok");
        return redirect()
            ->route("student.admission.proceed", $merit_list_id);// ecnrypted id
    }

    public function getDeclineOTP(Request $request){
        $mobile=Auth::user()->mobile_no;
        $otp     = mt_rand(123452, 998877);
        $message = "Dear Applicant, your OTP for decline seat is {$otp}.Tezpur University";
        if($request->status){
            $message = "Dear Applicant, your OTP for withdraw seat is {$otp}.Tezpur University";
        }
        
        // $message = "Dear Applicant, {$otp} is the OTP for Registration to apply at " . env("OTP_APP_NAME");
        sendSMS('+91'.$mobile, $message, "1207163247700372856");
        MeritList::where('id',$request->id)->update(['declined_otp'=>$otp]);
        return response()->json(['success' => true, 'data'=>$request->id]);
    }

    public function declineSeatOnline(Request $request,$id){
        
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404, "Invalid Request");
        }
        $flag=$this->validateIsTimeAvailable($decrypted);
        if($flag == 'over'){
            return redirect()->back()->with('error','Your Time is over.');
        }
        if($flag == 'wait'){
            return redirect()->back()->with('error','Wait for your turn.');
        }
        $merit_list=MeritList::where('id',$decrypted)->first();
        if($merit_list->declined_otp != $request->otp){
            return redirect()->back()->with('error','OTP does not match. Try Again.');
        }
        if($merit_list->course->admission_status==0){
            abort(404, "Invalid Request");
        }
        DB::beginTransaction();
        try{
            $record=MeritList::where('id',$decrypted)->first();
            // if($record->new_status=='time_extended'){
                $course_seat=CourseSeat::where('course_id',$record->course_id)->where('admission_category_id',$record->admission_category_id)->first();
                $course_seat->decrement("temp_seat_applied");
            // }
            if($merit_list->is_pwd==1){
                $course_seat=CourseSeat::where('course_id',$record->course_id)->where('admission_category_id',7)->first();
                $course_seat->decrement("temp_seat_applied");
            }

            if($merit_list->admission_category_id==1){
                if($merit_list->may_slide==1){
                    $course_seat->decrement('temp_attendence_occupied');
                }
            }
            MeritList::where('id',$decrypted)->update([
                'declined_at'     => now(),
                'declined_text'   => $request->reason_from_list,
                'declined_remark' => $request->reason,
                'status' => 9,
                'seat_declined_by' => 'Student',
                'new_status' =>'declined'
            ]);
            DB::commit();
            return redirect()->back()->with('success','Successfully Declined Your Seat.');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('error','Something Went Wrong.');
        }
        
    }


    public function slidingChanges($merit_list){
        if($merit_list->application->is_cuet_ug==1){
            $total_seat_applied=CourseSeat::where('course_id',$merit_list->course_id)
                    ->where('admission_category_id','!=',1)
                    ->sum('total_seats_applied');
            $pwd_seat_applied= CourseSeat::where('course_id',$merit_list->course_id)
                    ->where('admission_category_id',7)
                    ->sum('total_seats_applied');
            $count=$total_seat_applied-$pwd_seat_applied;
            if($count>0){
                Course::where('id',$merit_list->course_id)->withTrashed()->update(['sliding_possibility'=>1]);
                MeritMaster::where('id',$merit_list->merit_master_id)->update(['sliding_possibility'=>1]);
            } 
        }     
    }

    public function withdrawSeatOnline(Request $request,$id){
        // dd($request->all());
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404, "Invalid Request");
        }
        
        // dd($request->all());
        $merit_list=MeritList::where('id',$decrypted)->first();
        if($merit_list->declined_otp != $request->otp){
            return redirect()->back()->with('error','OTP does not match. Try Again.');
        }

        if($merit_list->new_status != "admitted"){
            return redirect()->back()->with('error','You Can`t withdraw');
        }

        DB::beginTransaction();
        try{
            $record=MeritList::where('id',$decrypted)->first();
            
            if($record->admission_category_id==1){
                $this->slidingChanges($merit_list);     
            }

            $course_seat=CourseSeat::where('course_id',$record->course_id)->where('admission_category_id',$record->admission_category_id)->first();
            $course_seat->decrement("total_seats_applied");
            
            MeritList::where('id',$decrypted)->update([
                'status' => 6,
                'new_status' =>'withdraw'
            ]);

            WithdrawalRequest::create([
                'merit_list_id' =>	$merit_list->id,
                'application_id' =>	$merit_list->application->id,
                'student_id' =>	$merit_list->student_id,	
                'bank_account' => $request->bank_account,
                'holder_name' => $request->holder_name,	
                'bank_name'	=> $request->bank_name,
                'branch_name' => $request->branch_name,	
                'ifsc_code' => $request->ifsc_code,
                'status' => 'success',
                'reason_from_list' => $request->reason_from_list,
                'reason' =>  $request->reason,
            ]);

            DB::commit();
            return redirect()->back()->with('success','Successfully Withdrawen Your Seat.');
        }catch(\Exception $e){
            dd($e);
            DB::rollback();
            return redirect()->back()->with('error','Something Went Wrong.');
        }
    }



    public function validateIsTimeAvailable($id){
        
        $merit_list = MeritList::where('id',$id)->first();
        
        if($merit_list->new_status=='cancel'){
            return 'over';
        }
        if($merit_list->valid_till < now()){
            return 'over';
        }
        if($merit_list->valid_from > now()){
            return 'wait';
        }
    }

    public function acceptInvite(Request $request,$id){
        // dd("ok");
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404, "Invalid Request");
        }
        $merit_list =  MeritList::where('id',$decrypted)->first();
        if($merit_list->course->admission_status==0){
            abort(404, "Invalid Request");
        }
        // dd("ok");
        // $previous_preference=MeritList::where('student_id',$merit_list->student_id)->where('preference',$request->preference)->first();
        
        // if($previous_preference!=null){
        //     return redirect()->back()->with('error','This preference is used for another course please select another preference');
        // }
        // dd($previous_preference);
        if($merit_list->attendance_flag==0 && now() >= $merit_list->valid_from && now() <= $merit_list->valid_till){
            MeritList::where('id',$decrypted)->update(['attendance_flag'=>1,
                                                       'attendance_time' => now(),
                                                       'preference' => 1/* $request->preference */
                                                    ]);
        }else{
            MeritList::where('id',$decrypted)->update(['attendance_flag'=>1,'attendance_time' => now()]);
        }

        // if($merit_list->meritMaster->semi_auto){
        //     $merit_list_second = MeritList::where('course_id',$merit_list->course_id)->where('student_id',$merit_list->student_id)->first();
        //     MeritList::where('id',$merit_list_second->id)->update(['attendance_flag'=>2, 'new_status'=>'denied','remarks'=>'declined due to admission category']);
        // }

        return redirect()->back()->with('success','successfully accepted for reporting');
        
    }

    
    public function declineInvite($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404, "Invalid Request");
        }
        // dd($decrypted);
        $merit_list =  MeritList::where('id',$decrypted)->first();
        if($merit_list->course->admission_status==0){
            abort(404, "Invalid Request");
        }
        if($merit_list->attendance_flag==0 && now() >= $merit_list->valid_from && now() <= $merit_list->valid_till){
            MeritList::where('id',$decrypted)->update(['attendance_flag'=>2, 'new_status'=>'denied']);
        }
        return redirect()->back()->with('success','successfully declined for reporting');
    }

    public function admissionSeatRelease($encrypted_id)
    {
        // dd("ok");
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list = MeritList::findOrFail($decrypted_id);
        
        // dd($merit_list->course_seat());
        // $application = $merit_list->application;
        DB::beginTransaction();
        try{
          
                $merit_list->admissionReceipt()->update([
                                                    "status"    => 1
                                                ]);
                $merit_list->status = 3;
                $merit_list->save();

                // if($merit_list->may_slide==3){
                //     $merit_list=MeritList::where(['student_id'=>$merit_list->student_id,
                //                                 'course_id' =>$merit_list->course_id,
                //                                 'may_slide' =>2,
                //                                 'status'    =>14])->first();
                //     $merit_list->status = 15;
                //     $merit_list->save();
                // }

                if($merit_list->admission_category_id==1){
                    $this->slidingChanges($merit_list);                  
                }


                $course_seat = $merit_list->course_seat();
                $application = $merit_list->application;
                if($course_seat){
                    
                    $course_seat->decrement("total_seats_applied");
                }else{
                    $message = "Application no {$application->application_no} admission Seat released.";
                    Log::error($message);
                    try {
                        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
                    } catch (\Throwable $th) {
                        Log::error("saveLogs Error application no seat released {$application->application_no}");
                    }
                }
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission seat released for application no ".$application->application_no);
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return redirect()->back()->with('error','Something went wrong');
            }
            return redirect()->back()->with('success','Successfully Released Seat You Can Take Admission to another program.');
    }
    
    public function SmsTest(){
        $application=Application::where('id',22981)->first();
        $admission_receipt=AdmissionReceipt::where('id',1)->first();
        $message = "Provisional admission for your {$application->application_no} is successful. Your provisional receipt no is {$admission_receipt->receipt_no}. Tezpur University";
        // $message = "Provisional admission for your ".$application->application_no." is successful. Your provisional receipt no is ".$admission_receipt->receipt_no.". Tezpur University";
        // sendSMS($application->mobile_no, $message);
        $mobile = $application->student->isd_code.$application->student->mobile_no;
        // $mobile = 8638279535;
        try {
            sendSMS($mobile, $message,1207161926030732465);
        } catch (\Throwable $th) {
            return false;
        }
        dd("ok");
        return true;
    }
    
}
