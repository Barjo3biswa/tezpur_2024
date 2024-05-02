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
                <td class="text-right">{{$item->amount}}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td class="text-right"> Total </td>
            <td class="text-right">{{number_format($collection->sum("amount"), 2)}}</td>
        </tr>
        
        @if($last_receipt)
            <tr>
                <td></td>
                <td class="text-right"> Previous Receipt <strong>#{{$last_receipt->receipt_no}}</strong> </td>
                <td class="text-right">-{{number_format($last_receipt->total, 2)}}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right"> Net Paid </td>
                <td class="text-right">{{number_format($collection->sum("amount") - $last_receipt->total, 2)}}</td>
            </tr>
        @endif
    @else
        <tr>
            <td colspan="2">Sorry data not found.</td>
        </tr>
    @endif

</table>