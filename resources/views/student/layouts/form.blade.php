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
            animation: blinker 2s linear infinite;
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
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @include('student.layouts.menu')
                    </ul>
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                        <li><a href="{{ route('student.login') }}">Login</a></li>
                        <li><a href="{{ route('student.register') }}">Register</a></li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-expanded="false" aria-haspopup="true" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('student.logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('student.logout') }}" method="POST"
                                    style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
        
    @if(env("MAINTENANCE"))
        <div class="container-fluid">            
            <div class="alert alert-danger" style="background-color:red; !important; color:white;">
                {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                <strong> {{env("MAINTENANCE_MESSAGE")}}</strong>
                </a>
            </div>
        </div>
    @endif
    @include("common/alert")
    @yield('content')
    <h4 class="text-center">Disclaimer : The eligibility criteria shown against a few programmes is
        subject to ratification.</h4>
</div>
@include('common/footer')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js?v=1.4.4') }}"></script>
    @yield('js')
</body>

</html>