<?php

namespace App\Http\Controllers;

use App\Models\MeritList;
use App\PaymentWebhook;
use Crypt;
use Illuminate\Http\Request;
use Log;
use App\Services\PaymentHandlerService;
class NotifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $encrypted_id)
    {
        //
        $accessId  = env("PAYMENT_ACCESS_ID");
        $secretKey = env("PAYMENT_SECRET_KEY");
        Log::notice(json_encode($request->all()));
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            Log::emergency($e);
        }
        try {
            $payment_service = new PaymentHandlerService;
            $payment_service->verifySignature($request->get("order_id"), $request->get("payment_id"), $request->get("payment_signature"));
            $order = $payment_service->orderFetchByOrderId($request->get("order_id"));
            $payment = $payment_service->retrievePayment($request->get("payment_id"));
        } catch (\Exception $e) {
            Log::emergency($e);
        }
        $merit_list = MeritList::findOrFail($decrypted_id);
        $application = $merit_list->application;
        $online_payment = PaymentWebhook::create([
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
