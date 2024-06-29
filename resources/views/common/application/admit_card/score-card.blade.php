
<style>
    ol {
        text-align: justify
    }

    #watermark {
        position: fixed;
        top: 0px;
        left: 15%;
        width: 60%;
        height: 400px;
        opacity: .1;
        z-index: -1;
    }

    th {
        text-align: left !important;
        font-weight: 300 !important;
    }

    /* .table-bordered > tbody > tr > th{
        font-weight:300!important;
}
    .table-1, .table-1 th, .table-1 td {
    border:unset!important;
    }
    .table-2, .table-2 th, .table-2 td {
    border: thin solid rgb(141, 141, 141)!important;
    border-collapse: collapse;
    }
td {font-size:12px!important;} */

    .table-2,
    .table-2 th,
    .table-2 td {
        border: 1px solid rgb(66, 66, 66);
        border-collapse: collapse;
        font-size: 10px;
    }

    .table-2 th,
    .table-2 td {
        padding:0 4px;
        /* line-height:24px; */
        text-align:left;
    }

    table,
    td,
    th {
        border: unset;
    }

    /* table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    } */
    body{
        font-family: "Roboto", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 13px;}

    /*! normalize.css v3.0.3 | MIT License | github.com/necolas/normalize.css */
    /*  */
</style>
<div class="admit-card-container">
    {{-- <div id="watermark"><img src="{{asset("logo.png")}}" height="100%" width="100%"></div> --}}
    <table class="table-1" width="100%" style="margin-bottom: 12px;">
        <tbody>
            <tr style="background: white;color: white;">
                <td width=20%>
                    <img style="max-width: 100px;" width="100" class="avatar avatar-xxl"
                        src="{{ base_path('public/logo.png') }}">
                </td>
                <td style="text-align: center;" width=60%>
                    <div style="text-align: center;">
                        <h1 style="color: #140707;margin-bottom:0!important;margin-bottom:0!important;"><strong>TEZPUR
                                UNIVERSITY</strong></h1>
                        <p style="color: #140707;margin-top:0!important;margin-bottom:0!important;">
                            NAPAAM, TEZPUR - 784028, ASSAM<br>
                            <strong>PROVISIONAL SCORE CARD @if($admit_card->tuee_result->is_revised) (REVISED) @endif</strong><br>
                            <strong>TEZPUR UNIVERSITY ENTRANCE EXAMINATION 2024 (CBT)</strong><br>
                        </p>
                    </div>
                </td>
                <td style="text-align: center;" width=20%>
                    @php
                        $url=env('APP_URL');
                        $id=Crypt::encrypt($admit_card->active_application->id);
                        $qr_string = $url . '/admit/' . $admit_card->active_application->id;   
                        $qr = QrCode::generate($qr_string);
                    @endphp
                    {!! $qr !!}
                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%" class="table-2">
        <tr>
            <td width=20% rowspan="6" style="text-align: center;height:200px;" >
                <img style="max-width: 121px; height:150px;border:1px solid"
                    src="{{ base_path('public/uploads/' . $admit_card->active_application->student_id . '/' . $admit_card->active_application->id . '/' . $admit_card->active_application->passport_photo()->file_name ?? 'NA') }}">
            </td>
            
            <td width=15%><strong>Roll No</strong></td>
            <td width=25%> <strong>{{ $admit_card->roll_no }}</strong></td>
            <td width=12%><strong>Exam Date/Time</strong></td>
            <td width=28%> <strong>{{ $admit_card->course->ExamGroup->exam_date }} &nbsp; {{ $admit_card->course->ExamGroup->exam_time }}</strong></td>
        </tr>
        <tr>
            <td><strong>Registration No</strong></td>
            <td><strong> {{$admit_card->student_id}}</strong></td>
            <td><strong>Application No</strong></td>
            <td><strong>{{ $admit_card->active_application->application_no }}</strong></td>
        </tr>
        <tr>
            {{-- <td><strong>Applied Program Id</strong></td>
            <td><strong> {{ $admit_card->applied_course_details->application_number }}</strong></td> --}}
            <td><strong>Program Name</strong></td>
            <th colspan=3> {{$admit_card->course->name}}({{$admit_card->course->code}}) 
                @if ($admit_card->anotherCourse)
                    / {{$admit_card->anotherCourse->name}}({{$admit_card->anotherCourse->code}}) 
                @endif
            </th>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td><strong> {{ strtoupper($admit_card->active_application->fullname) }}</strong></td>
            <td><strong>Gender</strong></td>
            <td> {{ $admit_card->application->gender }}</td>
        </tr>

        <tr>
            {{-- <td><strong>Category</strong></td>
            <td> {{ strtoupper($admit_card->application->caste->name) }}</td> --}}
            <td><strong>Date of Birth:</strong></td>
            <td colspan=3> {{ date('d-m-Y', strtotime($admit_card->application->dob)) }}</td>
        </tr>

        <tr>
            {{-- <td rowspan="2" style="text-align: center">
                <img style="width: 120px;"
                    src="{{ base_path('public/uploads/' . $admit_card->active_application->student_id . '/' . $admit_card->active_application->id . '/' . $admit_card->active_application->signature()->file_name) }}">
            </td> --}}
            <td><strong>Contact Number</strong></td>
            <td> {{ $admit_card->application->student->mobile_no }}</td>
            <td><strong>Email Address</strong></td>
            <td> {{ $admit_card->application->student->email }}</td>
        </tr>
        <tr>
            <td rowspan="4" style="text-align: center;height:50px;">
                <img style="width: 130px;height:40px;border:1px solid;"
                    src="{{ base_path('public/uploads/' . $admit_card->active_application->student_id . '/' . $admit_card->active_application->id . '/' . $admit_card->active_application->signature()->file_name) }}">
            </td>
            <td height="20"><strong>Guardian Name</strong></td>
            <td>{{ $admit_card->application->guardian_name }}</td>
            <td height="20"><strong>Guardian No.</strong></td>
            <td>{{ $admit_card->application->guardian_phone }}</td>
        </tr>
        <tr>
            <td rowspan=2><strong>Examination Center</strong></td>
            <td style="padding:2px;" rowspan=2> {{ $admit_card->exam_center->center_name }}
                {{ $admit_card->exam_center->center_code??"NA" }}<br/>
                {{ $admit_card->sub_exam_center->center_name }},<br/>
                {{ $admit_card->sub_exam_center->address }},<br/>
                City-{{ $admit_card->sub_exam_center->city }},
                State-{{ $admit_card->sub_exam_center->state }} {{-- -{{ $admit_card->sub_exam_center->pin }} --}}.
            </td>
            <td><strong>Marks Scored</strong></td>
            <td> <strong>{{$admit_card->tuee_result->marks_botained}}</strong></td>          
        </tr>

        <tr>
            <td><strong>Total Marks</strong></td>
            <td> <strong>{{$admit_card->tuee_result->total_marks}}</strong></td>
        </tr>

    </table>
    <table class="table-1" width="100%" style="margin-bottom: 12px;">
        <tr>
            <td style="text-align: left;">
                <img style="width: 100px; height: 40px; max-width: 160px; max-height: 60px;"
                    src="{{ base_path('public/controller.png') }}">
                <p><strong>Controller of Examinations, TU</strong></p>
            </td>
            <td style="text-align: right;">
                <img style="width: 100px; height: 40px; max-width: 160px; max-height: 60px;"
                    src="{{ base_path('public/director.png') }}">
                <p><strong>Director, TUEE-2024</strong></p>
            </td>
        </tr>
    </table>
    @if($admit_card->tuee_result->is_revised)
    <div class="container-instruction">
        <ol>
            <li>Score Card is reviced due to technical issues.</li>
        </ol>
    </div>
    @endif
</div>
