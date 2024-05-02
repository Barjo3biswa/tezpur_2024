<div class="row">
    <div class="col-md-12" id="printTable">
        <p style="text-align:center" class="">
                <img src="{{asset("logo.png")}}" alt="Logo" alt="Logo" width="40"><br>
                <strong>{{env("APP_NAME")}}</strong>
        </p>
        <table class="table table-bordered" style="border-collapse:collapse;" border="1" width="100%">
            <tbody>
                <tr>
                    <th colspan="3" class="text-center bold" style="padding:8px;">Payment Acknowledgement</th>
                </tr>
                <tr>
                    <td>Program Applied For {{$application->is_mba==0?"And Application No":""}}</td>
                    <th> 
                        {!!courseCode($application)!!}
                    </th>
                    {{-- @if ($application->is_mba!=1)
                      <th>
                        @foreach ($application->applied_courses as $applied)
                            {{$applied->application_number}}<br/>
                        @endforeach
                      </th>  
                    @endif --}}
                </tr>
                <tr>
                    <td style="padding:8px;">Registration No</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{$application->student_id}}</th>
                </tr>
                {{-- @if ($application->is_mba==1) --}}
                <tr>
                    <td style="padding:8px;">Application No</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{$application->application_no}}</th>
                </tr>
                {{-- @endif --}}
                <tr>
                    <td style="padding:8px;">Transaction ID</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{isset($paymentReceipt) ? $paymentReceipt->payment_id : $application->paymentReceipt->payment_id ?? "NA"}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Transaction Date</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{isset($paymentReceipt) ? dateFormat($paymentReceipt->created_at, "d-m-Y H:i:s") : $application->paymentReceipt->created_at ?? "NA"}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Applicant Name</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{$application->fullname}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Category</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{$application->caste->name ?? "NA"}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Type of Transaction</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">Online </th>
                </tr>
                <tr>
                    <td style="padding:8px;">Payment for </td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{isset($paymentReceipt) ? $paymentReceipt->payment_type : ($application->paymentReceipt->payment_type ?? "NA")}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Amount</td>
                    <th colspan="2" style="text-align:left; padding:8px;" class="bold">{{isset($paymentReceipt) ? $paymentReceipt->currency : $application->paymentReceipt->currency ?? "NA"}} {{number_format(isset($paymentReceipt) ? $paymentReceipt->amount : $application->paymentReceipt->amount ?? 0, 2)}}</th>
                </tr>
{{--                 <tr>
                    <td style="padding:8px;">Amount in Word</td>
                    <th style="text-align:left; padding:8px;" class="bold">{{ucwords(getIndianCurrency(isset($paymentReceipt) ? $paymentReceipt->amount : $application->paymentReceipt->amount))}}</th>
                </tr> --}}
            </tbody>
        </table>
    </div>
        <div class="col-xs-2 donot-print">
            <button type="button" class="btn btn-deafult dont-print" onclick="window.print()"><i class="fa fa-print"></i>
                Print</button>
        </div>

        @if (auth("student")->check())
        <div class="col-xs-4 donot-print">
            <a href="{{route("student.home")}}" class="btn btn-primary" ><i class="fa fa-home"></i>
                Back To Dashboard</a>
        </div>
        @endif
</div>