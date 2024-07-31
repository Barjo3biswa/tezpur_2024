@extends('student.layouts.auth')

@section('content')
@section("css")
@endsection
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <form action="{{route('student.cuet-details.submit', Crypt::encrypt($application->id))}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="panel-body">      
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">CUET Application No</label>
                            <input type="text" class="form-control" name="cuet_app_no" value="{{$application->application_academic->cuet_roll_no}}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="">CUET Roll No</label>
                            <input type="text" class="form-control" name="cuet_roll_no" value="{{$application->application_academic->cuet_form_no}}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="">CUET Year</label>
                            <input type="text" class="form-control" name="cuet_year" value="{{$application->application_academic->cuet_year}}" required>
                        </div>
                    </div> 
                    <br>
                    @if ($application->is_cuet_ug==1)
                    {{-- Integrated B.Sc.B.Ed. && Integrated M.Com.(4+1 Years as per NEP 2020)--}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Roll No</label>
                                <input type="number" class="form-control" name="roll_no[]" value="">
                            </div>
                            <div class="col-md-4">
                                <label for="">Paper Code</label>
                                <select name="course_code[]" class="form-control" readonly required>
                                    <option value="1" selected>English</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">CUET Marks (Obtained)</label>
                                <input type="number" class="form-control" name="marks[]" required>
                            </div>
                            
                            {{-- <div class="col-md-4">
                                <label for="">CUET Percentile Score</label>
                                <input type="text" class="form-control" name="percentile[]" required>
                            </div> --}}
                        </div>
                        <br>
                    @endif
                    
                    @php
                        $count=0;
                    @endphp

                    @if ($application->is_cuet_ug==1)
                        @foreach ($application->applied_courses as $key=>$applied)
                            @foreach ($applied->cuet_course_code as $course)
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" name="roll_no[]" value="roll_no">
                                    </div>
                                    <div class="col-md-4">
                                        <select name="course_code[]" class="form-control" readonly>
                                            <option value="{{$course->id}}" selected>{{$course->subject_name}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" name="marks[]" value="{{ old('marks.' . $count) }}">
                                    </div>
                                    {{-- <div class="col-md-4">
                                        @if ($count == 0 && $application->is_cuet_pg==1)
                                            <label for="">CUET Percentile Score</label>
                                        @endif
                                        <input type="text" class="form-control" name="percentile[]" value="{{ old('percentile.' . $count) }}">
                                    </div> --}}
                                </div> 
                                @php
                                    $count = $count+1;
                                @endphp
                                <br>
                            @endforeach   
                        @endforeach 
                    @endif

                    @if ($application->is_cuet_pg==1)
                        @foreach ($application->applied_courses as $key=>$applied)
                            @foreach ($applied->cuet_course_code as $course)
                                <div class="row">
                                    <div class="col-md-4">
                                        @if ($count == 0 && $application->is_cuet_pg==1)
                                            <label for="">Paper Code</label>
                                        @endif
                                        <select name="course_code[]" class="form-control" readonly>
                                            <option value="{{$course->id}}" selected>{{$course->subject_name}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        @if ($count == 0 && $application->is_cuet_pg==1)
                                            <label for="">Total Marks (Obtained)</label>
                                        @endif
                                        <input type="number" class="form-control" name="marks[]" value="{{ old('marks.' . $count) }}">
                                    </div>
                                    {{-- <div class="col-md-4">
                                        @if ($count == 0 && $application->is_cuet_pg==1)
                                            <label for="">CUET Percentile Score</label>
                                        @endif
                                        <input type="text" class="form-control" name="percentile[]" value="{{ old('percentile.' . $count) }}">
                                    </div> --}}
                                </div> 
                                @php
                                    $count = $count+1;
                                @endphp
                                <br>
                            @endforeach   
                        @endforeach 
                    @endif
                     
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">CUET Admit Card</label>
                            <input type="file" name="cuet_admit_card" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="">CUET Score Card</label>
                            <input type="file" name="cuet_score_card" class="form-control" required>
                        </div>
                    </div>

                    <br>
                    <div class="row" style="display:flex; justify-content: center;">
                        <input type="submit" class="btn btn-primary" name="Submit" onclick="return confirm('Are you sure you want Submit? After submit no modification will be allowed.');">
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

@endsection
