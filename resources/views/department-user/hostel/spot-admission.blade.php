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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Allow New Students For Spot Admission</div>
                    <div class="panel-body">
                        <form action="{{route(get_route_guard() . '.merit.spot-save')}}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="Programme" class="label-control">Programme:</label>
                                    <select name="course_id" id="course_id" class="form-control js-example-basic-single"
                                        style="width:100% !important" autocomplete="off">
                                        <option value="">All</option>
                                        @foreach (programmes_array() as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ request()->get('course_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <label for="Programme" class="label-control">Name:</label>
                                    <input type="text" class="form-control" value="" name="s_name" required>
                                </div>
                                <div class="col-sm-12">
                                    <label for="Programme" class="label-control">Mobile No:</label>
                                    <input type="text" class="form-control" value="" name="m_no" required>
                                </div>
                            
                                <div class="col-sm-12"></br>
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Allowed Students For Spot Admission</div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile No</th>
                                    <th>Programm</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $key=>$admiss)
                                    <tr>
                                        <th>{{++$key}}</th>
                                        <th>{{$admiss->name}}</th>
                                        <th>{{$admiss->mobile_no}}</th>
                                        <th>{{$admiss->course->name}}</th>
                                        <th><a href="{{route(get_route_guard() . '.merit.spot-delete',Crypt::encrypt($admiss->id))}}" class="btn btn-danger btn-xs">Delete</a></th>
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
    <script>
        function assignId(id){
            $("#ml_id").val(id);
        }
    </script>
@endsection
