@extends('department-user.layout.auth')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/css/bootstrap/zebra_datepicker.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
.form-control {
    min-width: 133px;
    border: 1px solid #949292 !important;
}

span.Zebra_DatePicker_Icon_Wrapper {
    position: unset !important;
}

label.date_time {
    font-weight: normal;
    font-weight: bold;
    line-height: 3.3rem;
}

.box.box-danger {
    border-top-color: #319DD3;
}
.box.box-light {
    border-top-color: #b4e6ff;
}
.box {
    position: relative;
    border-radius: 3px;
    background: #ffffff;
    border-top: 3px solid #d2d6de;
    margin-bottom: 20px;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}
.box-header {
    color: #444;
    display: block;
    padding: 10px;
    position: relative;
}
.box-body {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    padding: 10px;
}
</style>

@endsection
@section("content")
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                    <form action="" method="get">
                            @include('department-user/vacancy/filter')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                @foreach($courses as $key=>$course)
                    <div class="row">
                        <div class="col-md-12">
                                <div class="box box-danger" @if($key%2 == 0) style="border-top-color: ##40494e !important;" @endif>
                                    <div class="box-header with-border text-center">
                                        <strong style="font-size:14px" class="">{{$course->name}} {{$course->code}}</strong>
                                    </div>
                                    <div class="box-body">
                                    @if($course->courseSeats->count())
                                    @foreach($course->courseSeats as $key=>$courseSeat)
                                    <div class="col-md-3">
                                        <div class="box box-light">
                                            <div class="box-header with-border text-center">
                                                <strong style="font-size:12px" class="">{{$courseSeat->admissionCategory->name}}</strong>
                                            </div>
                                            <div class="box-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td class="text-center">Total</td>
                                                            <td  class="text-center">Filled-Up</td>
                                                            <td  class="text-center">Vacant</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td  class="text-center"><span class="badge" style="background:#000;font-size:16px">{{$courseSeat->total_seats}}</span></td>
                                                            <td  class="text-center"><span class="badge" style="background:#ff0000;font-size:16px">{{$courseSeat->total_seats_applied}}</span></td>
                                                            <td class="text-center"><span class="badge" style="background:#00a65a;font-size:16px">{{intval($courseSeat->total_seats)-intval($courseSeat->total_seats_applied)}}</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach  
                                    @endif
                                    </div>
                                </div>
                                <!-- /.box -->
                        </div> 
                    </div>
                @endforeach    
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
<script>
$(document).ready(function() {
    // $('.js-example-basic-single').select2();
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
@endsection
