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
                        @if (in_array(auth('department_user')->id(), [178, 180, 181, 182]))
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primaryy" style="background:#000">Total</button>
                                    <button class="btn btn-danger">Occupied</button>
                                    <button class="btn btn-success">Vacant</button>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width=28%>Course Name</th>
                                                <th width="12%">Unreserved</th>
                                                <th width="12%">ST</th>
                                                <th width="12%">SC</th>
                                                <th width="12%">OBC (Non Creamy Layer)</th>
                                                <th width="12%">EWS</th>
                                                {{-- <th width="12%">PWD</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($courses as $key => $course)
                                                @if ($course->id != 83)
                                                    <tr>
                                                        <th>{{ $course->name }} {{ $course->code }}</th>
                                                        @foreach ($course->courseSeats as $key => $courseSeat)
                                                            @if (in_array($courseSeat->admission_category_id, [1, 2, 3, 4, 6]))
                                                                <th>
                                                                    <span class="badge"
                                                                        style="background:#000;font-size:16px">{{ $courseSeat->total_seats }}</span>
                                                                    <span class="badge tezu-admission-{{ $courseSeat->id }}"
                                                                        style="background:#ff0000;font-size:16px">{{ $courseSeat->total_seats_applied }}</span>
                                                                    <span class="badge"
                                                                        id="tezu-admission-{{ $courseSeat->id }}"
                                                                        style="background:#00a65a;font-size:16px">{{ intval($courseSeat->total_seats) - intval($courseSeat->total_seats_applied) }}</span>
                                                                </th>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            @foreach ($courses as $key => $course)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-danger"
                                            @if ($key % 2 == 0) style="border-top-color: ##40494e !important;" @endif>
                                            <div class="box-header with-border text-center">
                                                <strong style="font-size:14px" class="">{{ $course->name }}
                                                    {{ $course->code }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($course_seat_type as $type)
                                    @if ($course->courseSeats->where('course_seat_type_id', $type->id)->count())
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="box box-danger"
                                                    @if ($key % 2 == 0) style="border-top-color: ##40494e !important;" @endif>
                                                    <div class="box-header with-border text-center">
                                                        <strong style="font-size:14px" class="">{{ $type->name }}</strong>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                @foreach ($course->courseSeats->where('course_seat_type_id', $type->id) as $key => $courseSeat)
                                                                    <div class="col-md-3">
                                                                        <div class="box box-light">
                                                                            <div class="box-header with-border text-center">
                                                                                <strong style="font-size:12px"
                                                                                    class="">{{ $courseSeat->admissionCategory->name }}</strong>
                                                                            </div>
                                                                            <div class="box-body">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <td class="text-center">Total
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                Filled-Up</td>
                                                                                            <td class="text-center">Vacant
                                                                                            </td>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td class="text-center"><span
                                                                                                    class="badge"
                                                                                                    style="background:#000;font-size:16px">{{ $courseSeat->total_seats }}</span>
                                                                                            </td>
                                                                                            <td class="text-center"><span
                                                                                                    class="badge tezu-admission-{{ $courseSeat->id }}"
                                                                                                    style="background:#ff0000;font-size:16px">{{ $courseSeat->total_seats_applied }}</span>
                                                                                            </td>
                                                                                            <td class="text-center"><span
                                                                                                    class="badge"
                                                                                                    id="tezu-admission-{{ $courseSeat->id }}"
                                                                                                    style="background:#00a65a;font-size:16px">{{ intval($courseSeat->total_seats) - intval($courseSeat->total_seats_applied) }}</span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                {{-- @if ($course->courseSeats->count())
                                            @foreach ($course->courseSeats->where('course_seat_type_id', 1) as $key => $courseSeat)
                                                <div class="col-md-3">
                                                    <div class="box box-light">
                                                        <div class="box-header with-border text-center">
                                                            <strong style="font-size:12px" class="">{{$courseSeat->admissionCategory->name}}</strong>
                                                        </div>
                                                        <div class="box-body">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <td class="text-center">Total</td>
                                                                        <td  class="text-center">Filled-Up</td>
                                                                        <td  class="text-center">Vacant</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td  class="text-center"><span class="badge" style="background:#000;font-size:16px">{{$courseSeat->total_seats}}</span></td>
                                                                        <td  class="text-center"><span class="badge tezu-admission-{{$courseSeat->id}}" style="background:#ff0000;font-size:16px">{{$courseSeat->total_seats_applied}}</span></td>
                                                                        <td class="text-center"><span class="badge" id="tezu-admission-{{$courseSeat->id}}" style="background:#00a65a;font-size:16px">{{intval($courseSeat->total_seats)-intval($courseSeat->total_seats_applied)}}</span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach  
                                        @endif --}}
                            @endforeach
                        @endif
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
    <script type="text/javascript">
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });
        var channelName = 'tezu-admission-channel';
        var channel = pusher.subscribe(channelName);
        channel.bind('pusher:subscription_succeeded', function(members) {
            //console.log(members);
            // alert('successfully subscribed!');
        });
        channel.bind('course-seat', function(data) {
            $('.tezu-admission-' + data.response.id).html(data.response.total_seats_applied);
            $('#tezu-admission-' + data.response.id).html(data.response.vacant_seats);
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr["success"](data.response.display_message);

        });
    </script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $("button[type='reset']").on("click", function() {
            $(".filter input").attr("value", "").val("");
            $(".filter").find("select").each(function(index, element) {
                $(element).find("option").each(function() {
                    if (this.defaultSelected) {
                        this.defaultSelected = false;
                        // this.selected = false;
                        $(element).val("").val("all");
                        return;
                    }
                });
            });
        });
        resetPassword = function(string) {
            if (!confirm("Change Password ?")) {
                return false;
            }
            var ajax_post = $.post('{{ route('admin.applicants.changepass') }}', {
                "_token": '{{ csrf_token() }}',
                'user_id': string
            });
            ajax_post.done(function(response) {
                alert(response.message);

            });
            ajax_post.fail(function() {
                alert("Failed Try again later.");
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
@endsection
