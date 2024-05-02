
<div class="modal fade" id="{{$modal_id}}">
    <div class="modal-dialog">
        <div class="modal-content panel panel-danger">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="text-danger fa fa-exclamation-triangle"></i> You are going to decline your seat</h4>
            </div>
            <form method="POST" action="{{route(get_route_guard().".admission.declined-seat", $merit->id)}}">
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{-- <div class="form-group">
                        <p class="text-info"><i class="fa fa-info-circle"></i>  Please provide some information why you want to decline your seat. Also verify it's really you by verifying your OTP.</p>
                    </div> --}}
                    {{-- <div class="form-group">
                        <label for="otp" class="control-label">Enter OTP</label>
                        <div class="input-group">
                            <input type="text" id="otp" name="otp" class="form-control" placeholder="OTP" aria-describedby="otp-addon" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onClick="sendOTP(event, this)" data-url="{{route("student.declining-otp", $merit->id)}}">SEND OTP</button>
                            </span>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="reason" class="control-label">Choose from the list</label>
                        
                        <select name="reason_from_list" id="reason_from_list" class="form-control" required="required">
                            <option value="">--Choose--</option>
                            @foreach(\App\Helpers\CommonHelper::admission_decline_rules() as $value)
                                <option value="{{$value}}" {{old("reason_from_list") == $value ? "selected" : ""}}>{{$value}}</option>
                            @endforeach
                        </select>   
                    </div>
                    <div class="form-group">
                        <input type="radio" id="student" name="decline_by" value="student" required>
                        <label for="student">Decline by Student</label><br>
                        @if(checkPermission(2)==true)
                        <input type="radio" id="authority" name="decline_by" value="Admission Officer" required>
                        <label for="authority">Decline by Admission Officer</label><br>
                        @elseif(checkPermission(3)==true)
                        <input type="radio" id="authority" name="decline_by" value="Payment Officer" required>
                        <label for="authority">Decline by Payment Officer</label><br>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="otp" class="control-label">Reason for declining <small class="text-mute">(max 1000 character)</small></small></label>
                        <textarea name="reason" id="inputreason" minlength="10" maxlength="1000" class="form-control" rows="3" required="required" placeholder="Reason between 10 to 500 characters.">{{old("reason")}}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-sm">Proceed </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    sendOTP = function(event, obj){
        var $this = $(obj);
        $.get($this.data("url"))
        .done(function(response){
            console.log(response);
            swal({
                title: "SUCCESS!",
                text: "Your OTP is successfully sent. Please check your mobile.!",
                icon: "success",
            });
        })
        .fail(function(error){
            console.log(error);
            swal({
                title: "FAILED!",
                text: error.responseJSON.message ?? "Unable ot send OTP. Please try again later.",
                icon: "error",
            });
        });
    }
</script>