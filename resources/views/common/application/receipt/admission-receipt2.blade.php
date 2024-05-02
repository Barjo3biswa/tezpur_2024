<div class="row">
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
       NAPAAM, TEZPUR - 784028, ASSAM<br> <strong> Provisional ({{strtoupper($receipt->type)}}) (Duplicate)
        @if($receipt->status)
        <br /><span class="text-danger">Seat Transferred</span>
        @endif
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
     <td>{{date("Y-m-d h:i a", strtotime($receipt->payment->created_at))}}</td>
    </tr>
    <tr>
     <th>Admission Category</th>
     <td>{{$merit_list->admissionCategory->name ?? "NA"}}</td>
     <th>Social Category</th>
     <td>{{$application->caste->name ?? "NA"}}</td>
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
    {{-- <tr>
     <th>Minority Status</th>
     <td>{{$application->religion ?? "NA"}} {{$application->religion == "Hinduism" ? "" : " (Minor)"}}</td>
     <th></th>
     <td></td>
    </tr> --}}
   </tbody>
  </table>
  @include('common.application.receipt.collection', ["collection" => $receipt->collections, "receipt" => $receipt])
 </div>

</div>