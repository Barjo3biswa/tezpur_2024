@php
    $guard = get_route_guard();
@endphp
@extends ($guard.'.layout.auth')
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
                    <form action="{{ route("admin.reports.admission-payments") }}" method="GET">
                        @include ("admin.reports.payments.filter")
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Admission fee collections:</strong>
                    <small>: {{ $payments->total() }} records found.</small>
                    <span class="pull-right">
                        <a
                            href="{{ request()->fullUrlWithQuery(['export' => "excel"]) }}">
                            <button class="btn btn-sm btn-success">Export</button>
                        </a>
                    </span>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        @include ("admin.reports.payments.admission-fee-daily-table", ["payments" => $payments])
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