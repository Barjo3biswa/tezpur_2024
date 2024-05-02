
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
                <div class="panel-heading">Filter1: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include ('department-user/merit/filter')
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
                        class="badge">{{ $merit_lists->total() }}</span> Records Found
                        
                    </div>
                <div class="panel-body">
                    <div id="admission_categories"></div>
                    
                        <table class="table">
                            <thead>
                                <tr>
                                   
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Prog. Name</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>S. Category</th>
                                    <th>Gender</th>
                                    <th>Rank</th>
                                    <th>Hostel</th>
                                    <th>P. Type</th>
                                    <th>Merit/Waiting</th>
                                    {{-- <th>Undertaking</th> --}}
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                

                               
                                @forelse ($merit_lists as $key=>$merit_list)

                                    <tr>
                                        
                                        <td>{{ $merit_list->student_id }}</td>
                                        <td>{{ $merit_list->application_no }}</td>
                                        <td>
                                            {{ $merit_list->course->name }}
                                           
                                           
                                        </td>

                                        <td>{{ $merit_list->application->first_name ?? "NA" }}
                                            {{ $merit_list->application->middle_name ?? "NA" }}
                                            {{ $merit_list->application->last_name ?? "NA" }}
                                        </td>
                                        <td>
                                            {{ $merit_list->admissionCategory->name }} 
                                            @if($merit_list->is_pwd)
                                            <span class="label label-danger">PWD</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-primary">{{ $castes[$merit_list->application->caste_id] ?? "NA" }}</span>
                                        </td>
                                        <td>{{ $merit_list->gender }}</td>
                                        <td>{{ $merit_list->student_rank }}</td>
                                        <td>{{ $merit_list->hostel_required ? "Required" : "Not Required" }}</td>
                                        <td>{{ str_replace("_", " ", $merit_list->programm_type) }}</td>
                                        <td>{!! $merit_list->selected_in_merit_list ? '<span class="label label-success">Merit</span>' : '<span class="label label-warning">Waiting</span>' !!}</td>
                                        {{-- <td>
                                            @if(!$merit_list->allow_uploading_undertaking)
                                                <span class="label label-success">Not Required</span>
                                            @elseif($merit_list->allow_uploading_undertaking)
                                                @if($merit_list->undertakings->isEmpty())
                                                    <span class="label label-danger">Not uploaded</span>
                                                @else
                                                    @if(auth("admin")->check())
                                                        <button class="btn btn-primary btn-xs" type="button" onClick="showUndertaking(this)" data-url="{{route("admin.application.undertaking-view", $merit_list->id)}}" data-app_no="{{$merit_list->application_no}}"><i class="glyphicon glyphicon-eye-open"></i> View Details</button>
                                                        @if($merit_list->undertakings->where("status", \App\Models\MeritListUndertaking::$accepted)->count())
                                                            <span class="label label-success">Undertaking Approved</span>
                                                        @elseif($merit_list->undertakings->where("status", \App\Models\MeritListUndertaking::$pending)->count())
                                                            <span class="label label-info">Undertaking Verification Pending</span>
                                                        @elseif($merit_list->undertakings->where("status", \App\Models\MeritListUndertaking::$rejected)->count())
                                                            <span class="label label-danger">Undertaking Rejected</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                            @if($merit_list->allow_uploading_undertaking == 3)
                                                <span class="label label-danger">Doc Uploading date Expired</span>
                                            @endif
                                        </td> --}}
                                        <td>
                                        @if($merit_list->status == 1)
                                            <span class="label label-warning">
                                                Approved </span><br>
                                                <span class="label label-primary">Admission timing : <br>
                                                    {{date("Y-m-d h:i a", strtotime($merit_list->valid_from))}} -
                                                    {{date("Y-m-d h:i a", strtotime($merit_list->valid_till))}}
                                                </span>
                                        @elseif ($merit_list->status == 2)
                                            <span class="label label-success">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                {{-- Provisional Booking Done --}}
                                                Provisionally admitted
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
                                        @elseif ($merit_list->status == 5)
                                            <span class="label label-danger">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                Temporarily on hold
                                            </span>
                                        @elseif ($merit_list->status == 6)
                                            <span class="label label-danger">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                Withdrawal
                                            </span>
                                        @elseif ($merit_list->status == 7)
                                            <span class="label label-primary">
                                                <i class="fa fa-bell" aria-hidden="true"></i>
                                                {{$merit_list->getStatusText()}}
                                            </span>
                                            <br>
                                            <span class="label label-primary">Admission timing : <br>
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_from))}} -
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_till))}}
                                            </span>
                                            {{-- <button class="btn btn-success btn-xs" onClick="instantApprove(event, this)" data-url="{{route("admin.merit.approve-system-generated", $merit_list)}}"> <i class="fa fa-check"></i> Instant Approve</button> --}}
                                            <button class="btn btn-success btn-xs"> <i class="fa fa-check"></i> Approved</button>
                                        @elseif ($merit_list->status == 8)
                                            <span class="label label-primary">
                                                <i class="fa fa-bell" aria-hidden="true"></i>
                                                Reported for counselling
                                            </span>
                                            <br>
                                            <span class="label label-primary">Admission timing : <br>
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_from))}} -
                                                {{date("Y-m-d h:i a", strtotime($merit_list->valid_till))}}
                                            </span>
                                        @elseif ($merit_list->status == 9)
                                            <span class="label label-danger">
                                                Seat declined by candidate
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
                               

                            </tbody>
                        </table>
                        {{ $merit_lists->appends(request()->all()) }}
                        

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
                var quota = '<div class="col-md-12 table-responsive"><table class="table">';
                quota += '<tbody><tr>';
                $.each(response.admission_categories,function(k,v){
                    quota += '<td>'+v.name+'</td>';
                    if(v.course_seats){
                        quota += '<td><span class="badge" style="background-color: #212121 !important;">'+v.course_seats.total_seats+'</span></td>';
                    }else
                        quota += '<td><span class="badge" style="background-color: #212121 !important;">0</span></td>';
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
    return confirm("Are you sure to decline");
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
</script>
@endsection 