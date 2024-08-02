<?php

namespace App\Http\Controllers\DepartmentUser;

use App\Course;
use App\HostelMaster;
use App\HostelReceipt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\AdmissionReceipt;
use App\Models\Application;
use App\Models\MeritList;
use App\Models\OnlinePaymentProcessing;
use App\Models\OnlinePaymentSuccess;
use App\Services\PaymentHandlerService;
use App\SpotAdmission;
use Crypt;
use DB;
use Exception;
use Log;
use Validator;
use Webpatser\Uuid\Uuid;

class HostelAllotmentController extends Controller
{

    public $fee_structure = null;

    public function index(Request $request){
        // dd("id");
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
        $merit_lists = $merit_lists->where('status',2)->get();
        // dd($merit_lists);
        $hostels= HostelMaster::get();
        if (auth("admin")->check()) {
            $layot = 'admin.layout.auth';
        }else{      
            $layot = 'department-user.layout.auth';
        }
        return view('department-user.hostel.index', compact('merit_lists','admission_categories','layot','hostels'));
    }

    public function spotAllow(){
        $courses = programmes_array();
        $course_array = [];
        foreach($courses as $id=>$name){
            array_push($course_array,$id);
        }
        $students = SpotAdmission::whereIn('course_id',$course_array)->get();
        return view('department-user.hostel.spot-admission',compact('courses','students'));
    }

    public function spotSave(Request $request){
        $students = SpotAdmission::where('mobile_no',$request->m_no)->first();
        if($students){
            return redirect()->back()->with('error','Already Exist');
        }
        SpotAdmission::create([
                'name'     => $request->s_name,
                'course_id'=> $request->course_id,
                'mobile_no'=> $request->m_no,
            ]);
        return redirect()->back()->with('success','Successfull');
    }

    public function spotDelete($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (\Exception $e) {
            
        }
        SpotAdmission::where('id',$decrypted)->delete();
        return redirect()->back()->with('success','Successfull');
    }

    public function assignHostel(Request $request){
        $rules = [
            "hos_name"    => "required",
            "hos_room_no" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error','please fill all the field');
        }
        MeritList::where('id',$request->ml_id)->update([
            'hostel_required' => 3,
            'hostel_name'=>$request->hos_name,
            'room_no'    =>$request->hos_room_no,
        ]);
        return redirect()->back()->with('success','Successfully assigned Hostel, Please Proceed for payment');
        // dd($request->all());
    }


    public function changeHostel(Request $request){
        dd("ok");
        $rules = [
            "hos_name"    => "required",
            "hos_room_no" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error','please fill all the field');
        }
        $ml = MeritList::where('id',$request->ml_id_change)->update([
            // 'hostel_required' => 3,
            'hostel_name'=>$request->hos_name,
            'room_no'    =>$request->hos_room_no,
        ]);

        $hostel_receipt = HostelReceipt::where('student_id',$ml->student_id)->update([
            'hostel_name'  => $request->hos_name,
            "room_no"      => $request->hos_room_no,
        ]);

        return redirect()->back()->with('success','Successfully changed Hostel');
        // dd($request->all());
    }

    private function setFeeStructure(MeritList $merit_list)
    {
        $this->fee_structure = $merit_list->fee_structure_hostel();
        return $this;
    }
    private function getAdmissionAmount($application)
    {
        $sum_amount = 0.00;
        if($this->fee_structure){
            $sum_amount = $this->fee_structure->sum("amount");
        }
        if(!$this->fee_structure){
            throw new Exception("Fee Structure not generate. Please contact Tezpur University Authority / Helpline No..", 1);
        }
        return [$sum_amount, "INR"];
    }
    public function hostelProcessPayment(Request $request, $encrypted_id)
    {    
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Proceed payment.");
            abort(404);
        }
        $merit_list = MeritList::whereId($decrypted_id)->with(["application.online_admission_payment_tried"])->first();
        if($merit_list->course->admission_status==0){
            abort(404, "Invalid Request");
        }
        
        $application = $merit_list->application;
       
        try {
            $this->setFeeStructure($merit_list);
            $fee_structure = $this->fee_structure;
            $application->load("online_admission_payments_succeed");
            // $application->online_admission_payments_succeed_count = $merit_list->online_admission_payments_succeed_hostel->count();
            
            // try {
            //     $this->checkPaymentAllowed($application, $merit_list);
            // } catch (\Throwable $th) {
            //     Log::emergency("Admission payment not allowed for " . $application->id);
            //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission payment not allowed for application id " . $application->id);
            //     return redirect()
            //         ->back()
            //         ->with("error", $th->getMessage());
            // } catch (\Exception $th) {
            //     Log::emergency("Admission payment not allowed for " . $application->id);
            //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission payment not allowed for application id " . $application->id);
            //     return redirect()
            //         ->back()
            //         ->with("error", $th->getMessage());
            // }
            // if($merit_list->isDateExpired()){
            //     Log::emergency("Admission payment date expired for " . $application->application_no);
            //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission payment date expired for application no " . $application->application_no);
            //     return redirect()
            //         ->back()
            //         ->with("error", "Time Expired");
            // }
          
        } catch (\Exception $e) {
            
            Log::emergency($e);
            return redirect()->back()->with("error", "Whoos! something went wrong. Please try again later.");
        }
        // if ($application->online_admission_payments_succeed_count) {
        //     return redirect()->back()->with("error", "Fee already paid.");
        // }
        
        DB::beginTransaction();
        try {
            $merchantOrderID           = env("MERCHANT_ORDER_ID", uniqid());
            $currency                  = "INR";
            [$amount, $currency_value] = $this->getAdmissionAmount($application);
            // dd($amount);
            // if($amount <= 0){
            //     try {
            //         $online_payment_obj = new OnlinePaymentSuccess();
            //         $application_already_paid = $application->online_admission_payments_succeed;
            //         if($application_already_paid->count()){
            //             $online_payment_obj = $application_already_paid->last();
            //         }
            //         $this->changeApplicationAnyDetails($application, $online_payment_obj, $merit_list);
            //         saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->application_no} Proceeding zero payment succeed.");
            //         DB::commit();
            //         return redirect()->route("department.admission.payment-receipt", Crypt::encrypt($merit_list->id))->with("success", "Payment Succssfull.");
            //     } catch (\Throwable $th) {
            //         // dd($th);
            //         Log::error($th);
            //         DB::rollback();
            //         saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->application_no} Proceeding zero payment failed.");
            //         // dd("OK");
            //         return redirect()->back()->with("error", "Whoops! something went wrong. try again later.");
            //     }
            // }
            $paymentHanlderService = new PaymentHandlerService;
            $tried_records = $merit_list->online_admission_payment_tried_hostel->last();
            if ($tried_records) {
                $previous_order = $paymentHanlderService->orderFetchByOrderId($tried_records->order_id);
                if (PaymentHandlerService::isOrderPaid($previous_order)) {
                    $payments_data = $previous_order->payments();
                    foreach ($payments_data->items as $index => $payment) {
                        if (PaymentHandlerService::isPaymentPaid($payment)) {
                            $online_payment = OnlinePaymentSuccess::create([
                                "application_id"    => $application->id,
                                "student_id"        => $application->student_id,
                                "order_id"          => $previous_order->id,
                                "amount"            => $previous_order->amount/100,
                                "amount_in_paise"   => $previous_order->amount,
                                "response_amount"   => $payment->amount,
                                "currency"          => $payment->currency,
                                "merchant_order_id" => $merchantOrderID,
                                "payment_id"        => $payment->id,
                                "course_id"         => $merit_list->course_id,
                                "payment_signature" => null,
                                "payment_type"      => "hostel",
                                "is_error"          => $payment->error_code ?? false,
                                "error_message"     => $payment->error_description,
                                "biller_status"     => $payment->status,
                                "biller_response"   => json_encode($payment),
                                "status"            => 1,
                            ]);
                            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
                        }
                    }
                    $this->changeApplicationAnyDetails($application, $online_payment, $merit_list);
                    DB::commit();
                    return redirect()->back()->with("success", "Your Payment is already done.");
                }
            }
            $transfers = [
                [
                    "account"   => config("vknrl.ADMISSION_ACC_RAZORPAY"),
                    "amount"    => $amount * 100,
                    "currency"  => $currency_value,
                    "notes"    => [
                        "name"  => "Admission fee collection account"
                    ]
                ]
            ];
            $order = $paymentHanlderService->setData($amount, "INR", $merchantOrderID, [
                "merchant_order_id" => (string)$merchantOrderID,
                "payment_processing"    => true,
                "student_id"        => $application->student_id,
                "application_id"    => $application->id,
                "curr_value"        => $currency_value,
            ], $transfers)->createOrder(true);
            $data = [
                'key'         => config("vknrl.RAZORPAY_KEY"),
                'order_id'    => $order->id,
                'amount'      => $amount,
                'image'       => asset("logo.png"),
                'description' => env("APP_NAME") . ': Order #' . $merchantOrderID,
                'prefill'     => [
                    'name'    => $application->fullname,
                    'email'   => $application->student->email,
                    'contact' => $application->student->mobile_no,
                ],
                'notes'       => [
                    'merchant_order_id' => (string) $merchantOrderID,
                ],
                'theme'       => [
                    'color' => '#2E86C1',
                ],
            ];
        } catch (Exception $e) {
            dd($e);
            Log::error($e);
            DB::rollback();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->id} Proceeding payment failed.");
            return redirect()->back()->with("error", "Whoops! something went wrong. try again later.");
        }
        $processing_obj = OnlinePaymentProcessing::create([
            "application_id"    => $application->id,
            "student_id"        => $application->student_id,
            "order_id"          => $order->id,
            "amount"            => ($amount / 100),
            "amount_in_paise"   => $amount,
            "currency"          => "INR",
            "merchant_order_id" => $merchantOrderID,
            "payment_done"      => 0,
            "payment_type"      => "hostel",
            "course_id"         => $merit_list->course_id,
            "merit_list_id"     => $merit_list->id,
        ]);
        DB::commit();

        $payment_type = $processing_obj->payment_type;
        // dd($fee_structure);
        if(auth("department_user")->check()){
            $response_url = route("department.hostel-payment-response", Crypt::encrypt($merit_list->id));
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for admission payment Application id: {$application->id}");
            return view('department-user.hostel.payment-process', compact(
                    "application", "order",
                    "data", "amount", "merchantOrderID",
                    "currency", "payment_type", "merit_list", "fee_structure"
                ));
        }
    }
    // after payment successfull
    public function hostelPaymentRecieved(Request $request, $encrypted_id)
    {
        // dd("ok");
        $accessId  = env("PAYMENT_ACCESS_ID");
        $secretKey = env("PAYMENT_SECRET_KEY");
        Log::notice(json_encode($request->all()));
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::emergency($e);
            return redirect()->/* route('student.application.index') */back()->with("error", "Whoops! Something went wrong.");
        }
        try {
            $payment_service = new PaymentHandlerService;
            $payment_service->verifySignature($request->get("order_id"), $request->get("payment_id"), $request->get("payment_signature"));
            $order = $payment_service->orderFetchByOrderId($request->get("order_id"));
            $payment = $payment_service->retrievePayment($request->get("payment_id"));
        } catch (Exception $e) {
            Log::emergency($e);
            return redirect()->/* route("student.application.index") */back()->with("error", "Payment Details fetching error. Wait sometimes or contact to helpline no.");
        }
        // dd($payment);
        DB::beginTransaction();
        try {
            $merit_list = MeritList::findOrFail($decrypted_id);

            $application = $merit_list->application;
            // $application = Application::findOrFail($decrypted_id);
            // if ($this->isPaymentAlreadyDone($merit_list)) {
            //     return redirect()->/* route("student.application.index") */back()->with("error", "Fee already paid.");
            // }
            // Application id from application_id , student_id is just passed so not taken.
            $online_payment = OnlinePaymentSuccess::create([
                "application_id"    => $application->id,
                "student_id"        => $application->student_id,
                "order_id"          => $request->get("order_id"),
                "amount"            => $order->amount/100,
                "amount_in_paise"   => $order->amount,
                "response_amount"   => $payment->amount,
                "currency"          => $payment->currency,
                "merchant_order_id" => $request->get("merchant_order_id"),
                "payment_id"        => $request->get("payment_id"),
                "payment_signature" => $request->get("payment_signature"),
                "is_error"          => $request->get("is_error"),
                "error_message"     => $request->get("error_message"),
                "biller_status"     => $payment->status,
                "biller_response"   => $request->get("response"),
                "payment_type"      => "hostel",
                "course_id"         => $merit_list->course_id,
                "merit_list_id"     => $merit_list->id,
                "status"            => 1,
            ]);
            // dd("pkk");
            
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            $this->changeApplicationAnyDetails($application, $online_payment, $merit_list);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->application_no} payment response done.");

        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()->back()->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();
        
            return redirect()->route("department.hostel-receipt", Crypt::encrypt($merit_list->id))->with("success", "Payment Succssfull.");
        
        
    }

    private function changeApplicationAnyDetails(Application $application, OnlinePaymentSuccess $online_payment, MeritList $merit_list)
    {
        
        $this->setFeeStructure($merit_list);
        
        [$amount, $currency_value] = $this->getAdmissionAmount($application);
        $fees = $this->fee_structure;

        // $admission_rcpt= $merit_list->admissionReceipt->roll_number;
        $hostel_receipt_data = [
            "uuid"              => Uuid::generate()->string,
            "receipt_no"        => date("Y"),
            "student_id"        => $merit_list->student_id,
            "application_id"    => $application->id,
            "course_id"         => $merit_list->course_id,
            "category_id"       => $merit_list->admission_category_id,
            "online_payment_id" => $online_payment->id,
            "pay_method"        => $online_payment->payment_signature=="Cash"? "Cash" : "Online", 
            "transaction_id"    => $online_payment->payment_id,
            "type"              => "hostel",
            "total"             => $amount,
            "status"            => 0,
            "year"              => date("Y"),
            "merit_list_id"     => $merit_list->id,
            "previous_receipt_id" =>  null,
            "previous_received_amount" =>  null,
            'hostel_name'  => $merit_list->hostel_name,
            "room_no"      => $merit_list->room_no,
            "roll_number"  => $merit_list->admissionReceipt->roll_number??NULL,
        ];
        $hostel_receipt = HostelReceipt::create($hostel_receipt_data);
        $admission_collections = [];
        // dd($fees);
        foreach($fees as $fee){
            $admission_collections[] = [
                "receipt_id"     => $hostel_receipt->id,
                "student_id"     => $merit_list->student_id,
                "application_id" => $application->id,
                "fee_id"         => $hostel_receipt->id,
                "fee_head_id"    => $fee->fee_head_id,
                "amount"         => $fee->amount,
                "free_amount"    => 0.00,
                "is_free"        => 0,
            ];
        }
        $hostel_receipt->collections()->createMany($admission_collections);
        $prev_count = HostelReceipt::where("id", "<=", $hostel_receipt->id)->withTrashed()->count();
        $receipt_no = "TU".date("Y").$prev_count;
        $hostel_receipt->receipt_no = $receipt_no;
        // $hostel_receipt->roll_number = $hostel_receipt->generateRollNumber();
        $hostel_receipt->save();
        $merit_list->update(['hostel_required'=>4]);
    }

    public function hostelPaymentReceipt(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
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
    
    public function noHostel($encrypted_id){
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        MeritList::where('id',$decrypted_id)->update(['hostel_required'=>5]);
        return redirect()->back()->with('success','Successfull');
        
    }

    public function allowHos($encrypted_id){
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        MeritList::where('id',$decrypted_id)->update(['hostel_required'=>1]);
        return redirect()->back()->with('success','Successfull');
        
    }

    public function laterHostel($encrypted_id){
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }
        MeritList::where('id',$decrypted_id)->update(['hostel_required'=>6]);
        return redirect()->back()->with('success','Successfull');
        
    }
    
}
