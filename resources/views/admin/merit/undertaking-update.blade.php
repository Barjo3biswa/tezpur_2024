@extends ('admin.layout.auth')
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
    }

    label.date_time {
        font-weight: normal;
        font-weight: bold;
        line-height: 3.3rem;
    }
</style>

@endsection 
@section ("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include ('admin/merit/filter')
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
                <div class="panel-heading"><strong> Merit List :: </strong> Total <span
                        class="badge">{{ $merit_lists->count() }}</span> Records Found
                       
                    </div>
                <div class="panel-body">
                    <div id="admission_categories"></div>
                    <form name="merit" id="merit" action="{{ route('merit.undertaking_update') }}"
                        method="POST">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Prog. Name</th>
                                    <th>Name</th>
                                    <th>Admission Category</th>
                                    <th>Original Category</th>
                                    <th>Gender</th>
                                    <th>Rank</th>
                                    
                                    <th>Merit/Waiting</th>
                                    <th>Undertaking</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{ csrf_field() }}

                                @if ($merit_lists->count())
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" id="approve" >Update Undertaking</button>
                                        
                                    </div>
                                @endif 
                                @forelse ($merit_lists as $key=>$merit_list)

                                    <tr>
                                        <td>
                                            <input type="checkbox" name="merit_list_id[]"
                                                value="{{ $merit_list->id }}" class="merit_list check">
                                        </td>
                                        <td>{{ $merit_list->student_id }}</td>
                                        <td>{{ $merit_list->application_no }}</td>
                                        <td>{{ $merit_list->course->name }}</td>

                                        <td>{{ $merit_list->application->first_name ?? "NA" }}
                                            {{ $merit_list->application->middle_name ?? "NA" }}
                                            {{ $merit_list->application->last_name ?? "NA" }}
                                        </td>
                                        <td>{{ $merit_list->admissionCategory->name }}</td>
                                        <td>{{ $merit_list->application->caste->name }}</td>
                                        
                                        <td>{{ $merit_list->gender }}</td>
                                        <td>{{ $merit_list->tuee_rank }}</td>

                                        <td>{!! $merit_list->selected_in_merit_list ? '<span class="label label-success">Merit</span>' : '<span class="label label-warning">Waiting</span>' !!}</td>
                                        <td>
                                            @if($merit_list->allow_uploading_undertaking == 0)
                                                <span class="label label-warning">Disabled</span>
                                            @elseif($merit_list->allow_uploading_undertaking == 1)
                                                <span class="label label-success">Enabled</span>
                                            @elseif($merit_list->allow_uploading_undertaking == 3)
                                                <span class="label label-danger">Expired</span>
                                            @elseif($merit_list->undertakings->isEmpty())
                                                <span class="label label-danger">Required</span>
                                            @endif    
                                        <td>
                                        @if($merit_list->status == 1)
                                            <span class="label label-warning">
                                                Approved </span>
                                        @elseif ($merit_list->status == 2)
                                            <span class="label label-success">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                Provisional Booking Done
                                            </span>
                                        @elseif ($merit_list->status == 3)
                                            <span class="label label-danger">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                Seat Transferred
                                            </span>
                                        @elseif ($merit_list->status == 4)
                                            <span class="label label-danger">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                Cancelled
                                            </span>
                                        @else 
                                            <span class="label label-default"> Pending </span>
                                        @endif
                                        
                                        @if($merit_list->admission_receipt_count)
                                        <a target="_blank" href="{{route(get_route_guard().".merit.admission-receipt", Crypt::encrypt($merit_list->id))}}">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        @endif
                                {{-- <span class="label label-default">
                                    {{ $merit_list->stringStatus() }}
                                </span> --}}

                                </td>
                                </tr>
                            @empty 
                                <p>No users</p>
                                @endforelse 

                                        {{-- <span class="label label-warning"> 
                                        Approved </span>
                                        @elseif($merit_list->status == 2)
                                        <span class="label label-success">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        Confirmed
                                        </span>
                                        @elseif($merit_list->status == 3)
                                        <span class="label label-danger">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        Transferred</span>
                                        @else
                                        <span class="label label-default"> Pending <span>
                                        @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <p>No users</p>
                                @endforelse --}}
                                

                                <!-- Modal -->
                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3 col-md-offset-2">
                                                               <input type="radio" name="undertaking" value="1" required>  Enable
                                                            </div>
                                                            <div class="col-md-3">
                                                               <input type="radio" name="undertaking" value="0" required>  Disable
                                                            </div>
                                                            <div class="col-md-3">
                                                               <input type="radio" name="undertaking" value="3" required>  Expire
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3 text-center col-md-offset-4">
                                                                <input type="submit" name="submit" id="submit"
                                                                    class="btn btn-success" value="Update">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </tbody>
                        </table>
                        {{ $merit_lists->links() }}
                      

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection 
@section ('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
<script>
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
    resetPassword = function (string) {
        if (!confirm("Change Password ?")) {
            return false;
        }
        var ajax_post = $.post('{{ route("admin.applicants.changepass") }}', {
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
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#opening_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#closing_date').datetimepicker({
            useCurrent: false, //Important! See issue #1075
            format: 'YYYY-MM-DD'
        });
        $("#opening_date").on("dp.change", function (e) {
            $('#closing_date').data("DateTimePicker").minDate(e.date);
        });
        $("#closing_date").on("dp.change", function (e) {
            $('#opening_date').data("DateTimePicker").maxDate(e.date);
        });
    });   

    var admissionCategoryList = ($id) =>{
        var master = "<option value=''>Select</option>";
        $.ajax({
            url:'{{route("admin.merit.master")}}',
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
                var quota = '<div class="col-md-7"><table class="table">';
                quota += '<tbody><tr>';
                $.each(response.admission_categories,function(k,v){
                    quota += '<td>'+v.name+'</td>';
                    quota += '<td><span class="badge" style="background-color: #212121 !important;">'+v.course_seats.total_seats+'</span></td>';
                });
                $('#admission_categories').html(quota);

                    

            },
            error:function(response){
                console.log(response);
            }

        })
    }
$('#course_id').change(function(){
    admissionCategoryList($(this).val());
})

$(document).ready(function(){
    admissionCategoryList({{Request::get('course_id')}});
});
$('#approve').click(function(){
    if ($(".merit_list:checked").length > 0)
        $('#myModal').modal('show');
    else
     toastr.error('Please check at least one application', 'Oops!')
})

checkSubmit = () => {
    if ($(".merit_list:checked").length == 0){
        toastr.error('Please check at least one application', 'Oops!');
        return false;
    }        
    else
    return confirm("Are you sure to update");
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

    $(".check").change(function () {
        if ($(".check").length == $(".check:checked").length) {
            $("#checkAll").prop("checked", true);
        } else {
            $("#checkAll").prop("checked", false);
        }
        calculateSelectedApplication();
    });

    $('#valid_from').Zebra_DatePicker({
        direction: true,
        pair: $('#valid_to'),
        format: 'Y-m-d H:i'
    });

    $('#valid_to').Zebra_DatePicker({
        direction: 1,
        format: 'Y-m-d H:i'
    });

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
        calculateSelectedApplication();

    });
</script>
@endsection 