<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> {{env("APP_NAME_2")}} - Terms & conditions  </title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset("vendor/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("vendor/bootstrap/css/style3.css")}}" rel="stylesheet">
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
<h1> Terms & Conditions </h1>
</div>

<div class="main_text">
<p>This website is designed, updated and maintained by Tezpur Admissions 2021.</p>
<p>Though all efforts have been made to ensure the accuracy of the content on this website, the same should not be construed as a statement of law or used for any legal purposes. In case of any ambiguity or doubt, users are advised to verify/check with Tezpur Admissions 2021 and/or other source(s), and to obtain appropriate professional advice.</p>
<p>Under no circumstances will Tezpur Admissions 2021 be liable for any expense, loss or damage including, without limitation, indirect or consequential loss or damage, or any expense, loss or damage whatsoever arising from use, or loss of use, of data, arising out of or in connection with the use of this website.</p>
<p>These terms and conditions shall be governed by and construed in accordance with the Indian Laws. Any dispute arising under these terms and conditions shall be subject to the jurisdiction of the courts of India.</p>
<p>The information posted on this website could include hypertext links or pointers to information created and maintained by non-Government / private organizations. Tezpur Admissions 2021 is providing these links and pointers solely for your information and convenience. When you select a link to an outside website, you are leaving the Tezpur Admissions 2021 website and are subject to the privacy and security policies of the owners/sponsors of the outside website. Tezpur Admissions 2021, does not guarantee the availability of such linked pages at all times. Tezpur Admissions 2021 cannot authorize the use of copyrighted materials contained in linked websites. Users are advised to request such authorization from the owner of the linked website. Tezpur Admissions 2021, does not guarantee that linked websites comply with Indian Government Web Guidelines.</p>
<p><strong>Disclaimer</strong><br>
This website of the Tezpur Admissions 2021 is being maintained for information purposes only. Even though every effort is taken to provide accurate and up to date information, officers making use of the circulars posted on the website are advised to get in touch with the Tezpur Admissions 2021 whenever there is any doubt regarding the correctness of the information contained therein. In the event of any conflict between the contents of the circulars on the website and the hard copy of the circulars issued by Tezpur Admissions 2021, the information in the hard copy should be relied upon and the matter shall be brought to the notice of the Tezpur Admissions 2021.</p> 
    
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
    {{-- <script src="{{asset("vendor/jquery/jquery.min.js")}}"></script>
    <script src="{{asset("vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script> --}}

  </body>

</html>
