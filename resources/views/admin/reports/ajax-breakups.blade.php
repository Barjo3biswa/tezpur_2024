@php
    $sums = [];
@endphp
<table class="table table-bordered">
    <thead>
        <tr>
            @foreach ($fee_heads as $head)
                <th class="text-right">{{ $head->name }}</th>
                @php
                    $sums[$head->id] = 0;
                @endphp
            @endforeach 
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @php
                $sum = 0;
            @endphp
            @foreach ($fee_heads as $head)
                <th class="text-right">
                    @php
                        $collections->where("fee_head_id", $head->id);
                        $amount = $collections->where("fee_head_id", $head->id)->count() ? $collections->where("fee_head_id", $head->id)->first()->sum_amount : 0;
                        $sums[$head->id] += $amount;
                    @endphp
                    {{ number_format($amount, 2)}}</th>
                    @php
                        $sum += $amount;
                    @endphp
            @endforeach 
                <th class="text-right">{{number_format($sum, 2)}}</th>
        </tr>
    </tbody>
{{--     <tfoot>
        @foreach ($fee_heads as $head)
                <th class="text-right">
                    {{number_format($sums[$head->id], 2)}}
                </th>
        @endforeach 
    </tfoot> --}}
</table>