<?php
namespace App\Traits;

use App\Course;
use App\Models\AdmissionCategory;
use App\Models\AdmissionCollection;
use App\Models\AdmissionReceipt;
use App\Models\Application;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\MeritMaster;
use App\Models\OnlinePaymentProcessing;
use App\Models\OnlinePaymentSuccess;
use App\Models\Session as ModelsSession;
use App\Models\User;
use App\Services\PaymentHandlerService;
use Crypt;
use DB;
use Exception;
use Gate;
use Log;
use Payabbhi\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;
use Pusher\Pusher;
use Session;

/**
 * Trait for student admission
 * date: 01-07-2019
 */
trait StudentsAdmission
{
    public $fee_structure = null;

    // payment processing
    public function admissionProcessPayment(Request $request, $encrypted_id)
    {
       
       
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // Log::error($e);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Proceed payment.");
            abort(404);
            // return redirect()->route(get_guard().".home")->with("error", "Whoops! Looks like you");
        }
        // dd("oktestee");
        $merit_list = MeritList::whereId($decrypted_id)->with(["application.online_admission_payment_tried"])->first();
        if($merit_list->course->admission_status==0){
            abort(404, "Invalid Request");
        }
        $application = $merit_list->application;
        // if(!Gate::check('access-via-application', $merit_list->application)){
        //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission hostel access denied for application no ".$merit_list->application->application_no);
        //     return redirect()
        //         ->route("student.application.index")
        //         ->with("error", "Unauthorized access.");
        // }
        
        try {
            $this->setFeeStructure($merit_list);
            $fee_structure = $this->fee_structure;
            $application->load("online_admission_payments_succeed");
            $application->online_admission_payments_succeed_count = $merit_list->online_admission_payments_succeed->count();
            // $application = Application::with("online_admission_payment_tried")->withCount(["online_admission_payments_succeed"])->find($decrypted_id);
            /************Payment condition check for date valid user***************/
            try {
                // dump($merit_list);
                // dd($application);
                $this->checkPaymentAllowed($application, $merit_list);
            } catch (\Throwable $th) {
                Log::emergency("Admission payment not allowed for " . $application->id);
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission payment not allowed for application id " . $application->id);
                return redirect()
                    ->back()
                    ->with("error", $th->getMessage());
            } catch (\Exception $th) {
                Log::emergency("Admission payment not allowed for " . $application->id);
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission payment not allowed for application id " . $application->id);
                return redirect()
                    ->back()
                    ->with("error", $th->getMessage());
            }
            // please uncheck after all merged
            if($merit_list->isDateExpired()){
                Log::emergency("Admission payment date expired for " . $application->application_no);
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission payment date expired for application no " . $application->application_no);
                return redirect()
                    ->back()
                    ->with("error", "Time Expired");
            }
            // if ($this->isPaymentNotAllow($application, $merit_list)) {
            //     Log::emergency("Admission payment not allowed for " . $application->id);
            //     saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admission payment not allowed for application id " . $application->id);
            //     return redirect()->route("student.application.index")->with("error", "Payment not allowed. Please ask authority for more information.");
            // }
            /************Payment condition check for date valid user end***************/

        } catch (Exception $e) {
            // dd($e);
            Log::emergency($e);
            return redirect()->back()->with("error", "Whoos! something went wrong. Please try again later.");
        }
        if ($application->online_admission_payments_succeed_count) {
            // return redirect()
            // ->route("student.application.index")
            // ->with("error", "Fee already paid.");
            return redirect()->back()->with("error", "Fee already paid.");
        }
        // dd($this->getAdmissionAmount($merit_list));
        // dd("OKKKKK33");
        DB::beginTransaction();
        try {
            // $accessId                  = env("PAYMENT_ACCESS_ID");
            // $secretKey                 = env("PAYMENT_SECRET_KEY");
            $merchantOrderID           = env("MERCHANT_ORDER_ID", uniqid());
            $currency                  = "INR";
            [$amount, $currency_value, $last_receipt] = $this->getAdmissionAmount($application);
            // converting amount into paise
            // $amount = round($amount * 100);
            // dd($amount);
            // if($amount <= 0){
            //     // if amount is zero able to proceed without payment.
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
            //The merchant_order_id is typically the identifier of the Customer Order, Booking etc in your system
            // $client        = new Client($accessId, $secretKey);
            $paymentHanlderService = new PaymentHandlerService;
            $tried_records = $merit_list->online_admission_payment_tried->last();
            // dd($tried_records);
            if ($tried_records) {
                // $previous_order = $client->order->retrieve($tried_records->order_id);
                $previous_order = $paymentHanlderService->orderFetchByOrderId($tried_records->order_id);
                // dd($previous_order);
                // $tried_records->succed_payments()->delete();
                // $order_status = (isset($previous_order["status"]) ? $previous_order["status"] : $previous_order->status);
                // if (strtolower($order_status) == "paid") {
                if (PaymentHandlerService::isOrderPaid($previous_order)) {
                    $payments_data = $previous_order->payments();
                    // dd($payments_data);
                    foreach ($payments_data->items as $index => $payment) {
                        // dd($payment);
                        // only if payment is done captured status
                        // if ($payment->status == "captured") {
                        if (PaymentHandlerService::isPaymentPaid($payment)) {
                            // dd("ok");
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
                                "payment_type"      => "admission",
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

            

            // check order if already payment
            // end of checking order payment
            // $order = $client->order->create([
            //     'amount'            => $amount,
            //     'currency'          => 'INR',
            //     'merchant_order_id' => $merchantOrderID,
            //     "notes"             => [
            //         "merchant_order_id"  => (string) $merchantOrderID,
            //         "payment_processing" => true,
            //         "student_id"         => $application->student_id,
            //         "application_id"     => $application->id,
            //         "m_id"               => $merit_list->id,// merit list id
            //         "curr_value"         => $currency_value,
            //     ],
            // ]);
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
            // $json = json_encode($data);
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
            "payment_type"      => "admission",
            "course_id"         => $merit_list->course_id,
            "merit_list_id"     => $merit_list->id,
        ]);
        DB::commit();

        $payment_type = $processing_obj->payment_type;
        if(auth("department_user")->check()){
            $response_url = route(/* "student.admission.payment-response" */"department.payment-response", Crypt::encrypt($merit_list->id));
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for admission payment Application id: {$application->id}");
            return view('student.admission.admin-payment-process', compact(
                    "application", "order", /* "client", */
                    "data", "amount", "merchantOrderID",
                    "currency", /* "url", */ "payment_type", "merit_list", "fee_structure", "last_receipt"
                ));
        }elseif(auth("student")->check()){
            $response_url = route("student.admission.payment-response", Crypt::encrypt($merit_list->id));
            
            // dd($response_url);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for admission payment Application id: {$application->id}");
            // dd("ok");
            return view("student.admission.process-payment", compact(
                "application", "order", /* "client", */
                "data", "amount", "merchantOrderID",
                "currency", /* "url", */ "payment_type", "merit_list", "fee_structure", "last_receipt"
            ));
        }
    }
    // after payment successfull
    public function admissionPaymentRecieved(Request $request, $encrypted_id)
    {
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
            if ($this->isPaymentAlreadyDone($merit_list)) {
                return redirect()->/* route("student.application.index") */back()->with("error", "Fee already paid.");
            }
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
                "payment_type"      => "admission",
                "course_id"         => $merit_list->course_id,
                "merit_list_id"     => $merit_list->id,
                "status"            => 1,
            ]);
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            $this->changeApplicationAnyDetails($application, $online_payment, $merit_list);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->application_no} payment response done.");


            
        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return redirect()->/* route("student.application.index") */back()->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();

        if($merit_list->admission_category_id==1){
            if(checkOpenAvailability($merit_list->course_id)==false){ 
                Course::where('id',$merit_list->course_id)->withTrashed()->update(['sliding_possibility'=>0]);
                MeritMaster::where('id',$merit_list->merit_master_id)->update(['sliding_possibility'=>0]);
            }
        }
        
        if(auth("department_user")->check()){
            return redirect()->route("department.receipt"/* "student.admission.payment-receipt" */, Crypt::encrypt($merit_list->id))->with("success", "Payment Succssfull.");
        }else{
            return redirect()->route('student.online-admission.process'/* "student.admission.payment-receipt" */, Crypt::encrypt($merit_list->student_id))->with("success", "Payment Succssfull.");
        }
        // return redirect()->route("department.receipt"/* "student.admission.payment-receipt" */, Crypt::encrypt($merit_list->id))->with("success", "Payment Succssfull.");
        
    }
    public function admissionPaymentReceipt(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list = MeritList::findOrFail($decrypted_id);
        $receipt = $merit_list->admissionReceipt->load("collections.feeHead");
        $application = $merit_list->application;
        // dd($application);
        return view($this->getAdmissionReceiptView(), compact("application", "merit_list", "receipt"));
    }
    public function admissionSeatRelease($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. Try again later.");
        }
        $merit_list = MeritList::findOrFail($decrypted_id);
        
        // dd($merit_list->course_seat());
        // $application = $merit_list->application;
        $merit_list->admissionReceipt()
            ->update([
                "status"    => 1
            ]);
            $merit_list->status = 3;
            $merit_list->save();

            // if($merit_list->may_slide==3){
            //     $merit_list=MeritList::where(['student_id'=>$merit_list->student_id,
            //                                   'course_id' =>$merit_list->course_id,
            //                                   'may_slide' =>2,
            //                                   'status'    =>14])->first();
            //     $merit_list->status = 15;
            //     $merit_list->save();
            // }


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
            return redirect()
                ->route("admission.release-receipt", Crypt::encrypt($merit_list->id))
                ->with("success", "Your admission seat is successfully released. Now you can take admission to other program.");
    }
    public function getAdmissionReceiptView()
    {
        $guard = get_guard();
        if ($guard == "admin") {
            return "admin.applications.payment-receipt";
        } elseif ($guard == "student") {
            return "student.admission.single-receipt";
        } elseif ($guard == "department_user") {
            return "student.admission.single-receipt";
            // return "department.applications.payment-receipt";
        }

    }
    
    private function checkPaymentAllowed(Application $application, MeritList $merit_list)
    {
        // dd($application->student_id);
        $merit_list_count = MeritList::where("student_id", $application->student_id/* auth("student")->id() */)
            ->where("status", 2)
            ->count();
        // dd($merit_list_count);
        if($merit_list_count){
            throw new Exception("Please release your previous seat to continue.", 1);
        }
        if(!$this->fee_structure){
            throw new Exception("Fee structure is not yet generated. Please try again later. Or Contact Tezpur University authority.", 1);
        }
        $course_seat = $merit_list->course_seat();
        if(!$course_seat){
            throw new Exception("Seat Details not available. Please contact Helpline No.", 1);
        }
        if($course_seat->total_seats <= $course_seat->total_seats_applied){
            throw new Exception("Admission Seat is already full. If found any issue please contact Tezpur University authrority.", 1);
            
        }
        if($merit_list->isAutomaticSystem()){
            if($merit_list->status !== 8){
                if($merit_list->status === 3){
                    throw new Exception("Your admission seat is already transferred.", 1);
                }
                if($merit_list->status === 4){
                    throw new Exception("Your admission seat is cancelled.", 1);
                }
                throw new Exception("Your application is in waiting list. Please wait for confirmation.", 1);
            }
        }else{
            if($merit_list->status !== 8){
                if($merit_list->status === 3){
                    throw new Exception("Your admission seat is already transferred.", 1);
                }
                if($merit_list->status === 4){
                    throw new Exception("Your admission seat is cancelled.", 1);
                }
                throw new Exception("Your application is in waiting list. Please wait for confirmationn.", 1);
            }
        }
        return true;
        // payment related condition if payment allowed or not.
    }
    private function isPaymentAlreadyDone(MeritList $merit_list){
        if(in_array($merit_list->status, [2,3])){
            return true;
        }
        return false;
    }
    private function getAdmissionAmount($application)
    {
        $sum_amount = 0.00;
        if($this->fee_structure){
            
            $sum_amount = $this->fee_structure->feeStructures->sum("amount");
            // dd($sum_amount);
        }
        if(!$this->fee_structure){
            throw new Exception("Fee Structure not generate. Please contact Tezpur University Authority / Helpline No..", 1);
        }
        $last_receipt = AdmissionReceipt::where("student_id", $application->student_id)->latest()->first();
        // dd($application->student_id);
        $sum_amount -= $last_receipt->total ?? 0.00;
        if($sum_amount < 0){
            $sum_amount = 0;
        }
        // else you can throw exception here. fee_structure not set yet.
        // payment related condition if payment allowed or not.
        return [$sum_amount, "INR", $last_receipt];
    }

    private function changeApplicationAnyDetails(Application $application, OnlinePaymentSuccess $online_payment, MeritList $merit_list)
    {
        // dd("ok");
        $was_hold=0;
        if($merit_list->new_status=="time_extended"){
            $was_hold=1;
        }
        $merit_list->update(["status" => "2",
                                        "new_status" => "admitted"]);
        // reject all previous admission merit list,
       /*  MeritList::where("student_id", $merit_list->student_id)
        ->where("id", "!=", $merit_list->id)
        ->where("status", 1)
        ->update([
            "status"    => 3
        ]); */
        // if application status neede to change and sms send logic here.
        // $application->payment_status = 1;
        // $application->status         = "payment_done";
        // $application->save();
        if(!isset($online_payment->id) || !$online_payment->id){
            // if payment amount is zero create a zero amount transaction
            $online_payment = OnlinePaymentSuccess::create([
                "application_id"    => $application->id,
                "student_id"        => $application->student_id,
                "order_id"          => uniqid(),
                "amount"            => 0.0,
                "amount_in_paise"   => 0.0,
                "response_amount"   => 0.0,
                "currency"          => "INR",
                "merchant_order_id" => "ZERO".uniqid(),
                "payment_id"        => "ZERO".uniqid(),
                "payment_signature" => null,
                "is_error"          => false,
                "error_message"     => null,
                "biller_status"     => "captured",
                "biller_response"   => null,
                "payment_type"      => "admission",
                "course_id"         => $merit_list->course_id,
                "merit_list_id"     => $merit_list->id,
                "status"            => 1,
            ]);
        }
        
        $this->setFeeStructure($merit_list);
        
        [$amount, $currency_value, $last_receipt] = $this->getAdmissionAmount($application);
        
        $fees = $this->fee_structure;
        
        $current_session = ModelsSession::where('is_active',1)->first()->id;
        // dd($current_session);
        $admission_receipt_data = [
            "uuid"              => Uuid::generate()->string,
            'session_id'        => $current_session,
            "receipt_no"        => date("Y"),
            "student_id"        => $merit_list->student_id,
            "application_id"    => $application->id,
            "course_id"         => $merit_list->course_id,
            "category_id"       => $merit_list->admission_category_id,
            "online_payment_id" => $online_payment->id,
            "pay_method"        => $online_payment->payment_signature=="Cash"? "Cash" : "Online", 
            "transaction_id"    => $online_payment->payment_id,
            "type"              => "admission",
            "total"             => $amount,
            "status"            => 0,
            "year"              => date("Y"),
            "merit_list_id"     => $merit_list->id,
            "previous_receipt_id" => $last_receipt->id ?? null,
            "previous_received_amount" => $last_receipt->total ?? null,
            'hostel_name'  => $merit_list->hostel_name,
            "room_no"      => $merit_list->room_no
        ];
        
        $admission_receipt = AdmissionReceipt::create($admission_receipt_data);
        $admission_collections = [];
        foreach($fees->feeStructures as $fee){
            $admission_collections[] = [
                "receipt_id"     => $admission_receipt->id,
                "student_id"     => $merit_list->student_id,
                "application_id" => $application->id,
                "fee_id"         => $admission_receipt->id,
                "fee_head_id"    => $fee->fee_head_id,
                "amount"         => $fee->is_free ? 0.00 : $fee->amount,
                "free_amount"    => $fee->is_free ? $fee->amount : 0.00,
                "is_free"        => $fee->is_free,
            ];
        }
        
        $admission_receipt->collections()->createMany($admission_collections);
        // prev count logic
        $prev_count = AdmissionReceipt::where("id", "<=", $admission_receipt->id)->withTrashed()->count();
        $receipt_no = "TU".date("Y").$prev_count;
        // prev count logic end
        $admission_receipt->receipt_no = $receipt_no;

        $flag=0;
        while($flag < 1){
            $admission_receipt->roll_number = $admission_receipt->generateRollNumber();
            $check = AdmissionReceipt::where('roll_number',$admission_receipt->roll_number)->where('session_id',$current_session)->count();
            if($check==0){
                $flag=1;
            }
        }

        $admission_receipt->save();
        $application->student()->update([
            "roll_number"   => $admission_receipt->roll_number
        ]);
        $this->paymentDoneSMS($application, $admission_receipt);
        $course_seat = $merit_list->course_seat();
        if($course_seat){
            $course_seat->increment("total_seats_applied");
            // if($was_hold==1){
                $course_seat->decrement("temp_seat_applied");
            // }
            if($merit_list->is_pwd){
                $shortlisted_course_seat = CourseSeat::courseFilter($merit_list->course_id)->pwdFilter()->first();
                if($shortlisted_course_seat){
                    $shortlisted_course_seat->increment("total_seats_applied");
                    $shortlisted_course_seat->decrement("temp_seat_applied");
                }
                if($shortlisted_course_seat->total_seats_applied==$shortlisted_course_seat->total_seats){
                    $shortlisted_course_seat->update(['invitation_flag'=>1,'admission_flag'=>'close']);
                }
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
            
            if($merit_list->admission_category_id==1){
                if($merit_list->may_slide==1){
                    $course_seat->decrement('temp_attendence_occupied');
                }
            }
            
        
        
        }else{
            $message = "Application no {$application->application_no} admission taken without seat details";
            Log::error($message);
            try {
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
            } catch (\Throwable $th) {
                Log::error("saveLogs Error application no {$application->application_no}");
            }
        }
    }
    private function paymentDoneSMS($application, $admission_receipt)
    {
        $message = "Admission Fee successfully received. for your application no " . $application->application_no;
        $message = "Provisional admission for your ".$application->application_no." is successful. Your provisional receipt no is ".$admission_receipt->receipt_no.". Tezpur University";
        // sendSMS($application->mobile_no, $message);
        $mobile = $application->student->isd_code.$application->student->mobile_no;
        // $mobile = 8638279535;
        try {
            sendSMS($mobile, $message,1207161926030732465);
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
    private function setFeeStructure(MeritList $merit_list)
    {
        // dd($merit_list->fee_structure());
        $this->fee_structure = $merit_list->fee_structure();
        return $this;
    }
    private function getIndexView(){
        return "student.application.index";
    }


    public function cashAdmissionPaymentRecieved(Request $request, $encrypted_id)
    {
       
        $this->validate(request(), [
            "transaction_id"    => "required",
            "amount"=> "required",
        ]);

        // dd($request->all());
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::emergency($e);
            return redirect()->back()->with("error", "Whoops! Something went wrong.");
        }
        DB::beginTransaction();
        try {
            $merit_list = MeritList::findOrFail($decrypted_id);

            $application = $merit_list->application;
            if ($this->isPaymentAlreadyDone($merit_list)) {
                return redirect()->back()->with("error", "Fee already paid.");
            }
            // Application id from application_id , student_id is just passed so not taken.
            $online_payment = OnlinePaymentSuccess::create([
                "application_id"    => $application->id,
                "student_id"        => $application->student_id,
                "order_id"          => 'Cash',
                "amount"            => $request->amount,
                "amount_in_paise"   => $request->amount*100,
                "response_amount"   => $request->amount*100,
                "payment_id"        => $request->get("payment_id"),
                "currency"          => 'INR',
                "merchant_order_id" => 'Cash',
                "payment_id"        => $request->get("transaction_id"),
                "payment_signature" => 'Cash',
                "is_error"          => 'Cash',
                "error_message"     => $request->get("remarks"),
                "biller_status"     => 'captured',
                "biller_response"   => 'Cash',
                "payment_type"      => "admission",
                "course_id"         => $merit_list->course_id,
                "merit_list_id"     => $merit_list->id,
                "status"            => 1,
            ]);
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            $this->changeApplicationAnyDetails($application, $online_payment, $merit_list);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->application_no} payment response done.");

        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return redirect()->back()->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();
        return redirect()->route("department.receipt", Crypt::encrypt($merit_list->id))->with("success", "Payment Succssfull.");
    }

    public function releaseReceipt($merit_id)
    {
        $id = decrypt($merit_id);
        $merit_list = MeritList::with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id");
                }, 'meritMaster', 'admissionCategory', 'course', 'admissionReceipt',
                "undertakings",
            ])->where('id',$id)->first();
        return view("student.admission.release-seat-receipt",compact("merit_list"));
    }
}
