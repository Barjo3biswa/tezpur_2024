@extends('department-user.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading">Application @isset($payment_type) {{ucwords($payment_type)}} @endisset Fee Payment <span class="pull-right"></div>
                <div class="panel-body" style="padding-left: 200px; padding-right: 200px;">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="padding-xs" style="text-align: center;" colspan="4">
                                    <h3 class="mb-3 text-uppercase">TEZPUR UNIVERSITY</h3> 
                                    <p class="mb-4 bold">
                                    NAPAAM, TEZPUR - 784028, ASSAM<br> <strong> @isset($payment_type)({{strtoupper($payment_type)}})@endisset</strong><br>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>Programme Applied</th>
                                <td colspan="3"> {{$merit_list->course->name}}</td>
                            </tr>
                            <tr>
                                <th>Applicant Name</th>
                                <td> {{$application->fullname}}</td>
                                <th>Registration No</th>
                                <td> {{$application->student_id}}</td>
                            </tr>
                            <tr>
                                <th>Transaction ID</th>
                                <td> NA </td>
                                <th>Category</th>
                                <td>{{$merit_list->admissionCategory->name}}</td>
                            </tr>
                            <tr>
                                <th>Application No</th>
                                <td>{{$merit_list->application_no}}</td>
                                <th>Receipt No</th>
                                <td>NA</td>
                            </tr>
                        </tbody>
                    </table>
                    @include('common.application.receipt.fee-heads', ["collection" => $fee_structure->feeStructures, "receipt" => $last_receipt])
                    @php
                        // $url = route(get_route_guard().".payment-response", Crypt::encrypt($merit_list->id));
                        if(auth("department_user")->check()){
                            $url = route(get_route_guard().".payment-response", Crypt::encrypt($merit_list->id));
                        }elseif(auth("student")->check()){
                            $url = route("student.admission.payment-response", Crypt::encrypt($merit_list->id));
                        }
                    @endphp
                    <button class="btn btn-primary" type="button" id="paymentButton">Proceed to Online Payment</button>
                    <form name='paymentForm' action="{{isset($response_url) ? $response_url : $url}}" method="POST" style="display:none">
                        {{-- form data will be posted and recieved --}}
                        {{ csrf_field() }}
                        <input type="hidden" name="merchant_order_id" value="{{$merchantOrderID}}">
                        <input type="hidden" name="order_id" id="order_id"  value="{{$data["order_id"]}}">
                        <input type="hidden" name="payment_id" id="payment_id">
                        {{-- <input type="hidden" name="name" id="name" value="{{$application->fullname}}"> --}}
                        <input type="hidden" name="amount" id="amount" value="{{$amount}}">
                        {{-- <input type="hidden" name="student_id" id="student_id" value="{{$application->student_id}}"> --}}
                        <input type="hidden" name="application_id" id="application_id" value="{{$application->id}}">
                        <input type="hidden" name="payment_signature"  id="payment_signature" >
                        <input type="hidden" name="is_error"  id="is_error" >
                        <input type="hidden" name="error_message"  id="error_message" >
                        <input type="hidden" name="response"  id="response" >
                    </form>
                    {{-- <a href="{{route(get_route_guard().".cash-payment", Crypt::encrypt($merit_list->id))}}" class="btn btn-primary">Proceed to Cash Payment</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@if(config("vknrl.payment_gateway") == "razorpay")
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Checkout details as a json
    var options = {!!json_encode($data)!!};
    
    /**
     * The entire list of Checkout fields is available at
     * https://docs.razorpay.com/docs/checkout-form#checkout-fields
     */
    options.handler = function (response){
        document.getElementById('payment_id').value = response.razorpay_payment_id;
        document.getElementById('payment_signature').value = response.razorpay_signature;
        document.getElementById('order_id').value = response.razorpay_order_id;

        document.getElementById('response').value = JSON.stringify(response);
        document.getElementById('is_error').value = response.is_error;
        document.getElementById('error_message').value = response.error_message;
        document.paymentForm.submit();
    };
    
    // Boolean whether to show image inside a white frame. (default: true)
    options.theme.image_padding = false;
    
    options.modal = {
        ondismiss: function() {
            console.log("This code runs when the popup is closed");
        },
        // Boolean indicating whether pressing escape key 
        // should close the checkout form. (default: true)
        escape: true,
        // Boolean indicating whether clicking translucent blank
        // space outside checkout form should close the form. (default: false)
        backdropclose: false
    };
    
    var rzp = new Razorpay(options);
    
    document.getElementById('paymentButton').onclick = function(e){
        rzp.open();
        e.preventDefault();
    }
    </script>
@else
<script src="https://checkout.payabbhi.com/v1/checkout.js"></script>
<script>
var options = {!!json_encode($data)!!};
    options.handler = function (response){
        document.getElementById('order_id').value = response.order_id;
        document.getElementById('payment_id').value = response.payment_id;
        document.getElementById('payment_signature').value = response.payment_signature;
        document.getElementById('response').value = JSON.stringify(response);
        document.getElementById('is_error').value = response.is_error;
        document.getElementById('error_message').value = response.error_message;
        document.paymentForm.submit();
    };

    var payabbhi = new Payabbhi(options);

    document.getElementById('paymentButton').onclick = function(e){
        payabbhi.open();
        e.preventDefault();
    }
</script>
@endif
@endsection