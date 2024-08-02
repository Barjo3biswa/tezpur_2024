
<a class="btn btn-primary btn-xs" data-toggle="modal" href='#viewDetailsModal{{$withdrawal_request->id}}'>View Details</a>
<div class="modal fade" id="viewDetailsModal{{$withdrawal_request->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeOneModal('viewDetailsModal{{$withdrawal_request->id}}')">&times;</button>
                <h4 class="modal-title">Withdrawal Request Details</h4>
                <button type="button" class="btn btn-primary btn-xs noPrint" onclick="printDiv('viewDetailsModal{{$withdrawal_request->id}}')">Print</button>
            </div>
            <div class="modal-body">
                    
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th>APPLICANT NAME</th>
                                <td>{{$w_request->application->full_name ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>APPLICATION NUMBER</th>
                                <td>{{$w_request->application->application_no ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>ROLL NUMBER</th>
                                <td>{{$withdrawal_request->meritList->admissionReceipt->roll_number  ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>DATE OF ADMISSION</th>
                                <td>{{ $withdrawal_request->meritList->admissionReceipt->created_at }}</td>
                            </tr>
                            <tr>
                                <th>RECEIPT NUMBER</th>
                                <td>{{ $withdrawal_request->meritList->admissionReceipt->receipt_no ?? "NA" }}</td>
                            </tr>
                            <tr>
                                <th>Admission Amount & Date</th>
                                <td>{{ $withdrawal_request->application->admission_receipt->total ?? "NA" }}/-(Trans Id: {{ $withdrawal_request->application->admission_receipt->transaction_id ?? "NA" }}, Date:{{date('d-m-Y', strtotime($withdrawal_request->application->admission_receipt->created_at))}})</td>
                            </tr>
                            <tr>
                                <th>Hostal Amount & Date</th>
                                <td>{{ $withdrawal_request->meritList->hostelReceipt->total ?? "NA" }}/-(Trans Id: {{ $withdrawal_request->meritList->hostelReceipt->transaction_id ?? "NA" }}, Date:{{date('d-m-Y', strtotime($withdrawal_request->meritList->hostelReceipt->created_at))}})</td>
                            </tr>

                            {{-- <tr>
                                <th>DOB</th>
                                <td>{{$withdrawal_request->dob ?? "NA"}}</td>
                            </tr> --}}
                            <tr>
                                <th>BANK ACCOUNT</th>
                                <td>{{$withdrawal_request->bank_account ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>ACCOUNT HOLDER NAME</th>
                                <td>{{$withdrawal_request->holder_name ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>BANK NAME</th>
                                <td>{{$withdrawal_request->bank_name ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>BRANCH NAME</th>
                                <td>{{$withdrawal_request->branch_name ?? "NA"}}</td>
                            </tr>
                            <tr>
                                <th>IFSC CODE</th>
                                <td>{{$withdrawal_request->ifsc_code ?? "NA"}}</td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
