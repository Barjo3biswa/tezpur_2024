<div class="modal fade bd-Payment-modal-lg{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h3 class="modal-title" id="PaymentModalLabel"> Please Choose Hostel Required/Not</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" action="{{route("student.online-admission.choose-hostal",Crypt::encrypt($merit_list->id))}}" onSubmit="return confirm('Are you sure ?')">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="alert alert-info">
                        <strong>Note:</strong> The confirmation and allotment of the hostel Seat will be made 
                        during the physical verification of the documents (25th – 28th July 2023). 
                        <br/>
                        <strong>Note:</strong> The allotment of the hostel seat is subject to availability, based on the merit in 
                        the respective programmes 
                        following the reservation policy of the different social categories..
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="hostel_required" {{-- onclick="showHide({{$key}})" --}} required type="radio" value="yes">
                            Hostel Required
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="hostel_required" {{-- onclick="showHideii({{$key}})" --}} required type="radio" value="no">
                            Hostel Not Required
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-sm">Proceed </button>
                </div>
            </form>
        </div>
    </div>
</div>