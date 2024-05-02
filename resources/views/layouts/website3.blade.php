<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> {{env("INSTITUTE_NAME")}} Admission Portal </title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset("vendor/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("vendor/bootstrap/css/media.css?v=1.1")}}" rel="stylesheet">
    <link href="{{asset("vendor/bootstrap/css/style2.css?v=1.1")}}" rel="stylesheet">
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
    {{-- </style>

    <style> --}}
    .dropbtn {
      background-color: #3498DB;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 13px;
    border: none;
    border-radius: 4px;
    padding: 3px 18px;
    text-transform: uppercase;
    font-weight: bold;
    }
    
    .dropbtn:hover, .dropbtn:focus {
      background-color: #2980B9;
    }
    
    .dropdown {
      position: relative;
      display: inline-block;
    }
    
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 160px;
      overflow: auto;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
      min-width: 200px;
      border-radius: 6px;
      top: 28px;
      background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(2.8px);
    border: 1px solid rgba(255, 255, 255, 0.26);
    }
    
    .dropdown-content a {
      color: #ffff;
      text-decoration: none;
      display: block;
      padding: 6px 10px;
      text-align:left;
      border-bottom: thin solid #dfdfdf;
    }
    
    .dropdown a:hover {background-color: #ddd;color:#111111}
    ul.log_reg_btns {
    margin-top: 45px;
    display: flex;
    align-items: center;
    justify-content: center;}
    .show {display: block;}
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
                    <div class="col-md-2 main-logo text-center">
                        <a class="navbar-brand" href="{{url("/")}}"> <img
                                src="{{asset("vendor/bootstrap/images/logo.png")}}" alt="logo"></a>
                    </div>

                    <div class="col-md-6 call-no text-right">
                        <span> For Technical queries <a href=""> {{env("CONTACT_NO", "+91 9999999999")}} </a> </span>
                        <span> For general TU Admission queries <a href=""> {{env("GEN_QUERY", "+91 9999999999")}} </a> </span>
                        {{-- <span> For MBA related queries <a href=""> +91 8638182977" </a> </span> --}}
                    </div>

                    <div class="col-md-4 text-right">
                        <ul class="log_reg_btns">
                            <li><a href="{{route("student.login")}}" class="login_btn"> Login </a></li>
                            {{-- <li><a href="{{route("student.register")}}" class="reg_btn"> New Registration </a></li> --}}
                            {{-- <li><a class="reg_btn" href="{{route('student.register',['is_mba'=>Crypt::encrypt(0)])}}">New Registration</a></li>
                            <li><a class="reg_btn" href="{{route('student.register',['is_mba'=>Crypt::encrypt(1)])}}">New Registration MBA</a></li> --}}
                            <div class="dropdown">
                                <button onclick="myFunction()" id="" class="dropbtn login_btn">New Registration</button>
                                <div id="myDropdown" class="dropdown-content">
                                  {{-- <a href="{{route('student.register',['is_mba'=>Crypt::encrypt('PHD')])}}">Ph.D(Spring 24-25) Registration</a> --}}
                                  {{-- <a href="{{route('student.register',['is_mba'=>Crypt::encrypt('PG')])}}">TUEE(PG) Registration</a>
                                  <a href="{{route('student.register',['is_mba'=>Crypt::encrypt('LATERAL')])}}">Lateral Entry Registration</a> --}}
                                  {{-- <a href="{{route('student.register',['is_mba'=>Crypt::encrypt('BTECH')])}}">B.Tech (JoSSA-CSAB)</a> --}}
                                  {{-- <a href="{{route('student.register',['is_mba'=>Crypt::encrypt('MBA')])}}">MBA Registration</a> --}}
                                  {{-- <a href="{{route('student.register',['is_mba'=>Crypt::encrypt('UG')])}}">UG(Through CUET)</a> --}}
                                  {{-- <a href="{{route('student.register',['is_mba'=>Crypt::encrypt('MBBT')])}}">MBBT</a> --}}
                                  
                                </div>
                            </div>
                        </ul>
                    </div>


                </div>
            </div>

        </div>
    </header>

    <section class="main_banner">
        @if(env("MAINTENANCE"))
            <h4 class="label label-danger" style="position: fixed; right:0px; top:0px; z-index: 999999;">{{env("MAINTENANCE_MESSAGE")}}</h4>
        @endif
        @yield('content')
    </section>

    <footer class="footer_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p> Copyright © {{env("INSTITUTE_NAME")}} . All rights reserved </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="{{asset("vendor/jquery/jquery.min.js")}}"></script>
    <script src="{{asset("vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
            $(document).on("click", "#refreshCaptcha", function(e){
            var $this = $(this);
            e.preventDefault();
            $this.prev("img").remove();
            $(this).addClass("fa-spin");
            var url = '{{route("refresh.captcha")}}';
            var xhr = $.get(url);
            xhr.done(function(response){
                $this.before(response)
                $this.removeClass("fa-spin");
            });
            xhr.fail(function(response){
                alert("Failed. try again.");
                $this.removeClass("fa-spin");
            });
          })
        });
    </script>
    <script>
        /* When the user clicks on the button, 
        toggle between hiding and showing the dropdown content */
        function myFunction() {
          document.getElementById("myDropdown").classList.toggle("show");
        }
        
        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
          if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
              var openDropdown = dropdowns[i];
              if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
              }
            }
          }
        }
        </script>
    @yield("js")
</body>

</html>