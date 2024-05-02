<div class="modal fade bd-withdraw-modal-lg{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title" id="withdrawModalLabel"> You are going to Withdraw your seat</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <form method="POST" action="{{route("student.online-admission.withdraw",Crypt::encrypt($merit_list->id))}}" onSubmit="return confirm('Are you sure ?')">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="reason" class="control-label">Choose from the list</label>
                            <select name="reason_from_list" id="reason_from_list" class="form-control" required="required">
                                <option value="">--Choose--</option>
                                @foreach(\App\Helpers\CommonHelper::admission_withdraw_rules() as $value)
                                    <option value="{{$value}}" {{old("reason_from_list") == $value ? "selected" : ""}}>{{$value}}</option>
                                @endforeach
                            </select> 
                        </div>

                        <div class="col-md-6">
                            <label for="otp" class="control-label">Enter OTP <span class="message"> </span></label>
                            <input type="number" class="form-control col-md-6" name="otp">
                            <input type="button" class="btn btn-primary btn-xs" value="Get OTP" onclick="requestForOTP({{$merit_list->id}})">
                        </div>                         
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="reason" class="control-label">Bank A/C No.</label>
                            <input type="text" class="form-control" name="bank_account">
                        </div>

                        <div class="col-md-6">
                            <label for="reason" class="control-label">A/C Holder Name.</label>
                            <input type="text" class="form-control" name="holder_name">
                        </div>                        
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="reason" class="control-label">Branch Name.</label>
                            <input type="text" class="form-control" name="branch_name">
                        </div> 
                        <div class="col-md-6">
                            <label for="reason" class="control-label">IFSC Code.</label>
                            <input type="text" class="form-control" name="ifsc_code">
                        </div>                       
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="reason" class="control-label">Bank Name.</label>
                            <input type="text" class="form-control" name="bank_name">
                        </div>                     
                    </div>

                    <div class="form-group" class="col-md-12">
                        <label for="otp" class="control-label">Reason for Withdraw <small class="text-mute">(max 1000 character)</small></small></label>
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

