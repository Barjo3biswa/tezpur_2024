@php
$recept = $merit->admissionReceipt;
@endphp
@if ($merit->allow_uploading_undertaking == 3)
    <strong>Document uploading date is expired.</strong>
@elseif (($merit->allow_uploading_undertaking == 1 && !$merit->approved_undertaking && !$merit->active_undertaking )
    ||
    btechNeedtoShowUploadButton($merit))
    @if ($undertakings->count() >= \App\Models\MeritLIstUndertaking::$upload_try_limit)
        <strong>Uploading Limit Crossed of
            {{ \App\Models\MeritLIstUndertaking::$upload_try_limit }}.</strong>
    @else
        {{-- check here if  if if any document marksheet, prc, category, uploaded and rejected then re-upload option should be there. --}}
        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#uploadingUndertaking">Upload
            Undertaking/documents &
            Register to Counselling</button>
        <div class="modal fade" id="uploadingUndertaking">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Upload documents to register
                            into counselling</h4>
                    </div>
                    <form enctype="multipart/form-data" method="POST"
                        action="{{ route(get_route_guard().'.admission.upload-undertaking', $merit->id) }}"
                        onSubmit="return confirm('Are you sure ? ')">
                        {{ csrf_field() }}
                        <div class="modal-body">

                            <div class="alert alert-danger">
                                <strong>Note:</strong> Max File size 1MB.
                                PDF, MS Word, Image files are allowed.
                            </div>
                            @if (($merit->rejected_undertaking || !$merit->undertakings->count()) && !$merit->approved_undertaking)
                                <div class="form-group {{ $errors->has('undertaking') ? 'has-error' : '' }}">
                                    <label for="undertaking">Undertaking
                                        <span class="text-danger">*
                                            (compulsory)</span></label>
                                    <input type="file" name="undertaking" id="undertaking" class="form-control">
                                    @if ($errors->has('undertaking'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('undertaking') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @endif
                            {{-- for btech student only --}}
                            @if (in_array($merit->course_id, btechCourseIds()))
                                @if (($merit->rejected_marksheet || !$merit->undertakings->count()) && !$merit->approved_marksheet)
                                    <div class="form-group {{ $errors->has('marksheet') ? 'has-error' : '' }}">
                                        <label for="marksheet">10+2
                                            Mark-sheet <span class="text-danger">*
                                                (compulsory)</span></label>
                                        <input type="file" name="marksheet" id="marksheet" class="form-control"
                                            required>
                                        @if ($errors->has('marksheet'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('marksheet') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                @if (($merit->rejected_prc || !$merit->undertakings->count()) && !$merit->active_prc)
                                    <div class="form-group {{ $errors->has('prc') ? 'has-error' : '' }}">
                                        <label for="prc">PRC
                                            {{-- <span class="text-info">(optional)</span> --}}</label>
                                        <input type="file" name="prc" id="prc" class="form-control">
                                        @if ($errors->has('prc'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('prc') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                @if (($merit->rejected_category || !$merit->undertakings->count()) && !$merit->active_category)
                                    <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                                        <label for="category">Category
                                            Certificate (EWS, PWD, OBC-NCL,
                                            SC, ST)
                                            {{-- <span class="text-info">(optional)</span> --}}</label>
                                        <input type="file" name="category" id="category" class="form-control">
                                        @if ($errors->has('category'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('category') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            {{-- btech condition end --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm">Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endif
@if ($recept)
    <a href="{{ route(get_route_guard().'.admission.payment-receipt', Crypt::encrypt($merit->id)) }}" target="_blank"><button
            class="btn btn-sm btn-primary">Receipt</button></a>
@endif
{{-- $merit_list->isDateExpired() please use this function with status to verify date is expire checking. --}}
@if (($merit->status == 1 && !$merit->allow_uploading_undertaking) || ($merit->status == 1 && $merit->approved_undertaking && btechDocumentsCleared($merit)) || ($merit->status == 1 && $merit->allow_uploading_undertaking == 1 && !$merit->undertakings->count() && btechDocumentsCleared($merit)))
    @if (!$merit->isDateExpired())
        {{-- if merit does not allow to select hostel required or not condition added. --}}
        @if ($merit->ask_hostel)
            <a data-toggle="modal" href='#chooseHostel{{ $merit->id }}' href="#">
                <button class="btn btn-sm btn-success">Make payment for
                    admission</button>
            </a>
            <div class="modal fade" id="chooseHostel{{ $merit->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Please Choose Hostel
                                Required/Not</h4>
                        </div>
                        <form method="POST"
                            action="{{ route(get_route_guard().'.admission.choose-hostel', Crypt::encrypt($merit->id)) }}"
                            onSubmit="return confirm('Are you sure ? ')">
                            {{ csrf_field() }}
                            <div class="modal-body">

                                <div class="alert alert-info">
                                    <strong>Note:</strong> Hostel will be
                                    alloted based on availability.
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input name="hostel_required" required type="radio" value="yes">
                                        Hostel Required
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="hostel_required" required type="radio" value="no">
                                        Hostel Not Required
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm">Proceed</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else

            <a href="{{ route(get_route_guard().'.admission.proceed', Crypt::encrypt($merit->id)) }}"
                onClick="return confirm('Are you sure ? Action undone.')">
                <button class="btn btn-sm btn-success">Make payment for
                    admission</button>
            </a>
            {{-- <a href="{{route("student.admission.proceed", Crypt::encrypt($merit->id)) }}"
                onClick="return confirm('Are you sure ? Action undone.')">
                <button class="btn btn-sm btn-primary">Make payment via
                    challan</button>
                </a> --}}
        @endif
    @else
        Admission Date
        <strong>{{ date('d-m-Y h:i:a', strtotime($merit->valid_from)) }}
            -
            {{ date('d-m-Y h:i:a', strtotime($merit->valid_till)) }}</strong>
    @endif
@endif
@if ($merit->status == 2 && $merit->release_seat_applicable)
    <a href="{{ route(get_route_guard().'.admission.release', Crypt::encrypt($merit->id)) }}"
        onClick="return confirm('Are you sure ?')">
        <button class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i> Release Seat</button>
    </a>
@endif
