<?php

namespace App\Http\Controllers\Student;

use App\HostelFeeStructureRepayment;
use App\HostelReceipt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MeritList;
use App\Models\OnlinePaymentProcessing;
use App\Models\OnlinePaymentSuccess;
use App\Services\PaymentHandlerService;
use Crypt;
use DB;
use Exception;
use Webpatser\Uuid\Uuid;
use Log;

class AdditionalController extends Controller
{
    public $fee_structure = null;
    private function setFeeStructure(MeritList $merit_list)
    {
        // $this->fee_structure = $merit_list->fee_structure_hostel();
        $this->fee_structure=HostelFeeStructureRepayment::get();
        return $this;
    }

    private function getAdmissionAmount($application)
    {
        $sum_amount = 0.00;
        if($application->re_payment_flag==1){
            $sum_amount = $application->re_amount_payment;
        }
        return [$sum_amount, "INR"];
    }

    public function index($id){
        try {
            $decrypted_id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            
        }
        // $merit_list = MeritList::whereId($decrypted_id)->with(["application.online_admission_payment_tried"])->first();
        // if($merit_list->course->admission_status==0){
        //     abort(404, "Invalid Request");
        // }
        
        $application = Application::whereId($decrypted_id)->where('re_payment_flag',1)->first();
       
        // try {
        //     $this->setFeeStructure($merit_list);
        //     $fee_structure = $this->fee_structure;
        //     $application->load("online_admission_payments_succeed");
          
        // } catch (\Exception $e) {
        //     // dd("ok");
        //     Log::emergency($e);
        //     return redirect()->back()->with("error", "Whoos! something went wrong. Please try again later.");
        // }
        
        DB::beginTransaction();
        try {
            $merchantOrderID           = env("MERCHANT_ORDER_ID", uniqid());
            $currency                  = "INR";
            [$amount, $currency_value] = $this->getAdmissionAmount($application);
            $paymentHanlderService = new PaymentHandlerService;
            $tried_records = $application->online_re_payment_tried->last();
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
                                "course_id"         => null,
                                "payment_signature" => null,
                                "payment_type"      => "application_repayment",
                                "is_error"          => $payment->error_code ?? false,
                                "error_message"     => $payment->error_description,
                                "biller_status"     => $payment->status,
                                "biller_response"   => json_encode($payment),
                                "status"            => 1,
                            ]);
                            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
                        }
                    }
                    // $this->changeApplicationAnyDetails($application, $online_payment, $merit_list);
                    $application->update(['re_payment_flag'=>2]);
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
        } catch (\Exception $e) {
            // dd($e);
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
            "payment_type"      => "hostel_repayment",
            "course_id"         => $merit_list->course_id,
            "merit_list_id"     => $merit_list->id,
        ]);
        DB::commit();

        $payment_type = $processing_obj->payment_type;
       
            $response_url = route("department.hostel-payment-response", Crypt::encrypt($merit_list->id));
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for admission payment Application id: {$application->id}");
            return view('additional.payment-response', compact(
                    "application", "order",
                    "data", "amount", "merchantOrderID",
                    "currency", "payment_type", "merit_list", "fee_structure"
                ));
    }

    //after payment successfull
    public function hostelPaymentRecieved(Request $request, $encrypted_id)
    {
        // dd("ok");
        $accessId  = env("PAYMENT_ACCESS_ID");
        $secretKey = env("PAYMENT_SECRET_KEY");
        Log::notice(json_encode($request->all()));
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
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
                "payment_type"      => "hostel_repayment",
                "course_id"         => $merit_list->course_id,
                "merit_list_id"     => $merit_list->id,
                "status"            => 1,
            ]);
            
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            $this->changeApplicationAnyDetails($application, $online_payment, $merit_list);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->application_no} payment response done.");

        } catch (Exception $e) {
            dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()->back()->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();
        
            return redirect()->route("student.hostel-receipt-re", Crypt::encrypt($merit_list->id))->with("success", "Payment Succssfull.");
        
        
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
            "type"              => "hostel_repayment",
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
        $hostel_receipt->save();
        $merit_list->update(['ask_hostel'=>3]);
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
        $receipt     = $merit_list->hostelReceiptRepayment->load("collections.feeHead");
        
        $application = $merit_list->application;
        // dd($receipt);
        // dd($merit_list->admissionReceipt->admission_category->name);
        return view("additional.receipt", compact("application", "merit_list", "receipt"));
    }
}
