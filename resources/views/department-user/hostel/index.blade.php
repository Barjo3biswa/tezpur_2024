@extends($layot)
@section('css')
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
@section('content')
    @php
        $castes = \App\Models\Caste::pluck('name', 'id')->toArray();
        $btech_programs = \App\Course::whereIn('id', btechCourseIds())
            ->withTrashed()
            ->get();
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Call Students For Admission Process: </div>
                    <div class="panel-body">
                        <form {{-- action="{{route(get_route_guard().'.merit.automate')}}" method="post" --}}>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="Programme" class="label-control">Programme:</label>
                                    <select name="course_id" id="course_id" class="form-control js-example-basic-single"
                                        style="width:100% !important" autocomplete="off">
                                        <option value="">All</option>
                                        @if (auth('admin')->check())
                                            @foreach ($courses as $key => $course)
                                                <option value="{{ $course->id }}"
                                                    @if (request()->get('course_id') == $course->id) selected @endif>{{ $course->name }}
                                                    ({{ $course->code }})
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach (programmes_array() as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ request()->get('course_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">

                                    <input type="submit" class="btn btn-success" name="submit" value="Search">
                                </div>
                            </div>
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
                    <div class="panel-body">
                        <div id="admission_categories"></div>
                        {{-- <form name="merit" id="merit" action="{{ route(get_route_guard().'.merit.call-for-admission') }}" 
                        method="POST">
                        {{ csrf_field() }} --}}

                        {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                            style="margin-left: 80%">
                            Proceed To Time Selection
                        </button> --}}

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#
                                        {{-- <input class="form-check-input checkAll" type="checkbox" id="checkAll"> --}}
                                    </th>
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Prog. Name</th>
                                    <th>Name</th>
                                    <th>Admt. Cat.</th>
                                    <th>Social Cat.</th>
                                    <th>Gender</th>
                                    <th>Rank</th>
                                    <th>Hostel</th>
                                    <th>P. Type</th>
                                    <th>Preference</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($merit_lists as $key=>$list)
                                    <tr>
                                        <td>{{ ++$key }}
                                        </td>
                                        @include('onlineAdmission.table-data')
                                        <td>
                                            @if($list->hostel_required==1)
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#myModal" onclick="assignId({{$list->id}})">Assign Hostel</button>

                                                <a href="{{ route(get_route_guard() . '.merit.no-hostel', Crypt::encrypt($list->id)) }}" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?');">No Hostel</a>
                                                <a href="{{ route(get_route_guard() . '.merit.later-hostel', Crypt::encrypt($list->id)) }}" class="btn btn-warning btn-sm"
                                                        onclick="return confirm('Are you sure?');">Hostel Will be allocated later </a>
                                            @elseif($list->hostel_required==3 && $list->course_id!=80)
                                               <a href="{{ route(get_route_guard() . '.merit.hostel-process-payment', Crypt::encrypt($list->id)) }}" class="btn btn-success btn-sm">Proceed for Payment</a>
                                            @elseif($list->hostel_required==3 && $list->course_id==80)
                                                <a href="{{ route(get_route_guard() . '.a-r-f', Crypt::encrypt($list->id)) }}" class="btn btn-primary btn-sm">Print ARF</a>
                                            @elseif($list->hostel_required==4 && $list->course_id!=80)
                                               <a href="{{ route(get_route_guard() . '.hostel-receipt', Crypt::encrypt($list->id)) }}" class="btn btn-primary btn-sm">Hostel Payment Receipt</a>
                                            @endif

                                            @if(in_array($list->hostel_required,[0,4,5,6]))
                                                <a href="{{ route(get_route_guard() . '.a-r-f', Crypt::encrypt($list->id)) }}" class="btn btn-primary btn-sm">Print ARF</a>
                                                <br/>
                                                <a href="{{ route(get_route_guard() . '.application.payment-reciept', Crypt::encrypt($list->application->id)) }}"
                                                    target="_blank"><button type="button" class="btn btn-success btn-xs">Application Payment Receipt</button></a>
                                                <a href="{{ route(get_route_guard() . '.application.show', Crypt::encrypt($list->application->id)) }}" target="_blank"><button
                                                    type="button" class="btn btn-success btn-xs" >View </button></a>
                                            @endif

                                            


                                        </td>
                                    </tr>
                                       
                                    
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    <form  action="{{ route(get_route_guard() . '.merit.assign-hostel') }}" method="POST">
                        {{ csrf_field() }}
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close"
                                            data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Enter Hostel Details</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="ml_id" id="ml_id">
                                                <label for=""> Hostel Name</label>
                                                {{-- <input type="text" class="form-control"
                                                    name="hos_name"> --}}
                                                    <select class="form-control"
                                                    name="hos_name">
                                                        <option value="">--select--</option>
                                                        @foreach ($hostels as $hos)
                                                            <option value="{{$hos->name}}">{{$hos->name}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Seat No</label>
                                                <input type="text" class="form-control"
                                                    name="hos_room_no">
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-md-12">
                                                <label for="">bed No</label>
                                                <input type="text" class="form-control"
                                                    name="bed_no">
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"
                                            >Submit</button>
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
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
    <script>
        function assignId(id){
            $("#ml_id").val(id);
        }
    </script>
@endsection
