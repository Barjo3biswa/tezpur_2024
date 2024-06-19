<div class="modal fade bd-accept-modal-lg{{ $key }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h3 class="modal-title">Accept Invitation page</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST"
                action="{{ route(get_route_guard().'.online-admission.accept-invite', Crypt::encrypt($merit_list->id)) }}"
                onSubmit="return confirm('Are you sure ?')">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="row col-md-6">
                        
                        <label for="">Current Preference </label><br />
                        <select name="preference" class="form-control" required>
                            <option value="">--select--</option>
                            @if($applied_preference)
                            <option value="1" {{$applied_preference->preference==1?'selected':''}}>1</option>
                            <option value="2" {{$applied_preference->preference==2?'selected':''}}>2</option>
                            {{-- <option value="3" {{$applied_preference->preference==3?'selected':''}}>3</option> --}}
                            @else
                            <option value="1">1</option>
                            <option value="2">2</option>
                            {{-- <option value="3">3</option> --}}
                            @endif
                        </select>
                    </div>
                    <br /><br /><br /><br />
                    <div  class="row col-md-12" style="color:rgb(158, 33, 33)">
                        {{-- <span><strong>Note:</strong></span><br /> --}}
                        <span><strong>Note:</strong> Please Provide your preference against this program.</span><br />
                        {{-- <span><strong>Floating:</strong> Floating means the candidate has kept the option of taking an
                            alternative programme if available.</span> --}}
                    </div><br /><br />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm"> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
