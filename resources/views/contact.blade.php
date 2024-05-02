<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Admission Portal 2023 - Frequently Asked Questions (FAQs)</title>

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
                        <h1> Contact-us </h1>
                    </div>
                    <div class=WordSection1>
                        <strong>For General Queries for TU Admission</strong>
                        <ol>
                            <li>+913712273149,</li>
                            <li>+913712273169</li>
                            <li>+91 9954449473 (for BTech admission through NE quota)</li>
                            (From 9.30 am to 5.30 pm)
                            <li>WhatsApp us @ +91 9957184355</li>
                           <li> e-mail id: asktuee@tezu.ernet.in</li>
                           <li> tubssc@tezu.ernet.in (for BTech admission through NE quota) </li>
                        </ol>
                        <strong>For Technical Queries</strong>
                        <ol>
                            <li>+91-8399894076, </li>
                            <li>+91-7002539044</li>
                            <li>tuadmission2023@gmail.com</li>

                        </ol>
                        {{-- <strong>For B.Tech. admission through NE quota :<strong>
                                   <ol>
                                       <li> +91 9954449473</li>
                                        
                                           <li> tubssc@tezu.ernet.in</li>
                                   </ol> --}}
        




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