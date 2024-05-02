@php
if (get_guard() == 'admin') {
    $layout = 'admin.layout.auth';
} elseif (get_guard() == 'student') {
    $layout = 'student.layouts.auth';
} elseif (get_guard() == 'department_user') {
    $layout = 'department-user.layout.auth';
}
$dept = \App\Department::where('id', $merit_list->course->department_id)->first();

$total = 0;
$date = 0;
foreach ($receipt->collections as $key => $val) {
    $total += $val->amount;
    $date = $val->created_at;
}
$fees = number_format($total, 2);

@endphp
@extends($layout)
<style>
	@media print {
	.cont {
		font-size: 14px;
	}

	.p-footer-font {
		font-size: 14px;
	}

	.checklist-font {
		font-size: 14px;
	}

	.t-font-size {

		font-size: 12px;
        padding-top:10px !important;
	}

	.pagebreak {
		page-break-before: always;
	}

	table {
		font-size: 14px;
		/* border-collapse: collapse; */
	}

	tbody {
		border: 6px solid transparent !important;
	}

	p {
		margin: 0 !important
	}

	h3 {
		margin: 0 !important
	}

	table>tbody>tr>td {
		padding: 0 !important
	}

	table>tbody>tr>th {
		padding: 0 !important;
		/* border-top: none !important; */
		/* border: none !important */
	}

	.table {
		margin-bottom: 0 !important
	}

	.table-bordered>tbody>tr>th {
		/* border: none !important */
	}

	.table-bordered>tbody>tr>td {
		/* border: none !important */
	}

	.table>tbody>tr>td {
		/* border-top: none !important */
	}

	.table-bordered>tbody> {
		/* border: none !important */
	}

	.table>tbody>tr> {
		/* border-top: none !important */
	}

	.tezu--heading-print-para {
		padding-bottom: 6rem !important;
	}

	tezu-form-h3 {
		margin: 2rem 0 !important
	}

	checklist-font {
		display: flex;
		flex-wrap: nowrap;
	}
    -webkit-print-color-adjust: exact !important;
    #checked{
        /* font-weight: bold; */
        color:black !important;
    }
    input[type=checkbox] {
        opacity: 1 !important;
        color:black
    }
    .checklist-font label {width:80% !important;}
	}

	table {
		border-collapse: collapse;
	}

	tbody {
		border: 24px solid transparent;
	}
</style>
@section('content')
	<div class="container cont" id="">
		@if(auth()->guard("department_user")->check())
		@if(checkPermission(3)==true)
		<div class="row">
            <a href="{{route('department.merit.payment',['course_id'=>$merit_list->course_id,'merit_master_id'=>$merit_list->merit_master_id])}}" class="btn btn-primary">Go To Payment Officer Panel</a>
		</div>
		@endif
		@endif
		<div class="row">
			<div class="col-md-12" style="padding: 15px;">
				<div class="panel panel-default">
					{{-- <div class="panel-heading">Payment <span class="pull-right"></div> --}}
					<div class="panel-body">
						@include('common.application.receipt.admission-receipt', [
							'receipt' => $receipt,
							'application' => $application,
							'merit_list' => $merit_list,
						])
					</div>
					<div class="panel-footer p-footer-font">
						<p>
							You are provisionally admitted. The pending documents have to be submitted in the office of the Controller of
							Examinations through the concerned Head of the Department, {{-- within <strong>31st October 2023</strong> --}} failing which
							your
							admission may be cancelled.
						</p>
						<p><strong>Note: Original receipt will be issued by the university after verification of receipt of the fee amount.</strong></p>
					    <p><strong>Fee does not include the hostel fee.</strong></p>
					</div>
					<hr>
					@php
						$test=0;
					@endphp
                    @if (auth("department_user")->check() && $test==1)
						<div class="row">
							<div class="col-md-12" style="float:left;margin-left:20px">
								<span><strong><u>Checklist</u></strong> </span>
							</div>
						</div>
						<br />
						@php
							$checklist_data = DB::table('admission_checklists')->get();
						@endphp
						@foreach ($checklist_data as $key => $check)
							<div class="row checklist-font">
								<div class='col-md-1' {{-- style="align:left" --}} style="float:left;margin-left:20px">
									@php
										$checked_data = DB::table('admission_checked_checklists')
											->where('merit_list_id', $merit_list->id)
											->where('document_id', $check->id)
											->first();
										// dump($checked_data);
									@endphp
									@if ($checked_data == null)
										{{ ++$key }} . {{-- <input type="checkbox" name="document[]" value="{{ $check->id }}" disabled> --}}<i class="fa fa-times"></i>
									@else
										{{ ++$key }} . {{-- <input type="checkbox"  id="checked" name="document[]" value="{{ $check->id }}" checked disabled> --}}<i class="fa fa-check"></i>
									@endif
								</div>
								<div class="col-md-10">
									<label for="" >{{ $check->name }}</label>
								</div>
							</div>
						@endforeach
						<br />
						<div class="row">
							<div class="col-md-12" style="float:left;margin-left:20px">
								<span><strong><u>Checklist Remarks:</u></strong> {{ $merit_list->checklist_remarks }}</span>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-md-3" style="float:left;margin-left:20px">
								<span>Students Signature</span>
							</div>
							<div class="col-md-3" style="float:right;margin-right:20px">
								<span>Admission Officer Signature (with Seal)</span>
							</div>
						</div>
						<div class="pagebreak"> </div>

						{{-- Copy of the first details page --}}
						<div class="panel-body">
							@include('common.application.receipt.admission-receipt2', [
								'receipt' => $receipt,
								'application' => $application,
								'merit_list' => $merit_list,
							])
						</div>
						<div class="panel-footer p-footer-font">
							<p>
								You are provisionally admitted. The pending documents have to be submitted in the office of the Controller of
								Examinations through the concerned Head of the Department within <strong>30th November 2022</strong> failing
								which
								your
								admission may be cancelled.
							</p>
							<p>Note: Original receipt will be issued by the university after verification of receipt of the fee amount.</p>
						</div>
						<hr>

						<div class="row">
							<div class="col-md-12" style="float:left;margin-left:20px">
								<span><strong><u>Checklist</u></strong> </span>
							</div>
						</div>
						<br />
						@php
							$checklist_data = DB::table('admission_checklists')->get();
						@endphp
						@foreach ($checklist_data as $key => $check)
						<div class="row checklist-font">
							<div class='col-md-1' {{-- style="align:left" --}} style="float:left;margin-left:20px">
								@php
								$checked_data = DB::table('admission_checked_checklists')
								->where('merit_list_id', $merit_list->id)
								->where('document_id', $check->id)
								->first();
								// dump($checked_data);
								@endphp
								@if ($checked_data == null)
								{{ ++$key }} . {{-- <input type="checkbox" name="document[]" value="{{ $check->id }}" disabled> --}}<i
									class="fa fa-times"></i>
								@else
								{{ ++$key }} . {{-- <input type="checkbox" id="checked" name="document[]" value="{{ $check->id }}" checked
									disabled> --}}<i class="fa fa-check"></i>
								@endif
							</div>
							<div class="col-md-10">
								<label for="">{{ $check->name }}</label>
							</div>
						</div>
						@endforeach
						<br />
						<div class="row">
							<div class="col-md-12" style="float:left;margin-left:20px">
								<span><strong><u>Checklist Remarks:</u></strong> {{ $merit_list->checklist_remarks }}</span>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-md-3" style="float:left;margin-left:20px">
								<span>Students Signature</span>
							</div>
							<div class="col-md-3" style="float:right;margin-right:20px">
								<span>Admission Officer Signature (with Seal)</span>
							</div>
						</div>
						<div class="pagebreak"> </div>


						{{--   <br /><br /><br /><br /><br /><br />
						<hr>
						<br /><br /><br /><br /><br /><br /><br /><br /> --}}

						<table class="table table-borderless t-font-size ">
							<tbody>
								<tr>
									<td class="padding-xs" style="text-align: center;" colspan="4">
										<h5 class="mb-3 text-uppercase">TEZPUR UNIVERSITY</h5>
										<p class="mb-4 bold">
											<strong>ADMISSION RECORD FORM</strong><br>
										</p>
									</td>
								</tr>
								<tr>
									<td class="padding-xs" colspan="4" align="right"> <strong>Academic Section's Copy</strong></td>
								</tr>
								<tr>
									<th><b>Name of the Student in capital letters</b> </th>
									<td colspan="3"> {{ $application->fullname }}</td>

								</tr>
								<tr>
									<th>Programme </th>
									<td colspan="3"> {{ $merit_list->course->name }}</td>

								</tr>

								<tr>
									<th>Admission Category</th>
									<td>{{ $merit_list->admissionCategory->name ?? 'NA' }}</td>

									<th>Social Category</th>
									<td>{{ $application->caste->name ?? 'NA' }}</td>
								</tr>
								<tr>
									<th>Sex</th>
									<td>{{ $merit_list->application->gender ?? 'NA' }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<th>Home Address (please write neatly)</th>
									<td colspan="3">
										C/O:{{ $merit_list->application->permanent_co ?? 'NA' }},
										House No:{{ $merit_list->application->permanent_house_no ?? 'NA' }} <br />
										Street Name/Locality: {{ $merit_list->application->permanent_street_locality ?? 'NA' }} ,
										Vill/Town:{{ $merit_list->application->permanent_village_town ?? 'NA' }}<br />
										{{-- PS: {{$application->correspondence_ps ?? "NA"}} </br> --}}
										PO:{{ $merit_list->application->permanent_po ?? 'NA' }} ,
										Dist:{{ $merit_list->application->permanent_district ?? 'NA' }}
										State:{{ $merit_list->application->permanent_state ?? 'NA' }} -
										{{ $merit_list->application->permanent_pin ?? 'NA' }}
									</td>
								</tr>
								<tr>
									<th>State of Domicile</th>
									<td> {{ $merit_list->application->permanent_state }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<th>Religion</th>
									<td> {{ $merit_list->application->religion }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<th>Do you belong to Minority Community</th>
									<td>{{ $application->religion == 'Hinduism' ? 'No' : 'Yes' }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<td colspan="4" align="right"><strong>Students Signature</strong></td>
								</tr>

								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="4" align="center"><strong>(For office use only)</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Admission Authorization:</u></strong></td>
								</tr>
								<tr>
									<td colspan="4">Relevant documents of the above mentioned student selected for admission into
										<b>{{ $merit_list->course->name }}</b>
										programme in the department of <b>{{ $dept->name }}</b> have been cheeked.He / She is recommended for
										admission /
										provisional admission into the above mentioned programme.He/She has also given an undertaking in the
										prescribed
										format (applicable to provisionally admitted students only).
									</td>
								</tr>
								<tr>
									<th>His / Her Roll No. is :
										(As per the new regulation)</th>
									<td>{{ $receipt->roll_number }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<th>Date</th>
									<th>Office Seal</th>
									<td colspan="2"><strong>Signature of the faculty member<br>
											Deptt. of {{ $dept->name }}</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Hostel : </u></strong></td>
								</tr>
								<tr>
									<td colspan="4">Not admitted / Admitted to …………………………..………hostel and room no is …………………………………….</td>

								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="2"><strong>Signature of Warden</strong></td>
									<td colspan="2"><strong>Signature of Dean of Students Welfare</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Fees:</u></strong></td>
								</tr>
								<tr>
									<td colspan="4">Admission fees of Rs <b>{{ $fees }}</b>(inclusive/exclusive Hostel fee) paid vide
										draft no./
										receipt no.
										<b>{{ $receipt->receipt_no }}</b> dated <b>{{ date('d-m-Y', strtotime($date)) }}</b>
									</td>
								</tr>
								<tr>
									<td colspan="4" align="right"><strong>In-charge, Cash Counter</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Admission Note:</u></strong><br />
										Admitted<br />
										Provisionally admitted*<br />
										(1) Yet to pass qualifying degree exam.<br />
										(2) Inadequacy of submitted documents<br />
										(3) Others (please give reason)<br /></td>
								</tr>
								<tr>
									<td colspan="4" align="right"><b>Signature of Officer-in-charge
											Designation</b></td>
								</tr>
								<tr>
									<td colspan="4"><b>* Provisionally admitted students must produce the required documents before 30th
											November, 2022.</b></td>
								</tr>
								<tr>
									<td colspan="4">ID Card : Not issued / Issued vide No. ………………………</td>
								</tr>
								<tr>
									<td colspan="4" align="right"><b>Signature of the Official</b>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="pagebreak"> </div>
						{{-- <br />
						<hr>
						<br /> --}}
						{{-- students Copy --}}
						<table class="table table-borderless t-font-size">
							<tbody>
								<tr>
									<td class="padding-xs" style="text-align: center;" colspan="4">
										<h5 class="mb-3 text-uppercase">TEZPUR UNIVERSITY</h5>
										<p class="mb-4 bold">
											<strong>ADMISSION RECORD FORM</strong><br>
										</p>
									</td>
								</tr>
								<tr>
									<td class="padding-xs" colspan="4" align="right"> <strong> Student's Copy</strong></td>
								</tr>
								<tr>
									<th><b>Name of the Student in capital letters</b> </th>
									<td colspan="3"> {{ $application->fullname }}</td>

								</tr>
								<tr>
									<th>Programme </th>
									<td colspan="3"> {{ $merit_list->course->name }}</td>

								</tr>
								<tr>
									<th>Admission Category</th>
									<td>{{ $merit_list->admissionCategory->name ?? 'NA' }}</td>

									<th>Social Category</th>
									<td>{{ $application->caste->name ?? 'NA' }}</td>
								</tr>
								<tr>
									<th>Sex</th>
									<td>{{ $merit_list->application->gender ?? 'NA' }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<th>Home Address (please write neatly)</th>
									<td colspan="3">
										C/O:{{ $merit_list->application->permanent_co ?? 'NA' }},
										House No:{{ $merit_list->application->permanent_house_no ?? 'NA' }} <br />
										Street Name/Locality: {{ $merit_list->application->permanent_street_locality ?? 'NA' }} ,
										Vill/Town:{{ $merit_list->application->permanent_village_town ?? 'NA' }}<br />
										{{-- PS: {{$application->correspondence_ps ?? "NA"}} </br> --}}
										PO:{{ $merit_list->application->permanent_po ?? 'NA' }} ,
										Dist:{{ $merit_list->application->permanent_district ?? 'NA' }}
										State:{{ $merit_list->application->permanent_state ?? 'NA' }} -
										{{ $merit_list->application->permanent_pin ?? 'NA' }}
									</td>

								</tr>
								<tr>
									<th>State of Domicile</th>
									<td> {{ $merit_list->application->permanent_state }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<th>Religion</th>
									<td> {{ $merit_list->application->religion }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<th>Do you belong to Minority Community</th>
									<td>{{ $application->religion == 'Hinduism' ? 'No' : 'Yes' }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<td colspan="4" align="right"><strong>Students Signature</strong></td>
								</tr>

								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="4" align="center"><strong>(For office use only)</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Admission Authorization:</u></strong></td>
								</tr>
								<tr>
									<td colspan="4">Relevant documents of the above mentioned student selected for admission into
										<b>{{ $merit_list->course->name }}</b>
										programme in the department of <b>{{ $dept->name }}</b> have been cheeked.He / She is recommended for
										admission /
										provisional admission into the above mentioned programme.He/She has also given an undertaking in the
										prescribed
										format (applicable to provisionally admitted students only).
									</td>
								</tr>
								<tr>
									<th>His / Her Roll No. is :
										(As per the new regulation)</th>
									<td>{{ $receipt->roll_number }}</td>
									<th></th>
									<td></td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<th>Date</th>
									<th>Office Seal</th>
									<td colspan="2"><strong>Signature of the faculty member<br>
											Deptt. of {{ $dept->name }}</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Hostel : </u></strong></td>
								</tr>
								<tr>
									<td colspan="4">Not admitted / Admitted to …………………………..………hostel and room no is …………………………………….</td>

								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="2"><strong>Signature of Warden</strong></td>
									<td colspan="2"><strong>Signature of Dean of Students Welfare</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Fees:</u></strong></td>
								</tr>
								<tr>
									<td colspan="4">Admission fees of Rs <b>{{ $fees }}</b>(inclusive/exclusive Hostel fee) paid vide
										draft no./
										receipt no.
										<b>{{ $receipt->receipt_no }}</b> dated <b>{{ date('d-m-Y', strtotime($date)) }}</b>
									</td>
								</tr>
								<tr>
									<td colspan="4" align="right"><strong>In-charge, Cash Counter</strong></td>
								</tr>
								<tr>
									<td colspan="4"><strong><u>Admission Note:</u></strong><br />
										Admitted<br />
										Provisionally admitted*<br />
										(1) Yet to pass qualifying degree exam.<br />
										(2) Inadequacy of submitted documents<br />
										(3) Others (please give reason)<br /></td>
								</tr>
								<tr>
									<td colspan="4" align="right"><b>Signature of Officer-in-charge
											Designation</b></td>
								</tr>
								<tr>
									<td colspan="4"><b>* Provisionally admitted students must produce the required documents before 30th
											November, 2022.</b></td>
								</tr>
								<tr>
									<td colspan="4">ID Card : Not issued / Issued vide No. ………………………</td>
								</tr>
								<tr>
									<td colspan="4" align="right"><b>Signature of the Official</b>
									</td>
								</tr>
							</tbody>
						</table>
						{{-- End ARF --}}
					@endif	
					</div>	
			</div>
		</div>
	</div>
@endsection
@section('js')
	@include('common/application/peyment-receipt-js')
	<script>
		history.pushState(null, null, location.href);
		window.onpopstate = function() {
			history.go(1);
		};
	</script>
@endsection
