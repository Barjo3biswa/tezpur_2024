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
    @else
        <tr>
            <td colspan="2">Sorry data not found.</td>
        </tr>
    @endif

</table>