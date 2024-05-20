<div class="admit-card-container" id="printableArea">
    <table width="100%" {{-- style="border:2px solid black;" --}}>
        <tbody>       
            <tr style="background: rgb(255, 255, 255);color: white;">
                <td style="display: flex; align-items: center;">
                    <img style="max-width: 100px;margin-left:20px;margin-top:30px;" width="100" class="avatar avatar-xxl" src="{{ asset('logo.png') }}"
                        align="left">
                </td>
                <td style="text-align: center;">
                    <div style="margin: 0 auto; text-align: center;">
                        <h3 class="mb-3 text-uppercase"><strong>TEZPUR UNIVERSITY</strong></h3>
                        <p style="color: #140707;">
                            NAPAAM, TEZPUR - 784028, ASSAM<br>
                            <strong>ADMIT CARD</strong><br>
                            <strong>TEZPUR UNIVERSITY ENTRANCE EXAMINATION 2023</strong><br>
                        </p>
                    </div>
                </td>
                <td style="text-align: center;">
                    {{-- @php
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
                            {!!'<img style="width:120px;" src="data:'.$qr_code->getContentType().';base64,'.$qr_code->generate().'" />'!!} --}}
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
                    <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px;"
                        src="{{ asset_public('controller.png') }}">
                    <p><strong>Controller of Examinations, TU</strong></p>
                </td>
                <td style="text-align: right;">
                    <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px;"
                        src="{{ asset_public('director.png') }}">
                    <p><strong>Director, TUEE-2023</strong></p>
                </td>
            </tr>
        </tbody>
    </table>
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
        </ol>
    </div>
</div>
