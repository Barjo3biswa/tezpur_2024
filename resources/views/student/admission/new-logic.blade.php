@php
$recept = $merit->admissionReceipt;
@endphp

{{-- @if($merit->status==1)
<button class="btn btn-success btn-xs" data-toggle="modal" href='#counselling_modal{{ $merit->id }}'><i
    class="fa fa-check"></i> Report for
Counselling </button>
@include("components.counselling-accept-modal", ["modal_id" => "counselling_modal{$merit->id}", "merit" =>$merit])
@endif --}}
{{-- {{$merit->status}} --}}
@if($merit->status==8 )
    @if ($merit->payment_mode=='offline')
        <a data-toggle="modal" href='#chooseHostel{{ $merit->id }}' href="#">
            <button class="btn btn-sm btn-success">Generate ARF (Offline Payment)</button>
        </a>
    @endif
    @if ($merit->payment_mode=='online')
        <a data-toggle="modal" href='#maymentlink{{ $merit->id }}' href="#">
            <button class="btn btn-sm btn-success">Send Online Payment Link</button>
        </a>
    @endif
@endif  
@if ($merit->status == 2 && $merit->release_seat_applicable)
    <a href="{{ route(get_route_guard().'.admission.release', Crypt::encrypt($merit->id)) }}"
        onClick="return confirm('Are you sure to release this seat ?');" target="_blank">
        <button class="btn btn-xs btn-danger"> <i class="fa fa-trash"></i> Release Seat</button>
    </a>
@endif

<div class="modal fade" id="maymentlink{{ $merit->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Set Timings For Admission.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="merit" id="merit" action="{{ route(get_route_guard().'.merit.send-payment-link', Crypt::encrypt($merit->id)) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Set Opening Time</label>
                                <input type="text" name="date_from" class="form-control date" autocomplete="off" required>
                            </div>                     
                        </div> 
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Select Time Window for student to complete</label>
                                <select name="closing_time" class="form-control" id="">
                                    <option value="">--select--</option>
                                    <option value="4">3 Hour Time Window</option>
                                </select>
                            </div>                  
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Call For Admission" onclick="return confirm('Are you sure you want Call Please check again?');">
                    </div>
                </form>
                </div>
        </div>
    </div>
</div>


<div class="modal fade" id="chooseHostel{{ $merit->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
                <h4 class="modal-title">Please Choose Hostel
                    Required/Not</h4>
            </div>
            <form method="POST"
                action="{{ route(get_route_guard().'.admission.choose-hostel', Crypt::encrypt($merit->id)) }}"
                onSubmit="return confirm('Are you sure ? ')">
                {{ csrf_field() }}
                <div class="modal-body">

                    <div class="alert alert-info">
                        <strong>Note:</strong> Hostel will be
                        alloted based on availability.
                    </div>

                    <div class="checkbox">
                        <label>
                            <input name="hostel_required" required type="radio" value="yes">
                            Hostel Required
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="hostel_required" required type="radio" value="no">
                            Hostel Not Required
                        </label>
                    </div>

                    <div id="hostel_dtl{{$key}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--
@if ($merit->isSystemGenerated())
    <label>Please wait for your turn.</label>
@elseif($merit->isApprove() && !$merit->isReported())
    @if (!$merit->isDateExpired() && in_array($merit->course_id, $programs))
        <button class="btn btn-success btn-xs" data-toggle="modal" href='#counselling_modal{{ $merit->id }}'><i
                class="fa fa-check"></i> Report for
            Counselling </button>
        @include("components.counselling-accept-modal", ["modal_id" => "counselling_modal{$merit->id}", "merit" =>
        $merit])
        <button class="btn btn-danger btn-xs" data-toggle="modal" href="#decline_seat_modal{{ $merit->id }}"><i
                class="fa fa-exclamation-triangle"></i>
            Decline your seat </button>
        @include("components.decline-seat-modal", ["modal_id" => "decline_seat_modal{$merit->id}", "merit" => $merit])
        <br />
    @endif
    @if($merit->isFutureTime())
        <span class="label label-danger">Please wait for mentioned reporting time.</span><br />
    @endif
    <span class="label label-default">Reporting time: {{ $merit->valid_from }} - {{ $merit->valid_till }}</span>
@elseif($merit->isReported() && !$merit->isDeclined() && !$merit->isBookingDone() && !$merit->isWithdrawal())
    @if($merit->isAvailableForPayment())
        @if (!$merit->isDateExpired())
            @if ($merit->ask_hostel)
                @if ($merit->status !=3 && in_array($merit->course_id, $programs))
                <a data-toggle="modal" href='#chooseHostel{{ $merit->id }}' href="#">
                    <button class="btn btn-sm btn-success">Make payment for seat confirmation</button>
                </a>
                @endif    
                <div class="modal fade" id="chooseHostel{{ $merit->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Please Choose Hostel
                                    Required/Not</h4>
                            </div>
                            <form method="POST"
                                action="{{ route(get_route_guard().'.admission.choose-hostel', Crypt::encrypt($merit->id)) }}"
                                onSubmit="return confirm('Are you sure ? ')">
                                {{ csrf_field() }}
                                <div class="modal-body">

                                    <div class="alert alert-info">
                                        <strong>Note:</strong> Hostel will be
                                        alloted based on availability.
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input name="hostel_required" {{-- onclick="showHide({{$key}})" --}} required type="radio" value="yes">
                                            Hostel Required
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="hostel_required" {{-- onclick="showHideii({{$key}})" --}} required type="radio" value="no">
                                            Hostel Not Required
                                        </label>
                                    </div>

                                    <div id="hostel_dtl{{$key}}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Proceed</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- <script>
                    function showHideii(){
                        $('#hostel_dtl').empty();
                    }
                    function showHide(){
                        alert("ok");
                        var html=`<div class="row">
                                        <div class="col-md-3">
                                                <label for="">Hostel Name</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="hostel_name" id="hostel_name" class="form-control" required>
                                                <option value="">--Select--</option>
                                                @foreach ($hostel_names as $host)
                                                        <option value="{{$host->name}}">{{$host->name}}</option>
                                                @endforeach      
                                                </select>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="">Room No</label>
                                            </div>
                                        <div class="col-md-6">
                                            <input type="text" name="room_no" class="form-control" required>
                                        </div>
                                    </div>`;
                        
                        $('#hostel_dtl').empty();
                        console.log(html);
                        $('#hostel_dtl').append(html);
                    }      
                </script> --}}
                <br />
            @else
                @if ($merit->status!=3 && in_array($merit->course_id, $programs))
                <a href="{{ route(get_route_guard().'.admission.proceed', Crypt::encrypt($merit->id)) }}"
                    onClick="return confirm('Are you sure ? Action undone.')">
                    <button class="btn btn-sm btn-success">Make payment for seat confirmation </button>
                </a>
                @endif   
            @endif
            @if ($merit->status!=3)
            <button class="btn btn-danger btn-xs" data-toggle="modal" href="#decline_seat_modal{{ $merit->id }}"><i
                    class="fa fa-exclamation-triangle"></i>
                Decline your seat </button>
            @endif   
            @include("components.decline-seat-modal", ["modal_id" => "decline_seat_modal{$merit->id}", "merit" => $merit])
            <br />
        @endif
        @if($merit->isFutureTime())
            <span class="label label-danger">Please wait for mentioned admission time.</span><br />
        @endif
        <span class="label label-default">Admission time: 
            {{date("Y-m-d h:i a", strtotime($merit->valid_from))}} -
            {{date("Y-m-d h:i a", strtotime($merit->valid_till))}}
        </span>
    @else
        <span class="label label-default">Please wait for your turn.</span>  
    @endif
@endif
@if ($recept)
    <a href="{{ route(get_route_guard().'.admission.payment-receipt', Crypt::encrypt($merit->id)) }}" target="_blank"><button
            class="btn btn-xs btn-primary">Receipt</button></a>
@endif
@if ($merit->status == 2 && $merit->release_seat_applicable)
    <a href="{{ route(get_route_guard().'.admission.release', Crypt::encrypt($merit->id)) }}"
        onClick="return confirm('Are you sure to release this seat ?');" target="_blank">
        <button class="btn btn-xs btn-danger"> <i class="fa fa-trash"></i> Release Seat</button>
    </a>
@endif
@if($merit->isValidTillExpired() &&  in_array($merit->status, [1,8]) && (!$merit->isReported() || $merit->isAvailableForPayment()))
    <span class="label label-danger"> Time Expired </span>
@endif
@if($merit->withdraw_seat_applicable && $merit->status == 2 && !$merit->withdrawalRequest && config("vknrl.withdrawal_seat_module"))
    <button class="btn btn-danger btn-xs" data-toggle="modal" href="#withdraw_seat_modal{{ $merit->id }}">
        <i class="fa fa-exclamation-triangle"></i> Withdraw your seat 
    </button>
    @include("components.withdraw-seat-modal", ["modal_id" => "withdraw_seat_modal{$merit->id}", "merit" => $merit])
@endif!-->
