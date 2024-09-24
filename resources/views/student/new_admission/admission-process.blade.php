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
            <div class="panel-heading">Admission Process For {{env("APP_NAME_2")}}
            </div>

                <div class="panel-body">
                    {{-- <div class="alert alert-danger" role="alert">
                        <strong><span class="blink">Note :</span> Reporting doesn't mean seat confirmation. Seat confirmation is subject to seat availability & payment</strong><br/>                                         
                        <strong><span class="blink">Note :</span>You will be notified through  email and sms to your registered email and mobile number, refresh this page when you get notification.</strong><br/>
                    </div> --}}

                    <div class="alert alert-danger" role="alert">
                        <strong><span class="blink">Note :</span>Please use latest version of the browser (Mozilla Firefox, Chrome, Microsoft Edge, etc.) .</strong><br/>                                                                                
                        <strong><span class="blink">Note :</span>You will be notified for Reporting / Admission process through  email and sms to your registered email and mobile number, refresh this page when you get notification.</strong><br/>
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


                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Candidate Name</th>
                                    <th>Program Name</th>
                                    <th>Admission Category</th>
                                    <th>Preference</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($merit_list as $key=>$merit_list)
                                    
                                    @php
                                        $applied_preference = $merit_list->application->applied_courses->where('course_id',$merit_list->course_id)->first();
                                    @endphp
                                    {{-- {{dump($applied_preference)}} --}}
                                    <tr>
                                        <th>{{++$key}}</th>
                                        <th>{{$merit_list->application->getFullNameAttribute()}}</th>
                                        <th>{{$merit_list->course->name}}
                                            @if ($merit_list->freezing_floating!=null)
                                            <br/><b style="color: rgb(150, 35, 35)">{{$merit_list->freezing_floating}}</b>
                                            @endif
                                        </th>
                                        <th>
                                            @if($merit_list->admission_category_id==1)
                                                Unreserved
                                            @else
                                                {{$merit_list->admissionCategory->name}}
                                            @endif
                                            
                                        </th>
                                        <th>
                                            {{$merit_list->preference??"NA"}}
                                        </th>
                                        <th>
                                            @if($merit_list->attendance_flag==1 && $merit_list->status!=2)
                                                <span class="label label-success">  I have already reported.</span>
                                            @elseif($merit_list->attendance_flag==2)
                                                <span class="label label-danger">  I have declined the invitation for reporting.</span>
                                            @endif
                                            <br/>

                                            @if ($merit_list->status == 1)
                                            <span class="label label-warning">
                                                Approved </span>
                                            @elseif ($merit_list->status == 2)
                                                <span class="label label-success">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                    {{-- Provisional Booking Done --}}
                                                    Provisionally Admitted
                                                </span>
                                            @elseif ($merit_list->status == 3)
                                                <span class="label label-danger">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    Seat Transferred
                                                </span>
                                            @elseif ($merit_list->status == 4)
                                                <span class="label label-danger">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    Cancelled
                                                </span>
                                            {{-- @elseif ($merit_list->status == 5)
                                                <span class="label label-danger">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    Temporarily on hold
                                                </span> --}}
                                            @elseif ($merit_list->status == 6)
                                                <span class="label label-danger">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    Withdrawal
                                                </span>
                                            @elseif($merit_list->status == 7)
                                                <span class="label label-default">Process:: Report for counselling. </span>
                                            @elseif($merit_list->status == 8)
                                                <span class="label label-primary"> Reported for counselling. </span><br />
                                               
                                            @elseif($merit_list->status == 9)
                                                <span class="label label-danger"> Seat declined by {{$merit_list->seat_declined_by}}. </span>
                                            @elseif($merit_list->isAutomaticSystem())
                                                <span class="label label-default">  {{-- Please wait for your turn.
                                                    Subject's to seat availability --}}
                                                    'Reporting’ does not ensure a seat for the candidates and admission will be strictly based on merit.
                                                </span>
                                            {{-- @else
                                                <span class="label label-default">  Shortlisted.</span> --}}
                                            @endif                                                                                                                     
                                        </th>
                                        <th>
                                            @if($merit_list->attendance_flag==0 && now() >= $merit_list->valid_from && now() <= $merit_list->valid_till)
                                                {{-- <button class="btn btn-primary" data-toggle="modal" data-target=".bd-accept-modal-lg{{$key}}">Request Accepted</button> --}}
                                                <a href="{{ route(get_route_guard().'.online-admission.accept-invite', Crypt::encrypt($merit_list->id)) }}" class="btn btn-primary"
                                                    @if($merit_list->meritMaster->semi_auto)
                                                        onclick="return confirm('Are you sure you want to Accept invitation for reporting?\n(Once Accepted invitation for other social category will declined automatically.)');"
                                                    @endif
                                                >Request Accepted</a>
                                                <a href="{{ route(get_route_guard().'.online-admission.decline-invite', Crypt::encrypt($merit_list->id)) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to DECLINE invitation for reporting?\n(Once declined the process is irreversible.)');">Request Declined </a>
                                                <br/>
                                            @else
                                                @if(in_array($merit_list->new_status, ['called', 'admitted','time_extended','payment_allowed']) && now() >= $merit_list->valid_from && now() <= $merit_list->valid_till )
                                                    @if($merit_list->status==0 || $merit_list->status==7)
                                                        <button class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg{{$key}}">Proceed</button>
                                                    @elseif($merit_list->status==8)
                                                        @if($merit_list->ask_hostel==1)
                                                            <button class="btn btn-success" data-toggle="modal" data-target=".bd-Payment-modal-lg{{$key}}">Make Payment</button>
                                                        @else
                                                            <a href="{{route('student.admission.proceed',Crypt::encrypt($merit_list->id))}}" class="btn btn-success">Make Payment</a>
                                                        @endif
                                                    @endif

                                                    @if($merit_list->status != 2 && $merit_list->status!=3)
                                                        <button class="btn btn-danger" data-toggle="modal" data-target=".bd-decline-modal-lg{{$key}}">Decline Seat</button>
                                                    @endif 
                                                    <br/>                                        
                                                @endif
                                                
                                            @endif
                                            
                                            
                                            @if ($merit_list->status == 2 && $merit_list->application->is_btech==0)
                                                <a href="{{ route(get_route_guard().'.admission.payment-receipt', Crypt::encrypt($merit_list->id)) }}" target="_blank"><button
                                                class="btn btn-xs btn-primary">Admission Receipt</button></a>
                                                @if($merit_list->release_seat_applicable==1 && IsItTimeToRelease($merit_list->id))
                                                    <a href="{{route(get_route_guard().'.online-admission.release-seat', Crypt::encrypt($merit_list->id))}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to release this seat, after release you can`t claim for this seat?');">Release Seat</a>
                                                @endif
                                                <br/>
                                            @endif
                                            
                                            @if($merit_list->status==2 && $merit_list->withdraw_seat_applicable==1)
                                                <button class="btn btn-danger" data-toggle="modal" data-target=".bd-withdraw-modal-lg{{$key}}">Withdraw Seat</button>
                                            @endif


                                            {{-- @if($merit_list->status==2 && $merit_list->application->is_btech==1)
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$merit_list->id}}">
                                                    Send Withdrawal Request
                                                </button>
                                            @endif --}}

                                            {{-- @if($merit_list->hostel_required==3)
                                                <br/>
                                                <a href="#" class="btn btn-success btn-sm">Proceed to pay Hostel Fee.</a>
                                            @elseif($merit_list->hostel_required==4)
                                                <br/>
                                                <a href="#" class="btn btn-primary btn-sm">Hostel Receipt</a>
                                            @endif --}}

                                            @if ( now() >= $merit_list->valid_till && in_array($merit_list->status,[0,7,8]))
                                                @if($merit_list->new_status!='invited')
                                                    <span class="btn btn-danger btn-sm">Your Reporting / Admission Time Is Over.</span>
                                                    <br/>
                                                @endif
                                            @endif
                                            @if (in_array($merit_list->attendance_flag,[0,1]) && in_array($merit_list->status,[0,7,8]))
                                                <span class="btn btn-info btn-xs">{{date('d-m-Y h:s:a', strtotime($merit_list->valid_from))}} to {{date('d-m-Y h:s:a', strtotime($merit_list->valid_till))}}</span>
                                            @endif
                                            
                                            @include('student.new_admission.accept-model')
                                            @include('student.new_admission.report-modal')
                                            @include('student.new_admission.payment-modal')
                                            @include('student.new_admission.decline-modal')
                                            @include('student.new_admission.withdraw-modal')

                                            
                                            
                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{$merit_list->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">You are going to withdraw your seat</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{route("student.admission.withdraw-seat", $merit_list->id)}}">
                                                            <div class="modal-body">
                                                                {{ csrf_field() }}
                                                                <div class="form-group">
                                                                    <p class="text-info"><i class="fa fa-info-circle"></i>  Please provide some information why you want to withdraw your seat.</p>
                                                                </div>                                          
                                                                <div class="form-group">
                                                                    <label for="dob" class="control-label">DOB <small class="text-danger">*</small></label>
                                                                    <input type="date" id="dob" name="dob" class="form-control" max="{{date("Y-m-d")}}" placeholder="DOB" aria-describedby="otp-addon" required value="{{old("dob")}}">
                                                                </div>
                                                                <hr>
                                                                <h4 class="title">Bank Details</h4>
                                                                <hr>
                                                                <div class="form-group">
                                                                    <label for="bank_account" class="control-label">BANK ACCOUNT NO. <small class="text-danger">*</small></label>
                                                                    <input type="text" id="bank_account" name="bank_account" class="form-control" placeholder="Bank Account No" aria-describedby="otp-addon" required  value="{{old("bank_account")}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="holder_name" class="control-label">ACCOUNT HOLDER NAME <small class="text-danger">*</small></label>
                                                                    <input type="text" id="holder_name" name="holder_name" class="form-control" placeholder="Account holder name" aria-describedby="otp-addon" required value="{{old("holder_name")}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="bank_name" class="control-label">BANK NAME <small class="text-danger">*</small></label>
                                                                    <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="Bank Name" required value="{{old("bank_name")}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="branch_name" class="control-label">BRANCH NAME <small class="text-danger">*</small></label>
                                                                    <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Branch Name" aria-describedby="otp-addon" required value="{{old("bank_account")}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="ifsc_code" class="control-label">IFSC CODE <small class="text-danger">*</small></label>
                                                                    <input type="text" id="ifsc_code" name="ifsc_code" class="form-control" placeholder="IFSC CODE" aria-describedby="ifsc-addon" required value="{{old("ifsc_code")}}">
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="reason" class="control-label">Choose withdrawal reason from the list <small class="text-danger">*</small></label>
                                                                    
                                                                    <select name="reason_from_list" id="reason_from_list" class="form-control" required="required">
                                                                        <option value="">--Choose--</option>
                                                                        @foreach(\App\Helpers\CommonHelper::admission_decline_rules() as $value)
                                                                            <option value="{{$value}}" {{old("reason_from_list") == $value ? "selected" : ""}}>{{$value}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="otp" class="control-label">Reason for withdrawal <small class="text-danger">*</small> <small class="text-mute">(min 10 character & max 1000 character)</small></small></label>
                                                                    <textarea name="reason" id="inputreason" minlength="10" maxlength="1000" class="form-control" rows="3" required="required" placeholder="Reason between 10 to 500 characters.">{{old("reason")}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure ? Your seat will be gone. Cannot revert back. ')">Proceed </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <span class="label label-danger">Reporting doesn't mean seat confirmation. Seat confirmation is subject to seat availability & payment.</span> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function requestForOTP(id){
        // alert(id);
        $("#message").empty();
        $.ajax({
            url:'{{route("student.online-admission.decline_otp")}}',
            type:'post',
            data:{
                'id' : id,
                'status' : 'withdraw',
                '_token':"{{csrf_token()}}"
            },
            success:function(response){
                console.log(response);
                $(".message").append('<h4 style="color: rgb(62, 151, 59)">An OTP has send to your registared mobile no.</h4>');    
            },
            error:function(response){
                console.log(response);
                $(".message").append('<h4 style="color: rgb(151, 59, 59)">Something went wrong.</h4>');
            }

        })
    }
    // $("#get_otp").click(function(){
    //     $("#message").empty();
    //     $.ajax({
    //         url:'{{route("student.online-admission.decline_otp")}}',
    //         type:'post',
    //         data:{
    //             '_token':"{{csrf_token()}}"
    //         },
    //         success:function(response){
    //             console.log(response);
    //             $("#message").append('An OTP has send to your registared mobile no.');    
    //         },
    //         error:function(response){
    //             console.log(response);
    //             $("#message").append('Ooopss.');
    //         }

    //     })
        
        

    // });
</script>
@endsection
