@extends ('admin.layout.auth')
@section ('css')
@endsection 
@section ("content")
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Fee Collections (Admission): <span class="label label-primary">total
                            admission {{ $total_receipts }}</span></strong>
                            <span class="pull-right">
                            <a href="{{route("admin.vacancy.booked")}}">
                                <button class="btn btn-sm btn-info">Admitted Candidates</button>
                            </a>
                            </span>
                        </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-right">Date</th>
                                    <th class="text-right">Collection Amount</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($collection_date_wise->count())
                                    @foreach ($collection_date_wise as $row)
                                        <tr>
                                            <td class="text-right">{{ $row->date }}</td>
                                            <td class="text-right">{{ number_format($row->sum_amount, 2) }}</td>
                                            <td class="text-right">
                                                <button class="btn btn-xs btn-primary"
                                                    onclick="viewBreakUps('{{ $row->date }}')">View Breakups</button>
                                                <button class="btn btn-xs btn-success"
                                                    onclick="applicationsWise('{{ $row->date }}')">Application Wise</button>
                                            </td>
                                        </tr>
                                    @endforeach 
                                    <tr>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">
                                            {{ number_format($collection_date_wise->sum("sum_amount"), 2) }}
                                        </th>
                                        <td></td>
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
<!-- Modal -->
<div id="breakupModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Collections Breakups : <strong id="date"></strong></h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
           {{--  <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> --}}
        </div>

    </div>
</div>
@endsection 

@section ('js')

<script>
    // $(document).ready(function(){})
    viewBreakUps = function (date = "") {
        console.log(date);
        var $modal = $("#breakupModal");
        $modal.find("#date").html(date);
        var url = `{{route("admin.fee.reports.breakup")}}`;
        var xhr = $.get(url, {date: date});
        $modal.find(".modal-body").html("<h3 class='text-center'>Loading...</h3>");       
        $modal.modal();
        xhr.done(function(resp){
            $modal.find(".modal-body").html(resp);;
        });
        xhr.fail(function(resp){
            $modal.find(".modal-body").html("<h3>Data fetching failed.</h3>");;
        });
    }
    applicationsWise = function (date = "") {
        console.log(date);
        var $modal = $("#breakupModal");
        $modal.find("#date").html(date);
        var url = `{{route("admin.fee.reports.applications")}}`;
        var xhr = $.get(url, {date: date});
        $modal.find(".modal-body").html("<h3 class='text-center'>Loading...</h3>");       
        $modal.modal();
        xhr.done(function(resp){
            $modal.find(".modal-body").html(resp);;
        });
        xhr.fail(function(resp){
            $modal.find(".modal-body").html("<h3>Data fetching failed.</h3>");;
        });
    }
</script>
@endsection