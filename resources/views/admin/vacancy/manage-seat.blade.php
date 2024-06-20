@extends('admin.layout.auth')
@section('css')
    <link rel="{{ asset('css/latest_toastr.min.css') }}" rel="stlyesheet">
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
        }

        label.date_time {
            font-weight: normal;
            font-weight: bold;
            line-height: 3.3rem;
        }

        .box.box-danger {
            border-top-color: #319DD3;
        }

        .box.box-light {
            border-top-color: #b4e6ff;
        }

        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }

        .box-body {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
        }

        
        .course-seat-container {
            display: flex;
            flex-direction: column;
        }
        .header-row, .row {
            display: flex;
        }
        .header-cell, .cell {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .header-cell {
            font-weight: bold;
            background-color: #f1f1f1;
        }
        .row-div {
            display: flex;
            align-items: center;
        }
        .cell {
            padding: 8px;
        }
        .form-control {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            @include('admin/vacancy/filter')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="course-seat-container">
                                    <div class="header-row">
                                        <div class="header-cell">A. Category</div>
                                        <div class="header-cell">Total Seat</div>
                                        <div class="header-cell">Filled Out</div>
                                        <div class="header-cell">Temp Filled Out</div>
                                        <div class="header-cell">admission_flag</div>
                                        <div class="header-cell">invitation_flag</div>
                                        <div class="header-cell">admission_date</div>
                                        <div class="header-cell">Action</div>
                                    </div>
                                    @foreach ($course_seat as $seat)
                                    <form action="{{route('admin.vacancy.update-seat',$seat->id)}}" method="post">
                                        {{ csrf_field() }}
                                        <div class="row-div">
                                            <div class="cell">
                                                {{$seat->admissionCategory->name}}
                                            </div>
                                            <div class="cell">
                                                <input type="text" class="form-control" readonly name="total_seats" value="{{$seat->total_seats}}">
                                            </div>
                                            <div class="cell">
                                                <input type="text" class="form-control" readonly name="total_seats_applied" value="{{$seat->total_seats_applied}}">
                                            </div>
                                            <div class="cell">
                                                <input type="text" class="form-control" readonly name="temp_seat_applied" value="{{$seat->temp_seat_applied}}">
                                            </div>
                                            <div class="cell">
                                                <select name="admission_flag" id="admission_flag" class="form-control">
                                                    <option value="open" {{$seat->admission_flag=='open'?'selected':''}}>Open</option>
                                                    <option value="close" {{$seat->admission_flag=='close'?'selected':''}}>Close</option>
                                                </select>
                                            </div>
                                            <div class="cell">
                                                <select name="invitation_flag" id="invitation_flag" class="form-control">
                                                    <option value="open" {{$seat->invitation_flag=='0'?'selected':''}}>Open</option>
                                                    <option value="close" {{$seat->invitation_flag=='1'?'selected':''}}>Close</option>
                                                </select>
                                            </div>
                                            <div class="cell">
                                                <input type="text" class="form-control" name="admission_date" value="{{$seat->admission_date}}">
                                            </div>
                                            <div class="cell">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    @endforeach
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
@endsection
