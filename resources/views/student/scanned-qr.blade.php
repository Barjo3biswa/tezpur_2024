<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @yield("page_title")</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/zebra_bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/inputmask.css') }}" rel="stylesheet">
    <link href="{{asset("css/font-awesome.min.css")}}" rel="stylesheet">
    <link href="{{asset("css/loader.css")}}" rel="stylesheet">
    <link rel="icon" href="{{asset("favicon.ico")}}" type="image/x-icon" />
    <style>
        .navbar-default {
            background-color: #319dd3;
            border-color: #1a6184;
        }

        .navbar-default .navbar-brand {
            color: #fcfeff;
        }

        .navbar-default .navbar-brand:hover,
        .navbar-default .navbar-brand:focus {
            color: #ffffff;
        }

        .navbar-default .navbar-text {
            color: #fcfeff;
        }

        .navbar-default .navbar-nav>li>a {
            color: #fcfeff;
        }

        .navbar-default .navbar-nav>li>a:hover,
        .navbar-default .navbar-nav>li>a:focus {
            color: #ffffff;
        }

        .navbar-default .navbar-nav>.active>a,
        .navbar-default .navbar-nav>.active>a:hover,
        .navbar-default .navbar-nav>.active>a:focus {
            color: #ffffff;
            background-color: #1a6184;
        }

        .navbar-default .navbar-nav>.open>a,
        .navbar-default .navbar-nav>.open>a:hover,
        .navbar-default .navbar-nav>.open>a:focus {
            color: #ffffff;
            background-color: #1a6184;
        }

        .navbar-default .navbar-toggle {
            border-color: #1a6184;
        }

        .navbar-default .navbar-toggle:hover,
        .navbar-default .navbar-toggle:focus {
            background-color: #1a6184;
        }

        .navbar-default .navbar-toggle .icon-bar {
            background-color: #fcfeff;
        }

        .navbar-default .navbar-collapse,
        .navbar-default .navbar-form {
            border-color: #fcfeff;
        }

        .navbar-default .navbar-link {
            color: #fcfeff;
        }

        .navbar-default .navbar-link:hover {
            color: #ffffff;
        }

        @media (max-width: 767px) {
            .navbar-default .navbar-nav .open .dropdown-menu>li>a {
                color: #fcfeff;
            }

            .navbar-default .navbar-nav .open .dropdown-menu>li>a:hover,
            .navbar-default .navbar-nav .open .dropdown-menu>li>a:focus {
                color: #ffffff;
            }

            .navbar-default .navbar-nav .open .dropdown-menu>.active>a,
            .navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover,
            .navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus {
                color: #ffffff;
                background-color: #1a6184;
            }
        }
        #page-content{
            background: #ffffff;
            padding: 5px;
            min-height: 80vh;
        }
        #app{
            min-height: 90vh;
            /* background: #fcfeff; */
        }
        .error{
            color:#a94442;
        }
        .is-invalid{
            border-color: #a94442;
        }
        .show-on-print{
            display: none;
        }
        {!!"@media"!!} print {
            a[href]:after {
                display: none;
                visibility: hidden;
            }
            footer{
                display: none;
            }
            .dont-print{
                display: none;
            }
            .show-on-print{
                display: initial;
            }
            .alert{
                display: none;
                visibility: hidden;
            }
        }
        .blink {
            animation: blinker 4s linear infinite;
        }
        
        @keyframes blinker {
            70% {
                opacity: 0;
            }
        }
    </style>
    @yield("css")
</head>

<body> 
    @include('common/application/show')
</div>
@include('common/footer')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>
    <script src="{{ asset('js/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/zebra_datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.inputmask.bundle.min.js') }}"></script>
    <script>
    $(document).ready(function(){
        $(":input").inputmask();
        $(".loading").fadeOut();
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    </script>
    @yield('js')
</body>

</html>