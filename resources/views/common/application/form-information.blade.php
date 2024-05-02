@php
$course = $application->applied_courses/* ->first()->course */;
$departments=[];
foreach($course as $key=>$cour){
array_push($departments, $cour->course->department->name);
}
$dept = array_unique($departments);
$sl = 0;
@endphp

@isset($download)
<h4>Personal Details</h4>
@endisset
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <tbody>
        {{-- personale information --}}
        @if(!isset($download))
        <tr>
            <td class="padding-xs bold" colspan="2"><strong>Applied Courses (As Per Subject of Preference(s))</strong></td>
        </tr>
        @endif
        <tr>
            <td>Program Applied for</td>
            @if($application->is_mba==1)
            <td>Tag ID (Official)</td>
            @else
            <td>Applied Course Id</td>
            @endif
            <td>Department</td>   
        </tr>
        <tr>
            <td>{!!courseCode($application)!!}</td>
            {{-- @if ($application->is_mba==1)
            <td>{{$application->application_no}}</td>
            @else --}}
            <td>@foreach ($application->applied_courses as $appli)
                {{$appli->application_number}}<br/>
            @endforeach</td>
            {{-- @endif --}}
            
            <td>    
                @foreach ($departments as $key=>$d)                 
                    {{++$key}}.{{$d}}<br/>
                @endforeach </td>          
        </tr>
        {{-- @if ($application->is_cuet_pg==1) --}}
        @if($application->is_mba!=1 && $application->exam_through=="TUEE")
        <tr>
            <td colspan=2>Exam Center</td>
            <td>1. {{$application->ExamCenter->center_name??"NA"}}<br/>
                2. {{$application->ExamCenter1->center_name??"NA"}}<br/>
                3. {{$application->ExamCenter2->center_name??"NA"}}
            </td>
        </tr>
        @endif
        {{-- @endif --}}
    </tbody>
</table>


<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <tbody>
        {{-- personale information --}}
        @if(!isset($download))
        <tr>
            <td class="padding-xs bold" colspan="3"><strong>Application Fee Details</strong></td>
        </tr>
        @endif
        <tr>
            <td>Application fee</td>
            <td>Transaction ID</td>
            <td>Transaction Date</td>
        </tr>
       
        <tr>
            <td>{{isset($paymentReceipt) ? $paymentReceipt->currency : $application->paymentReceipt->currency ?? "NA"}} {{number_format(isset($paymentReceipt) ? $paymentReceipt->amount : $application->paymentReceipt->amount ?? 0, 2)}}</td>
            <td>{{isset($paymentReceipt) ? $paymentReceipt->payment_id : $application->paymentReceipt->payment_id ?? "NA"}} </td>
            <td>{{isset($paymentReceipt) ? dateFormat($paymentReceipt->created_at, "d-m-Y H:i:s") : $application->paymentReceipt->created_at ?? "NA"}}</td>
        </tr>
    </tbody>
</table>

@php
    $application_mas = $application;
    if($application->applicationMaster && $application->application_no!=null){
        $application_mas = $application->applicationMaster;
    }else{
        $application_mas = $application;
    }
@endphp

<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <tbody>
        @if(!isset($download))
        <tr>
            <td class="padding-xs bold" colspan="4"><strong>Personal Details</strong></td>
        </tr>
        @endif
        {{-- <tr>
            <td class="padding-xs bold">Program Applied for</td>
            <td class="padding-xs">
                {!!courseCode($application_mas)!!}
            </td>
            <td class="padding-xs bold">Department</td>
            <th class="padding-xs bold"> 
                
                @foreach ($dept as $d)
                    @php
                        $sl++;
                    @endphp
                    {{$sl}}.{{$d}}<br/>
                @endforeach 
            </th>
        </tr> --}}
        <tr>
            <td class="padding-xs">Registration Number</td>
            <td class="padding-xs bold">{{$application->student_id}}</td>
            <td class="padding-xs">Application Number</td>
            <td class="padding-xs bold">{{$application->application_no ?? "NA"}}</td>
        </tr>
        
        <tr>
            <td class="padding-xs">Full Name</td>
            <td class="padding-xs bold">{{$application_mas->fullname  ?? "NA"}}</td>
            <td class="padding-xs">Date of Birth</td>
            <td class="padding-xs bold">{{dateFormat($application_mas->dob, "d-m-Y")  ?? "NA"}}</td>

        </tr>
        <tr>
            <td class="padding-xs">Contact Number</td>
            <td class="padding-xs bold">{{$application_mas->student->mobile_no ?? "NA"}}</td>
            <td class="padding-xs">E-mail</td>
            <td class="padding-xs bold">{{$application_mas->student->email ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Gender</td>
            <td class="padding-xs bold ">{{$application_mas->gender  ?? "NA"}}</td>
            <td class="padding-xs">Nationality</td>
            <td class="padding-xs bold">{{$application_mas->nationality ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Aadhaar No</td>
            <td class="padding-xs bold">{{$application_mas->adhaar ?? "NA"}}</td>
            <td class="padding-xs">DigiLocker ID<br/>About Academic Bank Of Credits(ABC) </td>
            <td class="padding-xs bold">{{$application_mas->nad_id ?? "NA"}}<br/>{{$application_mas->abc ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Category</td>
            <td class="padding-xs bold">{{$application_mas->caste->name ?? "NA"}}</td>
            <td class="padding-xs">Sub-Caste</td>
            <td class="padding-xs bold">
                {{$application_mas->sub_caste ?? "NA"}}
            </td>
        </tr>
        <tr>
            <td class="padding-xs">Religion / Faith</td>
            <td class="padding-xs bold">{{$application_mas->religion  ?? "NA"}}</td>
            <td class="padding-xs">Place of residence</td>
            <td class="padding-xs bold">{{$application_mas->place_residence ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Marital Status</td>
            <td class="padding-xs bold">{{$application_mas->marital_status ?? "NA"}}</td>
            <td class="padding-xs">Country of origin</td>
            <td class="padding-xs bold">{{$application_mas->student->country->name}}</td>                                    
        </tr>
        @if($application_mas->is_foreign)
        <tr>
            <td class="padding-xs">Passport No</td>
            <td class="padding-xs bold">{{$application_mas->passport_number ?? "NA"}}</td>
            <td class="padding-xs">Driving license or equivalent no</td>
            <td class="padding-xs bold">{{$application_mas->driving_license_equivalnet_no ?? "NA"}}</td>                                    
        </tr>
        @endif
        <tr>                                    
            <td class="padding-xs">Are you person with disability (PWD) ?</td>
            <td class="padding-xs bold" colspan="3">
                {{$application_mas->is_pwd==0 ? 'No': 'Yes'}}
                {{$application_mas->person_with_disablity ? " (&nbsp;&nbsp;Type:".$application_mas->person_with_disablity.")" : ""}}
                {{$application_mas->pwd_percentage ? " (&nbsp;&nbsp;PWD Percentage:".$application_mas->pwd_percentage.")" : ""}}
            </td>
        </tr>                                
        <tr>
            <td class="padding-xs">Are you a Kashmiri Migrant (KM)?</td>
            <td class="padding-xs bold">{{$application_mas->is_kmigrant ? "Yes" : "No"}}</td>
            <td class="padding-xs">Are you a student from J & K?</td>
            <td class="padding-xs bold">
                {{$application_mas->is_jk_student ? 'Yes':'No'}}
            </td>
        </tr>
        <tr>
            <td class="padding-xs">Are you ex-Serviceman or widow/ward<br> of ex-serviceman?</td>
            <td class="padding-xs bold" colspan="3">{{$application_mas->is_ex_servicement ? "Yes" : "No"}} {{$application_mas->priority ? "(".$application_mas->priority->name.")" : ""}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Do you belong to BPL/AAY?</td>
            <td class="padding-xs bold">{{$application_mas->is_bpl ? "Yes" : "No"}}</td>
            <td class="padding-xs">Do you belong to minority community?</td>
            <td class="padding-xs bold">{{$application_mas->is_minority ? "Yes" : "No"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Are you employed?</td>
            <td class="padding-xs bold" colspan="3">{{$application_mas->is_employed ? "Yes" : "No"}} {{$application_mas->is_employed ? "(".$application_mas->employment_details.")" : ""}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Have you represented the Country/State<br> in any sport?</td>
            <td class="padding-xs bold">
                {{$application_mas->application_academic ? ($application_mas->application_academic->is_sport_represented ? "Yes" : "No") : "No"}}
                {{$application_mas->application_academic ? ($application_mas->application_academic->sport_played ?  " (".$application_mas->application_academic->sport_played.")" : "") : ""}}
            </td>
            <td class="padding-xs">Academic distinction/ medals/ prizes, if any</td>
            <td class="padding-xs bold">{{$application_mas->application_academic ? ($application_mas->application_academic->is_academic_prizes ? "Yes" : "No") : "No"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Other information worth mentioning including<br> publications, if any?</td>
            <td class="padding-xs bold" colspan="3">
                {{$application_mas->application_academic->other_information ?? "NA"}}
            </td>
        </tr>
        <tr>
            <td class="padding-xs">Were you debarred from any examination (s)?</td>
            <td class="padding-xs bold">
                {{$application_mas->application_academic ? ($application_mas->application_academic->is_debarred ? "Yes" : "No") : "No"}}
            </td>
            <td class="padding-xs">Were you punished for misconduct?</td>
            <td class="padding-xs bold">
                {{$application_mas->application_academic ? ($application_mas->application_academic->is_punished ? "Yes" : "No") : "No"}}
                {{$application_mas->application_academic ? ($application_mas->application_academic->is_punished ? " (".$application_mas->application_academic->furnish_details.")" : "") : ""}}
            </td>
        </tr>
        @if($application_mas->applied_courses->first()->course->course_type_id == 4)
        <tr>
            <td class="padding-xs">JEE Main Roll No</td>
            <td class="padding-xs bold">{{$application_mas->application_academic->jee_roll_no ??  "NA"}}</td>
            <td class="padding-xs">JEE Form No</td>
            <td class="padding-xs bold">{{$application_mas->application_academic->jee_form_no ??  "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">JEE Year</td>
            <td class="padding-xs bold">{{$application_mas->application_academic->jee_year ??  "NA"}}</td>
            <td colspan="2"></td>
        </tr>
        {{--  only PHD student--}}
        @elseif(in_array($application_mas->applied_courses->first()->course->course_type_id, [11]))
        <tr>
            <td class="padding-xs">Whether passed/appeared the qualification exam?:</td>
            <td class="padding-xs bold">
                {{$application_mas->application_academic->passed_or_appeared_qualified_exam ?? "NA"}}</td>
            <td class="padding-xs">Whether applying for full time/part time?:</td>
            <td class="padding-xs bold">
                @if($application_mas->application_academic)
                    @if($application_mas->application_academic->is_full_time && $application_mas->application_academic->is_full_time)
                    Full Time
                    @else
                    Part time
                    @endif
                @else
                    NA
                @endif
                {{$application_mas->academic_application->part_time_details ?? ""}}</td>
        </tr>
        <tr>
            <td class="padding-xs"> Experience related to:</td>
            <td class="padding-xs bold">{{$application_mas->application_academic->academic_experience ?? "NA"}}</td>
            <td class="padding-xs">Publication Details (if any) :</td>
            <td class="padding-xs bold">{{$application_mas->application_academic->publication_details ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Statement of Purpose (SOP) of research </td>
            <td class="padding-xs bold">{{$application_mas->application_academic->statement_of_purpose ?? "NA"}}</td>
            <td class="padding-xs"></td>
            <td class="padding-xs bold"></td>
        </tr>
        @elseif(isMasterInDesign($application_mas)) 
            <tr>
                <td class="padding-xs">CEED score </td>
                <td class="padding-xs bold">{{$application_mas->application_academic->ceed_score ?? "NA"}}</td>
                <td class="padding-xs"></td>
                <td class="padding-xs bold"></td>
            </tr>
        @endif
        <tr>
            <td class="padding-xs">Correspondence&nbsp;Add.</td>
            <td class="padding-xs bold">
                {{-- {!!getCorrespondencePermanentAddress($application_mas) ?? "NA"!!} --}}
                @if(getCorrespondencePermanentAddress($application_mas))
                    C/O: {{$application_mas->correspondence_co ?? "NA"}}</br>
                    House No:  {{$application_mas->correspondence_house_no ?? "NA"}} </br> 
                    Street Name / Locality:  {{$application_mas->correspondence_street_locality ?? "NA"}} </br> 
                    Vill/Town: {{$application_mas->correspondence_village_town ?? "NA"}}</br>
                    {{-- PS: {{$application_mas->correspondence_ps ?? "NA"}} </br> --}}
                    PO: {{$application_mas->correspondence_po ?? "NA"}} </br>
                    Dist: {{$application_mas->cor_district->district_name ?? $application_mas->correspondence_district}} </br>
                    State: {{$application_mas->cor_state->name ?? $application_mas->correspondence_state}} - {{$application_mas->correspondence_pin ?? "NA"}}
                @else
                    NA
                @endif
            </td>
            <td class="padding-xs">Permanent&nbsp;Add.</td>
            <td class="padding-xs bold">
                @if(getApplicationPermanentAddress($application_mas))



                
                    C/O:  {{$application_mas->permanent_co ?? "NA"}}</br>
                    House No:  {{$application_mas->permanent_house_no ?? "NA"}} </br> 
                    Street Name / Locality:  {{$application_mas->permanent_street_locality ?? "NA"}} </br> 
                    Vill/Town:  {{$application_mas->permanent_village_town ?? "NA"}} </br> 
                    {{-- PS: {{$application_mas->permanent_ps ?? "NA"}} </br> --}}
                    PO: {{$application_mas->permanent_po ?? "NA"}} </br>
                    Dist: {{$application_mas->per_district->district_name ?? $application_mas->permanent_district}} </br>
                    State: {{$application_mas->per_state->name ?? $application_mas->permanent_state }} -  {{$application_mas->permanent_pin ?? "NA" }}
                @else
                    NA
                @endif
            </td>
        </tr>
        {{-- end of personal information --}}
        {{-- parents information --}}
        @isset($download)
        </tbody>
    </table>
    {{-- <div class="page-break-after" style="page-break-before: always;"></div> --}}
    <h4>Parent / Guardian Details</h4>
    <table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
        <tbody>
        @else
        <tr>
            <td class="padding-xs bold" colspan="4"><strong>Parent / Guardian Details</strong></td>
        </tr>
        @endisset
        <tr>
            <td class="padding-xs">Guardian's Name</td>
            <td class="padding-xs bold">{{$application_mas->guardian_name ?? "NA"}}</td>
            <td class="padding-xs">Occupation</td>
            <td class="padding-xs bold">{{$application_mas->guardian_occupation ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Mobile No</td>
            <td class="padding-xs bold">{{$application_mas->guardian_phone ?? "NA"}}</td>
            <td class="padding-xs">Email</td>
            <td class="padding-xs bold">{{$application_mas->guardian_email ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Father's Name</td>
            <td class="padding-xs bold">{{$application_mas->father_name ?? "NA"}}</td>
            <td class="padding-xs">Occupation</td>
            <td class="padding-xs bold">{{$application_mas->father_occupation ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Father's Qualification</td>
            <td class="padding-xs bold" colspan=3>{{$application_mas->fatherQualification->name ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Mobile No</td>
            <td class="padding-xs bold">{{$application_mas->father_mobile ?? "NA"}}</td>
            <td class="padding-xs">Email</td>
            <td class="padding-xs bold">{{$application_mas->father_email ?? "NA"}}</td>
        </tr>
       {{--  <tr>
            <td class="padding-xs">Annual Income</td>
            <td class="padding-xs bold">{{$application_mas->father_income_range ? $application_mas->father_income_range->min."-".$application_mas->father_income_range->max: "NA"}}</td>
            <td colspan="2"></td>
        </tr> --}}
        
        <tr>
            <td class="padding-xs">Mother's Name</td>
            <td class="padding-xs bold">{{$application_mas->mother_name ?? "NA"}}</td>
            <td class="padding-xs">Occupation</td>
            <td class="padding-xs bold">{{$application_mas->mother_occupation ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Mothers's Qualification</td>
            <td class="padding-xs bold" colspan=3>{{$application_mas->motherQualification->name ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Mobile No</td>
            <td class="padding-xs bold">{{$application_mas->mother_mobile ?? "NA"}}</td>
            <td class="padding-xs">Email</td>
            <td class="padding-xs bold">{{$application_mas->mother_email ?? "NA"}}</td>
        </tr>
       {{-- <tr>
            <td class="padding-xs">Annual Income</td>
            <td class="padding-xs bold">{{$application_mas->mother_income_range ? $application_mas->mother_income_range->min."-".$application_mas->mother_income_range->max: "NA"}}</td>
            <td colspan="2"></td>
        </tr>--}}
        <tr>
            <td class="padding-xs">Family  Income</td>
            <td class="padding-xs bold">{{$application_mas->family_income_range ? ($application_mas->family_income_range->min."-".$application_mas->family_income_range->max): "NA"}}</td>
            <td class="padding-xs">PAN No</td>
            <td class="padding-xs bold">{{$application_mas->application_academic ? ($application_mas->application_academic->pan_no ?? "NA") : "NA"}}</td>

        </tr>
        {{-- end of parents information --}}
    </tbody>
</table>
Banking delatins
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <tbody>
        <tr>
            <td class="padding-xs bold" colspan="4"><strong>Banking Details</strong></td>
        </tr>
        <tr>
            <td class="padding-xs">Account Number</td>
            <td class="padding-xs bold">
               {{$application_mas->bank_ac_no??"NA"}}
            </td>
            <td class="padding-xs ">Bank Name</td>
            <th class="padding-xs bold"> 
                {{$application_mas->bank_name??"NA"}}
            </th>
        </tr>
        <tr>
            <td class="padding-xs ">Branch Name</td>
            <td class="padding-xs bold">{{$application_mas->branch_name}}</td>
            <td class="padding-xs">IFSC CODE</td>
            <td class="padding-xs bold">{{$application_mas->ifsc_code?? "NA"}}</td>
        </tr>
        
        <tr>
            <td class="padding-xs ">Mobile </td>
            <td class="padding-xs bold">{{$application_mas->bank_reg_mobile_no?? "NA"}}</td>
            <td class="padding-xs ">A/C Holder Name </td>
            <td class="padding-xs bold">{{$application_mas->account_holder_name?? "NA"}}</td>

        </tr>
        </tbody>
    </table>
{{--End Banking details--}}
<h4>Academic Details</h4>
@php
    if($application->applicationMaster && $application->application_no!=null){
        $academics = $application->applicationMaster->application_academic;
    }else{
        $academics = $application->application_academic;
    }
    // $academics = $application->application_academic;    
@endphp
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <thead>
        <tr>
            <th>Name of Exam</th>
            <th>Board / University / Institute</th>
            <th>Year of Passing</th>
            <th>Class/Grade/ Division</th>
            <th style="max-width: 80px;">Subjects taken (including Honours)</th>
            <th>Total Marks</th>
            <th>Marks Obtained</th>
            <th>SGPA/CGPA</th>
            <th>Percentage of marks</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>10<sup>th</sup></th>
            <td>{{$academics->academic_10_board ?? "NA"}}</td>
            <td>{{$academics->academic_10_year ?? "NA"}}</td>
            <td>{{$academics->academic_10_grade ?? "NA"}}</td>
            <td style="max-width: 80px;">{{$academics->academic_10_subject ?? "NA"}}</td>
            <td>{{$academics->academic_10_total_mark ?? "NA"}}</td>
            <td>{{$academics->academic_10_mark_obtained ?? "NA"}}</td>
            <td>{{$academics->academic_10_cgpa ?? "NA"}}</td>
            <td>{{$academics->academic_10_percentage ?? "NA"}}</td>
            <td>{{$academics->academic_10_remarks ?? "NA"}}</td>
        </tr>
        <tr>
            <th>12<sup>th</sup></th>
            <td>{{$academics->academic_12_board ?? "NA"}} <br/>Stream : {{$academics->academic_12_stream ?? 'NA'}}</td>
            <td>{{$academics->academic_12_year ?? "NA"}}</td>
            <td>{{$academics->academic_12_grade ?? "NA"}}</td>
            <td style="max-width: 80px;">{{$academics->academic_12_subject ?? "NA"}}</td>
            <td>{{$academics->academic_12_total_mark ?? "NA"}}</td>
            <td>{{$academics->academic_12_mark_obtained ?? "NA"}}</td>
            <td>{{$academics->academic_12_cgpa ?? "NA"}}</td>
            <td>{{$academics->academic_12_percentage ?? "NA"}}</td>
            <td>{{$academics->academic_12_remarks ?? "NA"}}</td>
        </tr>
        <tr>
            <th>Bachelor Degree @if($academics->acadmeic_graduation_major ?? null) * @endif<br> {{$academics->academic_bachelor_degree ?? ""}}</th>
            <td>{{$academics->academic_graduation_board ?? "NA"}}</td>
            <td>{{$academics->academic_graduation_year ?? "NA"}}</td>
            <td>{{$academics->academic_graduation_grade ?? "NA"}}</td>
            <td style="max-width: 80px;">{{$academics->academic_graduation_subject ?? "NA"}} @if($academics->acadmeic_graduation_major ?? null) {!!$academics->acadmeic_graduation_major ?? "" ."<small> (major)</small>"!!} @endif</td>
            <td>{{$academics->academic_graduation_total_mark ?? "NA"}}</td>
            <td>{{$academics->academic_graduation_mark_obtained ?? "NA"}}</td>
            <td>{{$academics->academic_graduation_cgpa ?? "NA"}}</td>
            <td>{{$academics->academic_graduation_percentage ?? "NA"}}</td>
            <td>{{$academics->academic_graduation_remarks ?? "NA"}}</td>
        </tr>
        <tr>
            <th>Post-Graduate Degree<br> {{$academics->academic_post_graduation_degree ?? ""}}</th>
            <td>{{$academics->academic_post_graduation_board ?? "NA"}}</td>
            <td>{{$academics->academic_post_graduation_year ?? "NA"}}</td>
            <td>{{$academics->academic_post_graduation_grade ?? "NA"}}</td>
            <td style="max-width: 80px;">{{$academics->academic_post_graduation_subject ?? "NA"}}</td>
            <td>{{$academics->academic_post_graduation_total_mark ?? "NA"}}</td>
            <td>{{$academics->academic_post_graduation_mark_obtained ?? "NA"}}</td>
            <td>{{$academics->academic_post_graduation_cgpa ?? "NA"}}</td>
            <td>{{$academics->academic_post_graduation_percentage ?? "NA"}}</td>
            <td>{{$academics->academic_post_graduation_remarks ?? "NA"}}</td>
        </tr>
        <tr>
            <th>Engg. Diploma</th>
            <td>{{$academics->academic_diploma_board ?? "NA"}}</td>
            <td>{{$academics->academic_diploma_year ?? "NA"}}</td>
            <td>{{$academics->academic_diploma_grade ?? "NA"}}</td>
            <td style="max-width: 80px;">{{$academics->academic_diploma_subject ?? "NA"}}</td>
            <td>{{$academics->academic_diploma_total_mark ?? "NA"}}</td>
            <td>{{$academics->academic_diploma_mark_obtained ?? "NA"}}</td>
            <td>{{$academics->academic_diploma_cgpa ?? "NA"}}</td>
            <td>{{$academics->academic_diploma_percentage ?? "NA"}}</td>
            <td>{{$academics->academic_diploma_remarks ?? "NA"}}</td>
        </tr>
        @if($application->other_qualifications()->count())
            @foreach ( $application->other_qualifications as $other_application )
                <tr>
                    <th>Others* ({{$other_application->exam_name}})</th>
                    <td>{{$other_application->board_name ?? "NA"}}</td>
                    <td>{{$other_application->passing_year ?? "NA"}}</td>
                    <td>{{$other_application->class_grade ?? "NA"}}</td>
                    <td style="max-width: 80px;">{{$other_application->subjects_taken ?? "NA"}}</td>
                    <td>{{$other_application->total_mark ?? "NA"}}</td>
                    <td>{{$other_application->mark_obtained ?? "NA"}}</td>
                    <td>{{$other_application->cgpa ?? "NA"}}</td>
                    <td>{{$other_application->marks_percentage ?? "NA"}}</td>
                    <td>{{$other_application->remarks ?? "NA"}}</td>
                </tr>                                        
            @endforeach
        @endif
    </tbody>
</table>

@if(/* $application->is_mba!=1 && */ $application->exam_through=='CUET' || $application->exam_through=='CHINESE')
@php
    $academics_cuet = $application->application_academic;
@endphp
<h4>CUET Exam Details</h4>
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <thead>
        <tr>
            <th>CUET Roll No</th>
            <th>CUET Form No</th>
            <th>Year</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$academics_cuet->cuet_roll_no}}</td>
            <td>{{$academics_cuet->cuet_form_no}}</td>
            <td>{{$academics_cuet->cuet_year}}</td>
        </tr>
    </tbody>
</table>
<h4>CUET Exam Details With Subjects and Marks</h4>
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <thead>
    <tr>
       <th>sl No</th>
       <th>Subject Code</th>
       <th>CUET Total Marks </th>
       {{-- <th>Percentile Score</th> --}}
    </tr>
    </thead>
    <tbody>
       @forelse ($application->cuet_exam_details as $key=>$cuet)
       <tr>
           <th>{{++$key}}</th>
           <th>{{$cuet->subjects}}</th>
           <th>{{$cuet->marks}}</th>
           {{-- <th>{{$cuet->percentile}}</th> --}}
        </tr>
       @empty
       <tr>
           <th colspan="4">Not Available</th>
        </tr>
       @endforelse
    </tbody>
</table>
@endif
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <thead>
    <tr>
       <th>Medalist in any National and International Sports events?</th>
       <th>{{$academics->is_sport_represented == 1 ? "Yes" : "No"}}</th>
    </tr>
    @if($academics->is_sport_represented == 1)
    <tr>
        <th>Sport Name :{{$academics->sport_played}}</th>
        <th>Medal Type: {{$academics->medel_type}}</th>
    </tr>
    @endif
    <tr>
        <th>Academic distinction/Medals/Prizes,if any</th>
        <th>{{$academics->is_academic_prizes == 1 ? "Yes" : "No"}}</th>
    </tr>
    <tr>
        <th>GAT B Score</th>
        <th>{{$academics->gat_b_score??'NA'}}</th>
    </tr>
    </thead>
</table>



{{--Qualification Mark in Betch--}}
{{-- @if(($application->is_btech==1 || isIntegratedProgramm($application)) && !isIntegratedMCOM($application))
@php
    $academics = $application->application_academic;    
@endphp
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <tbody>
        <tr>
            <td class="padding-xs bold" colspan="4"><strong>Qualification Marks</strong></td>
        </tr>
        <tr>
            <th>Subject</th>
            <th>Total Mark</th>
            <th>Grade/Mark Obtained</th>
            <th>Percentage</th>
        </tr>
        
            <tr>
                <th>English 10<sup>th</sup>Marks</th>
                <td class="padding-xs ">{{$academics->english_mark_10_total_mark ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->english_mark_10_grade ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->english_mark_10 ?? "NA"}}</td>
            </tr>
        
            <tr>
                <th>Physics 10+2 Marks</th>
                <td class="padding-xs ">{{$academics->physics_total_mark ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->physics_grade ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->physics_mark ?? "NA"}}</td>
            </tr>
            <tr>
                <th>Chemistry 10+2 Marks</th>
                <td class="padding-xs ">{{$academics->chemistry_total_mark ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->chemistry_grade ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->chemistry_mark ?? "NA"}}</td>

            </tr>
            <tr>
                <th>Mathematics 10+2 Marks</th>
                <td class="padding-xs">{{$academics->mathematics_total_mark ?? "NA"}}</td>
                <td class="padding-xs">{{$academics->mathematics_grade ?? "NA"}}</td>
                <td class="padding-xs">{{$academics->mathematics_mark ?? "NA"}}</td>
            </tr>
        
        <tr>
            <th>English 10+2 Marks</th>
            <td class="padding-xs ">{{$academics->english_total_mark ?? "NA"}}</td>
            <td class="padding-xs ">{{$academics->english_grade ?? "NA"}}</td>
            <td class="padding-xs ">{{$academics->english_mark ?? "NA"}}</td>
        </tr>
       
            <tr>
                <th>Statistics 10+2 Mark</th>
                <td class="padding-xs ">{{$academics->statistics_total_mark ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->statistics_grade ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->statistics_mark ?? "NA"}}</td>
            </tr>
            <tr>
                <th>Biology 10+2 Marks</th>
                <td class="padding-xs ">{{$academics->biology_total_mark ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->biology_grade ?? "NA"}}</td>
                <td class="padding-xs ">{{$academics->biology_mark ?? "NA"}}</td>

            </tr>
       
    </tbody>
</table> --}}
{{--End Qualification Mark in Betch--}}
{{-- @endif --}}
@php
$is_phd = false;

foreach($application->applied_courses as $a_course){
    $is_phd = isPHDCourse($a_course->course_id);
    if($is_phd){
        break;
    }
}
@endphp
@if($is_phd)
<table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
    <tbody>
        <tr>
            <td class="padding-xs">Qualified national level test</td>
            <td class="padding-xs bold">{{$application->application_academic->qualified_national_level_test ?? "NA"}}</td>
            <td class="padding-xs">Qualified national level test mark</td>
            <td class="padding-xs bold">{{$application->application_academic->qualified_national_level_test_mark ?? "NA"}}</td>
        </tr>
        <tr>
            <td class="padding-xs">Proposed area of_research</td>
            <td class="padding-xs bold">{{
                ($application->application_academic && $application->application_academic->proposed_area_of_research) ?
                implode(",", $application->application_academic->proposed_area_of_research) :"NA"}}
            </td>
            <td class="padding-xs"></td>
            <td class="padding-xs bold"></td>
        </tr>
    </tbody>
</table>
@endif

@if($application->work_experiences()->count())
<h4>Work Experiences</h4>
    <table class="table table-bordered">
        <tr>
            <th>Organization</th>
            <th>Designation</th>
            <th>From</th>
            <th>To</th>
            <th>Details</th>
        </tr>  
        @foreach ( $application->work_experiences as $exam )
        <tr>
            <td>{{$exam->organization}}</td>
            <td>{{$exam->designation ?? "NA"}}</td>
            <td>{{$exam->from ?? "NA"}}</td>
            <td>{{$exam->to ?? "NA"}}</td>
            <td>{{$exam->details ?? "NA"}}</td>
        </tr>                                        
        @endforeach
    </table>
@endif

@if($application->extraExamDetails()->count())
<h4>CAT/MAT/ATMA/XAT Details</h4>
    <table class="table table-bordered">
        <tr>
            <th>Name of Exam</th>
            <th>Date of Exam</th>
            <th>Registration No</th>
            <th>Score Obtained</th>
        </tr>  
        @foreach ( $application->extraExamDetails as $exam )
        <tr>
            <td>{{$exam->name_of_the_exam}}</td>
            <td>{{$exam->date_of_exam ?? "NA"}}</td>
            <td>{{$exam->registration_no ?? "NA"}}</td>
            <td>{{$exam->score_obtained ?? "NA"}}</td>
        </tr>                                        
        @endforeach
    </table>
@endif
