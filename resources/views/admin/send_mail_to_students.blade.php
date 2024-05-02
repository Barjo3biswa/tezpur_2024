@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Send Mail              
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <table class="table table-responsive">
                                    <thead>
                                        <th>Total pending mail</th>
                                        <th>{{$sendable_mail}}</th>
                                    </thead>                                   
                                </table>
                                <form action="">
                                    <input type="number" class="form-control" name="count" placeholder="Enter the amount of mail you want to send">
                                    <input type="submit" class="btn btn-primary" value="Send">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection