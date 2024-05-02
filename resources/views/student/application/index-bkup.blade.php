@extends('student.layouts.auth')
@section("css")
<style>
    .text-white {
        color: white;
    }
    .bg-danger {
        background-color: rgba(207, 25, 25, 0.856);
        padding: 5px;
    }
</style>
@endsection
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('student.application.congratulation-message')
            
            <div class="panel panel-default">
                <div class="panel-heading">Application 
                    {{-- @if(!getActiveSessionApplication()) --}}
                    <span class="pull-right"><a href="{{route("student.application.create")}}"><button
                    class="btn btn-sm btn-primary"> Apply New Application</button></a></span>
                    {{-- @endif --}}
                </div>
                <div class="panel-body">
                <div class="alert alert-danger" role="alert">
                    <div class="alert alert-danger" role="alert">
                        <strong><span class="blink">Note :</span> Eligible applicants can proceed to payment after completion of the following steps / stages. Partial saving / uploading of documents will not be eligible for payments.</strong><br/>

                        {{-- 1. Filling up the Application Form : Upload Category Certificate(s) / NOC (if applicable) / Score Cards (if applicable) / Undertaking Documents.<br />
                        2. Complete the Eligibility Check.<br />
                        3. Upload Documents (Compulsory) : Upload all the educational related documents (Marksheet/Pass Certificate/Any other documents if applicable).<br />    --}}
                    </div>
{{--                    <div class="alert alert-danger" role="alert">
                        - Kindly check your application details before proceeding for payment . Once the payment process is completed, you won't be allowed to edit the application again.<br />
                        - Undertaking of Category Certificate is compulsory for all programmes <strong><i>(not required for General candidates)</i></strong><br />
                        - Undertaking for PRC is also compulsory for B. Tech./ M.Sc. in MBBT <strong><i>(under NE Quota) </i></strong>
                    </div>
--}}
                    {{-- <p class="text-white bg-danger">You are requested to thoroughly check the Academic Details, Attachments and Check Eligibility Criteria details. Incorrect information is subject to Application Form rejection. <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/extra_notice.jpg")}}">Click here for more details</a></p> --}}
                    {{-- <h4 class="text-white bg-danger">You are requested to update semester wise marks under Academic Details in Step 3. Use "Add New" button to add semester details.</h4> --}}
                    {{--                     <h4 class="text-white bg-danger">Dear applicant for B.Tech programme under NE Quota at Tezpur University, 2021. Send the document(s) through the attached Google form (that you did not upload during TU application) on or before 27.09.2021 (Monday). Without the mandatory documents, eg, PRC, 10+2 marksheet, Category certificate, etc. you will not be called for counseling. {Pls, ignore this form if all documents are uploaded already} For any query Cal at +91 99544 49473 or Email at "bssc2021@tezu.ac.in".
                    Google form for collecting missing documents from students
                    <a href="https://forms.gle/WFduHpZdy2aWBU367" target="_blank">https://forms.gle/WFduHpZdy2aWBU367</a></h4>  --}}      

                     {{--<h4 class="text-white bg-danger"><u>URGENT:: B.TECH PROGRAMME APPLICANTS 2021</u><br /> 
                    The admission committee are not able to download the JEE (Main) CRL for the following Students because of their incomplete applications. Students were informed about this few times. If you are interested in taking part in the admission process, send an email at bssc2021@tezu.ac.in by sending a) DOB b) JEE Form no c) JEE Score card latest by tomorrow, i.e, 28/09/2021. No requests beyond this time will be entertained. <br /><u>Subject line of the email should be “CRL SUBMISSION_YOUR NAME_TU application no”.</u> </h4> --}} 

{{--                    <h4 class="text-white bg-danger"><u>B.TECH PROGRAMME APPLICANTS 2021</u><br /> 
                    B.Tech applicants at TU '21: List-A of shortlisted candidates and Guideline is uploaded in Web on 01-10-2021 (Morning). Click here for <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/short_listed-candidates_counseling-btech.pdf")}}">Short Listed Candidates</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/info_guidelines_admission-btech.pdf")}}">Guideline</a></h4>
--}}
{{--                    <h4 class="text-white bg-danger"><u>Kind attention B.TECH PROGRAMME APPLICANTS 2021</u><br /> 
                    Time Slot and Admission procedure is uploaded in the News & Events section. <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/time_slot-btech-admn.pdf")}}">Click here to view</a> </h4>                    
--}}                                
                    {{-- <p class="text-white bg-danger">Applicants who have completed Pre final exam are requested to enter the marks up to the last completed semester. For more information please check the notification regarding  <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/addendum_cum_clarification.pdf")}}">Addendum cum Clarification in the News and Updates section.</a></p> --}}
                   @include("common.application.index")
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    @include('common.application.ajax-files-view')
@endsection
