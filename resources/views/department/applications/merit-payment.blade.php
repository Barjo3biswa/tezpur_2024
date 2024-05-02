@extends('department-user.layout.auth')
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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include ('admin/merit/filter')
                    </form>
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
                        <span class="pull-right">
                        </span>
                    </div>
                <div class="panel-body">
                    <div id="admission_categories"></div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Prog. Name</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>S. Category</th>
                                    <th>Gender</th>
                                    <th>Rank</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @forelse ($merit_lists as $key=>$merit_list)
                                    @php
                                        $tr_class_name = "";
                                        if($merit_list->attendance_flag == 1){
                                            $tr_class_name = "bg-success";
                                        }elseif($merit_list->attendance_flag == 2){
                                            // seat declined
                                            $tr_class_name = "bg-danger";
                                        }
                                    @endphp
                                    <tr class="{{ $tr_class_name}}">
                                        <td>
                                           {{++$key}}
                                        </td>
                                        <td>{{ $merit_list->student_id }}</td>
                                        <td>{{ $merit_list->application_no }}</td>
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

                                        <td>{{ $merit_list->application->first_name ?? "NA" }}
                                            {{ $merit_list->application->middle_name ?? "NA" }}
                                            {{ $merit_list->application->last_name ?? "NA" }}
                                        </td>
                                        <td>
                                            {{ $merit_list->admissionCategory->name }} 
                                            @if($merit_list->is_pwd)
                                            <span class="label label-danger">PWD</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-primary">{{ $castes[$merit_list->application->caste_id] ?? "NA" }}</span>
                                        </td>
                                        <td>{{ $merit_list->gender }}</td>
                                        <td>{{ $merit_list->student_rank }}</td>
                                        <td>
                                            <a href="{{route(get_route_guard().".application.show", Crypt::encrypt($merit_list->application->id))}}">
                                                <button type="button" class="btn btn-primary btn-sm">View</button>
                                            </a>
                                            @if($merit_list->is_payment_applicable == 1)
                                            <a href="{{route(get_route_guard().".admission.book.seat", Crypt::encrypt($merit_list->application->id))}}" target="blank">
                                                <button type="button" class="btn btn-danger btn-sm">Admission Seat Details</button>
                                            </a>
                                            @endif
                                            {{-- <form action="{{route(get_route_guard().".merit.ab-pre", Crypt::encrypt($merit_list->id))}}" method="post">
                                                {{csrf_field()}}
                                                <input type="hidden" name="flag" value="pre">
                                                <button type="submit" class="btn btn-success btn-sm">Prestnt</button>
                                            </form>
                                            <form action="{{route(get_route_guard().".merit.ab-pre", Crypt::encrypt($merit_list->id))}}" method="post">
                                                {{csrf_field()}}
                                                <input type="hidden" name="flag" value="abs">
                                                <button type="submit" class="btn btn-danger btn-sm">Absent</button>
                                            </form> --}}
                                        
                                        </td>
                                </tr>
                            @empty 
                                <p>No users</p>
                                @endforelse 

                            </tbody>
                        </table>
                        {{ $merit_lists->appends(request()->all()) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection