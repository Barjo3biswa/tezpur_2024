<?php

namespace App\Services;

use App\Exceptions\InvalidPaymentGatwayException;
use Payabbhi\Client;
use Razorpay\Api\Api;

class PaymentHandlerService
{
    private $handler;
    private $gateway;
    private $amount;
    private $currency;
    private $merchant_id;
    private $notes;
    private $transfers;

    public function __construct()
    {
        $this->initialize();
    }
    private function initialize()
    {
        $this->gateway = config('vknrl.payment_gateway');
        switch ($this->gateway) {
            case 'razorpay':
                $this->handler = new Api(config("vknrl.RAZORPAY_KEY"), config("vknrl.RAZORPAY_SECRET"));
                break;
            case 'payabbhi':
                $this->handler = new Client(config("vknrl.PAYMENT_ACCESS_ID"), config("vknrl.PAYMENT_SECRET_KEY"));
                break;
            default:
                throw new InvalidPaymentGatwayException("Invalid payment gateway driver");
                break;
        }
    }
    /**
     * @param int $amount
     * @param string $currency
     * @param string $merchant_id
     * @param array $notes
     */
    public function setData($amount, $currency, $merchant_id, $notes, $transfers = []): self
    {
        $this->amount      = $amount;
        $this->currency    = $currency;
        $this->merchant_id = $merchant_id;
        $this->notes       = $notes;
        $this->transfers   = $transfers;
        return $this;
    }
    public function createOrder($with_transfer = false)
    {
        if ($this->isRazorPay()) {
            if($with_transfer){
                return $this->createRazorPayOrderTransfer();
            }
            return $this->createRazorPayOrder();
        }else{
            die("need to impliment");
        }
    }
    private function createRazorPayOrder()
    {
        
        return $this->handler->order->create([
            'receipt'  => $this->merchant_id,
            'amount'   => $this->amount * 100,
            'currency' => $this->currency,
            "notes"    => $this->notes,
        ]);
        // Creates order;
    }
    private function createRazorPayOrderTransfer()
    {
        return $this->handler->order->create([
            'receipt'  => $this->merchant_id,
            'amount'   => $this->amount * 100,
            'currency' => $this->currency,
            "notes"    => $this->notes,
            "transfers"=> $this->transfers
        ]);
        // Creates order with transfers;
    }
    private function isRazorPay()
    {
        return $this->gateway == "razorpay";
    }
    public function orderFetchByOrderId($order_id)
    {
        return $this->handler->order->fetch($order_id);
    }
    public function paymentFetchByOrderId($order_id)
    {
        return $this->handler->order->fetch($order_id)->payments();
    }
    public static function isOrderPaid($array)
    {
        return in_array($array["status"], ["paid", "captured"]);
    }
    public static function isPaymentPaid($array)
    {
        return in_array($array["status"], ["paid", "captured"]);
    }
    public function verifySignature($order_id, $payment_id, $signature)
    {
        $attributes = [
            'razorpay_order_id' => $order_id,
            'razorpay_payment_id' => $payment_id,
            'razorpay_signature' => $signature
        ];
        $this->handler->utility->verifyPaymentSignature($attributes);
    }
    public function retrievePayment($payment_id)
    {
        return $this->handler->payment->fetch($payment_id);
    }
}
