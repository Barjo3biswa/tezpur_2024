<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> {{env("INSTITUTE_NAME")}} Admissions 2020 Portal </title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset("vendor/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("vendor/owl/assets/owl.carousel.min.css")}}" rel="stylesheet">
    <link href="{{asset("vendor/owl/assets/owl.theme.default.min.css")}}" rel="stylesheet">
    <link href="{{asset("vendor/bootstrap/css/media.css?v=1.1")}}" rel="stylesheet">
    <link href="{{asset("vendor/bootstrap/css/style.css")}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset("favicon.ico")}}" type="image/x-icon">
    <link rel="icon" href="{{asset("favicon.ico")}}" type="image/x-icon">
    <style>
        .text-italic{
            font-style: italic;
        }
        .form-control-uppercase{
            text-transform: uppercase;
        }

        .navbar-brand {
            text-align: left !important;
            padding-left: 45px !important;
            padding-top: 10px !important;
        }
        footer a{
            color:white;
        }
        .footer_a{
            color: #fff;
            font-weight: bold;
            font-size: 13px;
            text-transform: capitalize;
        }
        .label{
            display: inline;
            padding: .2em .6em .3em;
            /* font-size: 75%; */
            font-weight: 700;
            line-height: 1;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }
        .label-danger {
            background-color: #e51c23;
        }

        .owl-nav {
            position: absolute;
            top: 43% ;
            width: 100%;
        }
        button.owl-prev {
            position: absolute;
            left: 0;
        }

        button.owl-prev , button.owl-next {
            height: 40px;
            width: 40px;
            background-color: #0f1645 !important;
            color: #fff !important;
            font-size: 23px !important;
        }
        button.owl-next {
            position: absolute;
            right: 0;
        }

        .header h1 {
            font-size: 25px;
            line-height: 35px;
            color: #020831;
            position : relative;
        }
        .header p {
            margin: 10px 0px;
        }

        .last-date {
            background-color: #e60c0c;
            color: #fff;
            position: absolute;
            top:0 ; 
            right: 15px;
            padding: 10px 10px;
        }
        .last-date h4 {
            margin: 0;
            font-size: 12px;
        }

        .guidline-text p {
            font-size: 13px;
            line-height: 21px;
        }

        .guidline-text h4 {
            font-size: 15px;
            margin-bottom: 8px;
        }

        .guidline-text span.note {
            color: #e60c0c;
            font-size: 13px;
        }

        .guidline-text h3 {
            color: #2436b1;
            font-size: 18px;
        }

        @media(max-width:480px){
            .last-date {
            position: relative;
            right: 0;
            }
        } 
    </style>
    @yield("css")


    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2185394485027597');
        fbq('track', 'PageView');
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162541354-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-162541354-1');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=2185394485027597&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
    </head>

<body>

    <header id="header">
        <div class="container">


            <div class="top-logo-part">
                <div class="row">
                    <div class="col-md-4 main-logo">
                        <a class="navbar-brand" href="{{url("/")}}"> <img
                                src="{{asset("vendor/bootstrap/images/logo.png")}}" alt="logo"></a>
                        <ul class="log_reg_btns">
                            <li><a href="{{route("student.login")}}" class="login_btn"> Login </a></li>
                            <li><a href="{{route("student.register")}}" class="reg_btn"> New Registration </a></li>
                        </ul>
                    </div>

                    <div class="col-md-8 call-no text-right">
                        
                    </div>

                    <div class="col-md-4 text-right">
                        
                    </div>

                </div>
            </div>

        </div>
    </header>

    <section class="main_banner">
        @if(env("MAINTENANCE"))
            <a href="{{asset("notifications/btech-doc-upload-notification-2.pdf")}}" target="_blank">
                <h4 class="label label-danger" style="position: fixed; right:0px; top:0px; z-index: 999999;">{{env("MAINTENANCE_MESSAGE")}}</h4>
            </a>
        @endif

        @yield('content')
    </section>

    <footer class="footer_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <a href="{{route("terms")}}" class="footer_a">Terms & Conditions</a>&nbsp;&nbsp;&nbsp;
                        <a href="{{route("privacy")}}" class="footer_a">Privacy Policy</a>&nbsp;&nbsp;&nbsp;
                        <a href="{{route("refund")}}" class="footer_a">Refund Policy</a>&nbsp;&nbsp;&nbsp;
                        <span  class="footer_a">Copyright © {{env("INSTITUTE_NAME")}} . All rights reserved</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="{{asset("vendor/jquery/jquery.min.js")}}"></script>
    <script src="{{asset("vendor/owl/owl.carousel.min.js")}}"></script>
    <script src="{{asset("vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <script>
        $(document).ready(function(){
          $('[data-toggle="popover"]').popover();
        });

        $('.owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            responsiveClass:true,
            navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
            autoplay:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:2,
                    nav:false
                },
                1000:{
                    items:2,
                    nav:true,
                    loop:true
                }
            }
        });
    </script>
    @yield("js")
</body>

</html>