<table class="table table-bordered">
    <thead>
        <th>#</th>
        <th>Registration No</th>
        <th>Application No</th>
        <th>Applicant Name</th>
        <th>Programmes</th>
        <th>Payment Date</th>
        <th>Amount</th>
    </thead>
    <tbody>
        @forelse ($payments as $key => $payment)
            <tr>
                <td>{{$key + $payments->firstItem()}}</td>
                <td>{{$payment->application->student_id ?? "NA"}}</td>
                <td>{{$payment->application->application_no ?? "NA"}}</td>
                <td>{{$payment->application->full_name ?? "NA"}}</td>
                <td>{{$payment->courses->implode("name", ", ") ?? "NA"}}</td>
                <td>{{$payment->updated_at->format("d-m-Y h:i a") ?? "NA"}}</td>
                <td class="text-right">{{$payment->amount_decimal}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-danger">Sorry no records found.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="text-right"><strong>Total: </strong></th>
            <th class="text-right"><strong>{{number_format(($payments->sum("amount") ?? 0.00), "2", ".", "")}}</strong></th>
        </tr>
    </tfoot>
</table>
{{$payments->links()}}
