<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonApplicationController;
use App\Traits\ApplicationARHControll;
use App\Models\Application;
use App\Models\MeritList;
use App\Models\MeritListUndertaking;
use App\Models\OnlinePaymentProcessing;
use App\Notifications\StudentAnyMail;
use App\Services\PaymentHandlerService;
use Crypt;
use Validator;
use Excel;
use Exception;
use Session;
use Log;

class ApplicationController extends CommonApplicationController
{
    use ApplicationARHControll;

    
    public function qualifiedStudentImport()
    {
        return view("admin.applications.upload_excel");
    }

    
    public function qualifiedStudentImportPost(Request $request)
    {
        $rules = [
            "file"  => "file|required|mimes:xls,xlsx"
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            dd($validator->errors());
        }
        $file = $request->file('file');
        $excel_records = [];
        try {
            $error = "";
            Excel::load($file, function ($reader) use (&$error, &$excel_records) {
                $date = date("Y-m-d H:i:s");
                if(sizeof($reader->toArray()) > 1){
                    if(isset($reader->toArray()[0][0])){
                        $error = "please upload excel with single sheet as shown in sample.";
                        return true;
                    }
                }
                foreach ($reader->toArray() as $row) {
                    if($row){
                        $excel_records[] = [
                            "application_id"    => (Int)trim($row["application_no"]),
                            "student_reg_no"    => (Int)trim($row["student_reg_no"]),
                            "name"              => (String)trim($row["name"]),
                            "marks"             => (float)trim($row["marks"]),
                            "out_of"            => (float)trim($row["out_of"]),
                            "paas"              => (String)trim($row["paas"]),
                        ];
                    }
                }
            });
        } catch (Exception $e) {
            \Log::error($e);
            return redirect()->back()->with('error', "Whoops! Something went wrong with your excel file. Please try again later.");
        }
        if($error){
            return redirect()->back()->with('error', $error);
        }
        dump($excel_records);
        // dump($file->getMimeType());
        // dd($request->all());
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
    public function undertakingView(MeritList $meritList)
    {
        $meritList->load("undertakings");
        return view("common.merit-list.undertaking", compact("meritList"));
    }
    public function approveUndertaking(MeritListUndertaking $undertaking)
    {
        if(($undertaking->status != MeritListUndertaking::$pending) && ($undertaking->status != MeritListUndertaking::$other_pending)){
            return "Approve option not available please refresh your browser.";
        }
        $date = null;
        if(request("closing_date")){
            $date = request("closing_date")." ".request("hour").":".request("minute").":00";
            $date = date("Y-m-d H:i:s", strtotime($date));
        }
        try {
            if($undertaking->doc_name == "undertaking"){
                $undertaking->update([
                    "status"            => request()->get("rejected") ? MeritListUndertaking::$rejected : MeritListUndertaking::$accepted,
                    "remark_by_admin"   => request()->get("remark") ? request()->get("remark") : (request()->get("rejected") ? "rejected by admin" : "approved by admin"),
                    "closing_date_time" => $date
                ]);
            }else{
                $undertaking->update([
                    "status"            => request()->get("rejected") ? MeritListUndertaking::$other_rejected : MeritListUndertaking::$other_accepted,
                    "remark_by_admin"   => request()->get("remark") ? request()->get("remark") : (request()->get("rejected") ? "rejected by admin" : "approved by admin"),
                    "closing_date_time" => $date
                ]);
            }
            $application = $undertaking->application;
            $student = $application->student;
            $message = "Your undertaking/document is approved. Please login to the portal for more information. \nRegards \nTezpur Univeristy";
            try{
                if(request()->get("rejected")){
                    $message = "Your undertaking/document is rejected. Please login to portal for more information. \nRegards \nTezpur Univeristy";
                    sendSMS($student->mobile_no, $message, "1207162507831934945");
                    $student->notify(new StudentAnyMail($message));
                }else{
                    sendSMS($student->mobile_no, $message, "1207162507822940817");
                    $student->notify(new StudentAnyMail($message));
                }
            }catch(\Exception $e){
                \Log::error($e);
            }
            Session::flash("success", "Successfully ".(request()->get("rejected") ? "rejected." : "approved."));
            $message = "Successfully ".(request()->get("rejected") ? "rejected." : "approved.")." and notification sent via email and SMS.
            The latest status  of Undertaking will not reflect on your screen until the page refresh.
            ";
            return $message;
        } catch (\Throwable $th) {
            return "Failed.";
        }
        
    }

    public function failedPayments(Request $request){
        $session_id = $request->session_id??13;
        // dd($session_id);
        $client = new PaymentHandlerService;
        $application = Application::where('status','payment_pending')->where('session_id',$session_id)->get();
        foreach($application as $app){
            foreach($app->failedPayment as $payments){
                $previous_order = $client->orderFetchByOrderId($payments->order_id);
                if(PaymentHandlerService::isPaymentPaid($previous_order)){
                    dump('order_id:'.$payments->order_id.' student_id:'.$payments->student_id.' app_id:'.$payments->application_id) ;
                }
            }
        }
        // return view("")
        dd("alll");
        dd($application->count());
    }
}
