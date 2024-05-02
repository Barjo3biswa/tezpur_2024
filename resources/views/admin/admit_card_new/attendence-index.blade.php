

@extends('admin.layout.auth')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            <div class="filter dont-print">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="applicant_name_starting" class="label-control">Center Name</label>
                                        <select name="center_name" class="form-control">
                                            <option value="">--select--</option>
                                            @foreach ($exam_centers as $exam)
                                                <option value="{{ $exam->id }}"
                                                    {{ Request()->get('center_name') == $exam->id ? 'selected' : '' }}>
                                                    {{ $exam->center_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <div class="row">

                                    <div class="col-sm-3">
                                        <label for="submit" class="label-control"
                                            style="visibility: hidden;">Search</label><br>
                                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i>
                                            Filter</button>
                                        <button type="reset" class="btn btn-danger"><i class="fa fa-remove"></i>
                                            Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <table width="100%" class="table table-bordered">
                        @if (Request()->has('center_name'))
                                @if (auth('department_user')->check())
                                    @php
                                        $user_id = auth('department_user')->id();
                                        $department = DB::table('department_assigned_users')->where('department_user_id',$user_id)->first();
                                        $department_id = $department->department_id;
                                    @endphp
                                @endif
                            @foreach ($categories as $key=>$cat)
                                @if (auth('department_user')->check())
                                    @if ($cat->course->department_id==$department_id)
                                    <tr>
                                        <th>{{++$key}}</th>
                                        <th>{{$cat->course->name}}</th>
                                        <th>{{$cat->count}}</th>
                                        <th> <a href="{{route(get_route_guard() . '.print-view-attendence',['center_id'=>$center_id,'course_id'=>$cat->course_id])}}" class="btn btn-primary">View And Print</a> </th>
                                    </tr>
                                    @endif
                                @else
                                    <tr>
                                        <th>{{++$key}}</th>
                                        <th>{{$cat->course->name}}</th>
                                        <th>{{$cat->count}}</th>
                                        <th> <a href="{{route(get_route_guard() . '.print-view-attendence',['center_id'=>$center_id,'course_id'=>$cat->course_id])}}" class="btn btn-primary btn-xs">View And Print Attendence </a> </th>
                                        <th> <a href="{{route(get_route_guard() . '.print-view-attendence-admit',['center_id'=>$center_id,'course_id'=>$cat->course_id])}}" class="btn btn-primary btn-xs">Download All Admit Card </a> </th>
                                    </tr>
                                @endif
                            @endforeach
                            
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
