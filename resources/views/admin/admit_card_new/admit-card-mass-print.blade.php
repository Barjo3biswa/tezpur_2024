@extends('admin.layout.auth')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
    <style>
        .application-header {
            display: flex;
            align-items: center;
            justify-content: space-around;
            /* width: 1142px; */
            max-width: 100%;
            padding: 10px 2px;
        }
        .application-header .img-div{width:104px;}
        .application-header .img-div img {width:100%;}
        .application-header .tezu-title{
            width:350px;
            text-align: center;}
        .application-header .centre-details{width:220px;}
        .application-header .tezu-title h3{margin:0;font-size: 26px;}
        .application-header .tezu-title p {
            line-height: 18px;font-size: 13px;}
    .application-header .centre-details h6{margin:0; font-size: 13px;}
    .centre-details strong {font-size:12px!important; letter-spacing: .5px;}
    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid #202020!important;
    }
    .panel {
        box-shadow: unset!important;
    }
    
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        line-height: 12px;
    }
    h3, .h3 {
  font-size: 24px;
    }
    .admit-card-header-p{line-height: 16px;}
    .admit-card-header-p strong {font-size:10px;}
    @media print {
        /* .panel {
            border: 1px solid #e3e3e3;
        } */
       
        .table-row {
            /* Set the height of each table row */
            height: 50px;
        }
        
        .page-break {
            /* Force a page break after every 7th table row */
            page-break-after: always;
        }

    }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row" style="display: flex;justify-content: center;">
            <div class="col-md-10">   
                <input type="button" onclick="printDiv('printableArea')" class="btn btn-primary" value="print" />
            </div>
        </div>
    </div>
    <br/>
    <div class="container">
        <div class="row" id="printableArea" style="display: flex;justify-content: center;">
            <div class="col-md-10">
                <div class="panel panel-default">
                    

                    @foreach ($admit_cards as $admit_card)
                    <table width="100%" {{-- style="border:2px solid black;" --}}>
                        <tbody>       
                            <tr style="background: rgb(255, 255, 255);color: white;">
                                <td style="display: flex; align-items: center;">
                                    <img style="max-width: 80px;margin-left:20px;margin-top:30px;" width="100" class="avatar avatar-xxl" src="{{ asset('logo.png') }}"
                                        align="left">
                                </td>
                                <td style="text-align: center;">
                                    <div style="margin: 0 auto; text-align: center;">
                                        <h3 class="mb-3 text-uppercase"><strong>TEZPUR UNIVERSITY</strong></h3>
                                        <p class="admit-card-header-p" style="color: #140707;">
                                            NAPAAM, TEZPUR - 784028, ASSAM<br>
                                            <b>ADMIT CARD</b><br>
                                            <strong>TEZPUR UNIVERSITY ENTRANCE EXAMINATION 2023</strong><br>
                                        </p>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    @php
                                        $url=env('APP_URL');
                                        $id = Crypt::encrypt($admit_card->active_application->id);
                                        $qr_string = $url . '/admit/' . $admit_card->active_application->id;  
                                        $qr_code = QrCode();
                                        $qr_code
                                            ->setText($qr_string)
                                            ->setSize(300)
                                            ->setPadding(10)
                                            ->setErrorCorrection('high')
                                            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                                            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))                
                                            ->setLabelFontSize(16)
                                            ->setImageType(QrCode()::IMAGE_TYPE_PNG);
                                    @endphp
                                            {!!'<img style="width:100px;" src="data:'.$qr_code->getContentType().';base64,'.$qr_code->generate().'" />'!!}
                                    {{-- @if (config('vknrl.admit_qr_code'))
                                        {!! '<img src="data:' . $qr_code->getContentType() . ';base64,' . $qr_code->generate() . '" width="100px"/>' !!}
                                    @endif --}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table width="100%" class="table table-bordered">
                        <tbody >
                            <tr style="padding-top: 5px;">
                                <td width=20% rowspan="5"
                                    style="text-align: center">
                                    <img style="height:150px; width: 121px; border: 1px solid black; "
                                        src="{{ route('common.download.image', [$admit_card->active_application->student_id, $admit_card->active_application->id, $admit_card->active_application->passport_photo()->file_name ?? 'NA']) }}">
                                </td>
                                <td width=15%>Roll No</td>
                                <td width=25%> <strong>{{ $admit_card->roll_no }}</strong></td>
                                <td width=15%>Exam Date/Time</td>
                                <th width=25%> <strong>{{ $admit_card->course->ExamGroup->exam_date }} &nbsp; {{ $admit_card->course->ExamGroup->exam_time }}</strong></th>
                            </tr>
                            <tr>
                                <td>Registration No</td>
                                <th> {{$admit_card->student_id}}</th>
                                <td>Application No</td>
                                <td> <strong>{{ $admit_card->active_application->application_no }}</strong></td>
                            </tr>
                            <tr>
                                <td>Applied Program Id</td>
                                <td> <strong>{{ $admit_card->applied_course_details->application_number }}</strong></td>
                                <td>Program Name</td>
                                <th> {{$admit_card->course->name}}({{$admit_card->course->code}})</th>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td> <strong>{{ strtoupper($admit_card->active_application->fullname) }}</strong></td>
                                <td>Gender</td>
                                <th> <strong>{{ $admit_card->application->gender }}</strong></th>
                            </tr>
                
                            <tr>
                                <td>Category</td>
                                <td> <strong>{{ strtoupper($admit_card->application->caste->name) }}</strong></td>
                                <td>Date of Birth</td>
                                <th> <strong>{{date('d-m-Y', strtotime($admit_card->application->dob))}}</strong></th>
                            </tr>
                            
                            <tr>
                                <td rowspan="3" style="text-align: center">
                                    <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px; border: 1px solid black;"
                                        src="{{ route('common.download.image', [$admit_card->active_application->student_id, $admit_card->active_application->id, $admit_card->active_application->signature()->file_name]) }}">
                                </td>
                                <td>Contact Number</td>
                                <td><strong> {{ $admit_card->application->student->mobile_no }}</strong></td>
                                <td>Email Address</td>
                                <td><strong> {{ $admit_card->application->student->email }}</strong></td>
                            </tr>
                            <tr>
                                <td>Guardian Name</td>
                                <td><strong>{{ $admit_card->application->guardian_name }}</strong></td>
                                <td>Guardian Contact No.</td>
                                <td><strong>{{ $admit_card->application->guardian_phone }}</strong></td>
                            </tr>
                            <tr>
                                <td>Examination Center</td>
                                <td><strong> : {{ $admit_card->exam_center->center_name }}
                                            {{ $admit_card->exam_center->center_code??"NA" }}<br/>
                                            {{ $admit_card->exam_center->address }},<br/>
                                            {{ $admit_card->exam_center->city }},
                                            {{ $admit_card->exam_center->state }} -{{ $admit_card->exam_center->pin }}.
                                    </strong></td>
                                <td>Reporting Time</td>
                                <td><strong> {{$admit_card->course->ExamGroup->reporting_time}}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <table width="100%" >
                        <tbody >
                            <tr>
                                <td style="text-align: left;">
                                    {{-- <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px;"
                                        src="{{ asset_public('controller.png') }}"> --}}
                                    <img style="width: 110px;
                                    height: 32px;
                                    max-width: 160px;
                                    max-height: 32px;"
                                        src="{{ asset_public('controller.png') }}">
                                    <p><strong>Controller of Examinations, TU</strong></p>
                                </td>
                                <td style="text-align: right;">
                                    <img style="width: 110px;
                                    height: 32px;
                                    max-width: 160px;
                                    max-height: 32px;"
                                        src="{{ asset_public('director.png') }}">
                                    <p><strong>Director, TUEE-2023</strong></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
@endsection
