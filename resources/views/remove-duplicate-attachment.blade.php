@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Program Control                 
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <table class="table table-responsive">
                                    <thead>
                                        <th>Duplicate Estimated</th>
                                        <th>{{$attachments_cnt}}</th>
                                        <th><a href="{{route("admin.duplicate-attachments-delete")}}" class="btn btn-danger">Delete All Estimated</a></th>                                       
                                    </thead>                                   
                                </table>
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