<div class="modal fade bd-example-modal-lg{{ $key }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h3 class="modal-title" id="exampleModalLabel">Confirmation page</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST"
                action="{{ route('student.online-admission.reporting', Crypt::encrypt($merit_list->id)) }}"
                onSubmit="return confirm('Are you sure ?')">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="terms" class="control-label">Terms & conditions</label>
                        <div class="row">
                            <div class="col-sm-12" style="max-height: 300px; width:100%; overflow-y:scroll;">
                                {!! nl2br(returnTermsAndCond($merit_list->application, $bold_text = true)) !!}
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="">Seat Allocation Type (Freezing/Floating)</label><br />
                        <input type="radio" id="Freezing" name="seat_allocation" value="Freezing" required>
                        <label for="html">Freezing</label>
                        {{-- @if ($merit_list->preference == 1)
                            <input type="radio" id="Floating" name="seat_allocation" value="Floating" disabled
                                required>
                            <label for="css">Floating</label><br>
                        @else --}}
                            <input type="radio" id="Floating" name="seat_allocation" value="Floating" required>
                            <label for="css">Floating</label><br>
                        {{-- @endif --}}
                    </div>
                    <br />
                    <div style="color:rgb(158, 33, 33)">
                        <span><strong>Note:</strong></span><br />
                        <span><strong>Freezing:</strong> Freezing means the seat is taken, no alternative option
                            preferred by the candidate.</span><br />
                        <span><strong>Floating:</strong> Floating means the candidate has kept the option of taking an
                            alternative programme if available.</span>

                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="agree_checkbox" required> Agree and accept the terms and
                                conditions
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm"> Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>
