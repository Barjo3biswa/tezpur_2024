@extends('student.layouts.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Eligibility Criteria <span class="pull-right"></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 alert alert-danger">
                            <ol>
                                <li>Only if you meet the eligibility criteria, you will be allowed to proceed further.</li>
                                <li>Please verify before submitting. You will not be allowed to edit your response.</li>
                                <li>Applications without the below information and supporting documents will be treated as incomplete and will be rejected.</li>
                                <li>Incase of grade system enter 0 (zero) in Total and Aggregate Marks.</li>
                            </ol>
                        </div>
                        <div class="col-sm-12" id="ajax_message">

                        </div>
                    </div>

                    <div class="row col-sm-12">
                        <form class="form-horizontal form-class" onSubmit="submitEligibilityForm(event, this)">
                            {{ csrf_field() }}
                            @forelse ($questions as $question)
                                @php
                                    // need to increase on every input
                                    $input_counter = 0;
                                @endphp
                                <div class="form-group">
                                    <label for="father_name" class="col-md-4 control-label text-right margin-label">{{$question->question}}: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        {{-- {{dump($question)}} --}}
                                        @if($question->type === "options")
                                            <select class="form-control" id="option_id_{{$question->id}}">
                                                <option value="">--SELECT--</option>
                                                @foreach ($question->question_details as $key => $option)
                                                    <option value="{{$key}}">{{ucwords($key)}}</option>
                                                @endforeach
                                            </select>
                                            <script>
                                                
                                                window.addEventListener('load', function(event){
                                                    $(document).ready(function(){
                                                        $("#option_id_{{$question->id}}").change(function(){
                                                            var form_data = @json($question->question_details);
                                                            var value = $(this).val();
                                                            // php based logic in javascript
                                                            var html = "";
                                                            @foreach ($question->question_details as $key => $option)
                                                                if(value == '{{$key}}'){
                                                                    var html ='';
                                                                    @if(is_array($option) && isset($option[0]))
                                                                        @foreach($option as $index => $val)
                                                                            html +=`<div class="form-group">
                                                                                {{-- we cannot put below three hidden field as common on top because. sometime 
                                                                                
                                                                                    options has multiple question so $input_counter ++ not works
                                                                                --}}
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question_id]" value="{{$question->id}}">
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question]" value="{{$val->question}}">
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][operator_condition]" value="{{$val->operator}}">
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][eligibility_requirement]" value="{{$val->eligibility_mark}}">
                                                                                <label for="" class="col-md-4 control-label text-right margin-label">{{$val->question}}: <span
                                                                                class="text-danger">*</span></label>
                                                                                <div class="col-md-3">
                                                                                    <input type="number" name="form[{{$question->id}}][{{$input_counter}}][total]" class="form-control text-right" required placeholder="Total" value="{{$val->total_marks}}" {{stripos($val->question, "percentage") !== false ? 'readonly' : ''}} step="0.01">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input type="number" name="form[{{$question->id}}][{{$input_counter}}][answer]" class="form-control text-right" required placeholder="{{stripos($val->question, "percentage") !== false ? 'percentage' : 'mark'}}" value="0.0" step="0.01">
                                                                                </div>
                                                                            </div>`;
                                                                            @php
                                                                                $input_counter++;
                                                                            @endphp
                                                                        @endforeach
                                                                    @else
                                                                        html =`
                                                                            <div class="form-group">
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question_id]" value="{{$question->id}}">
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question]" value="{{$option["question"]}}">
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][operator_condition]" value="{{$option["operator"]}}">
                                                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][eligibility_requirement]" value="{{$option["eligibility_mark"]}}">

                                                                                <label for="" class="col-md-4 control-label text-right margin-label">{{$option["question"]}}: <span
                                                                                class="text-danger">*</span></label>
                                                                                <div class="col-md-3">
                                                                                    <input type="number" name="form[{{$question->id}}][{{$input_counter}}][total]" class="form-control text-right" required placeholder="Total" value="{{$option["total_marks"]}}" {{stripos($val->question, "percentage") !== false ? 'readonly' : ''}} step="0.01">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input type="number" name="form[{{$question->id}}][{{$input_counter}}][answer]" class="form-control text-right" required placeholder="{{stripos($val->question, "percentage") !== false ? 'percentage' : 'mark'}}" value="0.0" step="0.01">
                                                                                </div>
                                                                            </div>
                                                                        `;
                                                                    @endif
                                                                }
                                                            @endforeach


                                                            // end php logic on javascript
                                                            console.log(form_data);
                                                            var new_input_data = form_data[$(this).val()];
                                                            console.log(new_input_data);
                                                            {{--// if(new_input_data !== undefined){
                                                            //     var html = "";
                                                            //     if(new_input_data.length){
                                                            //         $(new_input_data).each(function(index, element){
                                                            //             html +=`
                                                                        
                                                            //             <div class="form-group">
                                                            //                 <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}]["question"]" value="{{$question->id}}">
                                                            //                 <label for="" class="col-md-4 control-label text-right margin-label">${element.question}: <span
                                                            //                 class="text-danger">*</span></label>
                                                            //                 <div class="col-md-3">
                                                            //                     <input type="number" name="total" class="form-control text-right" required placeholder="Total" value="${element.total_marks}">
                                                            //                 </div>
                                                            //                 <div class="col-md-4">
                                                            //                     <input type="number" name="Aggregate" class="form-control text-right" required placeholder="Aggregate" value="0.0">
                                                            //                 </div>
                                                            //             </div>
                                                            //             `;
                                                            //         });
                                                            //     }else{
                                                            //         html +=`
                                                            //             <div class="form-group">
                                                            //                 <input type="hidden" name="question_ids[]" value="{{$question->id}}">
                                                            //                 <label for="" class="col-md-4 control-label text-right margin-label">${new_input_data.question}: <span
                                                            //                 class="text-danger">*</span></label>
                                                            //                 <div class="col-md-3">
                                                            //                     <input type="number" name="total" class="form-control text-right" required placeholder="Total" value="${new_input_data.total_marks}">
                                                            //                 </div>
                                                            //                 <div class="col-md-4">
                                                            //                     <input type="number" name="Aggregate" class="form-control text-right" required placeholder="Aggregate" value="0.0">
                                                            //                 </div>
                                                            //             </div>
                                                            //         `;
                                                            //     }
                                                            // }else{
                                                            //     html =""
                                                            // } --}}
                                                            $("#depended_question_options_{{$question->id}}").hide(function(){
                                                                $(this).html("");
                                                                $("#depended_question_options_{{$question->id}}").html(html).promise().done(function(){
                                                                    $("#depended_question_options_{{$question->id}}").show("slow");
                                                                });;
                                                            });
                                                            
                                                        });
                                                    });
                                                })
                                            </script>
                                        @elseif($question->type === "text")
                                            <div class="form-group">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question_id]" value="{{$question->id}}">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question]" value="{{$question->question}}">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][operator_condition]" value="{{$question->question_details["operator"]}}">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][eligibility_requirement]" value="{{$question->question_details["eligibility_mark"]}}">
                                                <label for="" class="col-md-3 control-label text-right margin-label">Total: <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-md-3">
                                                    <input type="number" name="form[{{$question->id}}][{{$input_counter}}][total]" class="form-control text-right" required placeholder="Total" value="{{$question->question_details["total_marks"]}}" step="0.01" 
                                                    @if(stripos($question->question, "percentage") !== false) readonly @endif
                                                    >
                                                </div>
                                                <label for="" class="col-md-3 control-label text-right margin-label">@if(stripos($question->question, "percentage")) Percentage @else Marks: @endif: <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-md-3">
                                                    <input type="number" name="form[{{$question->id}}][{{$input_counter}}][answer]" class="form-control text-right" required placeholder="Aggregate" value="0.0" step="0.01" >
                                                </div>
                                            </div>
                                            @php
                                                $input_counter++;
                                            @endphp
                                        @elseif($question->type === "optional")
                                            <div class="form-group">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question_id]" value="{{$question->id}}">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question]" value="{{$question->question}}">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][operator_condition]" value="{{$question->question_details["operator"]}}">
                                                <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][eligibility_requirement]" value="{{$question->question_details["eligibility_mark"]}}">
                                                {{--<label for="" class="col-md-3 control-label text-right margin-label d-only">Total: <span
                                                    class="text-danger">*</span></label> --}}
                                                {{--<div class="col-md-3 d-only">
                                                    <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][total]" class="form-control text-right" required placeholder="Total" value="{{$question->question_details["total_marks"]}}">
                                                </div> --}}
                                                <label for="" class="col-md-3 control-label text-right margin-label">answer: <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="text" name="form[{{$question->id}}][{{$input_counter}}][answer]" class="form-control text-right" required placeholder="answer" value="">
                                                </div>
                                            </div>
                                            @php
                                                $input_counter++;
                                            @endphp
                                        @elseif($question->type === "radio")
                                            <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question_id]" value="{{$question->id}}">
                                            <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][question]" value="{{$question->question}}">
                                            <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][operator_condition]" value="{{$question->question_details["operator"]}}">
                                            <input type="hidden" name="form[{{$question->id}}][{{$input_counter}}][eligibility_requirement]" value="{{$question->question_details["eligibility_mark"]}}">

                                            <div class="form-group">
                                                @foreach ($question->question_details["options"] as $key => $option)
                                                    <label class="radio-inline"><input type="radio" name="form[{{$question->id}}][{{$input_counter}}][answer]" value="{{$option->value}}">{{$option->value}}</label>
                                                @endforeach
                                            </div>
                                            @php
                                                $input_counter++;
                                            @endphp
                                        @endif
                                    </div>
                                </div>
                                @if($question->type === "options")
                                    <div class="" id="depended_question_options_{{$question->id}}">
                                        
                                    </div>
                                    @php
                                        $input_counter++;
                                    @endphp
                                @endif
                            @empty
                                <div class="text-danger text-center">No eligibility test found.</div>
                            @endforelse
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure ? Edit option not available. Please verify before submit.')">Submit</button>                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('js')
<script>
    submitEligibilityForm = function(event, obj){
        event.preventDefault();
        var url = '{{route("student.application.eligibility.store", $application)}}';
        console.log($(obj).serialize())
        var data = $(obj).serializeArray();
        button = $(obj).find(".btn");
        button.text("Submitting...");
        $.post(url, data)
        .done(function(response){
            // console.log(response);
            $("#ajax_message").html(response);
            $("#ajax_message").html(response.message);
            if(response.status){
                alert("success.");
                // window.location.reload();
                window.location.replace(response.url)
            }
        })
        .fail(function(){
            alert("Whoops! something went wrong.");
        })
        .always(function(response){
            // $("#ajax_message").html(response);
            button.text("Submit");
        });
    }
</script>
@endsection