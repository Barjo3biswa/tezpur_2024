@if (auth('department_user')->check())
    @extends('department-user.layout.auth')
    {{-- @else
@extends ('student.layouts.auth') --}}
@endif
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/css/bootstrap/zebra_datepicker.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
@endsection
@section('content')
    @php
        function btechDocumentsCleared($merit)
        {
            // return true;
            // return false if b.tech document is not cleared except undertaking.
            if (in_array($merit->course_id, btechCourseIds())) {
                // only check if b.tech candidates
                // marksheet is compulsory
                if (
                    $merit->approved_marksheet &&
                    ($merit->approved_prc || (!$merit->approved_prc && !$merit->active_prc && !$merit->rejected_prc)) &&
                    ($merit->approved_category ||
                        (!$merit->approved_category && !$merit->active_category && !$merit->rejected_category))
                ) {
                    return true;
                }
                return false;
            }
            return true;
        }
        function btechNeedtoShowUploadButton($merit)
        {
            // return false;
            // return false if b.tech document is not cleared except undertaking.
            if (in_array($merit->course_id, btechCourseIds())) {
                // only check if b.tech candidates
                // marksheet is compulsory
                if (
                    (!$merit->approved_marksheet && !$merit->active_marksheet) ||
                    $merit->rejected_prc ||
                    $merit->rejected_category
                ) {
                    return true;
                }
                return false;
            }
            return false;
        }

        $program_array = programmes_array();
        $programs = [];
        foreach ($program_array as $key => $prog) {
            if ($key != '') {
                array_push($programs, $key);
            }
        }
    @endphp
    <div class="container">
        @if (checkPermission(2) == true)
            <div class="row">
                <a href="{{ route('department.merit.index', ['course_id' => $course_id, 'merit_master_id' => $merit_master_id]) }}"
                    class="btn btn-primary">Go To Admission Officer Panel</a>
            </div>
        @endif
        @if (checkPermission(3) == true)
            <div class="row">
                <a href="{{ route('department.merit.payment', ['course_id' => $course_id, 'merit_master_id' => $merit_master_id]) }}"
                    class="btn btn-primary">Go To Payment Officer Panel</a>
            </div>
        @endif
        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        {{-- <div class="alert alert-warning"> --}}
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                        {{-- <strong class="blink">Notice!</strong>Please note.
                        <ol> --}}
                        {{-- <li>If you proceed for booking admission your all previous seat have to released. You have to pay the admission fee according. Any query please contact Tezpur University Authority / Helpline No.</li> --}}
                        {{-- <li>Kindly update your browser before you proceed for Booking the seat and
                                payment.</li>

                            <li>For any queries for Booking the seat and Payment related queries, please mail
                                your queries to tuee2021@gmail.com</li> --}}
                        {{-- @if (array_intersect($application->merit_list->pluck('course_id')->toArray(), btechCourseIds()))
                                <li>For B.Tech Candidates Undertaking & 10+2 Mark-sheet is compulsory.</li>
                                <li><a target="_blank"
                                        href="{{ asset('notifications/undertaking for admission B.Tech.pdf') }}"><button
                                            class="btn btn-sm btn-danger btn-xs"><i class="fa fa-download"></i> Download
                                            Undertaking Format (B.Tech)</button></a></li>
                            @else 
                                <li><a target="_blank"
                                        href="{{ asset('notifications/2021/undertaking_for_admission_2021-22.doc') }}"><button
                                            class="btn btn-sm btn-danger btn-xs"><i class="fa fa-download"></i> Download
                                            Undertaking Format</button></a></li>
                            @endif
                            <li>For Ph.d candidates undertaking not required.</li> --}}
                        {{-- </ol>
                    </div> --}}

                    </div>
                    <div class="panel-body">


                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Candidate Name</th>
                                        <th>Selected for Programme</th>
                                        <th>A.Category</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($application->merit_list as $key => $merit)
                                        <tr>
                                            @php
                                                $undertakings = $merit->undertakings;
                                            @endphp
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $merit->application->first_name ?? 'NA' }}
                                                {{ $merit->application->middle_name ?? 'NA' }}
                                                {{ $merit->application->last_name ?? 'NA' }}</td>
                                            <td>
                                                {{-- @if (in_array($merit->course_id, btechCourseIds()))
                                                B.Tech Programme
                                            @else  --}}
                                                {{ $merit->course->name }}
                                                {{-- @endif  --}}
                                                @if ($merit->freezing_floating != null)
                                                    <br /><b
                                                        style="color: rgb(150, 35, 35)">{{ $merit->freezing_floating }}</b>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- hidden as requested  26-09-2020 --}}
                                                {{ $merit->admissionCategory->name }}
                                            </td>
                                            <td>
                                                {{--  <span class="label label-default">
                                                {{ $merit->stringStatus() }}
                                            </span> --}}
                                                @if ($merit->status == 1)
                                                    <span class="label label-warning">
                                                        Approved </span>
                                                @elseif ($merit->status == 2)
                                                    <span class="label label-success">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                        {{-- Provisional Booking Done --}}
                                                        Provisionally Admitted
                                                    </span>
                                                @elseif ($merit->status == 3)
                                                    <span class="label label-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                        Seat Transferred
                                                    </span>
                                                @elseif ($merit->status == 4)
                                                    <span class="label label-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                        Cancelled
                                                    </span>
                                                @elseif ($merit->status == 5)
                                                    <span class="label label-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                        Temporarily on hold
                                                    </span>
                                                @elseif ($merit->status == 6)
                                                    <span class="label label-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                        Withdrawal
                                                    </span>
                                                @elseif($merit->status == 7)
                                                    <span class="label label-default"> Under process. </span>
                                                @elseif($merit->status == 8)
                                                    <span class="label label-primary"> Reported for counselling.
                                                    </span><br />
                                                    <span class="label label-danger">Reporting doesn't mean seat
                                                        confirmation. Seat confirmation is subject to seat availability &
                                                        payment.</span>
                                                @elseif($merit->status == 9)
                                                    <span class="label label-danger"> Seat declined by
                                                        {{ $merit->seat_declined_by }}. </span>
                                                @elseif($merit->isAutomaticSystem())
                                                    <span class="label label-default"> Please wait for your turn.
                                                        Subject's to seat availability
                                                    </span>
                                                @else
                                                    <span class="label label-default"> Shortlisted.</span>
                                                @endif
                                                @if ($merit->withdrawalRequest && $merit->withdrawalRequest->status == 'request_sent')
                                                    <span class="label label-default"> Seat withdrawal request sent.</span>
                                                @endif
                                                @if ($merit->withdrawalRequest && $merit->withdrawalRequest->status == 'approved')
                                                    <span class="label label-primary"> Seat withdrawal approved.</span>
                                                @endif
                                                @if ($merit->withdrawalRequest && $merit->withdrawalRequest->status == 'request_rejected')
                                                    <span class="label label-danger"> Seat withdrawal rejected.
                                                        <small>( {{ $merit->withdrawalRequest->remark }} )</small>
                                                    </span>
                                                @endif
                                                @if ($undertakings->count())
                                                    {{-- <span
                                                    class="label label-primary">({{ ucwords(str_replace("_", " ",$undertakings->first()->status)) }})
                                                    @if ($undertakings->first()->status == \App\Models\MeritListUndertaking::$rejected)
                                                        due to {{ $undertakings->first()->remark_by_admin }}. last
                                                        date of submission
                                                        {{ $undertakings->first()->closing_date_time }}.
                                                    @endif 
                                                </span> --}}
                                                    <button class="btn btn-xs btn-danger" onClick="showUndertakings(this)"
                                                        data-url="{{ route('student.merit.undertaking-view', $merit->id) }}">
                                                        <i class="glyphicon glyphicon-eye-open"></i> Show Documents Status
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if (checkPermission(2) == true && in_array($merit->course_id, $programs))
                                                    @if ($merit->status == 9)
                                                        <a target="_blank"
                                                            href="{{ route(get_route_guard() . '.merit.decline-receipt', Crypt::encrypt($merit->id)) }}"
                                                            class="btn btn-danger btn-xs">
                                                            Seat Declined Remarks
                                                        </a>
                                                    @endif
                                                    <!--<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#checklistmodal{{ $key - 1 }}">Checklist</button>
                                                <div class="modal fade" id="checklistmodal{{ $key - 1 }}" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success">
                                                            <button type="button" class="close " data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Checklist</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                            <form action="{{ route(get_route_guard() . '.merit.checklist', Crypt::encrypt($merit->id)) }}" method="post">
                                                                {{ csrf_field() }}
                                                                @foreach ($checklist_data as $key2 => $check)
    <div class="row">
                                                                    <div class='col-md-1' style="align:left">
                                                                        @php
                                                                            $index = $key - 1;
                                                                            $checked_data = DB::table(
                                                                                'admission_checked_checklists',
                                                                            )
                                                                                ->where(
                                                                                    'merit_list_id',
                                                                                    $application->merit_list[$index]
                                                                                        ->id,
                                                                                )
                                                                                ->where('document_id', $check->id)
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($checked_data == null)
    {{ ++$key2 }} . <input type="checkbox" name="document[]" value="{{ $check->id }}">
@else
    {{ ++$key2 }} . <input type="checkbox" name="document[]" value="{{ $check->id }}" checked>
    @endif
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <label for="">  {{ $check->name }}</label>
                                                                    </div>
                                                                </div>
    @endforeach
                                                                <hr/>
                                                                <div class="row ">
                                                                    <div class="col-md-2">
                                                                    <span>Enter Remarks</span>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                    <textarea class="form-control" name="checklist_remarks" id="checklist_remarks" cols="30" rows="3"
                                                                        placeholder="Enter Your Coment" required>{!! $merit->checklist_remarks !!}</textarea><br/>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <input type="hidden" name="application_id" value="{{ $merit->application_no }}">
                                                                        <input type="hidden" name="student_id" value="{{ $merit->student_id }}">
                                                                        <input type="hidden" name="merit_id" value="{{ $merit->id }}">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="submit" class="btn btn-primary" value="Submit">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>!-->
                                                @endif
                                                @if (showAdmissionOldLogic($merit))
                                                    @include('student.admission.old-logic')
                                                @else
                                                    @include('student.admission.new-logic')
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="show-undertakings">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Uploaded undertaking/documents</h4>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="show-undertakings">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Uploaded undertaking/documents</h4>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @php
        $hostel_names = DB::table('hostel_master')->get();
    @endphp
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <script>
        $('.date').datetimepicker({
            // format: 'YYYY-MM-DD HH:mm:ss',
            format: 'YYYY-MM-DD',
            showClose: true
        });


        function showHideii(id) {
            $('#hostel_dtl' + id).empty();
        }

        function showHide(id) {
            var html = `<div class="row">
                        <div class="col-md-3">
                                <label for="">Hostel Name</label>
                        </div>
                        <div class="col-md-6">
                            <select name="hostel_name" id="hostel_name" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach ($hostel_names as $host)
                                        <option value="{{ $host->name }}">{{ $host->name }}</option>
                                @endforeach      
                                </select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Room No</label>
                            </div>
                        <div class="col-md-6">
                            <input type="text" name="room_no" class="form-control" required>
                        </div>
                    </div>`;

            $('#hostel_dtl' + id).empty();
            console.log(html);
            $('#hostel_dtl' + id).append(html);
        }
    </script>
    <script>
        showUndertakings = function(obj) {
            $(".loading").fadeIn();
            var $this = $(obj);
            var $modal = $("#show-undertakings");
            $modal.modal();
            console.log($this.data("url"));
            var xhr = $.get($this.data("url"));
            xhr.done(function(resp) {
                    $modal.find(".modal-body").html(resp);
                })
                .fail(function() {
                    alert("Whoops! something went wrong.");
                })
                .always(function() {
                    $(".loading").fadeOut();
                });
        }
    </script>
@endsection
