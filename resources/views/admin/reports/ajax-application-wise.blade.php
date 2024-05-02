@php 
    $sums = [];
@endphp 
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-right">SL</th>
            <th class="text-right">Application No</th>
            <th class="text-right">Receipt No</th>
            <th class="text-right">Roll No</th>
            <th class="text-right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collections as $key => $receipt)
            <tr>
                <td class="text-right">{{ ++$key }}</td>
                <td class="text-right">{{ $receipt->application->application_no }}</td>
                <td class="text-right">{{ $receipt->receipt_no }}</td>
                <td class="text-right">{{ $receipt->roll_number }}</td>
                <td class="text-right">{{ number_format($receipt->total, 2) }}</td>
            </tr>
        @endforeach 
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-right">Total</th>
            <th class="text-right">{{ number_format($collections->sum("total"), 2) }}</th>
        </tr>
    </tfoot>
</table>