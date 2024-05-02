@extends ('admin.layout.auth')
@section("title", "Questionnaires")
@section ('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .parent, .child, .options-child {
        border: 1px dashed gray;
        margin: 3px;
        margin-left:5px;
        padding-left:5px;
    }
</style>
@endsection 
@section ("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Questionnaires: <span class="label label-primary">Create</span></strong>
                            <span class="pull-right">
                                <a href="{{route("admin.questionnaires.index")}}">
                                    <button class="btn btn-sm btn-info"><i class="glyphicon glyphicon-list"></i> All questions</button>
                                </a>
                            </span>
                        </div>
                <div class="panel-body">
                    
                    <form action="{{route("admin.questionnaires.store")}}" method="POST">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label for="Programme" class="label-control">Programme:</label>
                                    <select name="course_id" id="course_id" class="js-example-basic-single select2" style="width:100% !important"  autocomplete="off">
                                        <option value="">SELECT</option>
                                        @foreach($courses as $key=>$course)
                                            <option value="{{$course->id}}" @if(request()->get("course_id") == $course->id) selected @endif >{{$course->name}} ({{$course->code}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <ol>
                            <div class="parent">
                                <div class="row">
                                    <li></li>
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <label for="type" class="label-control">Question type</label>
                                            <select name="form[0][type]" id="type" class="js-example-basic-single" style="width:100% !important"  autocomplete="off" onChange="questionTypeChange(this)">
                                                <option value="">SELECT</option>
                                                @foreach (\App\Models\EligibilityQuestion::$QUESTION_TYPES as $type)
                                                    
                                                    <option value="{{ $type}}" {{request()->get("type") == $type ? "selected" : ''}}>{{$type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-sm btn-danger" type="button" onClick="removeRow(this)"><i class="glyphicon glyphicon-remove-circle"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <ol>
                                        <div class="child"></div>
                                    </ol>
                                </div>
                            </div>
                        </ol>
                        <div class="row">
                            <div class="col-sm-offset-8 col-sm-2">
                                <button class="btn btn-sm btn-primary" type="button" onClick="addMore(this)">Add More</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 text-right">
                                <button class="btn btn-sm btn-success" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section ('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    input_counter = 0;
    getRow = function(){
        return `
            <div class="parent" style="display:none">
                <div class="row">
                    <li></li>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="type" class="label-control">Question type</label>
                            <select name="form[${input_counter + 1}][type]" id="type" class="js-example-basic-single select2" style="width:100% !important"  autocomplete="off" onChange="questionTypeChange(this)">
                                <option value="">SELECT</option>
                                @foreach (\App\Models\EligibilityQuestion::$QUESTION_TYPES as $type)
                                    
                                    <option value="{{ $type}}" {{request()->get("type") == $type ? "selected" : ''}}>{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-danger" type="button" onClick="removeRow(this)"><i class="glyphicon glyphicon-remove-circle"></i></button>
                    </div>
                </div>
                <div class="row child"></div>
            </div>
        `;
    }
    getTextQuestionRow = function(){
        return `
            <div class="row">
                
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <div class="form-group">
                        <label for="type" class="label-control">Question</label>
                        <input type="text" placeholder="question" class="form-control" name="form[${input_counter}][question]"/>
                    </div>
                    <div class="form-group">
                        <label for="type" class="label-control">Operator for comparison</label>
                        <select name=" name="form[${input_counter}][operator]" id="type" class="js-example-basic-single select2" style="width:100% !important"  autocomplete="off" onChange="questionTypeChange(this)">
                            <option value="">SELECT</option>
                            @foreach (\App\Models\EligibilityQuestion::$OPERATORS as $operator)
                                <option value="{{ $operator}}">{{$operator}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type" class="label-control">Eligibility mark / answer</label>
                        <input type="text" name="form[${input_counter}][eligibility_mark]" value="" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="type" class="label-control">Total Mark <small class="text-danger">(if not required leave blank)</small></label>
                        <input type="text" name="form[${input_counter}][total]" placeholder="total" value="" class="form-control"/>
                    </div>
                </div>
                
            </div>
        `;
    }
    getRadioRow = function(){
        return `<div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <div class="form-group">
                    <label for="type" class="label-control">Question</label>
                    <input type="text" value="" placeholder="question" class="form-control" name="form[${input_counter}][question]"/>
                </div>
                <div class="form-group">
                    <label for="type" class="label-control">Operator for comparison</label>
                    <select name="form[${input_counter}][operator]" id="type" class="js-example-basic-single select2" style="width:100% !important"  autocomplete="off" onChange="questionTypeChange(this)">
                        <option value="">SELECT</option>
                        @foreach (\App\Models\EligibilityQuestion::$OPERATORS as $operator)
                            <option value="{{ $operator}}">{{$operator}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="type" class="label-control">Eligibility mark / answer</label>
                    <input type="text" value="" name="form[${input_counter}][eligibility_mark]" class="form-control"/>
                </div>
                <div class="options-row" data-option_index="0" data-input_counter="${input_counter}">
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 options">
                            <ol>
                                <div class="col-md-4 option">
                                    <li></li>
                                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                        <div class="form-group">
                                            <label for="type" class="label-control">Label</label>
                                            <input type="text" name="form[${input_counter}][options][0][label]" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="type" class="label-control">Value</label>
                                            <input type="text" value="" name="form[${input_counter}][options][0][value]" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-sm btn-danger" type="button" onClick="removeOption(this)"><i class="glyphicon glyphicon-remove-circle"></i></button>
                                    </div>
                                </div>
                            </ol>
                            
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-sm btn-primary" type="button" onClick="addMoreOptions(this)">Add More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }
    getOptionsQuestionRow = function(){
        return `
            <div class="options-child">
                <div class="row">
                    <li></li>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="type" class="label-control">Question type</label>
                            <select name="form[${input_counter + 1}][type]" id="type" class="js-example-basic-single select2" style="width:100% !important"  autocomplete="off" onChange="questionTypeChangeUnderOptions(this)">
                                <option value="">SELECT</option>
                                @foreach (\App\Models\EligibilityQuestion::$QUESTION_TYPES as $type)
                                    @if($type=="text")
                                        <option value="{{ $type}}" {{request()->get("type") == $type ? "selected" : ''}}>{{$type}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-danger" type="button" onClick="removeRowUnderOptions(this)"><i class="glyphicon glyphicon-remove-circle"></i></button>
                        <button class="btn btn-sm btn-info" type="button" onClick="AddMoreQuestionToOptions(this)">Add More Question</button>
                    </div>
                </div>
                <div class="row">
                    <ol>
                        <div class="child"></div>
                    </ol>
                </div>
            </div>
        `;
    }
    questionTypeOptionsChange = function(obj){

    }
    removeRowOption = function(obj){

    }
    var type_options_row = `
        <div class="row options-parent">
            <li></li>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <div class="form-group">
                    <label for="type" class="label-control">Option</label>
                    <input type="text" placeholder="option" class="form-control"/>
                </div>
                <div class="form-group">
                    <ol>
                        ${getOptionsQuestionRow()}
                    </ol>
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <button class="btn btn-sm btn-danger" type="button" onClick="addMoreOptionsOnOptionQuestion(this)">
                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                </button>
                <button class="btn btn-sm btn-warning" type="button" onClick="addMoreOptionsOnOptionQuestion(this)">Add More option</button>
            </div>
        </div>    
    `;
    
    getOptionRow = function(obj){
        // obj is the add more option button
        return `
        <div class="row options-parent">
            <li></li>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <div class="form-group">
                    <label for="type" class="label-control">Option</label>
                    <input type="text" placeholder="option" class="form-control"/>
                </div>
                <div class="form-group">
                    <ol>
                        ${getOptionsQuestionRow()}
                    </ol>
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <button class="btn btn-sm btn-danger" type="button" onClick="addMoreOptionsOnOptionQuestion(this)">
                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                </button>
                <button class="btn btn-sm btn-warning" type="button" onClick="addMoreOptionsOnOptionQuestion(this)">Add More option</button>
            </div>
        </div> 
        `;
    }
    $(document).ready(function(){
        $(".select2").select2();
    });
    addMore = function(obj){
        // only increment while adding more row 
        input_counter++;
        $(".parent").last().after(getRow());
        $(".parent").last().show();
    }
    removeRow = function(obj){
        if($(".parent").length == 1){
            alert("at-least one row required.");
            return false;
        }
        $(obj).parents(".parent").hide(function(){
            $(this).remove();
        });
    }
    questionTypeChangeUnderOptions = function(obj){
        var type = $(obj).val();
        // it will only have text no need to think about radio and options.
        if(type == 'text'){
            $(obj).closest(".options-child").find(".child").html(getTextQuestionRow());
        }else if(type == 'radio'){
            $(obj).closest(".options-child").find(".child").html(getRadioRow());
        }else if(type == 'options'){
            $(obj).closest(".options-child").find(".child").html(getOptionRow());
            $(obj).closest(".options-child").find(".parent").show();
        }
    }
    removeRowUnderOptions = function(obj){
        if($(obj).parents(".options-parent").find(".options-child").length == 1){
            alert("at-least one option is required.");
            return false;
        }
        $(obj).parents(".options-child").hide(function(){
            $(this).remove();
        });
    }
    AddMoreQuestionToOptions = function(obj){
        $(".options-parent").find(".options-child:last").after(getOptionsQuestionRow())
    }
    questionTypeChange = function(obj){
        var type = $(obj).val();
        if(type == 'text'){
            $(obj).closest(".parent").find(".child").html(getTextQuestionRow());
        }else if(type == 'radio'){
            $(obj).closest(".parent").find(".child").html(getRadioRow());
        }else if(type == 'options'){
            $(obj).closest(".parent").find(".child").html(type_options_row);
            $(obj).closest(".parent").find(".parent").show();
        }
    }
    addMoreOptions = function(obj){
        console.log("addMoreOptions calling");
        var option_index = parseInt($(obj).parents(".options-row").data("option_index")) + 1;
        var this_input_counter = parseInt($(obj).parents(".options-row").data("input_counter"));
        var option_html = `
            <div class="col-md-4 option">
                <li></li>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <div class="form-group">
                        <label for="type" class="label-control">Label</label>
                        <input type="text" value="" name="form[${this_input_counter}][options][${option_index}][label]" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="type" class="label-control">Value</label>
                        <input type="text" value="" name="form[${this_input_counter}][options][${option_index}][value]" class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-sm btn-danger" type="button" onClick="removeOption(this)"><i class="glyphicon glyphicon-remove-circle"></i></button>
                </div>
            </div>
        `;
        // replacing new counter 
        $(obj).parents(".options-row").data("option_index", option_index);
        $(obj).parents(".options-row").find(".options").find(".option:last").after(option_html);
    }
    addMoreOptionsOnOptionQuestion = function(obj){
        $(obj).parents(".child").find(".options-parent:last").after(getOptionsRow);
    }
    removeOptionsFromOptionQuestion = function(obj){
        if($(obj).parents(".child").find(".options-parent").length == 1){
            return false;
        }
    }
    removeOption = function(obj){
        // $(this).parents(".option");
        if($(obj).parents(".options").find(".option").length == 1){
            alert("at-least one row required.");
            return false;
        }
        $(obj).parents(".option").hide(function(){
            $(this).remove();
        });
    }
</script>
@endsection