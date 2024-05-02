@php
if (get_guard() == 'admin') {
    $layout = 'admin.layout.auth';
} elseif (get_guard() == 'student') {
    $layout = 'student.layouts.auth';
} elseif (get_guard() == 'department_user') {
    $layout = 'department-user.layout.auth';
}
@endphp
@extends($layout)
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					{{-- <div class="panel-heading">Payment <span class="pull-right"></div> --}}
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" id="printTable">
								<p style="text-align:center" class="">
									<img src="{{asset('logo.png') }}" alt="Logo" alt="Logo" width="40">
								</p>
								<table class="table table-borderless">
									<thead>
										<tr>
											<th class="padding-xs" style="text-align: left;" colspan="4">
												<div class="col-xs-12 donot-print text-right">
													<button type="button" class="btn btn-deafult dont-print" onclick="window.print()"><i
															class="fa fa-print"></i>
														Print</button>
												</div>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="padding-xs" style="text-align: center;" colspan="4">
												<h3 class="mb-3 text-uppercase">TEZPUR UNIVERSITY</h3>
												<p class="mb-4 bold">
													NAPAAM, TEZPUR - 784028, ASSAM<br>
													<br /><span class="text-danger">Seat Released</span>
													</strong><br>
												</p>
											</td>
										</tr>
										<tr>
											<th>Programme Applied</th>
											<td colspan="3"> {{ $merit_list->course->name }}</td>
										</tr>
										<tr>
											<th>Applicant Name</th>
											<td> {{ $merit_list->application->fullname }}</td>
											<th>Application No</th>
											<td>{{ $merit_list->application_no }}</td>
										</tr>
										<tr>
          <th>Application No</th>
          <td>{{$merit_list->application_no}}</td>
          <th>Roll No</th>
          <td><strong>{{$merit_list->admissionReceipt->roll_number}}</strong></td>
         </tr>

										{{-- <tr>
          <th>Transaction ID</th>
          <td> {{$receipt->transaction_id}} </td>
          <th>Transaction Date</th>
          <td>{{date("Y-m-d h:i a", strtotime($receipt->payment->created_at))}}</td>
         </tr> --}}
										{{-- <tr>
          <th>Admission Category</th>
          <td>{{$merit_list->admissionCategory->name ?? "NA"}}</td>
          <th>Social Category</th>
          <td>{{$merit_list->application->caste->name ?? "NA"}}</td>
         </tr> --}}
										{{-- <tr>
          <th>Hostel Name</th>
          <td>{{$merit_list->hostel_name ?? "NA"}}</td>
          <th>Room No</th>
          <td>{{$merit_list->room_no ?? "NA"}}</td>
         </tr> --}}
										<tr>
											<th>Phone No.</th>
											<td>{{ $merit_list->application->student->mobile_no ?? 'NA' }}</td>
											<th></th>
											<td></td>
										</tr>
										{{-- <tr>
          <th>Minority Status</th>
          <td>{{$application->religion ?? "NA"}} {{$application->religion == "Hinduism" ? "" : " (Minor)"}}</td>
          <th></th>
          <td></td>
         </tr> --}}
									</tbody>
								</table>
							</div>

						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-md-12" style="float:left;margin-left:20px">
							<span>&nbsp;I have released the seat from the Programme {{ $merit_list->course->name }}</span>
						</div>
					</div>
                    <br />
                    <br />
                    <div class="row">
                        <div class="col-md-3" style="float:left;margin-left:20px">
                            <span>Date : {{date('d-m-Y')}} </span>
                        </div>
                        <div class="col-md-3" style="float:right;margin-right:20px">
                            <span></span>
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
					<br /><br />
					<br /><br />

				</div>
			</div>
		</div>
	</div>
@endsection
