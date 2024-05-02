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
                                        <th>Program Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($programs as $prog)
                                            <tr>
                                                <td>{{$prog->name}}</td>
                                                <td>{{$prog->deleted_at!=null?'Closed':'Open'}}</td>
                                                <td><a href="{{route("admin.open-close-change",Crypt::encrypt($prog->id))}}" class="btn btn-primary btn-xs">close</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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