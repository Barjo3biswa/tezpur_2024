@extends('layouts.website3')

@section('content')
<div class="container landing_block">
    <div class="row">
        <div class="col-md-12 text-center">

            <div class="login_box">
                <h2 class="title_login"> Reset Password </h2>
                <form class="login100-form validate-form" action="{{ route('student.password.email') }}" method="POST"
                    onsubmit="return LoginEncrypter(this)" autocomplete="off">
                    {{ csrf_field() }}
                    @if(session()->has("status"))
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{session()->get("status")}}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <label for="code">E-Mail Address </label>
                    <div class="wrap-input100 validate-input" data-validate="Email">
                        <input class="input100 form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            type="email" name="email" placeholder="Email" id="mobile_no" value="{{ old('mobile_no') }}"
                            autofocus spellcheck=false size="18" autocomplete="off" required>
                        @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                        @endif
                    </div>

                    <label for="code">Captcha <img class="text-right" src="{!!captcha_src()!!}" alt="captcha"
                            style="border:1px solid black;"> <a id="refreshCaptcha" class="btn btn-link" href="#"><i class="fa fa-refresh fa-2x"></i></a></label>
                    <div class="wrap-input100 validate-input " data-validate="Enter password">
                        <input class="input100 form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                            type="text" name="captcha" placeholder="Captcha" required>
                        @if ($errors->has('captcha'))
                        <div class="invalid-feedback">
                            {{ $errors->first('captcha') }}
                        </div>
                        @endif
                    </div>


                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn"> Send Password Reset Link </button>
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>
@endsection