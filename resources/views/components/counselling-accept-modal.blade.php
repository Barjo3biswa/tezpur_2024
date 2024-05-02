<div class="modal fade" id="{{ $modal_id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation page</h4>
            </div>
            <form method="POST" action="{{ route(get_route_guard().".admission.report-counselling", $merit->id) }}" onSubmit="return confirm('Are you sure ?')">
                <div class="modal-body">
                    {{-- <div class="form-group">
                        <h4 class="text-danger">Are you sure ?</h4>
                    </div> --}}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="terms" class="control-label">Terms & conditions</label>
                        <div class="row">
                            <div class="col-sm-12" style="max-height: 300px; width:100%; overflow-y:scroll;">
                                {!! nl2br(returnTermsAndCond($merit->application, $bold_text = true)) !!}
                            </div>
                        </div>
                        {{-- <textarea name="terms_and_condition" id="terms_and_condition" class="form-control" rows="3" style="display: none" required="required" readonly>{{ returnTermsAndCond($merit->application) }}</textarea> --}}
                    </div>
                    
                   
                    <div>
                        <label for="">Seat Allocation Type (Freezing/Floating)</label><br/>
                        <input type="radio" id="Freezing" name="seat_allocation" value="Freezing" required>
                        <label for="html">Freezing</label>
                        <input type="radio" id="Floating" name="seat_allocation" value="Floating" checked required>
                        <label for="css">Floating</label><br>
                    </div>
                    <br/>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="agree_checkbox" required> Agree and accept the terms and conditions
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Nah! <i
                            class="fa fa-thumbs-down"></i></button> --}}
                    <button type="submit" class="btn btn-primary btn-sm"> Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>
