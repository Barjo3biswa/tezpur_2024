<?php
namespace App\Traits;

use App\ApplicationFee;
use App\AppliedCourse;
use App\Course;
use Illuminate\Http\Request;
use Payabbhi\Client as PayabbhiClient;
use Crypt, Log, Exception, DB;
use App\Models\Application;
use App\Models\OnlinePaymentSuccess;
use App\Models\OnlinePaymentProcessing;
use App\Models\Program;
use App\Models\RePaymentProcessing;
use App\Models\RePaymentSuccess;
use App\Models\Session as ModelsSession;
use App\Models\User;
use App\Services\PaymentHandlerService;
use Auth;
use Session;

/**
 * Trait for handling Payment (Payment)
 * date: 01-07-2019
 */
trait VknrlPayment
{
    // payment processing
    public function processPayment(Request $request, $encrypted_id) {

        $user_table_session = User::where('id',Auth::id())->first()->session_id;
        $active_session = getActiveSession();
        if($user_table_session != getActiveSession()->id){
            return redirect()->back()->with(["error" => "You have registered in a old session. Please contact TU-technical support.", "status" => false], 501);
        }
         //dd(env('RAZORPAY_SECRET'));
        // dd($this->getIndexView());
        // abort(403, "Payment option will be available shortly. We will inform you.");
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            abort(404);
        }
        try {
            $application = Application::with("online_payment_tried", "online_payments_succeed")->find($decrypted_id);

            if($application->is_cuet_pg==1){
                return redirect()->back()->with(["error" => "Try Again.", "status" => false], 501);
            }
            // //is closed validation
            $application_type = Application::where('id',$decrypted_id)->first()->exam_through;
            $prog_name = Auth::user()->program_name;
            if($prog_name == "PHD" && $application->net_jrf){
                $application_type = 'NET_JRF';
            }/* else if($prog_name == "PG" && $application->is_gate){
                $application_type == 'GATE';
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
            // dd("ok");
            // //end

            
            $course_ids = [];
            foreach($application->applied_courses as $course){
                $course_ids[] = $course->course_id;
                if(in_array($course->course_id, [89,90,91,92,93,94,95])){
                    return redirect()->route("student.application.index")->with("error", "Last date for selected programm is over. Please select other programm and continue.");
                }
            }
            
            $courses_count =Course::whereIn("id", $course_ids)->count();
            $is_mba = Auth::user()->is_mba;
            // dd($is_mba);
            
            $alloed_ids = explode(",", config("vknrl.ALLOW_AFTER_CLOSING_APP_IDS"));
            
            if($courses_count !== sizeof($course_ids) && !in_array($application->id, $alloed_ids )&& $is_mba !=1){
                // dd("ok");
                $student_id = Auth::user()->id;
                $is_avail=DB::table('zzz_payment_allowed_students')->where('student_id',$student_id)->count();
                // dd($is_avail);
                if($is_avail==0){
                    Log::emergency("payment attempted for closed courses appliation id ".$application->id);
                    saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "payment attempted for closed courses appliation id ".$application->id);
                    return redirect()->route("student.application.index")->with("error", "Last date for selected programm is over. Please select other programm and continue.");
                }
                
            }
            
        } catch (Exception $e) {
            // dd($e);
            Log::emergency($e);
            return redirect()->back()->with("error", "Whoos! something went wrong. Please try again later.");
        }
        // dd("ok");
        


        
        
        // if(!$application->is_extra_doc_uploaded){
        //     return redirect()
        //         ->route("student.application.index")
        //         ->with("error", "Please upload all required document to process further.");
        // }
        if($application->payment_status){
            return redirect()->route("student.application.index")->with("error", "Fee already paid.");
        }
        if($application->online_payments_succeed->count()){
            return redirect()->route("student.application.index")->with("error", "Fee already paid.");
        }
        DB::beginTransaction();
        try {
            
            // $accessId = env("PAYMENT_ACCESS_ID");
            // $secretKey = env("PAYMENT_SECRET_KEY");
            $merchantOrderID = env("MERCHANT_ORDER_ID", uniqid());
            // dd($merchantOrderID);
            $currency = "INR";
            // $amount = 800;
            // if($application->is_cuet_pg == 1){
            //     $amount = 800;
            // }elseif($application->is_phd==1){
            //     $amount =800;
            // }elseif($application->is_cuet_ug==1){
            //     $amount =600;
            // }
            // if(in_array($application->caste_id, [2, 5, 6]) || $application->is_pwd){
            //     $amount = 400;
            //     if($application->is_cuet_pg == 1){
            //         $amount = 400;
            //     }elseif($application->is_phd==1){
            //         $amount =400;
            //     }elseif($application->is_cuet_ug==1){
            //         $amount =300;
            //     }
            // }
            // if(in_array($application->caste_id, [5, 6]) || $application->is_pwd){
            //     if($application->is_phd==1){
            //         $amount =400;
            //     }
            // }                     
            // if($application->is_mba==1){
            //     $amount = 1000;
            //     if(in_array($application->caste_id, [5, 6]) || $application->is_pwd){
            //         $amount = 500;
            //     }
            //     if($this->isMBADateExpired()){
            //         $amount += 200;
            //     }
            // }
            // dd($application->is_foreign);
            // dd("ok");
            if(!$application->is_foreign && $application->is_mbbt==0 && Auth::User()->program_name != "JOSSA"){
                // dd("ok");
                $caste_map=[
                    1=>'general',
                    2=>'ews',
                    3=>'obc',
                    4=>'obc_ncl',
                    5=>'sc',
                    6=>'st',
                    8=>'pwd',
                ];
                // dd("test");  
                $program_name = Auth::User()->program_name;
                $exam_through = $application->exam_through;
                if($program_name == "PHDPROF"|| $program_name=="VISVES"){
                    $program_name = "PHD";
                    $exam_through = "TUEE";
                }
                $program_id = Program::where('type', $program_name)->first()->id;      
                // dd($application->exam_through);   
                $amount_list = ApplicationFee::where('program_id',$program_id)->where('sub_prog',$exam_through)->first();
                if(!$amount_list ){
                    return redirect()->back()->with('error','Application Fee not found please contact technical support.');
                }
                $caste = $caste_map[$application->caste_id];
                if($application->is_pwd==1){
                    $caste= 'pwd';
                }
                $amount = $amount_list->$caste;
                if($application->is_mba==1){
                    if($this->isMBADateExpired()){
                        $amount += 200;
                    }
                }
            }
           
            if($application->is_foreign){
                $currency_value = getSiteSettingValue("currency");
                $amount = 30 * $currency_value;
                $currency = "INR";
            }else{
                $currency_value = "NA";
            }
            
            if($this->isFreeAdmissionAvailableForTheApplication($application)){
                return $this->proceedZeroPaymentApplication($application);
            }
           
            // converting amount into paise
            // $amount = round($amount * 100, 2);
            //The merchant_order_id is typically the identifier of the Customer Order, Booking etc in your system
           
             //$client = new PayabbhiClient(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $client = new PaymentHandlerService;
            
            $tried_records = $application->online_payment_tried->last();
            // dd($client->order->retrieve($tried_records->order_id));
            // dd($tried_records);
            if($tried_records){
               
                // $previous_order = $client->order->retrieve($tried_records->order_id);
                
                $previous_order = $client->orderFetchByOrderId($tried_records->order_id);
                
                // $tried_records->succed_payments()->delete();
                // $order_status = (isset($previous_order["status"]) ? $previous_order["status"] : $previous_order->status);
                if(PaymentHandlerService::isOrderPaid($previous_order)){
                    $payments_data = $previous_order->payments();
                    // dd($payments_data);
                    foreach($payments_data->items as $index => $payment){
                        // dd($payment);
                        // only if payment is done captured status
                        if(PaymentHandlerService::isPaymentPaid($payment)){
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
                                "payment_signature" => null,
                                "is_error"          => $payment->error_code ?? false,
                                "error_message"     => $payment->error_description,
                                "biller_status"     => $payment->status,
                                "biller_response"   => json_encode($payment),
                                "status"            => 1,
                            ]);
                            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
                        }
                    }
                    $application->payment_status = 1;
                    $application->status = "payment_done";
                    if(!$application->application_no){
                        $application->application_no = generateApplicationNo($application);
                        $application->is_master = checkIsMaster($application);
                    }
                    $application->save();

                    // //indivisual application_no for each applied course
                    // if(!isMbaStudent($application)){
                    //     foreach($application->applied_courses as $key=>$course){
                    //        $course_details = Course::where('id',$course->course_id)->first();
                    //        $application_number = $course_details->code.'/'.$application->application_no;
                    //        AppliedCourse::where('id',$course->id)->update(['application_number' => $application_number]);
                    //        dd($application_number);
                    //     }
                    //     dd("ok");
                    // }
                    // //Ends
                   
                    $this->sendApplicationNoSMS($application);
                    DB::commit();
                    return redirect()->route($this->getIndexView())->with("success", "Your Payment is already done.");
                }
            }



            $applied_program = Auth::user()->program_name;
            // if (Auth::User()->qualifying_exam==null && $applied_program!='BTECH') {
            //     return redirect()->route("student.application.index")->with("error", "Unable to proceed for registration.");
            // }


            // check order if already payment
            // end of checking order payment

            // $order = $client->order->create([
            //     'amount'    =>$amount,
            //     'currency'  =>'INR',
            //     'merchant_order_id' => $merchantOrderID,
            //     "notes"     => [
            //         "merchant_order_id" => (string)$merchantOrderID,
            //         "payment_processing"    => true,
            //         "student_id"        => $application->student_id,
            //         "application_id"    => $application->id,
            //         "curr_value"        => $currency_value,
            //     ]
            // ]);

            $order = $client->setData($amount, "INR", $merchantOrderID, [
                "merchant_order_id" => (string)$merchantOrderID,
                "payment_processing"    => true,
                "student_id"        => $application->student_id,
                "application_id"    => $application->id,
                "curr_value"        => $currency_value,
            ])->createOrder();
            
            $data = [
                'key'           => config("vknrl.RAZORPAY_KEY"),
                'order_id'      => $order->id,
                'amount'        => $amount,
                'image'         =>  asset("logo.png"),
                'description'   => env("APP_NAME").': Order #' . $merchantOrderID,
                'prefill'     => [
                    'name'      => $application->fullname,
                    'email'     => $application->student->email,
                    'contact'   => $application->student->mobile_no
                ],
                'notes'       => [
                    'merchant_order_id' => (string)$merchantOrderID
                ],
                'theme' => [
                    'color' => '#2E86C1'
                ]
            ];
            // $json = json_encode($data);
        }catch(Exception $e){
            // dd($e);
            Log::error($e);
            DB::rollback();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->id} Proceeding payment failed.");
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. try again later.");
        }
        OnlinePaymentProcessing::create([
            "application_id"    => $application->id,
            "student_id"        => $application->student_id,
            "order_id"          => $order->id,
            "amount"            => $amount,
            "amount_in_paise"   => $amount * 100,
            "currency"          => "INR",
            "merchant_order_id"    => $merchantOrderID,
            "payment_done"      => 0,
        ]);
        DB::commit();
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for payment Application No: {$application->id}");
        $application = Application::find($decrypted_id);
        // dd("ok");

        return view("student.application.process-payment", compact("application", "order", "client","data", "amount", "merchantOrderID", "currency"));
    }
    // after payment successfull
    public function paymentRecieved(Request $request, $encrypted_id) {

        // dd("ok");
        Log::notice(json_encode($request->all()));
        //dd("Reached.");
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::emergency($e);
            return redirect()->route('student.application.index')->with("error", "Whoops! Something went wrong.");
        }
        try {
            // $api = new PayabbhiClient($accessId, $secretKey);
            
            // $api->utility->verifyPaymentSignature([
            //     'payment_id'    => $request->get("payment_id"),
            //     'order_id'      => $request->get("order_id"),
            //     'payment_signature' => $request->get("payment_signature"),
            // ]);

            $payment_service = new PaymentHandlerService;
            $payment_service->verifySignature($request->get("order_id"), $request->get("payment_id"), $request->get("payment_signature"));
            $order = $payment_service->orderFetchByOrderId($request->get("order_id"));
            $payment = $payment_service->retrievePayment($request->get("payment_id"));
            // $payment = $api->payment->retrieve($request->get("payment_id"));
        } catch (Exception $e) {
            Log::emergency($e);
            return redirect()->route("student.application.index")->with("error", "Payment Details fetching error. Wait sometimes or contact to helpline no.");
        }
        // dd($payment);
        DB::beginTransaction();
        try {
            $application = Application::findOrFail($decrypted_id);
            // dd($application);
            if($application->payment_status){
                return redirect()->route("student.application.index")->with("error", "Fee already paid.");
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
                "biller_status"          => $payment->status,
                "biller_response"          => $request->get("response"),
                "status"          => 1,
            ]);
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            if($payment->status == "captured"){
                $application->payment_status = 1;
            }
            $application->status = "payment_done";
            if(!$application->application_no){
                $application->application_no = generateApplicationNo($application);
                $application->is_master = checkIsMaster($application);
            }
            if($application->is_btech==1 || $application->is_cuet_ug==1){
                $application->is_editable=1;
            }           
            $application->save();
            $this->sendApplicationNoSMS($application);
            
        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()->route("student.application.index")->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();
        return redirect()->route("student.application.payment-reciept", Crypt::encrypt($application->id))->with("success", "Payment Succssfull.");
    }


    public function paymentReceipt(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            // Log::error();
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. Try again later.");
        }
        $application = Application::find($decrypted_id);
        // dd($application);
        return view($this->getReceiptView(), compact("application"));
    }


    public function getReceiptView() {
        $guard = get_guard();
        if($guard == "admin"){
            return "admin.applications.payment-receipt";
        }elseif($guard == "student"){
            return "student.application.payment-receipt";
        }elseif($guard == "department_user"){
            return "department.applications.payment-receipt";
        }
    }


    public function sendApplicationNoSMS($application)
    {
        $message = "Payment Successful. Your application number is ".$application->application_no." generated.";
        $mobile = $application->student->isd_code.$application->student->mobile_no;
        try {
            sendSMS($mobile, $message,  "1207161901911630495");
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
    public function sendApplicationRePaymentSMS($application)
    {
        $message = "Payment Successful. Your application fee is successfully received.";
        $mobile = $application->student->isd_code.$application->student->mobile_no;
        try {
            sendSMS($mobile, $message);
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
    public function rePayment($encrypted_id)
    {
        $message = "Application Re-Payment Option Not Available.";
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("re_payment_tried", "re_payments_succeed")->find($id);
            if(!$application->re_payment || $application->status != "on_hold"){
                throw new \Exception($message, 100);
            }
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route("student.home")->with("error", $message);
        }
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // Log::error($e);
            // saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
            abort(404);
            // return redirect()->route(get_guard().".home")->with("error", "Whoops! Looks like you");
        }
        // dd
        if($application->payment_status && !$application->re_payment){
            return redirect()->route("student.application.index")->with("error", "Fee already paid.");
        }
        if($application->re_payments_succeed->count()){
            return redirect()->route("student.application.index")->with("error", "Fee already paid.");
        }
        DB::beginTransaction();
        try {
            $accessId = env("PAYMENT_ACCESS_ID");
            $secretKey = env("PAYMENT_SECRET_KEY");
            $merchantOrderID = env("MERCHANT_ORDER_ID", uniqid());
            $currency = "INR";
            $amount = $application->re_payment_amount;
            if($application->is_foreign){
                $currency_value = getSiteSettingValue("currency");
                $amount = $amount * $currency_value;
                $currency = "INR";
            }else{
                $currency_value = "NA";
            }
            // converting amount into paise
            // $amount = round($amount * 100, 2);
            //The merchant_order_id is typically the identifier of the Customer Order, Booking etc in your system
           
            $client = new PayabbhiClient($accessId, $secretKey);
            $tried_records = $application->re_payment_tried->last();
            if($tried_records){
                $previous_order = $client->order->retrieve($tried_records->order_id);
                // $tried_records->succed_payments()->delete();
                $order_status = (isset($previous_order["status"]) ? $previous_order["status"] : $previous_order->status);
                if(strtolower($order_status) == "paid"){
                    $payments_data = $previous_order->payments();
                    foreach($payments_data->data as $index => $payment){
                        // dd($payment);
                        $online_payment = RePaymentSuccess::create([
                            "application_id"    => $application->id,
                            "student_id"        => $application->student_id,
                            "order_id"          => $previous_order->id,
                            "amount"            => ($payment->amount/100),
                            "amount_in_paise"   => $payment->amount,
                            "response_amount"   => $payment->amount,
                            "currency"          => $payment->currency,
                            "merchant_order_id" => $merchantOrderID,
                            "payment_id"        => $payment->id,
                            "payment_signature" => null,
                            "is_error"          => $payment->error_code,
                            "error_message"     => $payment->error_description,
                            "biller_status"     => $payment->status,
                            "biller_response"   => json_encode($payment),
                            "status"            => 1,
                        ]);
                        $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
                    }
                    $application->payment_status = 1;
                    $application->re_payment = 0;
                    $application->status = "payment_done";                    
                    $application->save();
                    $this->sendApplicationRePaymentSMS($application);
                    DB::commit();
                    return redirect()->route($this->getIndexView())->with("success", "Your Payment is already done.");
                }
            }
            // check order if already payment
            // end of checking order payment

            $order = $client->order->create([
                'amount'    =>$amount,
                'currency'  =>'INR',
                'merchant_order_id' => $merchantOrderID,
                "notes"     => [
                    "merchant_order_id" => (string)$merchantOrderID,
                    "payment_processing"    => true,
                    "student_id"        => $application->student_id,
                    "application_id"    => $application->id,
                    "curr_value"        => $currency_value,
                ]
            ]);
            $data = [
                'access_id'     => $accessId,
                'order_id'      => $order->id,
                'amount'        => $amount,
                'image'         =>  asset("logo.png"),
                'description'   => env("APP_NAME").': Order #' . $merchantOrderID,
                'prefill'     => [
                    'name'      => $application->fullname,
                    'email'     => $application->student->email,
                    'contact'   => $application->student->mobile_no
                ],
                'notes'       => [
                'merchant_order_id' => (string)$merchantOrderID
                ],
                'theme' => [
                    'color' => '#2E86C1'
                ]
            ];
            // $json = json_encode($data);
        }catch(Exception $e){
            // dd($e);
            Log::error($e);
            DB::rollback();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->id} Proceeding payment failed.");
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. try again later.");
        }
        RePaymentProcessing::create([
            "application_id"    => $application->id,
            "student_id"        => $application->student_id,
            "order_id"          => $order->id,
            "amount"            => ($amount/100),
            "amount_in_paise"   => $amount,
            "currency"          => "INR",
            "merchant_order_id" => $merchantOrderID,
            "payment_done"      => 0,
        ]);
        DB::commit();
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for payment Application No: {$application->id}");
        $application = Application::find($decrypted_id);
        $repayment = true;
        return view("student.application.re-payment", compact("application", "order", "client","data", "amount", "merchantOrderID", "currency", "repayment"));
    }
    public function rePaymentRecieved(Request $request, $encrypted_id)
    {
        $accessId = env("PAYMENT_ACCESS_ID");
        $secretKey = env("PAYMENT_SECRET_KEY");
        $merchantOrderID = env("MERCHANT_ORDER_ID", uniqid());
        Log::notice(json_encode($request->all()));
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::emergency($e);
            return redirect()->route('student.application.index')->with("error", "Whoops! Something went wrong.");
        }
        try {
            $api = new PayabbhiClient($accessId, $secretKey);
            
            $api->utility->verifyPaymentSignature([
                'payment_id'    => $request->get("payment_id"),
                'order_id'      => $request->get("order_id"),
                'payment_signature' => $request->get("payment_signature"),
            ]);
            $payment = $api->payment->retrieve($request->get("payment_id"));
        } catch (Exception $e) {
            Log::emergency($e);
            return redirect()->route("student.application.index")->with("error", "Payment Details fetching error. Wait sometimes or contact to helpline no.");
        }
        // dd($payment);
        DB::beginTransaction();
        try {
            $application = Application::findOrFail($decrypted_id);
            // Application id from application_id , student_id is just passed so not taken.
            $online_payment = RePaymentSuccess::create([
                "application_id"    => $application->id,
                "student_id"        => $application->student_id,
                "order_id"          => $request->get("order_id"),
                "amount"            => $request->get("amount"),
                "amount_in_paise"   => ($request->get("amount") * 100),
                "response_amount"   => $payment->amount,
                "currency"          => $payment->currency,
                "merchant_order_id" => $request->get("merchant_order_id"),
                "payment_id"        => $request->get("payment_id"),
                "payment_signature" => $request->get("payment_signature"),
                "is_error"          => $request->get("is_error"),
                "error_message"     => $request->get("error_message"),
                "biller_status"     => $payment->status,
                "biller_response"   => $request->get("response"),
                "status"            => strtolower($payment->status) == "captured" ? 1 : 0,
            ]);
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            if($payment->status == "captured"){
                $application->status = "payment_done";
                $application->re_payment = 0;                   
            }
            $application->save();
            $this->sendApplicationRePaymentSMS($application);
            
        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return redirect()->route("student.application.index")->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();
        return redirect()->route("student.application.re-payment-reciept", Crypt::encrypt($application->id))->with("success", "Payment Succssfull.");

    }
    public function rePaymentReceipt(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. Try again later.");
        }
        $application = Application::find($decrypted_id);
        $paymentReceipt = $application->rePaymentReceipt;
        // dd($application);
        return view($this->getReceiptView(), compact("application", "paymentReceipt"));
    }
    private function isMBADateExpired(){
        // we can dynamically change the expiry date.
        // dump(time());
        // dd(strtotime(env("MBA_LAST_DATE", "2023-02-28")));
        if(time() > strtotime(env("MBA_LAST_DATE", "2024-03-01"))){
            return true;
        }
        return false;
    }
    private function isFreeAdmissionAvailableForTheApplication(Application $application){
        if($application->is_free_reg){
            return true;
        }
        $ids = config("vknrl.free_application_course_ids");
        $free_course_ids = [35];
        if($ids){
            $free_course_ids = array_merge($free_course_ids, explode(",", $ids));
        }
        if($free_course_ids){
            $applied_courses = $application->applied_courses;
            return $applied_courses->whereIn("course_id", $free_course_ids)->isNotEmpty();
            // old logic removed 25-10-2021
            // foreach($free_course_ids as $course_id){
            //     if($application->applied_courses->where("course_id", $course_id)->count()){
            //         return true;
            //     }
            // }
        }
        return false; 
    }
    private function proceedZeroPaymentApplication(Application $application){
        $already_free_payment_count = OnlinePaymentSuccess::where("payment_id", "LIKE", "%FREEREG%")->count() + 1;
        $order_id = $payment_id = "FREEREG".str_pad($already_free_payment_count, 4, "0", STR_PAD_LEFT);
        OnlinePaymentSuccess::create([
            "application_id"    => $application->id,
            "student_id"        => $application->student_id,
            "order_id"          => $order_id,
            "amount"            => 0,
            "amount_in_paise"   => 0.0,
            "response_amount"   => 0.0,
            "currency"          => "INR",
            "merchant_order_id" => uniqid(),
            "payment_id"        => $payment_id,
            "payment_signature" => null,
            "is_error"          => false,
            "error_message"     => null,
            "biller_status"     => "captured",
            "biller_response"   => "Free Admission",
            "status"            => 1,
        ]);
        $application->payment_status = 1;
        $application->status = "payment_done";
        if(!$application->application_no){
            $application->application_no = generateApplicationNo($application);
            $application->is_master = checkIsMaster($application);
        }
        $application->save();
        $this->sendApplicationNoSMS($application);
        DB::commit();
        return redirect()->route($this->getIndexView())->with("success", "Payment done for your application.");
    }
}
