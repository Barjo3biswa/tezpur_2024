@extends('student.layouts.auth')

@section('content')
@section("css")
<style>
    @import url(https://fonts.googleapis.com/css?family=Merriweather+Sans);

    .breadcrumb {
        /*centering*/
        display: inline-block;
        box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.35);
        overflow: hidden;
        border-radius: 5px;
        /*Lets add the numbers for each link using CSS counters. flag is the name of the counter. to be defined using counter-reset in the parent element of the links*/
        counter-reset: flag;
    }

    .breadcrumb a {
        text-decoration: none;
        outline: none;
        display: block;
        float: left;
        font-size: 12px;
        line-height: 36px;
        color: white;
        /*need more margin on the left of links to accomodate the numbers*/
        padding: 0 10px 0 60px;
        background: #666;
        background: linear-gradient(#666, #333);
        position: relative;
    }

    /*since the first link does not have a triangle before it we can reduce the left padding to make it look consistent with other links*/
    .breadcrumb a:first-child {
        padding-left: 46px;
        border-radius: 5px 0 0 5px;
        /*to match with the parent's radius*/
    }

    .breadcrumb a:first-child:before {
        left: 14px;
    }

    .breadcrumb a:last-child {
        border-radius: 0 5px 5px 0;
        /*this was to prevent glitches on hover*/
        padding-right: 20px;
    }

    /*hover/active styles*/
    .breadcrumb a.active,
    .breadcrumb a:hover {
        background: #333;
        background: linear-gradient(#333, #000);
    }

    .breadcrumb a.active:after,
    .breadcrumb a:hover:after {
        background: #333;
        background: linear-gradient(135deg, #333, #000);
    }

    /*adding the arrows for the breadcrumbs using rotated pseudo elements*/
    .breadcrumb a:after {
        content: '';
        position: absolute;
        top: 0;
        right: -18px;
        /*half of square's length*/
        /*same dimension as the line-height of .breadcrumb a */
        width: 36px;
        height: 36px;
        /*as you see the rotated square takes a larger height. which makes it tough to position it properly. So we are going to scale it down so that the diagonals become equal to the line-height of the link. We scale it to 70.7% because if square's: 
	length = 1; diagonal = (1^2 + 1^2)^0.5 = 1.414 (pythagoras theorem)
	if diagonal required = 1; length = 1/1.414 = 0.707*/
        transform: scale(0.707) rotate(45deg);
        /*we need to prevent the arrows from getting buried under the next link*/
        z-index: 1;
        /*background same as links but the gradient will be rotated to compensate with the transform applied*/
        background: #666;
        background: linear-gradient(135deg, #666, #333);
        /*stylish arrow design using box shadow*/
        box-shadow:
            2px -2px 0 2px rgba(0, 0, 0, 0.4),
            3px -3px 0 2px rgba(255, 255, 255, 0.1);
        /*
		5px - for rounded arrows and 
		50px - to prevent hover glitches on the border created using shadows*/
        border-radius: 0 5px 0 50px;
    }

    /*we dont need an arrow after the last link*/
    .breadcrumb a:last-child:after {
        content: none;
    }

    /*we will use the :before element to show numbers*/
    .breadcrumb a:before {
        content: counter(flag);
        counter-increment: flag;
        /*some styles now*/
        border-radius: 100%;
        width: 20px;
        height: 20px;
        line-height: 18px;
        margin: 8px 0;
        position: absolute;
        top: 0;
        left: 30px;
        background: #444;
        background: linear-gradient(#444, #222);
        font-weight: bold;
        padding-left: 6px;
    }


    .flat a,
    .flat a:after {
        background: white !important;
        color: black !important;
        transition: all 0.5s !important;
    }

    .flat a:before {
        background: white !important;
        box-shadow: 0 0 0 1px #ccc !important;
    }

    .flat a:hover,
    .flat a.active,
    .flat a:hover:after,
    .flat a.active:after {
        background: #9EEB62 !important;
    }
    .text-white {
        color: white;
    }
    .bg-danger {
        background-color: rgba(207, 25, 25, 0.856);
        padding: 5px;
    }
</style>
@endsection
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading">Welcome to Tezpur University 2024
                    <span class="pull-right">
                        
                        @if(Auth::User()->program_name=='PG' || Auth::User()->program_name=='MDES')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('TUEE')])}}" onclick="return confirm('Are you sure you want to continue with TUEE?');"><button class="btn btn-sm btn-danger"> Apply New Application (Through TUEE {{Auth::User()->program_name=='PG'?'/GATE-M.Tech':''}} )</button></a>
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('CUET')])}}" onclick="return confirm('Are you sure you want to continue with CUET?');"><button class="btn btn-sm btn-primary"> Apply New Application (Through CUET)</button></a>
                            @if (Auth::User()->program_name=='MDES')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('CEED')])}}" onclick="return confirm('Are you sure you want to continue with TUEE?');"><button class="btn btn-sm btn-danger"> Apply New Application (Through CEED/GATE/DAT )</button></a>
                            @endif
                        @elseif(Auth::User()->program_name=='PHD' || Auth::User()->program_name=='LATERAL' || Auth::User()->program_name=='FOREIGN' || Auth::User()->program_name=='PHDPROF')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('TUEE')])}}"><button class="btn btn-sm btn-primary"> Apply New Application through(TUEE)</button></a>
                        @elseif(Auth::User()->program_name=='UG')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('CUET')])}}"><button class="btn btn-sm btn-primary"> Apply New Application through</button></a>
                        @elseif(Auth::User()->program_name=='BDES')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('TUEE')])}}"><button class="btn btn-sm btn-primary"> Apply New Application through(TUEE)</button></a>
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('UCEED')])}}"><button class="btn btn-sm btn-primary"> Apply New Application through(UCEED)</button></a>
                        @elseif(Auth::User()->program_name=='CHINESE')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('CHINESE')])}}"><button class="btn btn-sm btn-primary"> Apply New Application</button></a>
                        @elseif(Auth::User()->program_name=='BTECH')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('JEE')])}}"><button class="btn btn-sm btn-primary"> Apply New Application</button></a>
                        @elseif(Auth::User()->program_name=='MBBT')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('MBBT')])}}"><button class="btn btn-sm btn-primary"> Apply New Application</button></a>
                        @elseif(Auth::User()->program_name=='VISVES')
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('Visvesvaraya')])}}"><button class="btn btn-sm btn-primary"> Apply New Application</button></a>
                        @else
                            <a href="{{route("student.application.create",['id'=>Crypt::encrypt('TUEE')])}}"><button class="btn btn-sm btn-primary"> Apply New Application</button></a>
                        @endif
                    </span>
            </div>

                <div class="panel-body">
                    <p>You are logged in!</p>
                    @php
                        $flag=0;
                        foreach ($applications as $app){
                            if (count($app->merit_list)>0){
                                $flag=1;
                            }        
                        }
                    @endphp

                    @if($flag==1)
                    <div class="alert alert-danger" role="alert">
                        <strong><span class="blink">Note :</span>Please use latest version of the browser (Mozilla Firefox, Chrome, Microsoft Edge, etc.) .</strong><br/>                                        
                        <strong><span class="blink">Note :</span> You will be notified for Reporting / Admission process through email and sms to your registered email and mobile number.</strong><br/>
                        <strong><span class="blink">Note :</span>Please follow the steps for REPORTING. </strong><br/>
                        <strong><span class="blink">STEP 1 :</span>Click "Proceed for Reporting" </strong><br/>
                        <strong><span class="blink">STEP 2 :</span>Click "Request Accepted" if you want to report. / Click "Request Declined" if you do not want to report. </strong><br/>
                        <strong><span class="blink">Note :</span> Reporting doesn't mean seat confirmation. Seat confirmation is subject to seat availability & payment</strong><br/>

                        <br/>

                        <strong><span class="blink">Note :</span>Please follow the steps to Admission Process </strong><br/>
                        <strong><span class="blink">STEP 1 :</span>Click "Proceed to Admission Process" </strong><br/>
                        <strong><span class="blink">STEP 2 :</span>Click "Proceed" to continue the admission process. / Click "Decline Seat" if you do not want to proceed with the admission process. </strong><br/>
                        <strong><span class="blink">STEP 3 :</span>Click "Make Payment" to continue the payment process. / Click "Decline Seat" if you do not want to proceed with the admission process. </strong><br/>
                    </div>
                    @else
                        @if(Auth::User()->program_name=="UG")
                        <div class="alert alert-danger" role="alert"> 
                            <strong>Note:</strong> - Attention CUET-UG applicants.
                You are requested to enter exact Percentile Score And Normalised Score as per your NTA Score Card.                    
                                {{-- <strong><span class="blink">Note:</span> Applicants Can Proceed To Payment After declaring CUET results.(Closing date is subject for extension.)</strong><br/> --}}
                                
                        </div>
                        @endif

                        <div class="alert alert-danger" role="alert">
                                <strong><span class="blink">Note :</span> Applicants can proceed to payment after completion of all the stages. Incomplete application will not be eligible for payments.</strong><br/>
                        
                            <strong><span class="blink">Note :</span> Applicants are requested to upload document related to conversion formula for CGPA/SGPA separately on the appropriate fields in STEP-4.  </strong><br/>
                            {{-- @if(Auth::user()->program_name=="PHD")
                            <strong><span class="blink">Note :</span> Candidates qualified in the UGC NET-JRF/ UGC-CSIR NET-JRF, UGC/CSIR NET (LS)/ SLET (LS), GATE 
                                and M.Phil degree holders need not appear in the written test; however, such candidates shall appear in the personal interview</strong><br/>
                            @endif --}}
                        </div>
                    <div class="alert alert-danger" role="alert">
                            - Kindly check your application details before proceeding for payment . Once the payment process is completed, you won't be allowed to edit the application again.<br />
                            - Undertaking of Category Certificate is compulsory for all programmes <strong><i>(not required for General candidates)</i></strong><br />
                            - Undertaking for PRC is also compulsory for B. Tech./ M.Sc. in MBBT <strong><i>(under NE Quota) </i></strong>
                        </div>

                        {{-- <p class="text-white bg-danger">You are requested to thoroughly check the Academic Details, Attachments and Check Eligibility Criteria details. Incorrect information is subject to Application Form rejection. <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/extra_notice.jpg")}}">Click here for more details</a></p> --}}
                        
                        {{-- <h4 class="text-white bg-danger">Kindly note that applicants applying for B.Ed, Master degree, M.Tech and Ph.D programmes are requested to update semester wise marks under Academic Details in Step 3. Use "Add New" button to add semester wise details.
                        </h4> --}}
                        
                        {{-- <h4 class="text-white bg-danger">Kind Attention B.Tech Applicants : We have enabled the editing of application forms till 09-09-2021, applicants are allowed to do the necessary changes in the form (if any). Kindly ignore/ skip this notification if there are no changes to be made.</h4> --}}

                        {{-- <h4 class="text-white bg-danger">Dear applicant for B.Tech programme under NE Quota at Tezpur University, 2021. Send the document(s) through the attached Google form (that you did not upload during TU application) on or before 27.09.2021 (Monday). Without the mandatory documents, eg, PRC, 10+2 marksheet, Category certificate, etc. you will not be called for counseling. {Pls, ignore this form if all documents are uploaded already} For any query Cal at +91 99544 49473 or Email at "bssc2021@tezu.ac.in".
                        Google form for collecting missing documents from students
                        <a href="https://forms.gle/WFduHpZdy2aWBU367" target="_blank">https://forms.gle/WFduHpZdy2aWBU367</a></h4> --}}

                        {{--<h4 class="text-white bg-danger"><u>URGENT:: B.TECH PROGRAMME APPLICANTS 2021</u><br /> 
                        The admission committee are not able to download the JEE (Main) CRL for the following Students because of their incomplete applications. Students were informed about this few times. If you are interested in taking part in the admission process, send an email at bssc2021@tezu.ac.in by sending a) DOB b) JEE Form no c) JEE Score card latest by tomorrow, i.e, 28/09/2021. No requests beyond this time will be entertained. <br /><u>Subject line of the email should be “CRL SUBMISSION_YOUR NAME_TU application no”.</u> </h4>  --}}
                        {{--
                        <h4 class="text-white bg-danger"><u>B.TECH PROGRAMME APPLICANTS 2021</u><br /> 
                        B.Tech applicants at TU '21: List-A of shortlisted candidates and Guideline is uploaded in Web on 01-10-2021 (Morning). Click here for <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/short_listed-candidates_counseling-btech.pdf")}}">Short Listed Candidates</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/info_guidelines_admission-btech.pdf")}}">Guideline</a></h4>

                        <h4 class="text-white bg-danger"><u>Kind attention B.TECH PROGRAMME APPLICANTS 2021</u><br /> 
                        Time Slot and Admission procedure is uploaded in the News & Events section. <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/time_slot-btech-admn.pdf")}}">Click here to view</a> </h4>
                        --}}
                        {{-- <p class="text-white bg-danger">Applicants who have completed Pre final exam are requested to enter the marks up to the last completed semester. For more information please check the notification regarding  <a class="btn btn-info btn-sm blink" target="_blank" href="{{asset("notifications/2021/addendum_cum_clarification.pdf")}}">Addendum cum Clarification in the News and Updates section.</a></p> --}}
                    @endif
                    @include('common.application.index')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Button trigger modal -->
  {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
  </button> --}}
  
  <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Modal title</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="reason">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
{{-- @include('common.application.ajax-files-view') --}}
<script>
    function alertFunction(){
        alert("Before proceeding to application process please verify your email")
        
    }

    // function ViewReason(title,reason){
    //     if(title=='Hold'){
    //        $('#exampleModalLabel').empty();
    //        $('#exampleModalLabel').append('Reason Of Holding the Application');
    //     }else{
    //        $('#exampleModalLabel').empty();
    //        $('#exampleModalLabel').append('Reason Of Rejection the Application');
    //     }
    //     $('#reason').empty();
    //     $('#reason').append(reason);
       
    // }
</script>
<script>
    function ViewReason(title, reason) {

        $.ajax({
				url: '{{ route(get_route_guard() . '.reason') }}',
				type: 'post',
				data: {
					'course_id': reason,
					'_token': "{{ csrf_token() }}"
				},
				success: function(response) {
                    $('#reason').empty();
					// console.log(response.data.reject_reason);
                    if (title == 'Hold') {
                        $('#exampleModalLabel').empty();
                        $('#exampleModalLabel').append('Reason Of Holding the Application');                     
                        $('#reason').append(response.data.hold_reason);
                        $('#reason').append(response.data.reject_reason);
                    } else {
                        $('#exampleModalLabel').empty();
                        $('#exampleModalLabel').append('Reason Of Rejection the Application');
                        $('#reason').append(response.data.reject_reason);
                    }                   					
				},
        })
    }
</script>
@endsection
