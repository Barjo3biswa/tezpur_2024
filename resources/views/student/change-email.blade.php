@extends('student.layouts.auth')

@section('content')
<div class="container landing_block register_block">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2 class="title_login"> Change Email </h2>
            <form  method="POST" class="login100-form validate-form" action="{{ route('student.save-change-email') }}"  onSubmit="return LoginEncrypter();" autocomplete="off">
                {{ csrf_field() }}
                <div class="row"> 
                    <div class="col-md-3"></div> 
                    <div class="col-md-6">
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
                </div><br/>
                <div class="row">  
                    <div class="col-md-3"></div>    
                    <div class="col-md-5">
                        <button class="btn btn-primary"> Save Change </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection