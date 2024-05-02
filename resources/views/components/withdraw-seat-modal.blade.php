
<div class="modal fade" id="{{$modal_id}}">
    <div class="modal-dialog">
        <div class="modal-content panel panel-danger">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="text-danger fa fa-exclamation-triangle"></i> You are going to withdraw your seat</h4>
            </div>
            <form method="POST" action="{{route("student.admission.withdraw-seat", $merit->id)}}">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <p class="text-info"><i class="fa fa-info-circle"></i>  Please provide some information why you want to withdraw your seat. Also verify it's really you by verifying your OTP, DOB and Bank details.</p>
                    </div>
                    <div class="form-group">
                        <label for="otp" class="control-label">Enter OTP <small class="text-danger">*</small></label>
                        <div class="input-group">
                            <input type="text" id="otp" name="otp" class="form-control" placeholder="OTP" aria-describedby="otp-addon" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onClick="sendOTP(event, this)" data-url="{{route("student.declining-otp", $merit->id)}}">SEND OTP</button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dob" class="control-label">DOB <small class="text-danger">*</small></label>
                        <input type="date" id="dob" name="dob" class="form-control" max="{{date("Y-m-d")}}" placeholder="DOB" aria-describedby="otp-addon" required value="{{old("dob")}}">
                    </div>
                    <hr>
                    <h4 class="title">Bank Details</h4>
                    <hr>
                    <div class="form-group">
                        <label for="bank_account" class="control-label">BANK ACCOUNT NO. <small class="text-danger">*</small></label>
                        <input type="text" id="bank_account" name="bank_account" class="form-control" placeholder="Bank Account No" aria-describedby="otp-addon" required  value="{{old("bank_account")}}">
                    </div>
                    <div class="form-group">
                        <label for="holder_name" class="control-label">ACCOUNT HOLDER NAME <small class="text-danger">*</small></label>
                        <input type="text" id="holder_name" name="holder_name" class="form-control" placeholder="Account holder name" aria-describedby="otp-addon" required value="{{old("holder_name")}}">
                    </div>
                    <div class="form-group">
                        <label for="bank_name" class="control-label">BANK NAME <small class="text-danger">*</small></label>
                        <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="Bank Name" required value="{{old("bank_name")}}">
                    </div>
                    <div class="form-group">
                        <label for="branch_name" class="control-label">BRANCH NAME <small class="text-danger">*</small></label>
                        <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Branch Name" aria-describedby="otp-addon" required value="{{old("bank_account")}}">
                    </div>
                    <div class="form-group">
                        <label for="ifsc_code" class="control-label">IFSC CODE <small class="text-danger">*</small></label>
                        <input type="text" id="ifsc_code" name="ifsc_code" class="form-control" placeholder="IFSC CODE" aria-describedby="ifsc-addon" required value="{{old("ifsc_code")}}">
                    </div>
                    
                    <div class="form-group">
                        <label for="reason" class="control-label">Choose withdrawal reason from the list <small class="text-danger">*</small></label>
                        
                        <select name="reason_from_list" id="reason_from_list" class="form-control" required="required">
                            <option value="">--Choose--</option>
                            @foreach(\App\Helpers\CommonHelper::admission_decline_rules() as $value)
                                <option value="{{$value}}" {{old("reason_from_list") == $value ? "selected" : ""}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="otp" class="control-label">Reason for withdrawal <small class="text-danger">*</small> <small class="text-mute">(min 10 character & max 1000 character)</small></small></label>
                        <textarea name="reason" id="inputreason" minlength="10" maxlength="1000" class="form-control" rows="3" required="required" placeholder="Reason between 10 to 500 characters.">{{old("reason")}}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure ? Your seat will be gone. Cannot revert back. ')">Proceed </button>
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
