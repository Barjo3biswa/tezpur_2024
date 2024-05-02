<?php
namespace App\Traits;

use App\AppliedCourse;
use App\Notifications\StudentAnyMail;
use App\Models\Application;
use App\Models\User;
use App\Notifications\sendMailAHR;
use Log, Crypt, Exception, Validator;

use Illuminate\Http\Request;
/**
 * Trait for handling Accept Reject Hold (ARH) of Application from Admin Panel
 * date: 09-07-2019
 */
trait ApplicationARHControll
{
    public function acceptApplication(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
        try {
            // $application = Application::findOrFail($decrypted_id);
            $application = AppliedCourse::findOrfail($decrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Selected Application Not found.");
        }
        // if($application->status != "payment_done" && $application->status != "on_hold" && $application->status != "document_resubmitted"){
        //     return $this->returnResponse(0, "Sorry! Application status {$application->status} so, unable to Accept.");
        // }
        try {
            
            // For B Tech
               $is_btech_chk=Application::where('id',$application->application_id)->first();
               if($is_btech_chk->is_btech==1){
                AppliedCourse::where('application_id',$application->application_id)->update(['status'=>"accepted"]);
               }else{
                $application->status = "accepted";
                $application->save();
               }
            // End

            
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application {$application->id} Accepted Administrator side.");
            return $this->returnResponse(1, "Application is Accepted", $application);
        } catch (\Exception $th) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
    }
    public function rejectApplication(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
        try {
            // $application = Application::findOrFail($decrypted_id);
            $application = AppliedCourse::findOrfail($decrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Selected Application Not found.");
        }
        // if($application->status != "payment_done" && $application->status != "on_hold"  && $application->status != "document_resubmitted"){
        //     return $this->returnResponse(0, "Sorry! Application status {$application->status} so, unable to Reject.");
        // }
        // validate here
        $rules = [
            "rejection_reason" => "required|min:10|max:255"
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $this->returnResponse(0, "Please fullfill the required critaria. ".implode(",\n",$validator->errors()));
        }
        try {

                $is_btech_chk=Application::where('id',$application->application_id)->first();
                if($is_btech_chk->is_btech==1){
                    AppliedCourse::where('application_id',$application->application_id)->update(['status'=>"rejected",'reject_reason'=>$request->get("rejection_reason")]);
                }else{
                    $application->status = "rejected";
                    $application->reject_reason = $request->get("rejection_reason");
                    $application->save();
                }
            
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application {$application->id} Rejected from Administrator side.");
            return $this->returnResponse(1, "Application is Rejected", $application);
        } catch (\Exception $th) {
            return $this->returnResponse(0, $th);
        }
    }
    public function holdApplication(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
        try {
            // $application = Application::findOrFail($decrypted_id);
            $application = AppliedCourse::findOrfail($decrypted_id);
            $applicationII = Application::findOrFail($application->application_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Selected Application Not found.");
        }
        // if($application->status != "payment_done"  && $application->status != "document_resubmitted"){
        //     return $this->returnResponse(0, "Sorry! Application status {$application->status} so, unable to Hold.");
        // }
        // validate here
        // dd($request->resubmit_date);
        $rules = [
            "holding_reason" => "required|min:10|max:255",
            "resubmit_date" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $this->returnResponse(0, "Please fullfill the required critaria. ".implode(",\n",$validator->errors()));
        }
        try {
            


            // $applicationII->resubmit_allow = $request->get("resubmit_allow") ? 1 : 0;
            // $applicationII->re_payment = $request->get("re_payment") ? 1 : 0;


            // // default amount is 400 given in database so below line removed.
            // // $applicationII->re_payment_amount = $request->get("re_payment_amount") ? $request->get("re_payment_amount") : 400;
            $applicationII->is_editable = 1/* $request->get("allow_to_edit") == "yes" ? 1 : 0 */;
            // $applicationII->is_extra_doc_uploaded = $request->get("allow_to_doc_upload") == "yes" ? 0 : 1;
            // $applicationII->is_eligibility_critaria_submitted = $request->get("allow_to_eligibility_check") == "yes" ? 0 : 1;
                $is_btech_chk=Application::where('id',$application->application_id)->first();
                if($is_btech_chk->is_btech==1){
                    AppliedCourse::where('application_id',$application->application_id)->update(['status'=>"on_hold",'last_date'=>$request->get("resubmit_date"),'reject_reason'=>$request->get("holding_reason")]);
                }else{
                    $application->status = "on_hold";
                    $application->last_date = $request->get("resubmit_date");
                    $application->hold_reason = $request->get("holding_reason");
                    $application->save();
                }
            
            $applicationII->save();
            $user = User::where('id',$application->student_id)->first();
            $user->notify(new sendMailAHR($user));
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application {$application->id} Hold from Administrator side.");
            return $this->returnResponse(1, "Application is on hold", $application);
        } catch (\Exception $th) {
            Log::error($th);
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
    }

    
    private function returnResponse($status, $message, $application = [], $status_code = 200){
        return response()->json([
            "message"   => $message,
            "application"=> (gettype($application) == "object" ? $application->only(["id", "status", "reject_reason", "hold_reason"]): ""),
            "status"    => $status
        ], $status_code);
    }
    
    public function sendSMS(Request $request)
    {
        // dd("ok");
        $rules = [
            "sms"               => "required|min:1",
            "application_ids"   => "required|array|min:1",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            \Log::error($validator->errors());
            return redirect()
                ->back()
                ->withError($validator->errors())
                ->withInput($request->all())
                ->with("error", "Whoops! looks like you have missed something.");;
        }
        $application_ids = $request->get("application_ids");
        $applications = Application::with("student")->whereIn("id", $application_ids)->get();
        $sent_counter   = 0;
        $last_id        = "";
        $failed_counter = 0;
        try {
            if($application_ids){
                foreach ($applications as $key => $application) {
                    $sms = $request->get("sms");
                    $sms = str_replace("##name##", $application->fullname, $sms);
                    $sms = str_replace("##app_no##", $application->id, $sms);
                    $sms = str_replace("##reg_no##", $application->student_id, $sms);
                    $sms = str_replace("##hold_reason##", $application->hold_reason, $sms);
                    $sms = str_replace("##rejected_reason##", $application->reject_reason, $sms);
                    $sms = str_replace("##school_name##", env("APP_NAME", ""), $sms);
                    try {
                        sendSMS($application->student->mobile_no, $sms);
                        if($request->send_email){
                            $application->student->notify(new StudentAnyMail($sms));
                        }
                        $last_id = $application->id;
                        $sent_counter++;
                    } catch (\Exception $e) {
                        \Log::error($e);
                        $failed_counter ++;
                    }
                }
            }
    
        } catch (\Exception $e) {
            return redirect()->back()
                ->with("success", "Message sent total {$sent_counter} & Failed {$failed_counter} last message sent application no {$last_id}");
        }
        return redirect()
                ->back()
                ->with("success", "Message Successfully Sent to {$sent_counter} Applicants and failed {$failed_counter}.");
    }
}