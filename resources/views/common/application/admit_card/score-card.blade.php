
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
                        <h1 style="color: #140707;margin-bottom:0!important;margin-bottom:0!important;">TEZPUR
                                UNIVERSITY</h1>
                        <p style="color: #140707;margin-top:0!important;margin-bottom:0!important;">
                            NAPAAM, TEZPUR - 784028, ASSAM<br>
                            SCORE CARD<br>
                            TEZPUR UNIVERSITY ENTRANCE EXAMINATION 2023<br>
                        </p>
                    </div>
                </td>
                <td style="text-align: center;" width=20%>
                    {{-- @php
                        $url = env('APP_URL');
                        $id = Crypt::encrypt($score_card->admit_card->active_application->id);
                        $qr_string = $url . '/admit/' . $score_card->admit_card->active_application->id;
                        $qr_code = QrCode();
                        $qr_code
                            ->setText($qr_string)
                            ->setSize(300)
                            ->setPadding(10)
                            ->setErrorCorrection('high')
                            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
                            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
                            ->setLabelFontSize(16)
                            ->setImageType(QrCode()::IMAGE_TYPE_PNG);
                    @endphp
                    {!! '<img style="width:120px;" src="data:' .
                        $qr_code->getContentType() .
                        ';base64,' .
                        $qr_code->generate() .
                        '" />' !!} --}}
                </td>
            </tr>
        </tbody>
    </table>



    <table width="100%" class="table-2">
        <tr>
            <td width=20% rowspan="5" style="text-align: center;height:200px;" >
                <img style="max-width: 121px; height:150px;border:1px solid"
                    src="{{ base_path('public/uploads/' . $score_card->admit_card->active_application->student_id . '/' . $score_card->admit_card->active_application->id . '/' . $score_card->admit_card->active_application->passport_photo()->file_name ?? 'NA') }}">
            </td>
            
            <td width=15%>Roll No</td>
            <td width=25%> <strong>{{ $score_card->admit_card->roll_no }}</strong></td>
            <td>Examination Center</td>
            <td style="padding:2px;"><strong> {{ $score_card->admit_card->exam_center->center_name }}
                ({{ $score_card->admit_card->exam_center->center_code}})<br />
                {{ $score_card->admit_card->exam_center->address }},<br />
                {{ $score_card->admit_card->exam_center->city }},
                {{ $score_card->admit_card->exam_center->state }}-{{ $score_card->admit_card->exam_center->pin }}.</strong>
            </td>
        </tr>
        <tr>
            <td>Registration No</td>
            <td> <strong> {{$score_card->admit_card->student_id}}</strong></td>
            <td>Application No</td>
            <td> <strong>{{ $score_card->admit_card->active_application->application_no }}</strong></td>
        </tr>
        <tr>
            <td>Applied Program Id</td>
            <td> <strong>{{ $score_card->admit_card->applied_course_details->application_number }}</strong></td>
            <td>Program Name</td>
            <td> <strong>{{$score_card->admit_card->course->name}}({{$score_card->admit_card->course->code}})</strong></td>
        </tr>
        <tr>
            <td>Name</td>
            <td> <strong>{{ strtoupper($score_card->admit_card->active_application->fullname) }}</strong></td>
            <td>Gender</td>
            <td> <strong>{{ $score_card->admit_card->application->gender }}</strong></td>
        </tr>

        <tr>
            <td>Guardian Name</td>
            <td><strong>{{ $score_card->admit_card->application->guardian_name }}</strong></td>
            <td>Guardian No.</td>
            <td><strong>{{ $score_card->admit_card->application->guardian_phone }}</strong></td>
            
        </tr>
        <tr>
            <td rowspan="2" style="text-align: center;height:50px;">
                <img style="width: 130px;height:40px;border:1px solid;"
                    src="{{ base_path('public/uploads/' . $score_card->admit_card->active_application->student_id . '/' . $score_card->admit_card->active_application->id . '/' . $score_card->admit_card->active_application->signature()->file_name) }}">
            </td>
            <td height="20" >Contact Number</td>
            <td> <strong>{{ $score_card->admit_card->application->student->mobile_no }}</strong></td>
            <td>Email Address</td>
            <td> <strong>{{ $score_card->admit_card->application->student->email }}</strong></td>
        </tr>
        <tr>
            <td height="20">Category</td>
            <td> <strong>{{ strtoupper($score_card->admit_card->application->caste->name) }}</strong></td>
            <td>Date of Birth:</td>
            <td> <strong>{{ date('d-m-Y', strtotime($score_card->admit_card->application->dob)) }}</strong></td>
        </tr>
    </table>
    <br/>

    <table width="100%" class="table-2" style="text-align:center;">
        <tr>           
            <td rowspan="2" width=20% height=40px> <h2> TUEE SCORE </h2> </td>
            <td rowspan="2"> <h2> {{$score_card->marks_botained}} </h2> </td>
            <td height=20px> <h2> MCQ </h2> </td>
            <td> <h2> Descriptve </h2> </td>
        </tr>

        <tr>
            <td height=20px><h2>{{$score_card->mcq_ob}}</h2></td>
            <td><h2>{{$score_card->descriptive_total==0?"--":$score_card->descriptive_ob}}</h2></td>
        </tr>
    </table>
    <br/>


    <table class="table-1" width="100%" style="margin-bottom: 12px;">
        <tr>
            <td style="text-align: left;">
                <img style="width: 100px; height: 40px; max-width: 160px; max-height: 60px;"
                    src="{{ base_path('public/controller.png') }}">
                <p>Controller of Examinations, TU</p>
            </td>
            <td style="text-align: right;">
                <img style="width: 100px; height: 40px; max-width: 160px; max-height: 60px;"
                    src="{{ base_path('public/director.png') }}">
                <p>Director, TUEE-2023</p>
            </td>
        </tr>
    </table>
</div>
