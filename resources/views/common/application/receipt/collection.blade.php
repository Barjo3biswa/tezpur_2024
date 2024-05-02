<table class="table table-bordered">
    <tr>
        <th>Sl. No.</th>
        <th>Particulars</th>
        <th class="text-right">Amount</th>
    </tr>
    @if($collection)
        @foreach ($collection as $key => $item)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$item->feeHead->name}}</td>
                <td class="text-right">{{number_format($item->amount, 2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td class="text-right"> Total </td>
            <td class="text-right">{{number_format($collection->sum("amount"), 2)}}</td>
        </tr>
        @if($receipt->previous)
            @php
                $previous_receipt = $receipt->previous;
                $previous_amount = ($previous_receipt ? $previous_receipt->total : 0.00);
            @endphp
            <tr>
                <td></td>
                <td class="text-right"> Previously Paid <strong>#{{$previous_receipt->receipt_no}}</strong></td>
                <td class="text-right">{{number_format($previous_amount, 2)}}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right"> Total Paid </td>
                @php
                    $fees = number_format(($collection->sum("amount") - $previous_amount ) > 0 ? ($collection->sum("amount") - $previous_amount) :
                    0.00, 2);
                @endphp
                <td class="text-right">{{number_format(($collection->sum("amount") - $previous_amount ) > 0 ? ($collection->sum("amount") - $previous_amount) : 0.00, 2)}}</td>
            </tr>
        @endif
    @else
        <tr>
            <td colspan="2">Sorry data not found.</td>
        </tr>
    @endif

</table>