@extends('department-user.layout.auth')
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#
                                    </th>
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Applied For</th>
                                    <th>Name</th>
                                    <th>Social Cat.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($application as $key=>$app )
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$app->student_id}}</td>
                                        <td>{{$app->application_no}}</td>
                                        <td>
                                            @foreach ($app->applied_courses as $k=>$cou)
                                                {{++$k}}. {{$cou->course->name}}<br/>
                                            @endforeach
                                        </td>
                                        <td>{{$app->FullName}}</td>
                                        <td>{{$app->caste->name}}</td>
                                        {{-- <td>
                                            @if($app->merit_list->count()==0)
                                                <a href="{{route(get_route_guard().".jossa.assign-branch",Crypt::encrypt($app->id))}}" class="btn btn-primary">Assign Branch & admission Category</a>
                                            @else
                                                <span class="btn btn-success btn-sm" >Branch Assigned</span>
                                            @endif 
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
@endsection
