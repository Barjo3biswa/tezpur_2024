<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> {{env("APP_NAME_2")}} - refund policy </title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/bootstrap/css/style3.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">

  </head>

  <body>

<header id="header" style="position:relative;">
<div class="container">


        <div class="top-logo-part">
        <div class="row">
<div class="col-md-12 main-logo text-left" style="margin-top:0px;">
        <a class="navbar-brand" href="{{url("/")}}"> <img src="vendor/bootstrap/images/logo.png" alt="logo"></a>
    </div>

<!--         <div class="col-md-10 call-no text-right">
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
<h1> Refund Policy </h1>
</div>

<div class="main_text">
<p>-Application fee is not refundable in any condition.<br>
<p>-Confirmation/ registration amount is non-refundable in any condition.</p>
<p>-No disputes regarding refunds will be entertained once the fees are paid.</p>
<p>-For more information contact / mail <a href="mailto:tuee2021@gmail.com">tuee2021@gmail.com</a></p>
    
</div>

</div>
</div>
</div>
</section>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="footer_links">
                    <li><a href="{{route("terms")}}">Terms & Conditions</a></li>
                    <li><a href="{{route("privacy")}}">Privacy Policy</a></li>
                    <li><a href="{{route("refund")}}">Refund Policy</a></li>
                    <li><a href="#">Copyright © {{env("INSTITUTE_NAME")}} . All rights reserved</a></li>
                </ul>
            </div>
        </div>
    </div>
    
</footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
