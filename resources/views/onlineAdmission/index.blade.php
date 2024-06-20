@extends($layot)
@section ('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/css/bootstrap/zebra_datepicker.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    .form-control {
        min-width: 133px;
        border: 1px solid #949292 !important;
    }

    span.Zebra_DatePicker_Icon_Wrapper {
        position: unset !important;
        width: 100% !important;
    }

    label.date_time {
        font-weight: normal;
        font-weight: bold;
        line-height: 3.3rem;
    }
    .bg-info {
        background-color: #d9edf7 !important;
    }
    
</style>

@endsection 
@section ("content")
@php
    $castes = \App\Models\Caste::pluck("name","id")->toArray();
    $btech_programs = \App\Course::whereIn("id", btechCourseIds())->withTrashed()->get();
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Call Students For Admission Process: </div>
                <div class="panel-body">
                    <form {{-- action="{{route(get_route_guard().'.merit.automate')}}" method="post" --}}>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="Programme" class="label-control">Programme:</label>
                                <select name="course_id" id="course_id" class="form-control js-example-basic-single" style="width:100% !important"  autocomplete="off">
                                    <option value="">All</option>
                                    @if(auth('admin')->check())
                                        @foreach($courses as $key=>$course)
                                        <option value="{{$course->id}}" @if(request()->get("course_id") == $course->id) selected @endif >{{$course->name}} ({{$course->code}})</option>
                                        @endforeach
                                    @else
                                        @foreach (programmes_array() as $id => $name)
                                        <option value="{{$id}}" {{request()->get("course_id") == $id ? "selected" : ""}}>{{$name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="merit_master_id" class="label-control">Select List:</label>
                                <select name="merit_master_id" id="merit_master_id" class="form-control" {{-- style="width:100% !important" --}}  autocomplete="off">
                                    <option value="">--select--</option>
                                    @foreach ($list as $li)
                                        <option value="{{$li->id}}" {{request()->get("merit_master_id") == $li->id ? "selected" : ""}}>{{$li->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="application_no" class="label-control">Set Admission Categopry:</label>
                                <select name="admission_cat" id="admission_cat" class="form-control">
                                    <option value="">--Select--</option>
                                    @foreach ($admission_cat as $cat)
                                    <option value="{{$cat->admissionCategory->id}}" {{request()->get("admission_cat") == $cat->admissionCategory->id ? "selected" : ""}}>
                                        @if($cat->admissionCategory->id==1)
                                            Unreserved
                                        @else
                                            {{$cat->admissionCategory->name}}
                                        @endif     
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                @if (auth("admin")->check())
                                    <input type="submit" class="btn btn-warning" name="submit" value="Process" onclick="return confirm('Are you sure you want to Process? Hence it is Department user task, you can continue with search.');">
                                @else      
                                    <input type="submit" class="btn btn-warning" name="submit" value="Process">
                                @endif
                               
                                <input type="submit" class="btn btn-success" name="submit" value="Search">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <form action="{{route(get_route_guard().'.merit.automate')}}" method="post">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-4">
            <label for="status" class="label-control">Category</label>
            <select name="admission_category_id" id="admission_category_id" class="form-control" style="width:100% !important"  autocomplete="off">
                <option value="">All</option>
                @foreach($admission_categories as $key=>$admission_category)
                <option value="{{$admission_category->id}}" {{request()->get("admission_category_id") == $admission_category->id ? "selected" : ''}}>{{$admission_category->name}}</option>
                @endforeach
            </select>
        </div>
    <input type="hidden" name="marit_master_id" value="{{$merit_lists[0]->merit_master_id}}">
    <input type="submit" class="btn btn-primary" value="Start">
    </div>
</form> --}}




<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="admission_categories"></div>
                    <form name="merit" id="merit" action="{{ route(get_route_guard().'.merit.call-for-admission') }}" 
                        method="POST">
                        {{ csrf_field() }}
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-left: 80%">
                            Proceed To Time Selection
                        </button>
                                               
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#
                                        <input class="form-check-input checkAll" type="checkbox" {{-- id="checkAll" --}}>
                                    </th>
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Prog. Name</th>
                                    <th>Name</th>
                                    <th>Admt. Cat.</th>
                                    <th>Social Cat.</th>
                                    <th>Gender</th>
                                    <th>Rank</th>
                                    <th>Hostel</th>
                                    <th>P. Type</th>
                                    <th>Preference</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($merit_lists as $key=>$list)
                                    <tr>
                                        <td>{{++$key}}
                                            @if($list->new_status=='can_call')
                                                <input class="form-check-input" type="checkbox" name="merit_list_ids[]" value="{{$list->id}}">
                                            @endif
                                        </td>
                                        @include('onlineAdmission.table-data')
                                        <td>@include('onlineAdmission.satatus')</td>
                                        <td>@include('onlineAdmission.action')</td>
                                    </tr> 
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                        {{-- {{ $merit_lists->appends(request()->all()) }} --}}

                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Set Timings For Admission.</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="">Set Opening Time</label>
                                            <input type="text" name="date_from" class="form-control date" autocomplete="off" required>
                                        </div>                     
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="">Select Time Window for student to complete</label>
                                            <select name="closing_time" class="form-control" id="">
                                                <option value="">--select--</option>
                                                <option value="1">10AM to 1PM</option>
                                                <option value="2">2PM to 5PM</option>
                                                <option value="3">Till 16 Jan 2024</option>
                                            </select>
                                            {{-- <input type="text" name="closing_time" class="form-control" value="4" required> --}}
                                        </div>                  
                                    </div> 
                                </div>
                                <div class="modal-footer">
                                    <input type="submit" class="btn btn-primary" value="Call For Admission" onclick="return confirm('Are you sure you want Call Please check again?');">
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
{{-- warning Modal --}}
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route(get_route_guard().'.merit.send-warning'/* ,Crypt::encrypt($list->id) */)}}"
                method="post">
                {{ csrf_field() }}
                <input type="hidden" name="merit_list_id" class="merit_list_id" value="">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="cancelModalLabel">Send A Warning Mail For Cancellation
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Message</span></label>
                            <textarea name="message" id="" cols="5" rows="3" class="form-control" required
                                onkeydown="restrictQuotes(event)" onkeyup="restrictQuotes(event)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary"
                        onclick="confirm('Are you sure you want to send a warning mail ?')" value="Send">
                </div>
            </form>
        </div>
    </div>
</div>
{{-- cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route(get_route_guard().'.merit.cancel-for-admission'/* ,Crypt::encrypt($list->id) */)}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="merit_list_id" class="merit_list_id" value="">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel Candidate
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Reason Of Cancel <span style="font-size:11px; color:red;">( * Mandatory)</span></label>
                            <textarea name="reason" id="" cols="5" rows="3" required class="form-control"></textarea>
                        </div>                     
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Email to candidate <span style="font-size:11px; color:red;">(If left blank, no email will be sent to the candidate)</span></label>
                            <textarea name="message" id="" cols="5" rows="3" class="form-control" onkeydown="restrictQuotes(event)" onkeyup="restrictQuotes(event)"></textarea>
                        </div>                  
                    </div> 
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" onclick="confirm('Are you sure you want to proceed with the operation? This cancellation cannot be rolled back.')" value="Submit & Cancel" >
                </div>
            </form>
        </div>
    </div>
</div>


@if (auth("admin")->check())
<div class="modal fade" id="newTime" tabindex="-1" role="dialog" aria-labelledby="newTimeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route(get_route_guard().'.merit.assign-new-time'/* ,Crypt::encrypt($list->id) */)}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="merit_list_id_new_time" id="merit_list_id_new_time" value="">
                <div class="modal-header  bg-warning">
                    <h5 class="modal-title" id="newTimeLabel">You are going to assign new time window.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Set New Opening Time</label>
                            <input type="text" name="new_date_from" class="form-control datenew" autocomplete="off" required>
                        </div>                     
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Set New Closing Time</label>
                            <input type="text" name="new_date_to" class="form-control datenew" autocomplete="off" required>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit & Cancel" >
                </div>
            </form>
        </div>
    </div>
</div>
@endif


@endsection
@section ('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
<script>
    function assignIdTo(id){
        $(".merit_list_id").val(id);
    }

    function assignIdToNew(id){
        $("#merit_list_id_new_time").val(id);
    }

   
    function restrictQuotes(event) {
    const key = event.key;
    if (key !== '"' && key !== "'") {
        return;
    }
    event.preventDefault();
    }

    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
    $("button[type='reset']").on("click", function () {
        $(".filter input").attr("value", "").val("");
        $(".filter").find("select").each(function (index, element) {
            $(element).find("option").each(function () {
                if (this.defaultSelected) {
                    this.defaultSelected = false;
                    // this.selected = false;
                    $(element).val("").val("all");
                    return;
                }
            });
        });
    });
    $("#template_id").change(function(event){
        var template = "";
        if($(this).val() !== ""){
            template = $("#template_id option:selected").data("template");
        }
        $("#sms").val(template);
    });
    resetPassword = function (string) {
        if (!confirm("Change Password ?")) {
            return false;
        }
        var ajax_post = $.post('{{ route(get_route_guard().".applicants.changepass") }}', {
            "_token": '{{ csrf_token() }}',
            'user_id': string
        });
        ajax_post.done(function (response) {
            alert(response.message);
        });
        ajax_post.fail(function () {
            alert("Failed Try again later.");
        });
    }
    showUndertaking = function(obj){
        $(".loading").fadeIn();
        var $this = $(obj);
        var xhr = $.get($this.data("url"));
        xhr.done(function(resp){
            var $modal = $("#viewUndertaking");
            $modal.find("#u_app_bno").html($this.data("app_no"));
            $modal.find(".modal-body").html(resp);
            $modal.modal("show");
        });
        xhr.fail(function(){
            alert("Whoops! something went wrong.");
        });
        xhr.always(function(){
            $(".loading").fadeOut();
        })
    }
    closeUndertakingModal = function(){
        $(".loading").fadeOut();
        $("#viewUndertaking").modal("hide");
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.date').datetimepicker({
            // format: 'YYYY-MM-DD HH:mm:ss',
            format: 'YYYY-MM-DD',
            showClose: true
        });

        $('.datenew').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            // format: 'YYYY-MM-DD',
            showClose: true
        });
        // $('#opening_date').datetimepicker({
        //     format: 'YYYY-MM-DD'
        // });
        // $('#closing_date').datetimepicker({
        //     useCurrent: false, //Important! See issue #1075
        //     format: 'YYYY-MM-DD'
        // });
        // $("#opening_date").on("dp.change", function (e) {
        //     $('#closing_date').data("DateTimePicker").minDate(e.date);
        // });
        // $("#closing_date").on("dp.change", function (e) {
        //     $('#opening_date').data("DateTimePicker").maxDate(e.date);
        // });
    });   

    var admissionCategoryList = ($id) =>{
        var master = "<option value=''>Select</option>";
        $.ajax({
            url:'{{route(get_route_guard().".merit.master")}}',
            type:'post',
            data:{
                'course_id':$id,
                '_token':"{{csrf_token()}}"
            },
            success:function(response){
                console.log(response);
                if(response.success == true){
                    $('#merit_master_id').html('');
                    $.each(response.data,function(k,v){
                        master += "<option value='"+v.id+"'>"+v.name+"</option>";
                    });
                    $('#merit_master_id').append(master);
                }
                else{
                    $('#merit_master_id').html('');
                    toastr.error('No merit list found', 'Oops!')
                }                
            },
            error:function(response){
                console.log(response);
            }

        })
    }
$('#course_id').change(function(){
// alert('ok');
    admissionCategoryList($(this).val());
})


var admissionCategoryListNew = ($id) =>{
    var master = "<option value=''>Select</option>";
    $.ajax({
        url:'{{route(get_route_guard().".merit.master-new")}}',
        type:'post',
        data:{
            'merit_master':$id,
            '_token':"{{csrf_token()}}"
        },
        success:function(response){
            console.log(response);
            var quota = '<div class="col-md-12 table-responsive"><table class="table">';
            quota += '<tbody><tr>';
            $.each(response.admission_categories,function(k,v){
                    var cl = '';
                if(v.course_seats && v.course_seats.is_selection_active == 1){
                    cl = 'activeBg';
                }
                if(v.id==1){
                    quota += '<td class="'+cl+'">Unreserved</td>';
                }else{
                    quota += '<td class="'+cl+'">'+v.name+'</td>';
                }
                if(v.course_seats){
                    quota += '<td><span class="badge" style="background-color: #212121 !important;">'+v.course_seats.total_seats+'-'+v.course_seats.total_seats_applied+'</span></td>';
                }else
                    quota += '<td><span class="badge" style="background-color: #212121 !important;">0</span></td>';
            });
            // console.log(quota);
            $('#admission_categories').html(quota);

                

        },
        error:function(response){
            console.log(response);
        }

    })
}

$(document).ready(function(){
    var merit_master=$('#merit_master_id').val();
    $.ajax({
        url:'{{route(get_route_guard().".merit.master-new")}}',
        type:'post',
        data:{
            'merit_master':merit_master,
            '_token':"{{csrf_token()}}"
        },
        success:function(response){
            console.log(response);
            var quota = '<div class="col-md-12 table-responsive"><table class="table">';
            quota += '<tbody><tr>';
            
            
            $.each(response.admission_categories,function(k,v){
                    var cl = '';
                if(v.course_seats && v.course_seats.is_selection_active == 1){
                    cl = 'activeBg';
                }
                if(v.id==1){
                    quota += '<td class="'+cl+'">Unreserved</td>';
                }else{
                    quota += '<td class="'+cl+'">'+v.name+'</td>';
                }
                if(v.course_seats){
                    quota += '<td><span class="badge" style="background-color: #212121 !important;">'+v.course_seats.total_seats+'-'+v.course_seats.total_seats_applied+'</span></td>';
                }else
                    quota += '<td><span class="badge" style="background-color: #212121 !important;">0</span></td>';
            });
            $('#admission_categories').html(quota);

                

        },
        error:function(response){
            console.log(response);
        }

    })
    
});

// $(document).ready(function(){
//     admissionCategoryList({{Request::get('course_id')}});
// });

$('#approve').click(function(){
    if ($(".merit_list:checked").length > 0)
        $('#myModal').modal('show');
    else
     toastr.error('Please check at least one application', 'Oops!')
})
$('#approvePayment').click(function(){
    if ($(".merit_list:checked").length > 0)
        $('#approvePaymentModal').modal('show');
    else
     toastr.error('Please check at least one application', 'Oops!')
})
checkSubmit = (msg) => {
    if ($(".merit_list:checked").length == 0){
        toastr.error('Please check at least one application', 'Oops!');
        return false;
    }        
    else
    return confirm(msg);
}

applicationSelected = function(){
        if(!$(".check:checked").length){
            toastr.error('Please select at-least one application to send sms.', 'Oops!');
            return false;
        }
        return true;
    }
    calculateSelectedApplication = function () {
        var counter = $(".check:checked").length;
        $("#counter").html(counter);
    }

    // $(".check").change(function () {
    //     if ($(".check").length == $(".check:checked").length) {
    //         $("#checkAll").prop("checked", true);
    //     } else {
    //         $("#checkAll").prop("checked", false);
    //     }
    //     calculateSelectedApplication();
    // });

    $('#valid_from').Zebra_DatePicker({
        direction: true,
        pair: $('#valid_to'),
        format: 'Y-m-d H:i'
    });

    $('#approve_valid_to').Zebra_DatePicker({
        direction: true,
        format: 'Y-m-d H:i'
    });
    $('#approve_valid_from').Zebra_DatePicker({
        direction: true,
        pair: $('#approve_valid_to'),
        format: 'Y-m-d H:i'
    });

    $('#valid_to').Zebra_DatePicker({
        direction: true,
        format: 'Y-m-d H:i'
    });

    // $("#checkAll").click(function () {
    //     $('input:checkbox').not(this).prop('checked', this.checked);
    //     calculateSelectedApplication();

    // });
    showChangeCourseForm = function(obj){
        console.log(obj);
        var $this = $(obj);
        var $modal = $("#courseChangeModal");
        $modal.find("form").attr("action", $this.data("url"));
        $modal.modal("show");
    }
    showTransferSeat  = function(obj){
        console.log(obj);
        var $this = $(obj);
        var merit_list = $this.data("merit");
        console.log($this.data("merit"));
        var $modal = $("#courseTransferModal");
        $modal.find("#application_no").html(merit_list.application_no);
        $modal.find("#student_no").html(merit_list.student_id);
        $modal.find("#admitted_category").html(merit_list.admission_category.name);
        $modal.find("form").attr("action", $this.data("url"));
        $modal.modal("show");
    }
    instantApprove = function(e, obj){
        // alert("okk");
        var $this = $(obj);
        e.preventDefault();
        // if(confirm("Are you sure ? Wanted to approve the candidate.")){
        //     toastr.success("Successfully approved.")
        //     $.post($this.data("url")).
        //     done(function(){
        //         $this.hide();
        //     })
        // }
        swal({
            title: "Are you sure?",
            text: "Once approved, you will not be able to revert the changes!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(willDelete){
                $.post($this.data("url"), {
                    "_token": '{{ csrf_token() }}'
                }, "json")
                .done((response) => {
                    console.log(response);
                    if(response.status == true){
                        swal(response.message ?? "Success", {
                            icon: "success",
                        });
                        $this.hide(function(){
                            $(this).remove();   
                        });
                        $this.parent("span").html(response.button_text)
                    }else{
                        swal(response.message ?? "Failed", {
                            icon: "error",
                        });
                    }
                })
                .fail((error) => {
                    swal("No action is taken.");
                    console.log("error");
                });
            }else{
                swal("No action is taken.");
            }
        })
        .catch((error) => {
            swal("Whoops!! something went wrong.");
        });
    }
    // $('input:checkbox').click(function(){
    // var $inputs = $('input:checkbox')
    //     if($(this).is(':checked')){
    //        $inputs.not(this).prop('disabled',true); // <-- disable all but checked one
    //     }else{
    //        $inputs.prop('disabled',false); // <--
    //     }
    // })

    $('#merit_master_id').change(function(){
        admissionCategoryListNew($(this).val());
        var course_id = $('#course_id').val();
        var merit_master_id = $(this).val();
        //  alert(course_id);
         $.ajax({
            url:'{{route(get_route_guard().".merit.load-category")}}',
            type:'post',
            data:{
                'course_id':course_id,
                'merit_master_id':merit_master_id,
                '_token':"{{csrf_token()}}"
            },
            success:function(response){
                // var html=``;
                $("#admission_cat").empty();
                console.log(response);    
                $("#admission_cat").append(`<option value=""> --select-- </option>`);
                $.each(response.data,function(k,v){
                    // console.log(v.admission_category.name);
                    if(v.admission_category.id==1){
                        var html=`<option value="`+v.admission_category.id+`">Unreserved</option>`;
                    }else{
                        var html=`<option value="`+v.admission_category.id+`">`+v.admission_category.name+`</option>`;
                    }
                    $("#admission_cat").append(html);
                });    

            },
            error:function(response){
                console.log(response);
            }

        })
    })
    $(".checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
@endsection 
