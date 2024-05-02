@php
    if (get_guard() == 'admin') {
        $layout = 'admin.layout.auth';
    } elseif (get_guard() == 'student') {
        $layout = 'student.layouts.auth';
    } elseif (get_guard() == 'department_user') {
        $layout = 'department-user.layout.auth';
    }
    // $dept = \App\Department::where('id', $merit_list->course->department_id)->first();
    
    // $total = 0;
    // $date = 0;
    // foreach ($receipt->collections as $key => $val) {
    //     $total += $val->amount;
    //     $date = $val->created_at;
    // }
    // $fees = number_format($total, 2);
    
@endphp
@extends($layout)
<style>
    @media print {
        .cont {
            font-size: 12px;
        }

        .p-footer-font {
            font-size: 14px;
        }
        tbody {
            border: 2px solid transparent;
        }

        /* .checklist-font {
            font-size: 14px;
        }

        .t-font-size {

            font-size: 12px;
            padding-top: 10px !important;
        } */


        table {
            font-size: 11px;
            /* border-collapse: collapse; */
        }

        tbody {
            border: 6px solid transparent !important;
        }

        p {
            margin: 0 !important
        }

        h3 {
            margin: 0 !important
        }

        table>tbody>tr>td {
            padding: 0 !important
        }

        table>tbody>tr>th {
            padding: 0 !important;
            /* border-top: none !important; */
            /* border: none !important */
        }

        .table {
            margin-bottom: 0 !important
        }

        .table-bordered>tbody>tr>th {
            /* border: none !important */
        }

        .table-bordered>tbody>tr>td {
            /* border: none !important */
        }

        .table>tbody>tr>td {
            /* border-top: none !important */
        }

        .table-bordered>tbody> {
            /* border: none !important */
        }

        .table>tbody>tr> {
            /* border-top: none !important */
        }

        .tezu--heading-print-para {
            padding-bottom: 2rem !important;
        }

        tezu-form-h3 {
            margin: 1rem 0 !important
        }

        checklist-font {
            display: flex;
            flex-wrap: nowrap;
        }

        -webkit-print-color-adjust: exact !important;

        #checked {
            /* font-weight: bold; */
            color: black !important;
        }

        input[type=checkbox] {
            opacity: 1 !important;
            color: black
        }

        .checklist-font label {
            width: 80% !important;
        }
        hr {
            height: 1px !important;
            background-color: unset;
            border-color: gray !important;
            margin: 0 !important;
        }
        .page-break {
            page-break-after: always;
        }
    }
    
    hr{
        height: 1px !important;
        background-color: unset;
        border-color: gray !important;
    }

    table {
        border-collapse: collapse;
    }

    tbody {
        border: 24px solid transparent;
    }
</style>
@section('content')
    <div class="container cont" id="">
        @if (auth()->guard('department_user')->check())
        <div class="row">
            <a href="{{route('department.merit.hostel-allotment',['course_id'=>$merit_list->course_id])}}" class="btn btn-primary dont-print">Go To Hostel Allotment</a>
		</div>
        @endif
        <div class="row">
            <div class="col-md-12" >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 donot-print text-right">
                                <button type="button" class="btn btn-deafult dont-print" onclick="window.print()"><i
                                        class="fa fa-print"></i>
                                    Print</button>
                                <span>date:
                                    @if ($merit_list->hostelReceipt)
                                        {{date('d-m-Y', strtotime($merit_list->hostelReceipt->created_at))}}
                                    @else
                                        {{date('d-m-Y', strtotime($merit_list->admissionReceipt->created_at))}}
                                        
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-12" id="printTable">
                                {{-- <p style="text-align:center" class="">
                                    <img src="{{ asset('logo.png') }}" alt="Logo" alt="Logo" width="40">
                                </p> --}}
                                @for($i=0; $i<=2; $i++)
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="padding-xs" style="text-align: center;" colspan="4">
                                                    <h4 class="mb-3 text-uppercase tezu-form-h3">TEZPUR UNIVERSITY</h4>
                                                    <p class="mb-4 bold tezu--heading-print-para">Admission Record Form<br/>
                                                    @if ($i == 0)
                                                        Student Copy
                                                    @elseif ($i == 1)
                                                        Department Copy
                                                    @else
                                                        Academic Copy
                                                    @endif</p>
                                                    {{-- <p class="mb-4 bold tezu--heading-print-para">
                                                        NAPAAM, TEZPUR - 784028, ASSAM<br> 
                                                        @if($merit_list->status==2)
                                                        <strong> Provisional
                                                            ({{ strtoupper($receipt->type) }})
                                                            @elseif($merit_list->status==4)
                                                            <span class="btn btn-danger btn-xs">Cancelled</span>
                                                            @endif
                                                            (Original){{ $merit_list->admissionReceipt->slide_date ? '[Slided]' : '' }}
                                                            @if ($receipt->status)
                                                                <br /><span class="text-danger">Seat Transferred</span>
                                                            @endif
                                                        </strong><br> --}}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Registration No :</th>
                                                <td > {{ $application->student_id }}</td>
                                                <th>Application No:</th>
                                                <td >{{ $application->application_no }}</td>
                                            
                                            </tr>
                                            <tr>
                                                <th>Name of the candidate:</th>
                                                <td colspan="3" style="text-transform: uppercase"> {{ $application->fullname }}</td>
                                            </tr>
                                            <tr>
                                                <th>Father's name and occupation :</th>
                                                <td >{{ $application->father_name }}-{{$application->father_occupation}}</td>
                                                <th>Mother's name and occupation :</th>
                                                <td >{{ $application->mother_name }}-{{$application->mother_occupation}}</td>
                                            </tr>
                                            
                                            <tr>
                                                <th>Social Category :</th>
                                                <td>
                                                   
                                                    {{-- @if ($merit_list->shortlistedCategory->id == 1)
                                                        Unreserved
                                                    @else --}}
                                                        {{ $merit_list->shortlistedCategory->name ?? 'NA' }}
                                                    {{-- @endif --}}
                                                    @if ($merit_list->is_pwd == 1)
                                                        (PWD)
                                                    @endif
                                                </td>
                                                <th>Gender :</th>
                                                <td>{{ $application->gender }}</td>
                                            </tr>
                                            <tr>
                                                <th>Home Address :</th>
                                                <td colspan="3">C/O:{{ $merit_list->application->permanent_co ?? 'NA' }},
                                                House No:{{ $merit_list->application->permanent_house_no ?? 'NA' }},
                                                Street Name/Locality: {{ $merit_list->application->permanent_street_locality ?? 'NA' }} ,
                                                Vill/Town:{{ $merit_list->application->permanent_village_town ?? 'NA' }},
                                                {{-- PS: {{$application->correspondence_ps ?? "NA"}} </> --}}
                                                PO:{{ $merit_list->application->permanent_po ?? 'NA' }} ,
                                                Dist:{{ $merit_list->application->per_district->district_name ?? 'NA' }}
                                                State:{{ $merit_list->application->per_state->name ?? 'NA' }} -
                                                {{ $merit_list->application->permanent_pin ?? 'NA' }}</td>
                                                                                                                                
                                            </tr>
                                            <tr>
                                                <th>Contact No. :</th>
                                                <td>{{ $application->student->mobile_no ?? 'NA' }}</td>
                                                <th>Email ID :</th>
                                                <td>{{ $application->student->email ?? 'NA' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Do you belong to minority community :</th>
                                                <td> 
                                                    @if($application->religion=="Hinduism")
                                                    No
                                                    @else
                                                    Yes
                                                    @endif
                                                </td>
                                                <th>Do you belong to Rural place or Urban place :</th>
                                                <td> {{$application->place_residence}}</td>
                                            </tr>
                                            <tr>
                                                <th>State of domicile :</th>
                                                <td> {{ $application->per_state->name ?? 'NA'  }}</td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                <th>Religion :</th>
                                                <td>
                                                    
                                                    {{ $application->religion }}
                                                </td>
                                                <th colspan="2" style="text-align:right;">Signature of the student with date</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th colspan="4" style="text-decoration: underline">Credential Verification & Admission Recommendation</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">Relevant documents of the above candidate selected for admission into  the programme<b>{{ $merit_list->course->name }}</b> discipline under the <b>@if ($merit_list->admissionReceipt->admission_category->id == 1)
                                                Unreserved
                                                @else
                                                {{ $merit_list->admissionReceipt->admission_category->name ?? 'NA' }}
                                                @endif
                                                @if ($merit_list->is_pwd == 1)
                                                (PWD)
                                                @endif </b> category / against NE seats have been verified as per the checklist . He / she is recommended for / admission /
                                                Provisional admission to the program .</td>
                                                
                                            </tr>
                                            <tr>
                                                <th>His / Her Roll No :</th>
                                                <td colspan="3">{{ $merit_list->admissionReceipt->roll_number }}</td>
                                            </tr>  
                                            <tr>
                                                <th>Date : </th>
                                                <th>Office Seal</th>
                                                <th colspan="2" style="float: right;">Signature of the faculty i/c</th>
                                            </tr>                             
                                        </tbody>
                                    </table>
                                    <hr>
                                    <!--<table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th colspan="4" style="text-align:center">(For Office use only)</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" style="text-decoration: underline">Medical Officer</th>
                                            </tr>
                                            <tr>
                                                <th>Person with disability certificate verified and found in order</th>
                                                <td colspan="3"></td>
                                            </tr>
                                            <tr>
                                                <th>The candidate has been found fit for admission to the programme:</th>
                                                <td colspan="3">{{-- {{ $merit_list->application_no }} --}}</td>
                                            </tr>
                                            <tr>
                                                <th>If NO , Please give the reasons :</th>
                                                <td colspan="3">{{-- {{ $merit_list->application_no }} --}}</td>
                                            </tr>
                                            <tr>
                                                <th>Date : </th>
                                                <th>Office Seal</th>
                                                <th colspan="2" style="float: right;">Signature of medical officer</th>
                                            </tr>
                                        </tbody>
                                    </table>!-->
                                    <hr>
                                    {{-- @if ($merit_list->hostelReceipt) --}}
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th colspan="4" style="text-decoration: underline">Hostel</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">Not admitted / Admitted to  <strong>{{$merit_list->hostel_name??"NA"}}</strong> Hostel and seat no is <strong>{{$merit_list->room_no??"NA"}}</strong>.</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">  </th>
                                            
                                                <th colspan="2" style="float: right;">Signature of the DSW</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    {{-- @endif --}}
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th colspan="4" style="text-decoration: underline">Fees</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">Admission fees Rs <strong>{{$merit_list->admissionReceipt->total}}</strong> by online payment ID <strong>{{$merit_list->admissionReceipt->transaction_id}}</strong> dated <strong>{{date('d-m-Y', strtotime($merit_list->admissionReceipt->created_at))}}</strong>.</td>
                                            </tr>
                                            @if ($merit_list->hostelReceipt)
                                                <tr>
                                                    <td colspan="4">Hostel fee with caution deposit <strong>{{$merit_list->hostelReceipt->total}}</strong> by online payment ID <strong>{{$merit_list->hostelReceipt->transaction_id}}</strong> dated <strong>{{date('d-m-Y', strtotime($merit_list->hostelReceipt->created_at??""))}}</strong>.</td>
                                                </tr>
                                            @endif
                                            {{-- <tr>                                            
                                                <th colspan="4" style="float: right;">In- charge , cash counter</th>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                    <hr>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th colspan="4" style="text-decoration: underline">Admission Note</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">Admitted / Provisionally admitted * because of</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">(1) Yet to pass the qualifying examination</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">(2) Documents NOT submitted (Document no () of the check list</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">(3) Other reasons (please give reason)</td>
                                            </tr>
                                            <tr>
                                                <th colspan="4" style="float: right;">Signature of faculty-in-charge</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">* Provisionally admitted students must submit the required documents to the office of the controller of examinations
                                                through the concerned Head within the specified date.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td colspan="2">Computer generated ARF is issued to the candidate .</td>
                                                <th colspan="2" style="float: right;">Signature of the official</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @if($i == 0 || $i==1)
                                        <div class="page-break" ></div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('js')
        @include('common/application/peyment-receipt-js')
        <script>
            history.pushState(null, null, location.href);
            window.onpopstate = function() {
                history.go(1);
            };
        </script>
    @endsection
