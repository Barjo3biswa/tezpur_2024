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
		{{-- @if(checkPermission(3)==true) --}}
		<div class="row">
            <a href="{{route('department.merit.hostel-allotment',['course_id'=>$merit_list->course_id])}}" class="btn btn-primary dont-print">Go To Hostel Allotment</a>
		</div>
		{{-- @endif --}}
		@endif
		<div class="row">
			<div class="col-md-12" style="padding: 15px;">
				<div class="panel panel-default">
					{{-- <div class="panel-heading">Payment <span class="pull-right"></div> --}}
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12 donot-print text-right">
								<button type="button" class="btn btn-deafult dont-print" onclick="window.print()"><i class="fa fa-print"></i>
									Print</button>
							</div>
							<div class="col-md-12" id="printTable">
								<p style="text-align:center" class="">
										<img src="{{asset("logo.png")}}" alt="Logo" alt="Logo" width="40">
								</p>
								<table class="table table-borderless">
									<tbody>
										<tr>
											<td class="padding-xs" style="text-align: center;" colspan="4">
												<h3 class="mb-3 text-uppercase tezu-form-h3">TEZPUR UNIVERSITY</h3> 
												<p class="mb-4 bold tezu--heading-print-para">
												NAPAAM, TEZPUR - 784028, ASSAM<br> <strong> Provisional Receipt for Balance Hostel Fee (Student / Academic)
													
												</strong><br>
												</p>
											</td>
										</tr>
										<tr>
											<th>Programme Applied</th>
											<td colspan="3"> {{$merit_list->course->name}}</td>
										</tr>
										<tr>
											<th>Applicant Name</th>
											<td> {{$application->fullname}}</td>
											<th>Receipt No</th>
											<td>{{$receipt->receipt_no ?? "NA"}}</td>
										</tr>
										<tr>
											<th>Application No</th>
											<td>{{$merit_list->application_no}}</td>
											<th>Roll No</th>
											<td><strong>{{$receipt->roll_number}}</strong></td>
										</tr>
						
										<tr>
											<th>Transaction ID</th>
											<td> {{$receipt->transaction_id}} ({{$receipt->pay_method}}) </td>
											<th>Transaction Date</th>
											<td>{{date("Y-m-d h:i s", strtotime($receipt->payment->created_at))}}</td>
										</tr>
										<tr>
											<th>Admission Category</th>
												 
											<td>
												{{-- {{$merit_list->hostelReceiptRepayment->admission_category->id}} --}}
												@if($merit_list->hostelReceiptRepayment->admission_category->id==1)
													Unreserved
												@else
													{{$merit_list->hostelReceiptRepayment->admission_category->name ?? "NA"}}
												@endif
												@if($merit_list->is_pwd==1)
												(PWD)
												@endif
											</td>
											<th>Social Category</th>
											<td>{{$application->caste->name ?? "NA"}}
												@if($merit_list->is_pwd==1)
												(PWD)
												@endif
											</td>
										</tr>
										{{-- <tr>
											<th>Hostel Name</th>
											<td>{{$merit_list->hostel_name ?? "NA"}}</td>
											<th>Room No</th>
											<td>{{$merit_list->room_no ?? "NA"}}</td>
										</tr> --}}
										<tr>                    
											<th>Phone No.</th>
											<td>{{$application->student->mobile_no ?? "NA"}}</td>
											<th>Seat Type</th>
											<td>{{$merit_list->freezing_floating ?? "NA"}}</td>
										</tr>
						
										<tr>
											<th>Hostel Name</th>
											<td>{{$merit_list->hostel_name ?? "NA"}}</td>
											<th>Seat No</th>
											<td>{{$merit_list->room_no ?? "NA"}}</td>
										</tr> 
									   
									</tbody>
								</table>
								@include('common.application.receipt.collection', ["collection" => $receipt->collections, "receipt" => $receipt])
							</div>
								
						</div>
					</div>
					<div {{-- class="panel-footer p-footer-font" --}}>
						<p style="margin-left: 10px">
							You are provisionally admitted in Hostel.{{--  The pending documents have to be submitted in the office of the Controller of
							Examinations through the concerned Head of the Department within <strong>31st October 2023</strong> failing which
							your
							admission may be cancelled. --}}
						</p>
						<p style="margin-left: 10px"><strong>Note: Original receipt will be issued by the university after verification of receipt of the fee amount.</strong></p>
					    {{-- <p><strong>Fee does not include the hostel fee.</strong></p> --}}
						<hr style="color: black">
						<p style="margin-left: 10px"><strong>Hostel seat to the candidate is alloted based on this payment receipt.</strong></p>
					</div>
					<hr>
					{{-- <table class="table table-borderless">
						<tbody>
							<tr>
								<td style="text-align: right;"><strong>
									Signature
									<br/>Admission-in-charge
									<br/>Department of {{$merit_list->course->department->name}}.</strong>
								</td>
							</tr>
						</tbody>
					</table> --}}
					
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
