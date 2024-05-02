<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Admission Portal 2023 - Important Data</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/bootstrap/css/style3.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
    <style>
        ol>li {
            margin-bottom: 5px;
        }

        .content_block {
            height: 60vh;
        }

        ol>li p {
            font-style: italic;
        }

        .btn-primary {
            background-color: #0e0c54;
            border-color: #0e0c54;
        }
    </style>
</head>

<body>

    <header id="header" style="position:relative;">
        <div class="container">
            <div class="top-logo-part">
                <div class="row">
                    <div class="col-md-12 main-logo text-left" style="margin-top:0px;">
                        <a class="navbar-brand" href="{{ url("/") }}"> <img src="vendor/bootstrap/images/logo.png" alt="logo"></a>
                    </div>

                    <!-- <div class="col-md-10 call-no text-right">
                        <span> For Information  <a href=""> +91 99999 99999 </a> </span>
                    </div> -->

                </div>
            </div>

        </div>

    </header>

    <section class="content_block">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sub_header">
                        <h1> Please Provide All The Details As possible. </h1>
                    </div>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <div class=WordSection1>
                        <form action="{{route('tezu-important-data-save')}}" method="post">
                            {{ csrf_field() }}
                            {{-- {{dd($errors->roll_no)}} --}}
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">Enter Your TUEE Roll No :</label>
                                    <input class="form-control" type="number" name="roll_no" value="{{ old('roll_no') }}">
                                    @if($errors->has('roll_no'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('roll_no') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Enter Your Application No :</label>
                                    <input class="form-control" type="text" name="application_no" value="{{ old('application_no') }}">
                                    @if($errors->has('application_no'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('application_no') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">Class X Mathematics Mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <input class="form-control" type="number" name="class_x" value="{{ old('class_x') }}">
                                    @if($errors->has('class_x'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('class_x') }}
                                        </div>
                                    @endif
                                    
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Class XII Mathematics Mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <input class="form-control" type="number" name="class_xii" value="{{ old('class_xii') }}">
                                    @if($errors->has('class_xii'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('class_xii') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label class="col-form-label">Graduation Mathematics Mark:</label></br>
                                    <label class="col-form-label">Lets Take An Example:</label></br>
                                    <label class="col-form-label">Suppose I want to enter marks for 1st sem and I have two papers </br> Paper 1 and paper 2 </br> then, (Paper 1 + Paper 2) have to enter summation value.</br>If marks in CGPA/SGPA then i have to connvert it to percentage with respect to university or board guideline then enter the marks. And so on for other semesters.</label></br>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">1st semester Mathematics mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <div class="row">
                                        <input class="form-control col-sm-5" type="number" name="first_tot" value="{{ old('first_tot') }}" placeholder="Total Marks">
                                        @if($errors->has('first_tot'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('first_tot') }}
                                            </div>
                                        @endif
                                        <input class="form-control col-sm-5" type="number" name="first" value="{{ old('first') }}" placeholder="Marks Obtained">
                                        @if($errors->has('first'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('first') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">2nd semester Mathematics mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <div class="row">
                                        <input class="form-control col-sm-5" type="number" name="second_tot" value="{{ old('second_tot') }}" placeholder="Total Marks">
                                        @if($errors->has('second_tot'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('second_tot') }}
                                            </div>
                                        @endif
                                        <input class="form-control col-sm-5" type="number" name="second" value="{{ old('second') }}" placeholder="Marks Obtained">
                                        @if($errors->has('second'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('second') }}
                                            </div>
                                        @endif
                                    </div>                                   
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">3 rd semester Mathematics mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <div class="row">
                                        <input class="form-control col-sm-5" type="number" name="third_tot" value="{{ old('third_tot') }}" placeholder="Total Marks">
                                        @if($errors->has('third_tot'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('third_tot') }}
                                            </div>
                                        @endif
                                        <input class="form-control col-sm-5" type="number" name="third" value="{{ old('third') }}" placeholder="Marks Obtained">
                                        @if($errors->has('third'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('third') }}
                                            </div>
                                        @endif
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">4th semester Mathematics mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <div class="row">
                                        <input class="form-control col-sm-5" type="number" name="fourth_tot" value="{{ old('fourth_tot') }}" placeholder="Total Marks">
                                        @if($errors->has('fourth_tot'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('fourth_tot') }}
                                            </div>
                                        @endif
                                        <input class="form-control col-sm-5" type="number" name="fourth" value="{{ old('fourth') }}" placeholder="Marks Obtained">
                                        @if($errors->has('fourth'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('fourth') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">5 th semester Mathematics mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <div class="row">
                                        <input class="form-control col-sm-5" type="number" name="fifth_tot" value="{{ old('fifth_tot') }}" placeholder="Total Marks">
                                        @if($errors->has('fifth_tot'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('fifth_tot') }}
                                            </div>
                                        @endif
                                        <input class="form-control col-sm-5" type="number" name="fifth" value="{{ old('fifth') }}" placeholder="Marks Obtained">
                                        @if($errors->has('fifth'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('fifth') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">6 th semester Mathematics mark <span style="color:rgb(184, 13, 13)">(If CGPA then convert your CGPA to Percentage)</span>:</label>
                                    <div class="row">
                                        <input class="form-control col-sm-5" type="number" name="sixth_tot" value="{{ old('sixth_tot') }}" placeholder="Total Marks">
                                        @if($errors->has('sixth_tot'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('sixth_tot') }}
                                            </div>
                                        @endif
                                        <input class="form-control col-sm-5" type="number" name="sixth" value="{{ old('sixth') }}" placeholder="Marks Obtained">
                                        @if($errors->has('sixth'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('sixth') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                    
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="submit" class="btn btn-primary" value="Submit" onclick="return confirm('Are you sure you want Submit !! once you have submited you can`t modify so please check again. ');">
                                </div>
                            </div>
                        </form>



                    </div>
                </div>
    </section>

    <footer>
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <ul class="footer_links">
                            <li><a href="{{ route("terms") }}">Terms & Conditions</a></li>
                            <li><a href="{{ route("privacy") }}">Privacy Policy</a></li>
                            <li><a href="{{ route("refund") }}">Refund Policy</a></li>
                            <li><a href="#">Copyright © {{ env("INSTITUTE_NAME") }} . All rights
                                    reserved</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>