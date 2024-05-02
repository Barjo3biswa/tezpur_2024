@extends('student.layouts.auth')
@section('content')
    <div class="container" id="page-content">
        <div class="col-md-12">
            <label>
                * 1. CAT/MAT/ATMA/XAT Details (only for MBA student) (if not enter <span class="text-info">NA,
                    Zero</span>)
            </label>
            <form action="{{route('student.application.update_mba_form_new',Crypt::encrypt($application->id))}}" method="POST">
                {{ csrf_field() }}
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name of the Exam</th>
                                        <th>Registration No.</th>
                                        <th>Date of Exam</th>
                                        <th>Score Obtained</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // public $appli = $application;
                                        function getData($application,$examname,$field){
                                            foreach($application->extraExamDetails as $mbaexam){
                                                if($mbaexam->name_of_the_exam==$examname){
                                                    return $mbaexam[$field];
                                                }
                                            }
                                        }
                                    @endphp
                                
                                    <tr>
                                        <th>CAT</th>
                                        <th><input type="text" class="form-control" name="reg_no[CAT]" value="{{getData($application,'CAT','registration_no')}}"></th>
                                        <th><input type="date" class="form-control" name="date_of_exam[CAT]" value="{{getData($application,'CAT','date_of_exam')}}"></th>
                                        <th><input type="text" class="form-control" name="score[CAT]" value="{{getData($application,'CAT','score_obtained')}}"></th>
                                    </tr>
                                    <tr>
                                        <th>MAT</th>
                                        <th><input type="text" class="form-control" name="reg_no[MAT]" value="{{getData($application,'MAT','registration_no')}}"></th>
                                        <th><input type="date" class="form-control" name="date_of_exam[MAT]" value="{{getData($application,'MAT','date_of_exam')}}"></th>
                                        <th><input type="text" class="form-control" name="score[MAT]" value="{{getData($application,'MAT','score_obtained')}}"></th>
                                    </tr>
                                    <tr>
                                        <th>XAT</th>
                                        <th><input type="text" class="form-control" name="reg_no[XAT]" value="{{getData($application,'XAT','registration_no')}}"></th>
                                        <th><input type="date" class="form-control" name="date_of_exam[XAT]" value="{{getData($application,'XAT','date_of_exam')}}"></th>
                                        <th><input type="text" class="form-control" name="score[XAT]" value="{{getData($application,'XAT','score_obtained')}}"></th>
                                    <tr>
                                        <th>GMAT</th>
                                        <th><input type="text" class="form-control" name="reg_no[GMAT]" value="{{getData($application,'GMAT','registration_no')}}"></th>
                                        <th><input type="date" class="form-control" name="date_of_exam[GMAT]" value="{{getData($application,'GMAT','date_of_exam')}}"></th>
                                        <th><input type="text" class="form-control" name="score[GMAT]" value="{{getData($application,'GMAT','score_obtained')}}"></th>
                                    </tr>
                                    <tr>
                                        <th>CMAT</th>
                                        <th><input type="text" class="form-control" name="reg_no[CMAT]" value="{{getData($application,'CMAT','registration_no')}}"></th>
                                        <th><input type="date" class="form-control" name="date_of_exam[CMAT]" value="{{getData($application,'CMAT','date_of_exam')}}"></th>
                                        <th><input type="text" class="form-control" name="score[CMAT]" value="{{getData($application,'CMAT','score_obtained')}}"></th>
                                    </tr>   

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="update" style="float: right">
        </form>
        </div>
    </div>
@endsection
