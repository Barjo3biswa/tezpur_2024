@extends('student.layouts.auth')
@section('css')
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
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
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
@section("content")
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                {{-- @foreach($courses as $key=>$course)
                    <div class="row">
                        <div class="col-md-12">
                                <div class="box box-danger" @if($key%2 == 0) style="border-top-color: ##40494e !important;" @endif>
                                    <div class="box-header with-border text-center">
                                        <strong style="font-size:14px" class="">{{$course->name}} {{$course->code}}</strong>
                                    </div>
                                    <div class="box-body">
                                        @if($course->courseSeats->count())
                                            @foreach($course->courseSeats as $key=>$courseSeat)
                                                <div class="col-md-3">
                                                    <div class="box box-light">
                                                        <div class="box-header with-border text-center">
                                                            <strong style="font-size:12px" class="">
                                                                @if($courseSeat->admissionCategory->id==1)
                                                                    Unreserved
                                                                @else
                                                                    {{$courseSeat->admissionCategory->name}}
                                                                @endif
                                                            </strong>
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
                                                                        @php
                                                                            $total_remaining = intval($courseSeat->total_seats)-intval($courseSeat->total_seats_applied);
                                                                            if($courseSeat->id == 667){
                                                                                $total_remaining = intval($courseSeat->total_seats)-intval($courseSeat->total_seats_applied);
                                                                            }
                                                                            $total_withdrawal = $courseSeat->withdrawalSeatsCount();
                                                                            if($total_withdrawal > $total_remaining){
                                                                                $total_withdrawal = $total_remaining;
                                                                            }elseif($total_remaining <= 0){
                                                                                $total_withdrawal = 0;
                                                                            }
                                                                        @endphp                                                           
                                                                        <td  class="text-center"><span class="badge" style="background:#000;font-size:16px">{{$courseSeat->total_seats}}</span></td>
                                                                        <td  class="text-center"><span class="badge" style="background:#ff0000;font-size:16px">{{$courseSeat->total_seats_applied}}</span></td>
                                                                        <td class="text-center"><span class="badge" style="background:#00a65a;font-size:16px">{{$total_remaining}}</span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach  
                                        @endif
                                    </div>
                                </div>
                        </div> 
                    </div>
                @endforeach     --}}

                @if ($flag==1)
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primaryy" style="background:#000">Total</button>
                        <button class="btn btn-danger">Occupied</button>
                        <button class="btn btn-success">Vacant</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="background: #e51c23;color: #fff; font-size: 15px;" width=28%>Course Name</th>
                                    <th style="background: #e51c23;color: #fff; font-size: 15px;" width="12%">Unreserved</th>
                                    <th style="background: #e51c23;color: #fff; font-size: 15px;" width="12%">ST</th>
                                    <th style="background: #e51c23;color: #fff; font-size: 15px;" width="12%">SC</th>
                                    <th style="background: #e51c23;color: #fff; font-size: 15px;" width="12%">OBC (Non Creamy Layer)</th>
                                    <th style="background: #e51c23;color: #fff; font-size: 15px;" width="12%">EWS</th>
                                    {{-- <th width="12%">PWD</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($btech_courses as $key => $course)
                                    @if ($course->id != 83)
                                        <tr>
                                            <th style="background: #e51c23;color: #fff; font-size: 15px;">{{ $course->name }} {{ $course->code }}</th>
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
                @endif
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
                @endforeach
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
