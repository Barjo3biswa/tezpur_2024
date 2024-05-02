<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
</head>
<body>
    <div id="app">
        <div class="container-fluid" style="min-height:100px; margin-bottom:20px;">
        <img src="{{asset("banner.jpg")}}" alt="" class="img-responsive">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-6">
                    @include("student.guidelines")
                
                </div>
                <div class="col-md-4 col-md-offset-1 col-sm-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @include('common/footer')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js?v=1.4.4') }}"></script>
    <script src="{{ asset('js/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/bootstrap-notify.min.js') }}"></script>
</body>
</html>
