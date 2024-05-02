@extends('student.layouts.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Email verification</div>

                <div class="panel-body">
                    @if(session()->has("status"))
                        <div class="alert alert-warning">
                            <strong>Notice:</strong> {{session()->get("status")}}
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a class="btn btn-link" href="{{ route('student.resend-email-verification') }}">
                                Resend verification link
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection