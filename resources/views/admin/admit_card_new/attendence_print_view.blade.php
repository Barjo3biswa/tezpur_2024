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
        <div class="row">   
          <input type="button" onclick="printDiv('printableArea')" class="btn btn-primary" value="print" />
        </div>
    </div>
    <br/>
    <div class="container">
        <div class="row" id="printableArea">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <table width="100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <td colspan=5 style="padding:0;">
                                    <div class="application-header">
                                        <div class="img-div">
                                            <img class="avatar avatar-xxl" src="{{ asset('logo.png') }}" align="left">
                                        </div>
                                    {{-- </td>
                                    <td colspan=2> --}}
                                        <div class="tezu-title">
                                            <h3 class="mb-3 text-uppercase"><strong>TEZPUR UNIVERSITY</strong></h3>
                                            <p style="color: #140707;">
                                                NAPAAM, TEZPUR - 784028, ASSAM<br>
                                                <strong>ATTENDANCE SHEET</strong><br>
                                                <strong>TUEE-2023</strong><br>
                                            </p>
                                        </div>
                                    {{-- </td>
                                    <td> --}}
                                        <div class="centre-details">
                                            <h6>Center Name: <strong>{{$center_name->center_name}}({{$center_name->center_code}})</strong></h6>
                                            <h6>Course Name: <strong>{{$course_name->name}}({{$course_name->code}})</strong></h6>
                                            <h6>No of Students: <strong>{{$count}}</strong></h6>

                                            <h6>Exam Date/Time: <strong>{{$course_name->ExamGroup->exam_date??"NA"}} / {{$course_name->ExamGroup->exam_time??"NA"}} ({{$course_name->ExamGroup->shift??"NA"}})</strong></h6>
                                        </div> 
                                    </div>                              
                                </td>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th width=3%>Sl</td>
                                <th width=35%>Applicant Details</td>
                                <th width=15%>Photo</td>
                                <th width=22% style="line-height: 15px;">Specimen Signature</td>
                                <th width=25%>Signature</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendence as $key => $atten)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td style="font-size:12px;padding:0;"><strong>Name:</strong> {{$atten->application->FullName}}<br/>
                                        <strong>Roll No:</strong> {{$atten->roll_no}}<br/>
                                        <strong>Registration No:</strong> {{$atten->application->student_id}}<br/>
                                        <strong>Application No:</strong> {{$atten->application->application_no}}<br/>
                                        <strong>Gender:</strong> {{$atten->application->gender}}<br/></td></td>
                                    <td><img style="height:100px; width: 90px; border: 1px solid black; "
                                            src="{{ route('common.download.image', [$atten->active_application->student_id, $atten->active_application->id, $atten->active_application->passport_photo()->file_name ?? 'NA']) }}">
                                        <br />
                                    </td>
                                    <td>
                                
                                        <img style="width: 150px; height: 100px; border: 1px solid black;"
                                            src="{{ route('common.download.image', [$atten->active_application->student_id, $atten->active_application->id, $atten->active_application->signature()->file_name]) }}">
                                    </td>
                                    {{-- <td></td> --}}
                                    <td>
                                    </td>
                                </tr>
                                @if (($key) % 7 == 0)
                                    <tr class="page-break">
                                        {{-- <td colspan="3"></td> --}}
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
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
