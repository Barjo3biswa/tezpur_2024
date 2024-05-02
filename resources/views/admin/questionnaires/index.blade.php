@extends ('admin.layout.auth')
@section("title", "Questionnaires")
@section ('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection 
@section ("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include ('admin.questionnaires.filter')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Questionnaires: <span class="label label-primary">total question {{ $questions->total() }}</span></strong>
                            <span class="pull-right">
                                <a href="{{route("admin.questionnaires.create")}}">
                                    <button class="btn btn-sm btn-info">Add New</button>
                                </a>
                            </span>
                        </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="">Question</th>
                                    <th class="">Type</th>
                                    <th class="">Details</th>
                                    <th class="">Programm</th>
                                    <th class="">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($questions as $question)
                                    <tr>
                                        <td>{{$question->question}}</td>
                                        <td>{{$question->type}}</td>
                                        <td>
                                            @if($question->type == "text" || $question->type == "optional")
                                                <ol>
                                                    @foreach ($question->question_details as $key => $value)
                                                        <li>{{$key}} : <strong>{{$value}}</strong></li>
                                                    @endforeach
                                                </ol>
                                            @elseif($question->type == "radio")
                                                <ol>
                                                    <li>operator : <strong>{{$question->question_details["operator"]}}</strong></li>
                                                    <li>eligibility_mark  : <strong>{{$question->question_details["eligibility_mark"]}}</strong></li>
                                                    <li>options  : <strong>
                                                        @foreach ($question->question_details["options"] as $option)
                                                            {{$option->value}},
                                                        @endforeach
                                                        </strong></li>
                                                </ol>
                                            @elseif($question->type == "options")
                                                <ol>
                                                    @foreach ($question->question_details as $option_value => $option_question_details)
                                                        <li>
                                                            Option: <strong>{{$option_value}}</strong>
                                                            @if(is_array($option_question_details) && isset($option_question_details[0]))
                                                            <ol>
                                                                @foreach ($option_question_details as $key => $array)
                                                                    @php
                                                                        $array = (array)$array;
                                                                    @endphp
                                                                    <li>
                                                                        <ul>
                                                                            <li>type: {{$array["type"]}}</li>
                                                                            <li>operator: {{$array["operator"]}}</li>
                                                                            <li>question: {{$array["question"]}}</li>
                                                                            <li>default total_marks: {{$array["total_marks"]}}</li>
                                                                            <li>eligibility_mark: {{$array["eligibility_mark"]}}</li>
                                                                        </ul>
                                                                    </li>
                                                                @endforeach
                                                            </ol>
                                                            @else
                                                                <ul>
                                                                    <li>type: {{$option_question_details["type"]}}</li>
                                                                    <li>operator: {{$option_question_details["operator"]}}</li>
                                                                    <li>question: {{$option_question_details["question"]}}</li>
                                                                    <li>default total_marks: {{$option_question_details["total_marks"]}}</li>
                                                                    <li>eligibility_mark: {{$option_question_details["eligibility_mark"]}}</li>
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </td>                                        
                                        <td>{{$question->course->name ?? "NA"}}</td>
                                        <td>
                                            {{-- <a class="" href="#"><button class="bnt btn-primary btn-xs"> <i class="glyphicon glyphicon-edit"></i> Edit</button></a> --}}
                                            <a class="" href="#"><button class="bnt btn-danger btn-xs" onClick="alert('Under construction')"><i class="glyphicon glyphicon-trash"></i> Remove</button></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-danger text-center" colspan="5"><strong>No Data found.</strong></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{$questions->appends(request()->only("type", "course_id"))->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section ('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $(".select2").select2();
    })
</script>
@endsection