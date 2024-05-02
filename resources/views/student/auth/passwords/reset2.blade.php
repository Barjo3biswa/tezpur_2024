@extends('layouts.website3')

@section('content')
<div class="container landing_block register_block">
    <div class="row">
        <div class="col-md-12 text-center">

            <div class="login_box register_box">
                <h2 class="title_login"> New Password </h2>
                <form class="login100-form validate-form" action="{{ route('student.password.request') }}" method="POST"  onsubmit="return LoginEncrypter(this)" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <label for="code">E-Mail Address </label>
                            <div class="wrap-input100 validate-input" data-validate="Email">
                                <input class="input100 form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    type="email" name="email" placeholder="Email" id="email" value="{{ old('email') }}"
                                    autofocus spellcheck=false size="18" autocomplete="off" required autofocus>
                                @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>    
                        </div>

                        <div class="col-sm-6">
                            <label for="code">Captcha <span class="mt-10"><img class="text-right" src="{!!captcha_src()!!}" alt="captcha" style="border:1px solid black;"> <a id="refreshCaptcha" class="btn btn-link" href="#"><i class="fa fa-refresh fa-2x"></i></a></label>
                            <div class="wrap-input100 validate-input " data-validate="Enter password">
                                <input class="input100 form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}" type="text" name="captcha" placeholder="Captcha" required>
                                @if ($errors->has('captcha'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('captcha') }}
                                    </div>
                                @endif
                            </div>
                        </div>                      
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <label for="code">Password </label>
                            <div class="wrap-input100 validate-input " data-validate="Enter password">
                                <input class="input100 form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Password" size="50" spellcheck="false" autocomplete="new-password" id="password" required
                                data-html="true" 
                                data-toggle="popover" 
                                data-trigger="focus" 
                                title="Password Conditions" 
                                data-content="<ul  id='d1' class='list-group'>
                                        {{-- <li class='list-group-item list-group-item-success'>Password Conditions</li> --}}
                                        <li class='list-group-item' id=d12><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Upper Case Letter</li>
                                        <li class='list-group-item' id=d13 ><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter </li>
                                        <li class='list-group-item' id=d14><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Char </li>
                                        <li class='list-group-item' id=d15><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Number</li>
                                        <li class='list-group-item' id=d16><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Length 8 Char</li>
                                    </ul>">
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <label for="code">Confirm Password </label>
                            <div class="wrap-input100 validate-input " data-validate="Enter Confirm Password">
                                <input class="input100 form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" type="password" name="password_confirmation"
                                    placeholder="Confirm Password"  minlength="8" required id="password-confirm">
                                    @if ($errors->has('password_confirmation'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn"> Reset Password </button>
                    </div>



                </form>

            </div>

        </div>

    </div>
</div>

@endsection
@section('js')
<script>
    $(window).on("load", function(){
        $('input#password').popover({trigger:'focus'});
    });
    $('#password').keyup(function(){
        console.log("Key Up event Fired");
        var str=$('#password').val();
        var upper_text= new RegExp('[A-Z]');
        var lower_text= new RegExp('[a-z]');
        var number_check=new RegExp('[0-9]');
        var special_char= new RegExp('[!/\'!$#%@\]');
        flag = 'T';
        if(str.match(upper_text)){
            $('#d12').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Upper Case Letter ");
            $('#d12').css("color", "green");
            }else{$('#d12').css("color", "red");
            $('#d12').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Upper Case Letter ");
            flag='F';
        }
        
        if(str.match(lower_text)){
            console.log("reached");
            $('#d13').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Lower Case Letter ");
            $('#d13').css("color", "green");
        }else{$('#d13').css("color", "red");
            $('#d13').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter ");
            flag='F';
        }
        
        if(str.match(special_char)){
            $('#d14').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Special Char ");
            $('#d14').css("color", "green");
        }else{
            $('#d14').css("color", "red");
            $('#d14').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Char ");
            flag='F';
        }
        
        if(str.match(number_check)){
            $('#d15').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Number ");
            $('#d15').css("color", "green");
        }else{
            $('#d15').css("color", "red");
            $('#d15').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Number ");
            flag='F';
        }
        
        
        if(str.length>7){
            $('#d16').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Length 8 Char ");
            
            $('#d16').css("color", "green");
        }else{
            $('#d16').css("color", "red");
            $('#d16').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Length 8 Char ");
        
            flag='F';
        }
        if(flag=='T'){
            // $("#d1").fadeOut();
            $('#display_box').css("color","green");
            $('#display_box').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> "+str);
        }else{
            // $("#d1").show();
            $('#display_box').css("color","red");
            $('#display_box').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> "+str);
        }
    });
    $("#password").on('shown.bs.popover', function(){
        $('#password').keyup();
    });
    confirmPasswordCondition = function(){
        if(flag == "F"){
            alert("Please match password Conditions.");
            $("#password").focus();
            return false;
        }
        return true;
    }
</script>    
@endsection