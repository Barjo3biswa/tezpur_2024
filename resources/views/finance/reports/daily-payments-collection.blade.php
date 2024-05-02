@extends ('finance.layout.auth')
@section ('css')
@endsection
@section ("content")
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-filter"></i> Filter:</strong>
                </div>
                <div class="panel-body">
                    <form action="{{ route("finance.reports.daily-collections") }}" method="GET">
                        @include ("admin.reports.payments.filter", ["showPage" => false])
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Daily Fee Collections (Admission/Application): <span class="label label-primary">Total collections {{ $total_receipts }}</span></strong>
                            <span class="pull-right">
                                <a
                                    href="{{ request()->fullUrlWithQuery(['export' => "excel"]) }}">
                                    <button class="btn btn-sm btn-success">Export</button>
                                </a>
                            </span>
                        </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-right">SL. No.</th>
                                    <th class="text-right">Date</th>
                                    <th class="text-right">Collection Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($collection_date_wise->count())
                                    @foreach ($collection_date_wise as $key => $row)
                                        <tr>
                                            <td class="text-right">{{ $key +1 }}</td>
                                            <td class="text-right">{{ $row->date }}</td>
                                            <td class="text-right">{{ number_format($row->sum_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th class="text-right" colspan="2">Total</th>
                                        <th class="text-right">
                                            {{ number_format($collection_date_wise->sum("sum_amount"), 2) }}
                                        </th>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="3">No Data found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section ('js')
<script>
    $(document).ready(function () {
        $('input#date_from').Zebra_DatePicker({
            format: 'd-m-Y',
            readonly_element: false,
            // direction: [-1,false],
            direction: false,
            pair: $('input#date_to'),
            onSelect: function () {
                $(this).change();
                // console.log($(this).val());
            }
        });
        $('input#date_to').Zebra_DatePicker({
            format: 'd-m-Y',
            readonly_element: false,
            direction:true,
            onSelect: function () {
                $(this).change();
                // console.log($(this).val());
            }
        });
        $('input.zebra').each(function (index, element) {
            $(this).attr({
                "data-inputmask": "'alias': 'dd-mm-yyyy'",
                "pattern": "(?:(?:0[1-9]|1[0-9]|2[0-9])-(?:0[1-9]|1[0-2])|(?:30)-(?:(?!02)(?:0[1-9]|1[0-2]))|(?:31)-(?:0[13578]|1[02]))-(?:19|20)[0-9]{2}"
            });
            $(this).inputmask();
        });
    });
</script>
@endsection 