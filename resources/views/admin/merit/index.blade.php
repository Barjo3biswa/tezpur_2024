{{-- @if (auth("department_user")->check())
@extends('department-user.layout.auth')  
@endif

@if (auth("admin")->check())
@extends ('admin.layout.auth')
@endif --}}


@section ('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/css/bootstrap/zebra_datepicker.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    .form-control {
        min-width: 133px;
        border: 1px solid #949292 !important;
    }

    span.Zebra_DatePicker_Icon_Wrapper {
        position: unset !important;
        width: 100% !important;
    }

    label.date_time {
        font-weight: normal;
        font-weight: bold;
        line-height: 3.3rem;
    }
    .bg-info {
        background-color: #d9edf7 !important;
    }
    
</style>

@endsection 
@section ("content")
@php
    $castes = \App\Models\Caste::pluck("name","id")->toArray();
    $btech_programs = \App\Course::whereIn("id", btechCourseIds())->withTrashed()->get();
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    
                    {{-- @if (auth("department_user")->check())
                    <form action="{{route("department.application.index", request()->all())}}" method="get">
                        @include('admin/applications/filter')
                    </form> 
                    @endif
                    @if (auth("admin")->check()) --}}
                    <form action="" method="get">
                        @include ('admin/merit/filter-new')
                        {{-- @include('admin/applications/filter') --}}
                    </form>
                    {{-- @else --}}
                    
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong> Merit List :: </strong> Total <span
                        class="badge">{{ $merit_lists->total() }}</span> Records Found
                        <!--@if (auth("department_user")->check())
                        <span class="pull-right">
                            <a href="#">
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#admission_category_modal">Set Admission Category</button>
                            </a>
                        </span>
                        @endif

                        @if (auth("admin")->check())
                        <span class="pull-right">
                            <a href="{{request()->fullUrlWithQuery(['export-data' => 1])}}" class="btn btn-sm btn-warning">
                                Export To Excel
                            </a>
                        </span>
                        @endif
                        <span class="pull-right">
                            <a href="{{route(get_route_guard().".fee.reports")}}">
                                <button class="btn btn-sm btn-info">Fees Collections</button>
                            </a>
                        </span>!-->
                    </div>
                <div class="panel-body">
                    {{-- <div id="admission_categories"></div> --}}
                    <form name="merit" id="merit" action="{{ route(get_route_guard().'.merit.approve') }}"
                        method="POST">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Prog. Name</th>
                                    <th>Name</th>
                                    <th>Admt. Cat.</th>
                                    <th>Social Cat. / Claimed cat.</th>
                                    <th>Gender</th>
                                    <th>JEE Rank</th>
                                    <th>Hostel</th>
                                    <th>P. Type</th>
                                    {{-- <th>Merit/Waiting</th> --}}
                                    {{-- <th>Undertaking</th> --}}
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{ csrf_field() }}

                                @if ($merit_lists->count() &&  auth("department_user")->check())
                                <div class="row" style="margin-top: 10px;">

                                    <!--<div class="text-right col-md-12">
                                        @if(checkPermission(2)==true)
                                        <button type="submit" class="btn btn-warning btn-sm" {{-- id="approvePayment" --}}
                                        onclick="return checkSubmit('Are you sure to unhold the seat')" name="submit" value="Unhold Seat"
                                        >Unhold Seat</button>
                                        <button type="submit" class="btn btn-warning btn-sm" {{-- id="approvePayment" --}}
                                        onclick="return checkSubmit('Are you sure to hold the seat')" name="submit" value="Hold Seat"
                                        >Hold Seat</button>
                                        @endif
                                        @if(checkPermission(3)==true)
                                        <button type="submit" class="btn btn-primary btn-sm" {{-- id="approvePayment" --}}
                                        onclick="return checkSubmit('Are you sure for payment approval')" name="submit" value="Approve for payment"
                                        >Approve for payment</button>
                                        @endif

                                        @if(checkPermission(2)==true)
                                        <button type="submit" class="btn btn-success btn-sm" {{-- id="approve" --}}
                                        onclick="return checkSubmit('Are you sure for reporting')" name="submit" value="Approve "
                                        >Approve for Reporting</button>
                                        @endif
                                        {{-- <input type="submit" name="submit" class="btn btn-danger" id="button"
                                            value="Decline for Admission" onclick="return checkSubmit('Are you sure to decline')"> --}}
                                    </div>!-->
                                </div>
                                @endif 
                                @php
                                    $currentPage = $merit_lists->currentPage();
                                    $itemsPerPage = $merit_lists->perPage();
                                    $startingSlNo = ($currentPage - 1) * $itemsPerPage;
                                @endphp
                                @forelse ($merit_lists as $key=>$merit_list)
                                    @php
                                        $tr_class_name = "";
                                        if($merit_list->status == 2){
                                            $tr_class_name = "bbg-success";
                                        }elseif($merit_list->status == 9){
                                            // seat declined
                                            $tr_class_name = "bbg-danger";
                                        }elseif($merit_list->status == 4){
                                            // seat cancelled
                                            $tr_class_name = "bbg-danger";
                                        }elseif($merit_list->status == 6){
                                            // seat withdrawal
                                            $tr_class_name = "bbg-danger";
                                        }elseif($merit_list->status == 5){
                                            // temporary on hold
                                            $tr_class_name = "bbg-primary";
                                        }elseif($merit_list->status == 8){
                                            // seat transferred
                                            $tr_class_name = "bbg-warning";
                                        }elseif($merit_list->isValidTillExpired() && $merit_list->isValidTillExpired() &&  in_array($merit_list->status, [1,8])){
                                            $tr_class_name = "bbg-info";
                                        }
                                    @endphp

                                   
                                    <tr @if(Session::has('reg_id') && !empty(Session::get('reg_id')))
                                    @if(in_array($merit_list->student_id,Session::get('reg_id')))
                                    class="error-seat"
                                    @endif
                                    @endif
                                    >

                                    <tr class="{{ $tr_class_name}}">
                                        {{-- {{dd($merit_list->meritMaster->closed_list)}} --}}
                                        <td>{{++$key+$startingSlNo}}
                                            <!--@if (auth("department_user")->check() && $merit_list->meritMaster->closed_list==0)
                                                @if(checkPermission(2)==true && in_array($merit_list->status, [0,7]))
                                                <input type="checkbox" name="merit_list_id[]"
                                                value="{{ $merit_list->id }}" class="merit_list check">
                                    
                                                @elseif(checkPermission(3)==true && in_array($merit_list->status, [8]) && $merit_list->is_payment_applicable==0)
                                                <input type="checkbox" name="merit_list_id[]"
                                                value="{{ $merit_list->id }}" class="merit_list check">
                                                @endif
                                            @endif!-->
                                            {{-- @if ($merit_list->status != 4)
                                                <input type="checkbox" name="merit_list_id[]"
                                                    value="{{ $merit_list->id }}" class="merit_list check">
                                            @endif --}}
                                        </td>
                                        <td>
                                            @if ($merit_list->btechPrevious)
                                                @if ($merit_list->btechPrevious->attendance_flag==1)
                                                    <span style="color: rgb(89, 170, 89)"><b>{{ $merit_list->student_id }}</b></span>
                                                @else
                                                    <span>{{ $merit_list->student_id }}</span>
                                                @endif
                                            @else
                                                <span>{{ $merit_list->student_id }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $merit_list->application_no }}
                                            <br/>
                                            JEE:{{$merit_list->cmr}}
                                        </td>
                                        <td>
                                            {{ $merit_list->course->name }}
                                            @if(request("change_course") && in_array($merit_list->course_id, array_merge(btechCourseIds(), [83])))
                                                <!-- Trigger the modal with a button -->
                                                <button type="button" class="btn btn-danger btn-xs" onclick="showChangeCourseForm(this)" data-url="{{route(get_route_guard().".merit.change-programm", $merit_list->id)}}">Change</button>
                                            @endif
                                            @if(request("show_seat_transfer") && $merit_list->status == 2)
                                                <!-- Trigger the modal with a button -->
                                        <button type="button" class="btn btn-warning btn-xs" onclick="showTransferSeat(this)" data-url="{{route(get_route_guard().".application.transfer-seat", $merit_list->id)}}" data-merit="{{$merit_list->toJson()}}">Transfer Seat</button>
                                            @endif
                                        </td>
                                        @php
                                            $is_admited=app\Models\MeritList::with('course')->where('student_id',$merit_list->student_id)->whereIn('status',[2,3])->get();
                                        @endphp
                                        <td>{{ $merit_list->application->first_name ?? "NA" }}
                                            {{ $merit_list->application->middle_name ?? "NA" }}
                                            {{ $merit_list->application->last_name ?? "NA" }}
                                            @forelse ($is_admited as $key=>$name)
                                                <br/><span style="color:rgb(155, 91, 19)">{{++$key}}.&nbsp;{{$name->course->name}}<b>({{$name->freezing_floating}})</b><span><br/>
                                            @empty
                                                
                                            @endforelse
                                        </td>
                                        @php
                                           $admisn_catagory=app\Models\MeritList::where('course_id',$merit_list->course_id)->where('student_id',$merit_list->student_id)->where('status',14)->first();
                                        @endphp
                                        <td>
                                            @if($merit_list->status=='2' || $merit_list->new_status=='branch_assigned')
                                                @if ($merit_list->may_slide==3)
                                                {{$admisn_catagory->admissionCategory->name}}
                                                @else
                                                {{ $merit_list->admissionCategory->name }} 
                                                @endif
                                            @else
                                                ----
                                            @endif

                                            @if($merit_list->is_pwd==1)
                                                <span class="label label-danger">PWD</span>   
                                            @elseif($merit_list->application->is_pwd==1)
                                               <span class="label label-success">PWD</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-primary">{{ $castes[$merit_list->application->caste_id] ?? "NA" }}</span>
                                        </td>
                                        <td>{{ $merit_list->gender }}</td>
                                        <td>{{ $merit_list->student_rank }} {{-- [{{$merit_list->cmr}}] --}}</td>
                                        <td>@if($merit_list->status==2){{ $merit_list->hostel_required ? "Required" : "Not Required" }}@endif</td>
                                        <td>{{ str_replace("_", " ", $merit_list->programm_type) }}</td>
                                        {{-- <td>{!! $merit_list->selected_in_merit_list ? '<span class="label label-success">Merit</span>' : '<span class="label label-warning">Waiting</span>' !!}</td> --}}
                                        {{-- <td>
                                            @if(!$merit_list->allow_uploading_undertaking)
                                                <span class="label label-success">Not Required</span>
                                            @elseif($merit_list->allow_uploading_undertaking)
                                                @if($merit_list->undertakings->isEmpty())
                                                    <span class="label label-danger">Not uploaded</span>
                                                @else
                                                    @if(auth("admin")->check())
                                                        <button class="btn btn-primary btn-xs" type="button" onClick="showUndertaking(this)" data-url="{{route(get_route_guard().".application.undertaking-view", $merit_list->id)}}" data-app_no="{{$merit_list->application_no}}"><i class="glyphicon glyphicon-eye-open"></i> View Details</button>
                                                        @if($merit_list->undertakings->where("status", \App\Models\MeritListUndertaking::$accepted)->count())
                                                            <span class="label label-success">Undertaking Approved</span>
                                                        @elseif($merit_list->undertakings->where("status", \App\Models\MeritListUndertaking::$pending)->count())
                                                            <span class="label label-info">Undertaking Verification Pending</span>
                                                        @elseif($merit_list->undertakings->where("status", \App\Models\MeritListUndertaking::$rejected)->count())
                                                            <span class="label label-danger">Undertaking Rejected</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                            @if($merit_list->allow_uploading_undertaking == 3)
                                                <span class="label label-danger">Doc Uploading date Expired</span>
                                            @endif
                                        </td> --}}
                                        <td>
                                        @if($merit_list->status == 1)
                                            <span class="label label-warning">
                                                Approved </span><br>
                                                {{-- <span class="label label-primary">Admission timing : <br>
                                                    {{date("Y-m-d h:i a", strtotime($merit_list->valid_from))}} -
                                                    {{date("Y-m-d h:i a", strtotime($merit_list->valid_till))}}
                                                </span> --}}
                                        @elseif ($merit_list->status == 2)
                                            <span class="label label-success">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                {{-- Provisional Booking Done --}}
                                                Provisionally admitted
                                            </span>
                                            @if ($merit_list->may_slide==3)
                                            <span class="label label-danger" aria-hidden="true">
                                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                slided 
                                            </span>    
                                            @endif
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
                                        @elseif ($merit_list->status == 5)
                                            <span class="label label-danger">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                Temporarily on hold
                                            </span>
                                        @elseif ($merit_list->status == 6)
                                            <span class="label label-danger">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                Withdrawal
                                            </span>
                                        @elseif ($merit_list->status == 7)
                                            <span class="label label-primary">
                                                {{-- <i class="fa fa-bell" aria-hidden="true"></i> --}}
                                                {{-- {{$merit_list->getStatusText()}} --}}
                                            </span>
                                            <br>
                                            {{-- <span class="label label-primary">Admission timing : <br>
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_from))}} -
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_till))}}
                                            </span> --}}
                                             {{-- <button class="btn btn-success btn-xs" onClick="instantApprove(event, this)" data-url="{{route("admin.merit.approve-system-generated", $merit_list)}}"> <i class="fa fa-check"></i> Instant Approve</button> --}}
                                             {{-- <button class="btn btn-success btn-xs" type="button"> <i class="fa fa-check"></i> Approved</button> --}}
                                             <span class="label label-default"> Pending </span>
                                        @elseif ($merit_list->status == 8)
                                            <span class="label label-primary">
                                                <i class="fa fa-bell" aria-hidden="true"></i>
                                                Reported for counselling
                                            </span>
                                            @if($merit_list->is_payment_applicable)
                                                <span class="label label-success">
                                                    Payment allowed
                                                </span>
                                            @else
                                                @if(checkPermission(3)==true &&  auth("department_user")->check())
                                                <span class="label label-danger">
                                                    Payment not-allowed
                                                </span>
                                                @endif                          
                                            @endif
                                            <br>
                                            {{-- <span class="label label-primary">Admission timing : <br>
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_from))}} -
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_till))}}
                                            </span> --}}
                                        {{-- @elseif ($merit_list->status == 9)
                                            <span class="label label-danger">
                                                Seat declined by {{$merit_list->seat_declined_by}} </span>
                                                <a target="_blank" href="{{route(get_route_guard().".merit.decline-receipt", Crypt::encrypt($merit_list->id))}}">
                                                    <i class="glyphicon glyphicon-eye-open"></i>
                                                </a> --}}
                                           
                                        @elseif ($merit_list->status == 12)
                                            <span class="label label-danger">
                                                category Changed </span>
                                        @elseif ($merit_list->status == 14)
                                        <span class="label label-danger">
                                            A.category slided </span>
                                            {{-- <a target="_blank" href="{{route(get_route_guard().".merit.admission-slide-receipt", Crypt::encrypt($merit_list->id))}}">
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>  --}}
                                        @elseif ($merit_list->status == 16)
                                        <span class="label label-danger">
                                           Sliding Denied </span>
                                        @else 
                                            <span class="label label-default"> Pending </span>
                                        @endif
                                        @if($merit_list->is_hold == 1 && $merit_list->attendance_flag==1)
                                        <span class="label label-danger">
                                           Temp on hold
                                        </span>
                                        @endif
                                        @if($merit_list->isValidTillExpired() &&  in_array($merit_list->status, [1,8]) && (!$merit_list->isReported() || $merit_list->isAvailableForPayment()))
                                        <span class="label label-danger"> Time Expired </span>
                                        @endif
                                        {{-- @if($merit_list->admission_receipt_count)
                                        <a target="_blank" href="{{route(get_route_guard().".merit.admission-receipt", Crypt::encrypt($merit_list->id))}}">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        @endif --}}
                                

                                </td>
                                    <td>
                                        <a href="{{route(get_route_guard().".application.show", Crypt::encrypt($merit_list->application->id))}}">
                                            <button type="button" class="btn btn-primary btn-sm">View</button>
                                        </a>

                                        @if(in_array($merit_list->status,[2]))
                                            <a href="{{route('department.merit.btech-recpt',Crypt::encrypt($merit_list->id))}}" class="btn btn-primary btn-sm" target="_blank">Print ARF</a>
                                        @endif

                                        @if(in_array($merit_list->status,[4]))
                                            <a href="{{route('department.merit.btech-recpt-canceled',Crypt::encrypt($merit_list->id))}}" class="btn btn-primary btn-sm" target="_blank">Print Denied Receipt</a>
                                        @endif

                                        @if(in_array($merit_list->status,[6]))
                                            <a href="{{route('department.merit.btech-recpt-canceled',Crypt::encrypt($merit_list->id))}}" class="btn btn-primary btn-sm" target="_blank">Print Withdrawal Receipt</a>
                                        @endif

                                        @if(in_array($merit_list->status,[2]))
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="AssignId({{$merit_list->id}})">
                                               Withdraw Seat
                                              </button>
                                        @endif
                                        @if(checkPermission(2)==true && in_array($merit_list->status, [1,8] ))
                                            <a href="{{route(get_route_guard().".admission.book.seat",Crypt::encrypt(['app_id'=>$merit_list->application->id,'course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id]))}}" {{-- target="blank" --}}>
                                                <button type="button" class="btn btn-danger btn-sm">Admission Seat Details</button>
                                            </a>        
                                        @endif
                                        @if($merit_list->new_status!='branch_assigned' && $merit_list->status==0)
                                            <a href="{{route("department.merit.assign-branch-absent", Crypt::encrypt($merit_list->id))}}" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to remove this candidate from your desk?');">
                                                Not Responded           
                                            </a>
                                            <a href="{{route("department.merit.assign-branch", Crypt::encrypt($merit_list->id))}}" class="btn btn-primary btn-sm">
                                                Assign Branch & Admission Category             
                                            </a>
                                        @endif
                                        
                                    
                                       
                                        <!--@if (auth("department_user")->check())
                                            @if($merit_list->is_payment_applicable == 1 || $merit_list->status==1)
                                            <a href="{{route(get_route_guard().".admission.book.seat", Crypt::encrypt($merit_list->application->id))}}" {{-- target="blank" --}}>
                                            @endif
                                            @if(checkPermission(3)==true && $merit_list->status == 8 && $merit_list->is_payment_applicable)
                                            <a href="{{route(get_route_guard().".admission.book.seat",Crypt::encrypt(['app_id'=>$merit_list->application->id,'course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id]))}}" {{-- target="blank" --}}>
                                                <button type="button" class="btn btn-danger btn-sm">Admission Seat Details</button>
                                            </a>
                                            @endif
                                            @if(checkPermission(2)==true && $merit_list->status == 1)
                                                {{-- <a href="{{route(get_route_guard().".admission.book.seat",Crypt::encrypt(['app_id'=>$merit_list->application->id,'course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id]))}}">
                                                    <button type="button" class="btn btn-danger btn-sm">Admission Seat Details</button>
                                                </a>  --}}
                                                @if ($merit_list->may_slide==0)
                                                <a href="{{route(get_route_guard().".admission.book.seat",Crypt::encrypt(['app_id'=>$merit_list->application->id,'course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id]))}}" {{-- target="blank" --}}>
                                                    <button type="button" class="btn btn-danger btn-sm">Admission Seat Details</button>
                                                </a> 
                                                @else
                                                <a href="{{route(get_route_guard().".admission.slide-seat",Crypt::encrypt(['merit_list_id'=>$merit_list->id,'course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id]))}}" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to Slide admission category.?');"> 
                                                    Slide Admt. Cat.</a>
                                                {{-- <a href="{{route(get_route_guard().".admission.slide-seat-deny",Crypt::encrypt(['merit_list_id'=>$merit_list->id,'course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id]))}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to Deny Sliding.?');"> 
                                                    Deny Sliding</a> --}}
                                                @endif            
                                            @endif
                                        @endif!-->
                                        {{-- @if (auth("admin")->check() && $merit_list->may_slide==1 && $merit_list->meritMaster->allow_attendence==0)
                                        <a href="{{route(get_route_guard().".admission.slide-seat",Crypt::encrypt(['merit_list_id'=>$merit_list->id,'course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id]))}}" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to Slide admission category.?');"> 
                                            Slide Admt. Cat.</a>
                                        @endif  --}}
                                    </td>
                                </tr>
                            @empty 
                                <p>No users</p>
                                @endforelse 

                                        {{-- <span class="label label-warning"> 
                                        Approved </span>
                                        @elseif($merit_list->status == 2)
                                        <span class="label label-success">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        Confirmed
                                        </span>
                                        @elseif($merit_list->status == 3)
                                        <span class="label label-danger">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        Transferred</span>
                                        @else
                                        <span class="label label-default"> Pending <span>
                                        @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <p>No users</p>
                                @endforelse --}}
                                

                                <!-- Modal -->
                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="alert alert-danger">
                                                                <strong>Please verify that only merit list candidates are selected.</strong>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            {{-- <div class="col-md-2">
                                                                <label class="date_time"> Valid From </label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input class="form-control" type="text"
                                                                    name="valid_from" id="valid_from" readOnly required>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="date_time"> Valid To </label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input class="form-control" type="text" name="valid_to"
                                                                    id="valid_to" readOnly required>
                                                            </div> --}}

                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="date_time col-md-2"> Admission expiry limit <small class="text-danger">(hour)</small> </label>
                                                            <div class="col-md-3">
                                                                <input class="form-control text-right" type="number" min="0" name="hour" id="hour" required value="{{config("vknrl.ADMISSION_EXPIRY_HOUR")}}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3 text-center col-md-offset-4">
                                                                <input type="submit" name="submit" id="submit"
                                                                    class="btn btn-success" value="Approve">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Modal -->
                                <div id="approvePaymentModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Approve for payment</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="alert alert-danger">
                                                                <strong>Please verify that only merit list candidates are selected.</strong>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="date_time"> Valid From </label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input class="form-control" type="text"
                                                                    name="approve_valid_from" id="approve_valid_from" readOnly required>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="date_time"> Valid To </label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input class="form-control" type="text" name="approve_valid_to"
                                                                    id="approve_valid_to" readOnly required>
                                                            </div>

                                                        </div> --}}
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3 text-center col-md-offset-4">
                                                                <input type="submit" name="submit" id="submit"
                                                                    class="btn btn-success" value="Approve for payment">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </tbody>
                        </table>
                        {{ $merit_lists->appends(request()->all()) }}
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Applications
                                            <strong>: {{ $merit_lists->total() }} records found.</strong>

                                        </div>
                                        @if(env('SMS_APPLICABLE') == true)
                                        <div class="panel-body">
                                            <br>
                                            <div class="form-group">
                                                <div class="form-check"><input type="checkbox" value="1" id="defaultCheck1" name="send_email" class="form-check-input"> 
                                                        <label for="defaultCheck1" class="form-check-label">
                                                            Send Email ?
                                                        </label>
                                                    </div>
                                                </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="sms" class="control-label">Please choose a sms template to send</label>
                                                    <select class="form-control" name="template_id" id="template_id">
                                                        <option value="">--Choose--</option>
                                                        @foreach ($sms_templates as $template)
                                                            <option value="{{$template["template_id"]}}" data-template="{{$template["template"]}}">{{$template["name"]}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="sms" class="control-label">SMS <span
                                                            class="text-danger">Please type your message
                                                            below</span></label>
                                                    <textarea name="sms" id="sms" cols="30" rows="3"
                                                        class="form-control"
                                                        placeholder="Type your message here">Please login to the panel and make the fees payment by {{now()->format("d/m/Y h:i a")}} for completing the provisional admission. Tezpur University</textarea>
                                                    <span class="text-right pull-right" id="sms_counter">0</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5># <span id="counter">0</span> Application Selected</h5>
                                                    <h5># Place Dynamic Name, Application No, Registration No, In SMS
                                                    </h5>
                                                    <p>
                                                        <strong>##name##</strong> : <strong>Applicant Name</strong><br>
                                                        <strong>##app_no##</strong> : <strong>Application
                                                            No</strong><br>
                                                        <strong>##roll_no##</strong> : <strong>Roll No</strong><br>
                                                        <strong>##category##</strong> : <strong>Category
                                                            Name</strong><br>
                                                        <strong>##school_name##</strong> : <strong>School
                                                            Name</strong><br>
                                                        <strong>##dept_name##</strong> : <strong>Department Name of the programme</strong><br>
                                                        <strong>##programme_name##</strong> : <strong>Programme Name</strong><br>
                                                        <strong>##date_from##</strong> : <strong>Date from <small class="text-muted">(eg: 2021-10-11 02:00 pm)</small></strong><br>
                                                        <strong>##date_to##</strong> : <strong>Date to <small class="text-muted">(eg: 2021-10-11 02:00 pm)</small></strong><br>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" name="submit" class="btn btn-success btn-md"
                                                    value="Send SMS" onclick="return applicationSelected(this)">
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewUndertaking">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Undertaking for Application no <span id="u_app_bno"></span></h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@if(request("change_course"))
<!-- Modal -->
<div id="courseChangeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Change Programme</h4>
        </div>
        <form action="" method="POST">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-group">
                    <label for="control-label">Select New Programme</label>
                    <select name="new_program_id" id="new_programm_id" required  class="form-control">
                        <option value="" selected disabled>--SELECT--</option>
                        @foreach ($btech_programs as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm">Save Changes</button>
            </div>
        </form>
        </div>

    </div>
</div>
@endif
@if(request("show_seat_transfer"))
    <div id="courseTransferModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Transferring Programme</h4>
            </div>
            <form action="" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <h4>Application No: <span id="application_no"></span></h4>
                        <h4>Student No: <span id="student_no"></span></h4>
                        <h4>Admitted Category: <span id="admitted_category"></span></h4>
                    </div>
                    <div class="form-group">
                        <label for="control-label">Select New Programme</label>
                        <select name="new_program_id" id="new_programm_id" required  class="form-control">
                            <option value="" selected disabled>--SELECT--</option>
                            @foreach ($btech_programs as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Transfer to New Programm</button>
                </div>
            </form>
            </div>
    
        </div>
    </div>
@endif
<div id="admission_category_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Choose Programme</h4>
        </div>
        <form action="{{ route('department.merit.set-active-admission-category')}}" method="POST">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-group">
                    <label for="control-label">Select Programme</label>
                    <select name="course_id" id="course_id" required  class="form-control">
                        @foreach (programmes_array() as $id => $name)
                            <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="control-label">Select Admission Category</label>
                    <select name="admission_category_id" id="admission_category_id" required  class="form-control">
                        <option value="" selected disabled>--SELECT--</option>
                        @foreach ($admission_categories->where('id','!=',1) as $key => $admission_category)
                            <option value="{{$admission_category->id}}">{{$admission_category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm">Save Changes</button>
            </div>
        </form>
        </div>

    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('department.merit.btech-cancel')}}" method="POST">
            {{ csrf_field() }}
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cancel this Candidate</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="row">
                <input type="hidden" id="ml_id" name="ml_id">
                <input type="hidden"  name="type" name="withdrow">
                <label for="">Reason Of Cancellation</label>
                <textarea name="reason" id="reason" cols="10" rows="3" class="form-control" required></textarea>
            </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary" 
            onclick="return confirm('Are you sure you want to Cancel?');">Submit</button>
            </div>
        </form>
    </div>
    </div>
</div>


@endsection 
@section ('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
<script>
    function AssignId(id){
        $("#ml_id").val(id);
    //    alert(id);
    }
</script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
    $("button[type='reset']").on("click", function () {
        $(".filter input").attr("value", "").val("");
        $(".filter").find("select").each(function (index, element) {
            $(element).find("option").each(function () {
                if (this.defaultSelected) {
                    this.defaultSelected = false;
                    // this.selected = false;
                    $(element).val("").val("all");
                    return;
                }
            });
        });
    });
    $("#template_id").change(function(event){
        var template = "";
        if($(this).val() !== ""){
            template = $("#template_id option:selected").data("template");
        }
        $("#sms").val(template);
    });
    resetPassword = function (string) {
        if (!confirm("Change Password ?")) {
            return false;
        }
        var ajax_post = $.post('{{ route(get_route_guard().".applicants.changepass") }}', {
            "_token": '{{ csrf_token() }}',
            'user_id': string
        });
        ajax_post.done(function (response) {
            alert(response.message);
        });
        ajax_post.fail(function () {
            alert("Failed Try again later.");
        });
    }
    showUndertaking = function(obj){
        $(".loading").fadeIn();
        var $this = $(obj);
        var xhr = $.get($this.data("url"));
        xhr.done(function(resp){
            var $modal = $("#viewUndertaking");
            $modal.find("#u_app_bno").html($this.data("app_no"));
            $modal.find(".modal-body").html(resp);
            $modal.modal("show");
        });
        xhr.fail(function(){
            alert("Whoops! something went wrong.");
        });
        xhr.always(function(){
            $(".loading").fadeOut();
        })
    }
    closeUndertakingModal = function(){
        $(".loading").fadeOut();
        $("#viewUndertaking").modal("hide");
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#opening_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#closing_date').datetimepicker({
            useCurrent: false, //Important! See issue #1075
            format: 'YYYY-MM-DD'
        });
        $("#opening_date").on("dp.change", function (e) {
            $('#closing_date').data("DateTimePicker").minDate(e.date);
        });
        $("#closing_date").on("dp.change", function (e) {
            $('#opening_date').data("DateTimePicker").maxDate(e.date);
        });
    });   

    var admissionCategoryList = ($id) =>{
        var master = "<option value=''>Select</option>";
        $.ajax({
            url:'{{route(get_route_guard().".merit.master")}}',
            type:'post',
            data:{
                'course_id':$id,
                '_token':"{{csrf_token()}}"
            },
            success:function(response){
                console.log(response);
                if(response.success == true){
                    $('#merit_master_id').html('');
                    $.each(response.data,function(k,v){
                        master += "<option value='"+v.id+"'>"+v.name+"</option>";
                    });
                    $('#merit_master_id').append(master);
                }
                else{
                    $('#merit_master_id').html('');
                    toastr.error('No merit list found', 'Oops!')
                }
                var quota = '<div class="col-md-12 table-responsive"><table class="table">';
                quota += '<tbody><tr>';
                
                
                $.each(response.admission_categories,function(k,v){
                     var cl = '';
                    if(v.course_seats && v.course_seats.is_selection_active == 1){
                        cl = 'activeBg';
                    }
                    quota += '<td class="'+cl+'">'+v.name+'</td>';
                    if(v.course_seats){
                        quota += '<td><span class="badge" style="background-color: #212121 !important;">'+v.course_seats.total_seats+'-'+v.course_seats.total_seats_applied+'</span></td>';
                    }else
                        quota += '<td><span class="badge" style="background-color: #212121 !important;">0</span></td>';
                });
                $('#admission_categories').html(quota);

                    

            },
            error:function(response){
                console.log(response);
            }

        })
    }
$('#course_id').change(function(){
    admissionCategoryList($(this).val());
})

$(document).ready(function(){
    // admissionCategoryList({{Request::get('course_id')}});
    // var id=$('#course_id').val();
    // $.ajax({
    //         url:'{{route(get_route_guard().".merit.master")}}',
    //         type:'post',
    //         data:{
    //             'course_id':id,
    //             '_token':"{{csrf_token()}}"
    //         },
    //         success:function(response){
    //             // console.log(response);
    //             // if(response.success == true){
    //             //     $('#merit_master_id').html('');
    //             //     $.each(response.data,function(k,v){
    //             //         master += "<option value='"+v.id+"'>"+v.name+"</option>";
    //             //     });
    //             //     $('#merit_master_id').append(master);
    //             // }
    //             // else{
    //             //     $('#merit_master_id').html('');
    //             //     toastr.error('No merit list found', 'Oops!')
    //             // }
    //             var quota = '<div class="col-md-8 table-responsive"><table class="table">';
    //             quota += '<tbody><tr>';
                
                
    //             $.each(response.admission_categories,function(k,v){
    //                  var cl = '';
    //                 if(v.course_seats && v.course_seats.is_selection_active == 1){
    //                     cl = 'activeBg';
    //                 }
    //                 quota += '<td class="'+cl+'">'+v.name+'</td>';
    //                 if(v.course_seats){
    //                     quota += '<td><span class="badge" style="background-color: #212121 !important;">'+v.course_seats.total_seats+'-'+v.course_seats.total_seats_applied+'</span></td>';
    //                 }else
    //                     quota += '<td><span class="badge" style="background-color: #212121 !important;">0</span></td>';
    //             });
    //             $('#admission_categories').html(quota);

                    

    //         },
    //         error:function(response){
    //             console.log(response);
    //         }

    //     })
});
$('#approve').click(function(){
    if ($(".merit_list:checked").length > 0)
        $('#myModal').modal('show');
    else
     toastr.error('Please check at least one application', 'Oops!')
})
$('#approvePayment').click(function(){
    if ($(".merit_list:checked").length > 0)
        $('#approvePaymentModal').modal('show');
    else
     toastr.error('Please check at least one application', 'Oops!')
})
checkSubmit = (msg) => {
    if ($(".merit_list:checked").length == 0){
        toastr.error('Please check at least one application', 'Oops!');
        return false;
    }        
    else
    return confirm(msg);
}

applicationSelected = function(){
        if(!$(".check:checked").length){
            toastr.error('Please select at-least one application to send sms.', 'Oops!');
            return false;
        }
        return true;
    }
    calculateSelectedApplication = function () {
        var counter = $(".check:checked").length;
        $("#counter").html(counter);
    }

    $(".check").change(function () {
        if ($(".check").length == $(".check:checked").length) {
            $("#checkAll").prop("checked", true);
        } else {
            $("#checkAll").prop("checked", false);
        }
        calculateSelectedApplication();
    });

    $('#valid_from').Zebra_DatePicker({
        direction: true,
        pair: $('#valid_to'),
        format: 'Y-m-d H:i'
    });

    $('#approve_valid_to').Zebra_DatePicker({
        direction: true,
        format: 'Y-m-d H:i'
    });
    $('#approve_valid_from').Zebra_DatePicker({
        direction: true,
        pair: $('#approve_valid_to'),
        format: 'Y-m-d H:i'
    });

    $('#valid_to').Zebra_DatePicker({
        direction: true,
        format: 'Y-m-d H:i'
    });

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
        calculateSelectedApplication();

    });
    showChangeCourseForm = function(obj){
        console.log(obj);
        var $this = $(obj);
        var $modal = $("#courseChangeModal");
        $modal.find("form").attr("action", $this.data("url"));
        $modal.modal("show");
    }
    showTransferSeat  = function(obj){
        console.log(obj);
        var $this = $(obj);
        var merit_list = $this.data("merit");
        console.log($this.data("merit"));
        var $modal = $("#courseTransferModal");
        $modal.find("#application_no").html(merit_list.application_no);
        $modal.find("#student_no").html(merit_list.student_id);
        $modal.find("#admitted_category").html(merit_list.admission_category.name);
        $modal.find("form").attr("action", $this.data("url"));
        $modal.modal("show");
    }
    instantApprove = function(e, obj){
        alert("okk");
        var $this = $(obj);
        e.preventDefault();
        // if(confirm("Are you sure ? Wanted to approve the candidate.")){
        //     toastr.success("Successfully approved.")
        //     $.post($this.data("url")).
        //     done(function(){
        //         $this.hide();
        //     })
        // }
        swal({
            title: "Are you sure?",
            text: "Once approved, you will not be able to revert the changes!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(willDelete){
                $.post($this.data("url"), {
                    "_token": '{{ csrf_token() }}'
                }, "json")
                .done((response) => {
                    console.log(response);
                    if(response.status == true){
                        swal(response.message ?? "Success", {
                            icon: "success",
                        });
                        $this.hide(function(){
                            $(this).remove();   
                        });
                        $this.parent("span").html(response.button_text)
                    }else{
                        swal(response.message ?? "Failed", {
                            icon: "error",
                        });
                    }
                })
                .fail((error) => {
                    swal("No action is taken.");
                    console.log("error");
                });
            }else{
                swal("No action is taken.");
            }
        })
        .catch((error) => {
            swal("Whoops!! something went wrong.");
        });
    }
    $('input:checkbox').click(function(){
    var $inputs = $('input:checkbox')
        if($(this).is(':checked')){
           $inputs.not(this).prop('disabled',true); // <-- disable all but checked one
        }else{
           $inputs.prop('disabled',false); // <--
        }
    })
</script>

{{-- <script>
    $(document).ready(function() {
        $(document).load(function() {
            alert("ok");
            $("html, body").animate({
                scrollTop: $(
                  'html, body').get(0).scrollHeight
            }, 2000);
        });
    });
</script> --}}
@endsection 
