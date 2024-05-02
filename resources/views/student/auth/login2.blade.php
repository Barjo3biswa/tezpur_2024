@extends('layouts.website3')

@section('content')
<div class="container landing_block">
    <div class="row">
        <div class="col-md-12 text-center">

            <div class="login_box">
                <h2 class="title_login"> Login </h2>
                <form class="login100-form validate-form" action="{{ route('student.login') }}" method="POST"  onsubmit="return LoginEncrypter(this)" autocomplete="off">
                    {{ csrf_field() }}
                    @if(session()->has("status"))
                        <div class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{session()->get("status")}}
                        </div>
                    @endif
                    <label for="code">Email / Mobile Number <small class="text-danger">(without isd code)</small></label>
                    <div class="wrap-input100 validate-input" data-validate="Email / Mobile No">
                        <input class="input100 form-control {{ $errors->has('mobile_no') ? 'is-invalid' : '' }}" type="text" name="mobile_no" placeholder="Email / Mobile No" id="mobile_no" value="{{ old('mobile_no') }}" autofocus  spellcheck=false size="18" autocomplete="off" required>                        
                        @if ($errors->has('mobile_no'))
                            <div class="invalid-feedback">
                                {{ $errors->first('mobile_no') }}
                            </div>
                        @endif
                    </div>
                        

                    <label for="code">Password </label>
                    <div class="wrap-input100 validate-input " data-validate="Enter password">
                        <input class="input100 form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Password" size="50" spellcheck="false" autocomplete="new-password" id="password" required>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <label for="code">Captcha <img class="text-right" src="{!!captcha_src()!!}" alt="captcha" style="border:1px solid black;"> <a id="refreshCaptcha" class="btn btn-link" href="#"><i class="fa fa-refresh fa-2x"></i></a></label>
                    <div class="wrap-input100 validate-input " data-validate="Enter password">
                        <input class="input100 form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}" type="text" name="captcha" placeholder="Enter the value" required>
                        @if ($errors->has('captcha'))
                            <div class="invalid-feedback" style="margin-bottom:14px;">
                                {{ $errors->first('captcha') }}
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('student.password.request') }}" class="forgot" style="margin-top:12px;"> Forgot Password </a>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn"> Login </button>
                    </div>



                </form>

            </div>

        </div>

    </div>
</div>

@endsection
@section("js")

<script src="{{asset("js/aes.js")}}"></script>
<script src="{{asset("js/aes-json-format.js")}}"></script>
@php
    $admin_login_crypt = md5(time().uniqid());
    // $crypt2 = md5(date('Y-md h:is').uniqid());

    \Session::put('admin_login_crypt', $admin_login_crypt);
    // Session::set('crypt2', $crypt2);
@endphp
<script>
    LoginEncrypter = function(Obj){
        var encrypted_pass = CryptoJS.AES.encrypt(jQuery("#password").val(), "{{$admin_login_crypt}}", {format: CryptoJSAesJson}).toString();
        // var decrypted_pass = CryptoJS.AES.decrypt(encrypted_pass, "123456", {format: CryptoJSAesJson}).toString();
        // console.log(encrypted_pass);
        // console.log(decrypted_pass);
        jQuery("#password").val(encrypted_pass);
        return true;
    }
</script>
@endsection