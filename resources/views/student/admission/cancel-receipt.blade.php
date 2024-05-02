@php
    if (get_guard() == 'admin') {
        $layout = 'admin.layout.auth';
    } elseif (get_guard() == 'student') {
        $layout = 'student.layouts.auth';
    } elseif (get_guard() == 'department_user') {
        $layout = 'department-user.layout.auth';
    }
    $dept = \App\Department::where('id', $merit_list->course->department_id)->first();
    
    $total = 0;
    $date = 0;
    // foreach ($receipt->collections as $key => $val) {
    //     $total += $val->amount;
    //     $date = $val->created_at;
    // }
    $fees = number_format($total, 2);
    
@endphp
@extends($layout)
<style>
    @media print {
        .cont {
            font-size: 14px;
        }

        .p-footer-font {
            font-size: 14px;
        }

        .checklist-font {
            font-size: 14px;
        }

        .t-font-size {

            font-size: 12px;
            padding-top: 10px !important;
        }

        .pagebreak {
            page-break-before: always;
        }

        table {
            font-size: 14px;
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
            padding-bottom: 6rem !important;
        }

        tezu-form-h3 {
            margin: 2rem 0 !important
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
            @if (checkPermission(3) == true)
                <div class="row">
                    <a href="{{ route('department.merit.payment', ['course_id' => $merit_list->course_id, 'merit_master_id' => $merit_list->merit_master_id]) }}"
                        class="btn btn-primary">Go To Payment Officer Panel</a>
                </div>
            @endif
        @endif
        <div class="row">
            <div class="col-md-12" style="padding: 15px;">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 donot-print text-right">
                                <button type="button" class="btn btn-deafult dont-print" onclick="window.print()"><i
                                        class="fa fa-print"></i>
                                    Print</button>
                            </div>
                            <div class="col-md-12" id="printTable">
                                <p style="text-align:center" class="">
                                    <img src="{{ asset('logo.png') }}" alt="Logo" alt="Logo" width="40">
                                </p>
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="padding-xs" style="text-align: center;" colspan="4">
                                                <h3 class="mb-3 text-uppercase tezu-form-h3">TEZPUR UNIVERSITY</h3>
                                                <p class="mb-4 bold tezu--heading-print-para">
                                                    NAPAAM, TEZPUR - 784028, ASSAM<br>
                                                    @if($merit_list->status==4)
                                                        <span class="btn btn-danger btn-xs">Denied</span>
                                                    @elseif($merit_list->status==6)
                                                        <span class="btn btn-danger btn-xs">Withdrawal</span>
                                                    @endif
                                                    <br>
                                                </p>
                                            </td>
                                        </tr>
                                        
                                        
                                
                                        <tr>
                                            <th>Programme Applied</th>
                                            <td colspan="3"> {{ $merit_list->course->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Applicant Name</th>
                                            <td> {{ $application->fullname }}</td>
                                            <th>Application No</th>
                                            <td>{{ $merit_list->application_no }}</td>
                                        </tr>

                                        <tr>
                                            <th>JEE roll No:</th>
                                            <td> {{ $merit_list->cmr }}</td>
                                            <th>JEE Rank</th>
                                            <td>{{ $merit_list->student_rank }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Phone No.</th>
                                            <td>{{ $application->student->mobile_no ?? 'NA' }}</td>
                                            <th>Social Category</th>
                                            <td>{{ $application->caste->name ?? 'NA' }}
                                                @if ($merit_list->is_pwd == 1)
                                                    (PWD)
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4"> <hr></td>
                                        </tr>
                                        <tr>
                                            <th>Reason Of Cancel</th>
                                            <td colspan="3"> {{ $merit_list->reason_of_cancel }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"> <hr></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div><br/><br/>
                            <div class="row">
                                <div class="col-md-6">
                                    @if($merit_list->status==6)
                                         <span style="float: left; margin-left:20px">Tezpur University</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <span style="float: right; margin-right:20px">Student Signature</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer p-footer-font">
                        <p>

                        </p>
                    </div>
                    <hr>
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
