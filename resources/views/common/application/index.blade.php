<div class="table-responsive">
    <table class="table table-bordered table-striped" id="myTable">
        <thead>
            <tr>
                @if (auth('admin')->check() || auth('department_user')->check())
                    <th><label class="checkbox-inline"><input type="checkbox" id="select-all" value="">Select
                            All</label></th>
                @endif
                <th>#</th>
                <th>Registration No</th>
                <th>Application No</th>
                <th>Applicant Name</th>
                @php
                    $spn = 2;
                    if (auth('department_user')->check()) {
                        $spn = 3;
                    }
                @endphp
                <th colspan={{ $spn }} width=25%>Program Applied</th>
                <th>Category</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $rowspan = 0;
                $sl = 1;
            @endphp
            @forelse ($applications as $key => $application)
                @php
                    if ($program == 83) {
                        $programs = [72, 73, 74, 75, 76, 77];
                    } else {
                        $program_array = programmes_array();
                        $programs = [];
                        foreach ($program_array as $key => $prog) {
                            if ($key != '') {
                                array_push($programs, $key);
                            }
                        }
                    }
                    
                    $applied_courses = $application->applied_courses->whereIn('course_id', $programs)->sortBy('preference');
                    
                    $rowspan = sizeof($applied_courses);
                    
                    $count = 1;
                    
                @endphp
                @foreach ($applied_courses as $pref => $applied)
                    <tr id="row_{{ $application->id }}">

                        @if ($count == 1)
                            @if (auth('admin')->check() || auth('department_user')->check())
                                <td rowspan={{ $rowspan }}><label class="checkbox-inline"><input type="checkbox"
                                            class="check" name="application_ids[]"
                                            value="{{ $application->id }}">&nbsp;</label>
                                </td>
                            @endif
                            <td rowspan={{ $rowspan }}>{{ $sl++ }}</td>
                            <td rowspan={{ $rowspan }}>{{ $application->student->id }}</td>
                            <td rowspan={{ $rowspan }}>{{ $application->application_no ?? 'After Payment' }} 
                                @if($application->is_mba==0){
                                    {{$application->exam_through?'(Through '.$application->exam_through.')': ''}}
                                }    
                                @endif

                                @if ($application->is_phd==1 && auth('admin')->check() || auth('department_user')->check() )
                                    @if ($application->net_jrf==1)
                                        <span class="btn btn-info btn-xs">QNLT</span>
                                    @endif
                                @endif
                            </td>
                            <td rowspan={{ $rowspan }}>{{ $application->fullname }}</td>
                        @endif
                        <td>{{ ++$pref }}.&nbsp;{{ $applied->course->name }}&nbsp;{{ $applied->application_number ?? '' }}
                            @if ($applied->status == 'on_hold')
                                <br /><strong><span style="color:rgb(219, 67, 67)">Your Application is ON HOLD. Click on
                                        "Click here to view" below "On Hold" button to view the On Hold reason. Please
                                        respond / update latest by
                                        {{ date('d-m-Y', strtotime($applied->last_date??date('Y-m-d'))) }}.</span></strong>
                            @endif
                        </td>
                        <td>
                            @if ($applied->status == null)
                                <button class='btn btn-primary btn-xs'>Applied</button>
                            @elseif($applied->status == 'accepted')
                                <button class='btn btn-success btn-xs'>Accepted</button>
                            @elseif($applied->status == 'on_hold')
                                <button class='btn btn-warning btn-xs' data-toggle="modal" data-target="#exampleModal"
                                    onclick="ViewReason('Hold',{{ $applied->id }})">On Hold</button>
                                <i class="fa fa-question-circle" data-toggle="modal" data-target="#exampleModal"
                                    onclick="ViewReason('Hold',{{ $applied->id }})"> Click here to view</i>
                                @if ($applied->is_updated == 1)
                                    <br /><button class="btn btn-success btn-xs"> On Hold is updated</button>
                                @endif
                            @elseif($applied->status == 'rejected')
                                <button class='btn btn-danger btn-xs'>Rejected</button>
                                <i class="fa fa-question-circle" data-toggle="modal" data-target="#exampleModal"
                                    onclick="ViewReason('Reject',{{ $applied->id }})">Click here to view</i>
                            @endif
                            @if ($applied->admitcardPublished)
                                {{-- {{dd($applied->admitcardPublished->tuee_result)}} --}}
                                {{-- <span class="pull-right"><a href="{{route("student.download-admit-card", Crypt::encrypt($applied->admitcard->id))}}"><button
                                class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-download"></i>Admit Card</button></a></span> --}}
                                {{-- <span class="pull-right"><a
                                        href="{{ route(get_route_guard() . '.download-admit-card', Crypt::encrypt($applied->admitcard->id)) }}"
                                        class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-download"></i>Admit
                                        Card</a></span> --}}
                                {{-- @if (auth('admin')->check()) --}}
                                    @if ($applied->admitcardPublished->tuee_result)
                                        <span class="pull-right"><a
                                                href="{{ route(get_route_guard() . '.download-score-card', Crypt::encrypt($applied->admitcard->id)) }}"
                                                class="btn btn-xs btn-primary"><i
                                                    class="glyphicon glyphicon-download"></i>Score Card</a></span>
                                    @endif
                                {{-- @endif --}}
                            @endif
                        </td>
                        @if (auth('department_user')->check())
                        {{-- @if (in_array(auth('department_user')->id(), [178, 3, 12, 90, 91, 92])) --}}
                            {{-- @if($application->is_phd==1) --}}
                            <td>
                                @if ($application->status == 'payment_done' || $application->status == 'document_resubmitted')
                                    <data style="display:none"
                                        data-application="{{ json_encode(collect($application->only(['fullname']))->merge(['caste' => $application->caste->name ?? 'NA', 'application_no' => $application->application_no])) }}"></data>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        onclick="verfiyApplication(this)"
                                        data-application-id="{{ Crypt::encrypt($applied->id) }}">Accept</button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="rejectApplication(this)"
                                        data-application-id="{{ Crypt::encrypt($applied->id) }}">Reject</button>
                                    <button type="button" class="btn btn-sm btn-warning"
                                        onclick="holdApplication(this)"
                                        data-application-id="{{ Crypt::encrypt($applied->id) }}">On Hold</button>
                                @endif
                            </td>
                            {{-- @endif --}}
                        @endif

                        @if ($count == 1)
                            <td rowspan={{ $rowspan }}>{{ $application->caste->name ?? 'NA' }}</td>
                            <td rowspan={{ $rowspan }}>{{ $application->gender }}</td>
                            <td rowspan={{ $rowspan }} id="status">
                                @if ($application->form_step <= 4 && $application->status == 'application_submitted')
                                    @if ($application->form_step < 4)
                                        Step {{ $application->form_step }} is Completed
                                    @elseif(!$application->is_eligibility_critaria_submitted)
                                        Payment Process is pending.
                                    @else
                                        Waiting for Final Review (Applicant)
                                    @endif
                                @else
                                    @if (in_array($application->status, ['on_hold', 'rejected']))
                                        <span class="text-danger" data-toggle="tooltip"
                                            data-title="{{ $application->reject_reason ? $application->reject_reason : $application->hold_reason }}">
                                            {{ ucwords(str_replace('_', ' ', $application->status)) }} <br>
                                            ({{ $application->reject_reason ? $application->reject_reason : $application->hold_reason }})
                                        </span>
                                        {{-- @elseif($application->admit_card_published && $application->status == 'accepted')
                                        {{ 'Admit Card Available' }} --}}
                                    @else
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    @endif
                                @endif
                                @if (auth('admin')->check() || auth('department_user')->check())
                                    @if ($application->is_extra_doc_uploaded)
                                        <span class="label label-info">(Documents Uploaded)</span>
                                    @endif
                                @endif
                            </td>
                            <td rowspan={{ $rowspan }}>
                                @if (auth('admin')->check() || auth('department_user')->check())
                                    <a
                                        href="{{ route(get_route_guard() . '.application.show', Crypt::encrypt($application->id)) }}"><button
                                            type="button" class="btn btn-primary btn-sm">View </button></a>
                                    @if (request()->has('show_edit_option'))
                                        <a
                                            href="{{ route('admin.application.edit', Crypt::encrypt($application->id)) }}"><button
                                                type="button" class="btn btn-warning btn-sm">Edit</button></a>
                                    @endif
                                @elseif(auth('student')->check())
                                        <a
                                        href="{{ route('student.application.show', Crypt::encrypt($application->id)) }}"><button
                                        type="button" class="btn btn-primary btn-xs">View </button></a>
                                    @if ($application->session_id == 13)
                                        
                                        @if ($application->form_step <= 4 && $application->status == 'application_submitted')
                                            <a
                                                href="{{ route('student.application.edit_form', Crypt::encrypt($application->id)) }}"><button
                                                    type="button" class="btn btn-warning btn-xs">Edit</button></a>
                                        @elseif($application->is_editable == 1 || $application->is_editable == 3)
                                            <a
                                                href="{{ route('student.application.edit_form', Crypt::encrypt($application->id)) }}"><button
                                                    type="button" class="btn btn-warning btn-xs">Edit</button></a>
                                        @endif

                                        @if (
                                            !$application->payment_status &&
                                                $application->form_step >= 4 &&
                                                $application->status == 'payment_pending' &&
                                                env('PAYMENT_EAB_DIS') == true)
                                            <a
                                                href="{{ route('student.application.process-payment', Crypt::encrypt($application->id)) }}"><button
                                                    type="button" class="btn btn-primary btn-xs">Go for
                                                    Payment</button></a>
                                        @endif
                                    @endif
                                    @if ($application->form_step == 4 && $application->status == 'application_submitted' && env('PAYMENT_EAB_DIS') == true)
                                        @if ($application->session_id == 13 || $application->is_mba == 1)
                                            @if (!$application->is_eligibility_critaria_submitted)
                                                <a
                                                    href="{{ route('student.application.final-submit', Crypt::encrypt($application->id)) }}"><button
                                                        type="button" class="btn btn-danger btn-xs"
                                                        onclick="return confirm('You are proceeding for “Payment & Final Submission”.  Modification will not be allowed. \nContinue ?')">Payment
                                                        & Final Submission</button></a>
                                            @elseif($application->is_eligibility_critaria_fullfilled && $application->is_extra_doc_uploaded)
                                                <a
                                                    href="{{ route('student.application.final-submit', Crypt::encrypt($application->id)) }}"><button
                                                        type="button" class="btn btn-danger btn-xs"
                                                        onclick="return confirm('You are proceeding for “Payment & Final Submission”.  Modification will not be allowed. \nContinue ?')">Payment
                                                        & Final Submission</button></a>
                                            @elseif($application->is_extra_doc_uploaded)
                                                <a
                                                    href="{{ route('student.application.final-submit', Crypt::encrypt($application->id)) }}"><button
                                                        type="button" class="btn btn-danger btn-xs"
                                                        onclick="return confirm('You are proceeding for “Payment & Final Submission”.  Modification will not be allowed. \nContinue ?')">Payment
                                                        & Final Submission</button></a>
                                            @endif
                                        @endif
                                    @endif
                                    @if (isDeleteAvailable($application))
                                    @endif
                                    @if ($application->re_payment && $application->status == 'on_hold')
                                        <a
                                            href="{{ route('student.application.re-payment', Crypt::encrypt($application->id)) }}"><button
                                                type="button" class="btn btn-danger btn-xs"> Go for
                                                Payment</button></a>
                                    @endif
                                    @if ($application->form_step >= 4 && $application->status != 'application_submitted')
                                        @if (!$application->is_eligibility_critaria_submitted)
                                        @endif
                                    @endif
                                @endif
                                @if ($application->payment_status)
                                    <a href="{{ route(get_route_guard() . '.application.payment-reciept', Crypt::encrypt($application->id)) }}"
                                        target="_blank"><button type="button" class="btn btn-success btn-xs">Payment
                                            Receipt</button></a>
                                    @if ($application->net_jrf == 1 && $application->is_invited)
                                        <a href="{{ route(get_route_guard() . '.download-invitation-card', Crypt::encrypt($application->id)) }}"  class="btn btn-primary btn-xs">Provisional Selection Card</a>
                                    @endif
                                    @if ($application->is_center_editable == 1)
                                        {{-- <a
                                        href="{{ route('student.application.change-exam-center', Crypt::encrypt($application->id)) }}"><button
                                            type="button" class="btn btn-primary btn-xs">Change Exam Center
                                        </button></a> --}}

                                        <a
                                            href="{{ route('student.application.change-preference', Crypt::encrypt($application->id)) }}"><button
                                                type="button" class="btn btn-primary btn-xs">Change Subject Preference
                                            </button></a>
                                    @endif
                                    @if ($application->rePaymentReceipt)
                                        <a href="{{ route(get_route_guard() . '.application.re-payment-reciept', Crypt::encrypt($application->id)) }}"
                                            target="_blank"><button type="button"
                                                class="btn btn-primary btn-xs">Payment Receipt 2</button></a>
                                    @endif
                                    @if ($application->admit_card_published)
                                        {{-- @if (auth('admin')->check() || auth('department_user')->check())
                                            <a href="{{ route(get_route_guard() . '.admit-card.download.pdf', Crypt::encrypt($application->admit_card_published->id)) }}"
                                                target="_blank"><button type="button" class="btn btn-default btn-xs"><i
                                                        class="glyphicon glyphicon-download"></i> Admit
                                                    Card</button></a> --}}
                                        {{-- @elseif(auth('student')->check())
                                            <a href="{{ route('student.admit-card.download', Crypt::encrypt($application->id)) }}"
                                                target="_blank"><button type="button" class="btn btn-danger  btn-xs">
                                                    Admit Card</button></a> --}}
                                        {{-- @endif  --}}
                                    @endif

                                    @php
                                        $flag = 0;
                                        if($application->is_cuet_ug==1){
                                            $flag=DB::table('programs')->where('id',1)->first()->cuet_marks;
                                        }elseif($application->is_cuet_pg==1){
                                            $flag=DB::table('programs')->where('id',2)->first()->cuet_marks;
                                        }
                                        
                                    @endphp
                                    @if ($application->exam_through =="CUET" && $flag && $application->payment_status)
                                        <a href="{{route('student.cuet-details.form',Crypt::encrypt($application->id))}}" class="btn btn-danger btn-xs">Update CUET Scorecard</a>
                                    @endif

                                    @php
                                        $status=0;
                                        foreach($application->merit_list as $list){
                                            if(in_array($list->new_status,['called','admitted','invited','time_extended','denied','declined','withdraw','payment_allowed'])){
                                                $status=1;
                                            }
                                        }
                                    @endphp
                                    @if ($status==1){{-- count($application->merit_list) > 0 --}}
                                        {{-- @if ($application->is_btech == 0) --}}
                                            @if ($application->merit_list[0]->new_status == 'called')
                                                <br /><br /><a
                                                    href="{{ route('student.online-admission.process', Crypt::encrypt($application->student_id)) }}"
                                                    class="btn btn-danger ">Proceed to Admission Process</a>
                                            @else
                                                <br /><br /><a
                                                    href="{{ route('student.online-admission.process', Crypt::encrypt($application->student_id)) }}"
                                                    class="btn btn-danger ">Proceed for Reporting</a>
                                            @endif
                                        {{-- @endif --}}
                                        {{-- for additional hostel Payment --}}
                                    @endif

                                    @php
                                            $isre_collect = 0;
                                            $receipt_re = 0;
                                            if($application->re_payment_flag==1){
                                                $isre_collect = 1;
                                            }elseif($application->re_payment_flag==2){
                                                $receipt_re = 1;
                                            }
                                    @endphp
                                    @if($isre_collect==1)
                                        <a href="{{ route('student.online-admission.hostel-fee-re', Crypt::encrypt($application->id)) }}" class="btn btn-primary btn-xs">Proceed For Balance Amount (Registration Fee)</a>
                                    @endif
                                    @if($receipt_re==1)
                                        <a href="{{ route(get_route_guard() . '.hostel-receipt-re', Crypt::encrypt($application->id)) }}" class="btn btn-primary btn-xs">Balance Amount Receipt (Registration Fee)</a>
                                    @endif
                                @endif
                            </td>
                        @endif

                        @php
                            
                            if ($count == $rowspan) {
                                $count = 0;
                            }
                            $count++;
                        @endphp
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td rowspan={{ $rowspan }} class="text-danger text-center" colspan="8">No Records found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $applications->appends(request()->all())->links() }}
</div>
