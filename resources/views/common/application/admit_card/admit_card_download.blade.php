
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
                            <strong>ADMIT CARD</strong><br>
                            <strong>TEZPUR UNIVERSITY ENTRANCE EXAMINATION 2024</strong><br>
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
            <td colspan=3><strong>{{$admit_card->course->name}}({{$admit_card->course->code}})</strong></td>
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
            <td rowspan="2" style="text-align: center;height:50px;">
                <img style="width: 130px;height:40px;border:1px solid;"
                    src="{{ base_path('public/uploads/' . $admit_card->active_application->student_id . '/' . $admit_card->active_application->id . '/' . $admit_card->active_application->signature()->file_name) }}">
            </td>
            <td height="20"><strong>Guardian Name</strong></td>
            <td>{{ $admit_card->application->guardian_name }}</td>
            <td height="20"><strong>Guardian No.</strong></td>
            <td>{{ $admit_card->application->guardian_phone }}</td>
        </tr>
        <tr>
            <td><strong>Examination Center</strong></td>
            <td style="padding:2px;"> {{ $admit_card->exam_center->center_name }}
                ({{ $admit_card->exam_center->center_code}})<br />
                {{ $admit_card->exam_center->address }},<br />
                {{ $admit_card->exam_center->city }},
                {{ $admit_card->exam_center->state }}-{{ $admit_card->exam_center->pin }}.
            </td>
            <td><strong>Reporting Time</strong></td>
            <td> {{$admit_card->course->ExamGroup->reporting_time}}</td>
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
</div>
<div class="container-instruction">
    <h4 style="text-align: center;">Instructions to the candidates</h4>
    <ol>
        <li>This Admit Card must be presented for verification along with at least one original (not photocopy or scanned copy), valid photo Identification card (for example: College ID, Employee ID, Driving License, Passport, PAN Card, Voter ID, Aadhaar-UUID).</li>
        <li>This Admit Card is valid only if the candidate's photograph and signature images are legible. To ensure this, print the admit card on a A4 sized paper using a laser printer, preferably a colour photo printer.If there is any discrepancies in the admit card. Please contact, COE. TUEE and Director, TUEE (03712-273141, 03712-273142, 9957184355).</li>
        <li>Please report to the examination venue at least one hour before the commencement of the examination for identity verification. </li>
        <li>Candidates will be permitted to appear for the examination ONLY after their credentials are verified by centre officials. </li>
        <li>Candidates are advised to locate the examination centre and its accessibility at least a day before the examination, so that they can reach the centre on time for the examination.</li>
        <li>Candidates will be permitted to occupy their allotted seats 35 minutes before the scheduled start of the examination.</li>
        <li>Candidates are allowed to bring blue / black ball point pen and pencil to the examination hall.</li>
        <li>Candidates have to mark their response in the OMR sheet for scoring.</li>
        <li>The duration of Examination will be of 2:00 hours. Candidates will NOT be permitted to leave the examination hall before the end of the test.</li>
        <li>Candidates will NOT be permitted to enter the examination hall after the commencement of the exam.</li>
        <li>Device like non-programmable calculators are allowed. However, mobile phones and any other electronic gadgets are strictly not permitted in the examination hall.</li>
        <li>Violation of any instruction and adoption of any unfair means found in the examination hall, examinee will be booked under unfair means activity and action will be taken as per TUEE Rules.</li>
        <li><b>Use your Roll Number will as your login ID, and your password will be your date of birth in the DDMMYYYY format</b></li>
    </ol>
</div>
