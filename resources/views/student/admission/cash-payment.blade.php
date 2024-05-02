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
                        @include('common.application.receipt.fee-heads', ["collection" => $fee_structure->feeStructures, "receipt" => $last_receipt])
                    </table>
                <form action="{{route(get_route_guard().".cash-payment-response", Crypt::encrypt($merit_list->id))}}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Enter Transaction Id</label>
                            </div>
                            <div class="col-md-6">
                                 <input type="text" class='form-control' name="transaction_id" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Enter Amount</label>
                            </div>
                            <div class="col-md-6">
                                 <input type="text" class='form-control' name="amount" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Enter Remarks</label>
                            </div>
                            <div class="col-md-8">
                                 <textarea name="remarks" id="remarks" cols="30" rows="3" class='form-control'></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-6">
                                 <input type="submit" class="btn btn-primary" >
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection