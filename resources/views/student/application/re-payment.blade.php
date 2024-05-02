@extends('student.layouts.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Application Fee Payment <span class="pull-right"></div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Program Applied For</th>
                                <td> 
                                    {{courseCode($application)}}
                                </td>
                            </tr>
                            <tr>
                                <th>Registration No</th>
                                <td> {{$application->student_id}}</td>
                            </tr>
                            <tr>
                                <th>Application No</th>
                                <td> {{$application->application_no ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>Applicant Name</th>
                                <td> {{$application->fullname}}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px;">Category</td>
                                <th style="text-align:left; padding:8px;" class="bold">{{$application->caste->name ?? "NA"}}</th>
                            </tr>
                            <tr>
                                <th>Contact No</th>
                                <td> {{$application->student->mobile_no}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td> {{$application->student->email}}</td>
                            </tr>
                            <tr>
                                <th>Application Fee </th>
                                <td>{{$currency}} {{$amount/100}}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><button class="btn btn-primary" type="button" id="paymentButton">Proceed to Payment</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <form name='payabbhiform' action="{{route("student.application.re-payment-post", Crypt::encrypt($application->id))}}" method="POST" style="display:none">
                        {{-- form data will be posted and recieved --}}
                        {{ csrf_field() }}
                        <input type="hidden" name="merchant_order_id" value="{{$merchantOrderID}}">
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" name="payment_id" id="payment_id">
                        {{-- <input type="hidden" name="name" id="name" value="{{$application->fullname}}"> --}}
                        <input type="hidden" name="amount" id="amount" value="{{$amount/100}}">
                        {{-- <input type="hidden" name="student_id" id="student_id" value="{{$application->student_id}}"> --}}
                        <input type="hidden" name="application_id" id="application_id" value="{{$application->id}}">
                        <input type="hidden" name="payment_signature"  id="payment_signature" >
                        <input type="hidden" name="is_error"  id="is_error" >
                        <input type="hidden" name="error_message"  id="error_message" >
                        <input type="hidden" name="response"  id="response" >
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
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
        document.payabbhiform.submit();
    };

    var payabbhi = new Payabbhi(options);

    document.getElementById('paymentButton').onclick = function(e){
        payabbhi.open();
        e.preventDefault();
    }
</script>
@endsection