@extends('layouts.website3')
@section("css")
    <style>
        #d1{
            font-size: 13px;
        }
    </style>
@endsection
@section('content')
<div class="container landing_block register_block">
    <div class="row">
        <div class="col-md-12 text-center">

            <div class="register_box">
                <h2 class="title_login"> Registration </h2>
                <form class="login100-form validate-form"  method="POST" action="{{ route('student.register') }}"  onSubmit="return LoginEncrypter();" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="by_mba" value={{$is_mba}}>
                    <input type="hidden" name="t" value={{$t}}>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="email">First Name <strong class="text-danger">*</strong></label>
                            <div class="wrap-input100 validate-input" data-validate="Enter name">
                                <input class="input100 form-control form-control-uppercase {{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" name="first_name" placeholder="FIRST NAME" autofocus size="50" alt="first_name" required value="{{old("first_name")}}">
                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email">Middle Name </label>
                            <div class="wrap-input100 validate-input" data-validate="Middle Name">
                                <input class="input100 form-control form-control-uppercase {{ $errors->has('middle_name') ? ' is-invalid' : '' }}" type="text" name="middle_name" placeholder="MIDDLE NAME" size="50" alt="middle_name" value="{{old("middle_name")}}">
                                @if ($errors->has('middle_name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('middle_name') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email">Last Name</label>
                            <div class="wrap-input100 validate-input" data-validate="Last name">
                                <input class="input100 form-control form-control-uppercase {{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" name="last_name" placeholder="LAST NAME" size="50" alt="last_name" value="{{old("last_name")}}">
                                @if ($errors->has('last_name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-5">
                            <label for="email">Email <strong class="text-danger">*</strong></label>
                            <div class="wrap-input100 validate-input " data-validate="Enter email">
                                <input class="input100 form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" placeholder="Email" required value="{{old("email")}}">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="isd_code">ISD Code <strong class="text-danger">*</strong></label>
                            <select class="form-control input100 {{ $errors->has('isd_code') ? ' is-invalid' : '' }}" id="isd_code" name="isd_code" required>
                                @foreach (isd_codesNew($is_mba) as $code)
                                    <option value="{{$code}}" {{old("isd_code", null) == $code ? "selected" : ""}}>{{$code}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('isd_code'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('isd_code') }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <label for="code">Mobile No. <strong class="text-danger">*</strong></label>
                            <div class="wrap-input100 validate-input " data-validate="Enter Mobile No.">
                                <input class="input100 form-control {{$errors->has('mobile_no') ? 'is-invalid' : ''}}" type="text" name="mobile_no" placeholder="Mobile No." required value="{{old("mobile_no")}}" maxlength="10" minlength="10">
                                @if ($errors->has('mobile_no'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('mobile_no') }}</strong>
                                </div>
                            @endif
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="code">Password <strong class="text-danger">*</strong></label>
                            <div class="wrap-input100 validate-input " data-validate="Enter Password">
                                <input class="input100 form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Password"
                                   
                                    id="password" 
                                    data-html="true" 
                                    data-toggle="popover" 
                                    data-trigger="focus" 
                                    title="Password Conditions" 
                                    data-content="<ul  id='d1' class='list-group'>
                                            {{-- <li class='list-group-item list-group-item-success'>Password Conditions</li> --}}
                                            <li class='list-group-item' id=d12><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> <span> One Upper Case Letter</span></li>
                                            <li class='list-group-item' id=d13 ><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> <span>One Lower Case Letter </span></li>
                                            <li class='list-group-item' id=d14><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> <span>One Special Char !$#%@ </span></li>
                                            <li class='list-group-item' id=d15><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> <span>One Number</span></li>
                                            <li class='list-group-item' id=d16><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> <span>Min. 8 Char</span></li>
                                        </ul>" required>
                                
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="code">Confirm Password <strong class="text-danger">*</strong></label>
                            <div class="wrap-input100 validate-input " data-validate="Enter Confirm Password">
                                <input class="input100 form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" type="password" name="password_confirmation"
                                   id="password-confirm" placeholder="Confirm Password"  minlength="8" required>
                                    @if ($errors->has('password_confirmation'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </div>

                    @if($is_mba != "FOREIGN")
                    <div class="row">
                        <div class="col-md-5">
                            <input type="hidden" name="nationality" value="1">
                        </div>
                    </div>
                    @else 
                    <div class="row">
                        <div class="col-md-5">
                            <label for="code">Country of Origin <strong class="text-danger">*</strong></label>
                            <select class="form-control input100 {{ $errors->has('nationality') ? ' is-invalid' : '' }}" id="nationality" name="nationality" required>
                                <option value="">--select--</option>
                                @foreach($countries as $key=>$country)
                                <option value="{{$country->id}}" {{old("nationality", null) == $country->id ? "selected" : ""}}>{{$country->name}}</option>
                                @endforeach
                                
                            </select>
                            @if ($errors->has('nationality'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('nationality') }}</strong>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-2" id="flexible2">
                        </div>
                        <div class="col-md-5" id="flexible">
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        @if ($is_mba == "MDESS" /* || $is_mba=="PG" */)
                        <div class="col-md-6">
                            <label for="code">Examination Through <strong class="text-danger">*</strong></label>
                            <div class="wrap-input100 validate-input " data-validate="">
                                <select name="exam_through" id=""  class="input100 form-control {{ $errors->has('exam_through') ? ' is-invalid' : '' }}" required>
                                    <option value="">--select--</option>
                                    @foreach ($mdes_exam as $exam)
                                        <option value="{{$exam->exam_name}}">{{$exam->exam_name}}</option>
                                    @endforeach    
                                </select>  
                                @if ($errors->has('exam_through'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('exam_through') }}</strong>
                                </div>
                                @endif                             
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="code">Marks Obtained<strong class="text-danger">*</strong></label>
                            <div class="wrap-input100 validate-input " data-validate="">
                                <input name="marks_ob" id=""  class="input100 form-control {{ $errors->has('marks_ob') ? ' is-invalid' : '' }}" required>  
                                @if ($errors->has('marks_ob'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('marks_ob') }}</strong>
                                </div>
                                @endif                             
                            </div>
                        </div>
                        @endif
                        @if($is_mba=="UG")
                        <div class="col-md-12">
                            <label for="vehicle1">
                                {{-- <input type="checkbox" required id="declear_tu" name="declear_tu" value="1">  --}}
                                {{-- Yes, --}} I selected Tezpur University as my preference during CUET registration.
                            </label>{{-- <br> --}}
                            
                            <label for="html"><input type="radio" id="html" name="declear_tu" value="1" required>Yes</label>
                            
                            <label for="css"><input type="radio" id="css" name="declear_tu" value="0" required>No</label>
                            
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label for="code">Captcha <strong class="text-danger">*</strong> <img src="{!!captcha_src()!!}" alt="catcha" style="border:1px solid black;"> <a id="refreshCaptcha" class="btn btn-link" href="#"><i class="fa fa-refresh fa-2x"></i></a></label>
                            <div class="wrap-input100 validate-input " data-validate="">
                                <input class="input100 form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}" type="text" name="captcha" placeholder="Enter the value" required>
                                @if ($errors->has('captcha'))
                                    <div class="invalid-feedback" style="margin-bottom:14px;">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <a href="{{route("student.login")}}" class="forgot"> Already Registered? Sign in
                    </a>
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn"> Register </button>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>


<div class="modal fade" id="instructionModal" tabindex="-1" role="dialog" aria-labelledby="instructionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
           {{--  <div class="modal-header">
                <h5 class="modal-title" id="instructionModalLabel"><span class="text-danger">Instructions</span> <small>(Please follow the instructions)</small></h5>
            </div> --}}
            <div class="modal-body">
                {{-- <ol>
                    <li>Applicant name same as certificate. <small class="text-danger text-italic">Changes not allowed.</small></li>
                    <li>Own valid mobile no. <small class="text-danger text-italic">Verification needed.</small></li>
                    <li>Own valid email address. <small class="text-danger text-italic">Verification needed.</small></li>
                    <li>Strong password.<small class="text-danger text-italic">Combination of Capital, small, numeric and special character. </small></li>
                </ol> --}}
                @include('new_guidelines')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-success" data-dismiss="modal"><i class="fa fa-thumbs-up"></i> Proceed</button>
                <a href="{{url("/")}}"><button type="button" class="btn btn-sm btn-outline-danger"><i class="fa fa-thumbs-down"></i> Cancel</button></a>
            </div>
        </div>
    </div>
</div>

@endsection
@section("js")
    
@php
    $admin_login_crypt = md5(time().uniqid());
    // $crypt2 = md5(date('Y-md h:is').uniqid());

    \Session::put('admin_login_crypt', $admin_login_crypt);
    // Session::set('crypt2', $crypt2);
@endphp

<script src="{{asset("js/aes.js")}}"></script>
<script src="{{asset("js/aes-json-format.js")}}"></script>
<script>

function othercountry(){
    // alert($('#nationality').val());
    if($('#nationality').val()==5){
        var html = `<label for="code">Enter Country of Origin <strong class="text-danger">*</strong></label>
                    <input type="text" id="other_country" name="other_country" class="form-control" required>
                    @if ($errors->has('other_country'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('other_country') }}</strong>
                        </div>
                    @endif`;

        var html2=`<label for="isd_code">ISD Code <strong class="text-danger">*</strong></label>
                    <input type="text" id="other_isd_code" name="other_isd_code" class="form-control" required>
                    @if ($errors->has('isd_code'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('isd_code') }}</strong>
                        </div>
                    @endif
                    `;
        $("#flexible").empty();
        $("#flexible2").empty();
        $("#flexible2").append(html2);
        $("#flexible").append(html);        
    }else{
        $("#flexible").empty();
        $("#flexible2").empty();
    }
}

$("#nationality").change(function(){
    othercountry(); 
});





$(document).ready(function(){
//    var input = document.querySelector("#phone");
    // window.intlTelInput(input);
    @if(!$errors->any())
        $("#instructionModal").modal({
            keyboard:false,
            backdrop: 'static',
        });
    @endif
});
$(window).on("load", function(){
   
    othercountry(); 
    $('input#password').popover({trigger:'focus'});
});
$('#password').keyup(function(){
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
        $('#d13').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Lower Case Letter ");
        $('#d13').css("color", "green");
    }else{$('#d13').css("color", "red");
        $('#d13').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter ");
        flag='F';
    }
    
    if(str.match(special_char)){
        $('#d14').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Special Char !$#%@ ");
        $('#d14').css("color", "green");
    }else{
        $('#d14').css("color", "red");
        $('#d14').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Char !$#%@ ");
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
$("#password-confirm").on("keyup", function(){
    if($(this).val() != $("#password").val()){
        if($(this).next().length){
            $(this).next("small").text("Confirm Password Does not match.")
        }else{
            $(this).after("<small class='text-danger'>Confirm Password Does not match.</small>");
        }
    }else{
        $(this).next("small").hide(function(){
            $(this).remove();
        });
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
    if($("#password-confirm").val() != $("#password").val()){
        alert("Confirm password doesnot match.");
        $("#password-confirm").focus();
        return false;
    }
    return true;
}

LoginEncrypter = function(Obj){
    if(!confirmPasswordCondition()){
        return false;
    }
    var encrypted_pass = CryptoJS.AES.encrypt(jQuery("#password").val(), "{{$admin_login_crypt}}", {format: CryptoJSAesJson}).toString();
    var ecrypted_password_confirmation = CryptoJS.AES.encrypt(jQuery("#password-confirm").val(), "{{$admin_login_crypt}}", {format: CryptoJSAesJson}).toString();
    {{--  // var decrypted_pass = CryptoJS.AES.decrypt(encrypted_pass, "123456", {format: CryptoJSAesJson}).toString();
    // console.log(encrypted_pass);
    // console.log(decrypted_pass);--}}
    jQuery("#password").val(btoa(encrypted_pass));
    jQuery("#password-confirm").val(btoa(ecrypted_password_confirmation));
    return true;
}
</script>
@endsection