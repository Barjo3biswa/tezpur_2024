@extends('admin.layout.auth')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection
@section("content")
@if(Session::has('warning'))
<div class="container">
    @foreach(session('warning') as $e)
    <div class="row">
        <div class="alert alert-danger">
            {{$e}}
        </div>
    </div>
    @endforeach
</div>   
@endif
{{-- <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            @include('admin/applicants/filter')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
<div class="container">
<form name="merit" action="{{route('admin.merit.store')}}" method="POST" enctype="multipart/form-data" autocomplete="false">  
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"><strong> Upload Merit List</strong></div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                               Name
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" value="{{old('name')}}" required>
                             </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                Select Programme
                            </div>
                            <div class="col-md-8">
                                
                                <select name="course_id" class="js-example-basic-single" style="width:100% !important" required autocomplete="off">
                                    <option value="">Select</option>
                                    @foreach($courses as $key=>$course)
                                    <option value="{{$course->id}}" @if($course->id == old('course_id')) selected @endif >{{$course->name}}</option>
                                    @endforeach
                                </select>
                            
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                Choose merit list excel file
                            </div>
                            <div class="col-md-8">
                                <input type="file" name="merit_list" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                Admission Opening date for Initial candidate
                            </div>
                            
                            <div class="col-md-8">
                                <input type="text" name="date_from" class="form-control date" value="{{old('date_from')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                Admission link will be automatically closed after how many days ?
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" name="days" class="form-control" value="{{old('days')}}" required>
                                    <div class="input-group-addon">Days</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                How do you want to process the seat opening & closing
                            </div>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    <input type="radio" id="inlineRadio1" value="automatic" name="processing_technique" checked> Automatic
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="inlineRadio2" value="manual" name="processing_technique"> Manual
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                How do you want to process the Admission Process
                            </div>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    <input type="radio" id="inlineRadio3" value="0" name="admission_technique" checked> By Student
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="inlineRadio4" value="1" name="admission_technique"> By Department
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                List Type (Merit/ Waiting)
                            </div>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    <input type="radio" id="merit1" value="merit" name="list_type" checked> Merit list
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="merit2" value="waiting" name="list_type"> Waiting List
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" name="submit" class="btn btn-success">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>   
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
$("button[type='reset']").on("click", function(){
    $(".filter input").attr("value", "").val("");
    $(".filter").find("select").each(function(index, element){
        $(element).find("option").each(function(){
            if (this.defaultSelected) {
                this.defaultSelected = false;
                // this.selected = false;
                $(element).val("").val("all");
                return;
            }
        });
    });
});
resetPassword = function(string){
    if(!confirm("Change Password ?")){
        return false;
    }
    var ajax_post =  $.post('{{route("admin.applicants.changepass")}}', {"_token" :'{{csrf_token()}}', 'user_id':string});
    ajax_post.done(function(response){
        alert(response.message);
    });
    ajax_post.fail(function(){
        alert("Failed Try again later.");
    });
}
</script>    
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            showClose: true
        });
        $('#closing_date').datetimepicker({
            useCurrent: false, //Important! See issue #1075
            format: 'YYYY-MM-DD HH:mm'
        });
        $("#opening_date").on("dp.change", function (e) {
            $('#closing_date').data("DateTimePicker").minDate(e.date);
        });
        $("#closing_date").on("dp.change", function (e) {
            $('#opening_date').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>
@endsection