<a class="btn btn-success btn-sm" data-toggle="modal" href='#approveModal{{$request->id}}'>Approve</a>
<div class="modal fade" id="approveModal{{$request->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Approve the request no: {{$request->id}} of {{$request->application->full_name}}</h4>
            </div>
            <form method="POST" action="{{route("department.merit.withdrawal-request.approve", $request)}}">

                <div class="modal-body">
                    {{ csrf_field() }}
                    {{-- <h3 class="text-danger">Are you sure ?</h3> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-ok"></i> Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
