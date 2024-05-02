@extends(dynamic_layout())
@section("content") 
<div class="container" id="page-content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title" style="padding-bottom: 5px;">Eligibility Criteria for application ID: {{$application->id}} <br /> Status : {!! $application->is_eligibility_critaria_fullfilled ? "<span class='label label-success'>Eligible</span>" : "<span class='label label-danger'>Not Eligible</span>" !!}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Eligibility Question</th>
                        <th>Criteria</th>
                        <th>Total</th>
                        <th>Entered by student</th>
                        <th>eligibility fullfilled</th>
                    </tr>
                </thead>
                <tbodY>
                    @foreach ($eligibility_criteria as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->question}}</td>
                            <td class="text-right">{{str_replace("_", " ",  $item->operator_condition)}}
                                @if($item->operator_condition != "any")
                                    <strong>{{$item->eligibility_requirement}}</strong>
                                @endif
                            </td>
                            <td class="text-right">{{$item->total ? $item->total : "NA"}}</td>
                            <td class="text-right">{{$item->answer}}</td>
                            <td class="text-right">{!!$item->is_eligibility_pass ? "<span class='label label-success'>yes</span>" :  "<span class='label label-danger'>no</span>" !!}</td>
                        </tr>
                    @endforeach
                </tbodY>
            </table>
        </div>
    </div>
</div>
@endsection