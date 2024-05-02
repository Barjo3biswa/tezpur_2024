<a class="btn btn-danger btn-sm" data-toggle="modal" href='#rejectModal{{$request->id}}'>Reject</a>
<div class="modal fade" id="rejectModal{{$request->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger  text-white">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-white">Reject the request no: {{$request->id}} of {{$request->application->full_name}}</h4>
            </div>
            <form action="{{route("department.merit.withdrawal-request.reject", $request->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="remark">Remark <span class="text-danger font-bold">*</span></label>
                        <textarea name="remark" class="form-control" id="remark" cols="10" rows="3" placeholder="Remark"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
