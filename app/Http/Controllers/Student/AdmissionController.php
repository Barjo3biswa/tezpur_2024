<?php

namespace App\Http\Controllers\Student;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonAdmissionController;
use App\Models\Application;
use App\Models\MeritList;
use App\Models\MeritListUndertaking;
use App\Models\WithdrawalRequest;
use App\Notifications\StudentAnyMail;
use App\Traits\StudentsAdmission;
use Crypt;
use DB;
use Exception;
use Gate;
use Log;
use Mail;

class AdmissionController extends CommonAdmissionController

{
//     use StudentsAdmission;
//     public function bookSeat($encrypted_id)
//     {
//         dd("ok");
//         try {
//             $id = Crypt::decrypt($encrypted_id);
//             $application = Application::find($id);
//         } catch (Exception $e) {
//             abort(404, "Invalid Request");
//         }
//         if($application->student_id != auth("student")->id()){
//             return redirect()
//                 ->back()
//                 ->with("error", "Access denied.");
//         }
//         saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Visiting Book Seat page with application no ".$application->application_no);
//         $application->load(["merit_list.course", "applied_courses.course"]);
//         return view("student.admission.boot-seat", compact("application"));
//     }
    
//     public function chooseHostel($merit_list_id)
//     {
//         try {
//             $id = Crypt::decrypt($merit_list_id);
//             $merit_list = MeritList::with(["application"])->findOrFail($id);
//         } catch (Exception $e) {
//             return redirect()
//                 ->back()
//                 ->with("error", "Whoops! something went wrong. try again later.");
//         }
        
//         if(!Gate::check('access-via-application', $merit_list->application)){
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Choose hostel access denied for application no ".$merit_list->application->application_no);
//             return redirect()
//                 ->back()
//                 ->with("error", "Unauthorized access.");
//         }
//         $merit_list->update([
//             "hostel_required" => (request("hostel_required") === "yes")
//         ]);
//         // $merit_list->refresh();
//         return redirect()
//             ->route("student.admission.proceed", $merit_list_id);// ecnrypted id
//     }
//     public function uploadingUnderTaking(MeritList $meritList)
//     {
//         if(!$meritList){
//             return redirect()
//                 ->back()
//                 ->with("error", "Whoops! something went wrong.");
//         }

//         $this->validate(request(), $this->getUndertakingRules($meritList));
//         $application = $meritList->application;
//         if($meritList->undertakings->count() >= MeritLIstUndertaking::$upload_try_limit){
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Undertaking uploading limit crossed. ".$application->application_no);
//             return redirect()
//                 ->back()
//                 ->with("error", "Uploading limit crossed.");
//         }
//         if(!Gate::check('access-via-application', $application)){
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission undertaking uploading error for application no ".$application->application_no);
//             return redirect()
//                 ->back()
//                 ->with("error", "Unauthorized access.");
//         }
//         $return_data = [];
//         $destinationPath = public_path('uploads/' . $application->student_id . "/" . $application->id);
//         if (request()->hasFile('undertaking')) {
//             $undertaking = request()->file('undertaking');
//             $undertaking_name = date('YmdHis') . "_" . rand(4512, 6859) . "-undertaking." . $undertaking->getClientOriginalExtension();
//             $undertaking->move($destinationPath . "/", $undertaking_name);
//             $return_data = [
//                 "doc_name" => "undertaking",
//                 "file_name" => $undertaking_name,
//                 "original_name" => $undertaking->getClientOriginalName(),
//                 "mime_type" => $undertaking->getClientMimeType(),
//                 "destination_path" => $destinationPath,
//             ];
//         }
//         $bec_uploads_documents = [];
//         if (request()->hasFile('marksheet')) {
//             $marksheet = request()->file('marksheet');
//             $marksheet_name = date('YmdHis') . "_" . rand(4512, 6859) . "-marksheet." . $marksheet->getClientOriginalExtension();
//             $marksheet->move($destinationPath . "/", $marksheet_name);
//             $bec_uploads_documents[] = [
//                 "doc_name" => "12_+_2_marksheet",
//                 "file_name" => $marksheet_name,
//                 "original_name" => $marksheet->getClientOriginalName(),
//                 "mime_type" => $marksheet->getClientMimeType(),
//                 "destination_path" => $destinationPath,
//             ];
//         }
//         if (request()->hasFile('category')) {
//             $category = request()->file('category');
//             $category_name = date('YmdHis') . "_" . rand(4512, 6859) . "-category." . $category->getClientOriginalExtension();
//             $category->move($destinationPath . "/", $category_name);
//             $bec_uploads_documents[] = [
//                 "doc_name" => "category",
//                 "file_name" => $category_name,
//                 "original_name" => $category->getClientOriginalName(),
//                 "mime_type" => $category->getClientMimeType(),
//                 "destination_path" => $destinationPath,
//             ];
//         }
//         if (request()->hasFile('prc')) {
//             $prc = request()->file('prc');
//             $prc_name = date('YmdHis') . "_" . rand(4512, 6859) . "-prc." . $prc->getClientOriginalExtension();
//             $prc->move($destinationPath . "/", $prc_name);
//             $bec_uploads_documents[] = [
//                 "doc_name" => "PRC",
//                 "file_name" => $prc_name,
//                 "original_name" => $prc->getClientOriginalName(),
//                 "mime_type" => $prc->getClientMimeType(),
//                 "destination_path" => $destinationPath,
//             ];
//         }
//         DB::beginTransaction();
//         try {
//             // for btech candidate same document uploaded for other selection list too.
//             // reflection is also reflected on other selected list.
//             // which merit list is already admitted thats should be release.
//             $inserted_documents    = [];
//             if($return_data){
//                 $inserted_documents[]  = MeritListUndertaking::create([
//                     "merit_list_id"    => $meritList->id,
//                     "application_id"   => $meritList->application->id,
//                     "undertaking_link" => $return_data["file_name"],
//                     "destination_path" => $return_data["destination_path"],
//                     "doc_name"         => $return_data["doc_name"],
//                     "attachment_type"  => $return_data["mime_type"],
//                     "status"           => MeritListUndertaking::$pending,
//                 ]);
//             }
//             foreach ($bec_uploads_documents as $key => $doc_details) {
//                     $inserted_documents[]  = MeritListUndertaking::create([
//                     "merit_list_id"    => $meritList->id,
//                     "undertaking_link" => $doc_details["file_name"],
//                     "application_id"   => $meritList->application->id,
//                     "doc_name"         => $doc_details["doc_name"],
//                     "destination_path" => $doc_details["destination_path"],
//                     "attachment_type"  => $doc_details["mime_type"],
//                     "status"           => MeritListUndertaking::$other_pending,
//                 ]);
//             }
//             // if btech students.
//             if(in_array($meritList->course_id, btechCourseIds())){
//                 $other_merit_list = MeritList::where("student_id", $meritList->student_id)
//                 ->where("id", "!=", $meritList->id)
//                 ->whereIn("course_id", btechCourseIds())
//                 ->with("application")
//                 ->where("status", 0)
//                 ->get();
//                 // if other merit list also found then
//                 if($other_merit_list->count()){
//                     foreach($other_merit_list as $list){
//                         foreach($inserted_documents as $doc){
//                             MeritListUndertaking::create([
//                                 "merit_list_id"    => $list->id,
//                                 "application_id"   => $list->application->id,
//                                 "doc_name"         => $doc["doc_name"],
//                                 "destination_path" => $doc["destination_path"],
//                                 "attachment_type"  => $doc["attachment_type"],
//                                 "status"           => MeritListUndertaking::$other_pending,
//                                 "undertaking_link" => $doc["undertaking_link"],
//                             ]);
//                         }
//                     }
//                 }
//             }
//             DB::commit();
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Undertaking uploaded. ".$application->application_no);
//             return redirect()
//                 ->back()
//                 ->with("success", "Undertaking/documents successfully uploaded. Pending for verification.");
//             } catch (\Throwable $th) {
//                 // dd($th);
//                 Log::error($th);
//                 DB::rollback();
//                 return redirect()
//                     ->back()
//                     ->with("error", "Undertaking/documents uploading failed.");
//             }
//     }
//    private function getUndertakingRules(MeritList $merit_list)
//    {
//         $rules = [
//             "undertaking"    => "required|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024"
//         ];
//         $btechRules = [
//             "marksheet" => "required|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024",
//             "prc"       => "nullable|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024",
//             "category"  => "nullable|file|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:1024",
//         ];
//         if($merit_list->active_marksheet || $merit_list->approved_marksheet){
//             $btechRules["marksheet"] = str_replace("required", "nullable", $btechRules["marksheet"]);
//         }
//         if($merit_list->active_undertaking || $merit_list->approved_undertaking){
//             $rules["undertaking"] = str_replace("required", "nullable", $rules["undertaking"]);
//         }
//         // checking btech course if btech course then add extra conditions
//        return in_array($merit_list->course_id, btechCourseIds()) ? array_merge($rules, $btechRules) : $rules;
//    }
//     public function undertakingView(MeritList $meritList)
//     {
//         $meritList->load("undertakings");
//         try {
//             //code...
//             if(!Gate::check('access-via-application', $meritList->application)){
//                 saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "student undertaking view unauthorized access ".$meritList->application->application_no);
//                 return "Unauthorized access";
//             }
//             return view("common.merit-list.undertaking", compact("meritList"));
//         } catch (\Throwable $th) {
//             return "<p class='text-danger'>
//                 Whoops! something went wrong.
//             </p>";
//         }
//     }
//     public function reportCounselling(MeritList $merit_list)
//     {
//         $user = request()->user();
//         saveLogs($user->id, $user->name, "student", "Reporting for merit list application no {$merit_list->application_no}.");
//         request()->merge([
//             "terms_and_condition"   => returnTermsAndCond($merit_list->application)
//         ]);
//         $this->validate(request(), [
//             "agree_checkbox"    => "required",
//             "terms_and_condition"=> "required|max:5000|min:10",
//         ]);
//         $merit_list->load("application");
//         if (!Gate::check('access-via-application', $merit_list->application)) {
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Accepting merit couselling access denied. Application no " . $merit_list->application->application_no);
//             return redirect()
//                 ->back()
//                 ->with("error", "Unauthorized access.");
//         }
//         $admission_hour_interval = $merit_list->expiry_hour ?: config("vknrl.ADMISSION_EXPIRY_HOUR");
//         if($merit_list->expiry_hour && $merit_list->expiry_hour > 0){
//             $update_data = [
//                 "terms_and_conditions" => request("terms_and_condition"),
//                 "is_payment_applicable"=> in_array($merit_list->course_id, btechCourseIds()) ? 1 : 0,
//                 // setting payment applicable option to 0 for new candidates default is one
//                 "reported_at"          => now(),
//                 "status"               => 8, // reported for admission
//                 "valid_from"           => now(), // reported for admission
//                 "valid_till"           => now()->addHour($merit_list->expiry_hour), // reported for admission
//             ];
//         }else{
//             $update_data = [
//                 "terms_and_conditions" => request("terms_and_condition"),
//                 "is_payment_applicable"=> in_array($merit_list->course_id, btechCourseIds()) ? 1 : 0,
//                 "reported_at"          => now(),
//                 "status"               => 8, // reported for admission
//             ];
//         }
//         $merit_list->update($update_data);
//         saveLogs($user->id, $user->name, "student", "Reported successfull for merit list application no {$merit_list->application_no}.");
//         $sms = "Your application is successfully reported for counseling. Please wait for for your seat confirmation notification via SMS/E-Mail to book your provisional seat.";
//         // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
//         try {
//             // sendSMS($merit_list->application->student->mobile_no, $sms, "1207162521403708453");
//             $merit_list->application->student->notify(new StudentAnyMail($sms));
//         } catch (\Throwable $th) {
//             Log::error($th);
//         }
//         return redirect()
//             ->back()
//             ->with("success", "Successfully reported.");
//     }
//     public function declineSeat(MeritList $merit_list)
//     {
//         // dd(request()->all());
//         $user = request()->user();
//         saveLogs($user->id, $user->name, "student", "Declining merit list seat for application no {$merit_list->application_no}.", true);
//         $this->validate(request(), [
//             "otp"               => "required",
//             "reason_from_list"  => "required|in:". implode(",", CommonHelper::admission_decline_rules()),
//             "reason"            => "required|max:1000|min:10",
//         ]);
//         $merit_list->load("application");
//         if (!Gate::check('access-via-application', $merit_list->application)) {
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Accepting merit couselling access denied. Application no " . $merit_list->application->application_no);
//             return redirect()
//                 ->back()
//                 ->with("error", "Unauthorized access.");
//         }
//         if($merit_list->declined_otp != request("otp")){
//             saveLogs($user->id, $user->name, "student", "Declining merit list seat for application no {$merit_list->application_no}. OTP validation FAILED.");
//             return redirect()
//                 ->back()
//                 ->with("error", "OTP doesn't matched.")
//                 ->withInput(request()->all());
//         }
//         $merit_list->update([
//             "declined_at"     => now(),
//             "declined_text"   => request("reason_from_list"), // reported for admission
//             "declined_remark" => request("reason"), // reported for admission
//             "status"          => 9, // reported for admission
//         ]);
//         saveLogs($user->id, $user->name, "student", "Declined successfull merit list seat for application no {$merit_list->application_no}.");
//         return redirect()
//             ->back()
//             ->with("success", "Your seat is declined.");
//     }
//     public function withdrawSeat(MeritList $merit_list)
//     {
//         $user = request()->user();
//         saveLogs($user->id, $user->name, "student", "Withdrawing merit list seat for application no {$merit_list->application_no}.");
//         $this->validate(request(), [
//             "otp"              => "required",
//             "reason_from_list" => "required|in:" . implode(",", CommonHelper::admission_decline_rules()),
//             "reason"           => "required|max:1000|min:10",
//             'dob'              => "required|date_format:Y-m-d",
//             'bank_account'     => "required|string|max:100",
//             'holder_name'      => "required|string|max:100",
//             'bank_name'        => "required|string|max:100",
//             'branch_name'      => "required|string|max:100",
//             'ifsc_code'        => "required|string|max:50",
//         ]);
//         $merit_list->load("application");
//         if (!Gate::check('access-via-application', $merit_list->application)) {
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Accepting merit couselling access denied. Application no " . $merit_list->application->application_no);
//             return redirect()
//                 ->back()
//                 ->with("error", "Unauthorized access.");
//         }
//         if($merit_list->declined_otp != request("otp")){
//             saveLogs($user->id, $user->name, "student", "Withdrawing merit list seat for application no {$merit_list->application_no}. OTP validation FAILED.");
//             return redirect()
//                 ->back()
//                 ->with("error", "OTP doesn't matched.")
//                 ->withInput(request()->all());
//         }
//         DB::beginTransaction();
//         try {
//             WithdrawalRequest::create([
//                 "merit_list_id"    => $merit_list->id,
//                 "application_id"   => $merit_list->application->id,
//                 "student_id"       => $merit_list->student_id,
//                 "reason_from_list" => request("reason_from_list"),
//                 "reason"           => request("reason"),
//                 'dob'              => request("dob"),
//                 'bank_account'     => request("bank_account"),
//                 'holder_name'      => request("holder_name"),
//                 'bank_name'        => request("bank_name"),
//                 'branch_name'      => request("branch_name"),
//                 'ifsc_code'        => request("ifsc_code"),
//                 "status"           => "approved",
//                 "by_id"            => $user->id,
//                 "by_type"          => get_class($user),
//             ]);
//             $merit_list->convertToSeatWithdrawal();
//             $merit_list->course_seat()->decrement("total_seats_applied");
//             $cc = ["acad@tezu.ernet.in", "tuee2021@tezu.ernet.in"];
//             if(in_array($merit_list->course_id, btechCourseIds())){
//                 $cc[] = "bssc2021@tezu.ac.in";
//             }
//             Mail::to($user->email)
//             ->bcc("tuee2021@gmail.com")
//             ->cc($cc)->send(new \App\Mail\SeatWithdrawalRequest($merit_list));
//         } catch (\Throwable $th) {
//             report($th);
//             saveLogs($user->id, $user->name, "student", "Withdrawal requesed for application no {$merit_list->application_no} is failed.");
//             return redirect()
//             ->back()
//             ->with("error", "Request failed. Try again later or contact helpline no.");
//         }

//         saveLogs($user->id, $user->name, "student", "Withdrawal requesed for application no {$merit_list->application_no} is sent.");
//         DB::commit();
//         return redirect()
//             ->back()
//             // ->with("success", "Your seat is withdraw request is sent. Please wait for department approval.");
//             ->with("success", "Your seat is withdrew successfully.");
//     }
//     public function sendOTP(MeritList $merit_list)
//     {
//         $merit_list->load("application", "application");
//         if(!Gate::check('access-via-application', $merit_list->application)){
//             saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "OTP verificaiton unauthorized access for declining merit list. For application no ".$merit_list->application->application_no);
//             abort(401, "unauthorized access.");
//         }
//         $otp = rand(1020, 9899);
//         $merit_list->update([
//             "declined_otp"   => $otp
//         ]);
//         // $message = "{$otp} is your OTP. Tezpur University";
//         $message = "Dear Applicant, your OTP for decline seat is {$otp} .Tezpur University";
//         sendSMS($merit_list->student->mobile_no, $message, "1207163247700372856");
//         saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "OTP for declining Merit List success for application no ".$merit_list->application_no);
//         return [
//             "message"   => "OTP sent"
//         ];
//     }
}
