<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Admission Portal 2023 - Shortlisted Candidates.</title>

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
                        <h1> Shortlisted Candidates </h1>
                    </div>
                    <div class=WordSection1>
                        @foreach ($shortlisted as $prog)
                            <strong>For {{$prog->name}}</strong>
                            @foreach ($prog->shortlistedCandidate as $key => $list)
                                <ul style="list-style: none">
                                    <li><a href="{{ asset('notifications') . '/' . $list->folder_name . '/' . $list->file_name }}" target="_blank"><strong>{{++$key}}. {{$list->heading}}</strong>
                                        @if($list->is_new==1)
                                            <span class="badge badge-danger blink"> New </span>
                                        @endif
                                    </a></li>
                                </ul>
                            @endforeach
                        @endforeach
                    </div>
                    <button class="btn btn-primary" onclick="history.back()">Go Back</button>
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