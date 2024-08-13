
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
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        <div class="filter dont-print">
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
                                    <label for="Programme" class="label-control">Programme Group:</label>
                                    <select name="program_group" id="program_group" class="form-control js-example-basic-single" style="width:100% !important"  autocomplete="off">
                                        <option value="">All</option>
                                        @foreach($programs as $key=>$prog)
                                        <option value="{{$prog->id}}" @if(request()->get("program_group") == $prog->id) selected @endif >{{$prog->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- <div class="col-sm-4">
                                    <label for="merit_master_id" class="label-control">Select List:</label>
                                    <select name="merit_master_id" id="merit_master_id" class="form-control" style="width:100% !important"  autocomplete="off">
                                        
                                    </select>
                                </div> --}}
                                <div class="col-sm-4">
                                    <label for="application_no" class="label-control">Application No:</label>
                                    <input type="text" name="application_no" id="application_no" class="form-control input-sm"
                                        value="{{request()->get("application_no")}}" autocomplete="off">
                                </div>
                            </div>
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
                                {{-- <div class="col-sm-4">
                                    <label for="status" class="label-control">Merit/Waiting</label>
                                    <select name="merit" id="merit" class="form-control" style="width:100% !important"  autocomplete="off">
                                        <option value="">All</option>
                                        <option value="merit" {{request()->get("merit") == "merit" ? "selected" : ''}}>Merit</option>
                                        <option value="waiting" {{request()->get("merit") == "waiting" ? "selected" : ''}}>Waiting</option>
                                    </select>
                                </div>                    --}}
                                <div class="col-sm-4">
                                    <label for="status" class="label-control">Status</label>
                                    <select name="status" id="status" class="form-control" style="width:100% !important"  autocomplete="off">
                                        <option value="">All</option>
                                        <option value="2" {{request()->get("status") == 2  ? "selected" : ''}}>Admited</option>
                                        {{-- <option value="14"{{request()->get("status") == 14 ? "selected" : ''}}>Slided</option> --}}
                                        <option value="3"{{request()->get("status") == 3 ? "selected" : ''}}>Transfered</option>
                                        <option value="6" {{request()->get("status") == 6  ? "selected" : ''}}>Withdrawal</option>         
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="status" class="label-control">Gender</label>
                                    <select name="gender" id="gender" class="form-control" style="width:100% !important"  autocomplete="off">
                                        <option value="">All</option>
                                        <option value="Male" {{request()->get("gender") == 2  ? "selected" : ''}}>Male</option>
                                        <option value="Female"{{request()->get("gender") == 14 ? "selected" : ''}}>Female</option>         
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="status" class="label-control">Admission Date</label>
                                    <input type="date" class="form-control" name="admission_date" value="{{request()->get("admission_date")}}">
                                </div>

                                <div class="col-sm-4">
                                    <label for="status" class="label-control">B.Tech Filter</label>
                                    <select name="btech_fil" id="btech_fil" class="form-control">
                                        <option value="all">All</option>
                                        <option value="jossa">Jossa</option>
                                        <option value="ne">NE</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
                                    <a href="{{route(get_route_guard().'.merit.index')}}" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</a>
                                </div>
                            </div>
                        </div>
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
                        @if (auth("department_user")->check())
                        <span class="pull-right">
                            <a href="#">
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#admission_category_modal">Set Admission Category</button>
                            </a>
                        </span>
                        @endif

                        @if (auth("admin")->check())
                        <span class="pull-right">
                            <a href="{{request()->fullUrlWithQuery(['export-data' => 1])}}" class="btn btn-sm btn-warning">
                                Export To Excel(Roll No)
                            </a>
                        </span>

                        <span class="pull-right">
                            <a href="{{request()->fullUrlWithQuery(['export-data' => 2])}}" class="btn btn-sm btn-warning">
                                Export To Excel(Application Details)
                            </a>
                        </span>
                        @endif
                    </div>
                <div class="panel-body">
                    {{-- <div id="admission_categories"></div> --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reg. No</th>
                                    <th>App. No</th>
                                    <th>Prog. Name</th>
                                    <th>Name</th>
                                    <th>Admt. Cat.</th>
                                    <th>Social Cat.</th>
                                    <th>Gender</th>
                                    <th>Rank</th>
                                    <th>Hostel</th>
                                    <th>Roll No</th>
                                    <th>Preference</th>
                                    <th>Status</th>     
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>  
                                @forelse ($merit_lists as $key=>$merit_list)
                                    @php
                                        $tr_class_name = "";
                                        if($merit_list->status == 2){
                                            $tr_class_name = "bg-success";
                                        }elseif($merit_list->status == 9){
                                            // seat declined
                                            $tr_class_name = "bg-danger";
                                        }elseif($merit_list->status == 4){
                                            // seat cancelled
                                            $tr_class_name = "bg-danger";
                                        }elseif($merit_list->status == 6){
                                            // seat withdrawal
                                            $tr_class_name = "bg-danger";
                                        }elseif($merit_list->status == 5){
                                            // temporary on hold
                                            $tr_class_name = "bg-primary";
                                        }elseif($merit_list->status == 8){
                                            // seat transferred
                                            $tr_class_name = "bg-warning";
                                        }elseif($merit_list->isValidTillExpired() && $merit_list->isValidTillExpired() &&  in_array($merit_list->status, [1,8])){
                                            $tr_class_name = "bg-info";
                                        }
                                    @endphp

                                   
                                    <tr @if(Session::has('reg_id') && !empty(Session::get('reg_id')))
                                    @if(in_array($merit_list->student_id,Session::get('reg_id')))
                                    class="error-seat"
                                    @endif
                                    @endif
                                    >

                                    <tr class="{{ $tr_class_name}}">
                                        <td>
                                            {{++$key}}
                                        </td>
                                        <td>{{ $merit_list->student_id }}</td>
                                        <td>{{ $merit_list->application_no }}</td>
                                        <td>
                                            {{ $merit_list->course->name }}
                                            {{-- @if ($merit_list->status==3)
                                                {{$merit_list->student->admitedCourse->course->name}}
                                            @endif --}}
                                            @if(request("change_course") && in_array($merit_list->course_id, array_merge(btechCourseIds(), [83])))
                                                <button type="button" class="btn btn-danger btn-xs" onclick="showChangeCourseForm(this)" data-url="{{route(get_route_guard().".merit.change-programm", $merit_list->id)}}">Change</button>
                                            @endif
                                            @if(request("show_seat_transfer") && $merit_list->status == 2)
                                        <button type="button" class="btn btn-warning btn-xs" onclick="showTransferSeat(this)" data-url="{{route(get_route_guard().".application.transfer-seat", $merit_list->id)}}" data-merit="{{$merit_list->toJson()}}">Transfer Seat</button>
                                            @endif
                                        </td>
                                        @php
                                            $is_admited=app\Models\MeritList::with('course')->where('student_id',$merit_list->student_id)->where('status',2)->get();
                                        @endphp
                                        <td>{{ $merit_list->application->first_name ?? "NA" }}
                                            {{ $merit_list->application->middle_name ?? "NA" }}
                                            {{ $merit_list->application->last_name ?? "NA" }}
                                            @forelse ($is_admited as $key=>$name)
                                                <br/><span style="color:rgb(155, 91, 19)">{{++$key}}.&nbsp;{{$name->course->name}}<b>({{$name->freezing_floating}})</b><span><br/>
                                            @empty
                                                
                                            @endforelse
                                        </td>
                                        <td>
                                            @if ($merit_list->may_slide==3 )
                                            @php
                                                $new=app\Models\MeritList::where(['student_id'=>$merit_list->student_id,'course_id'=>$merit_list->course_id/* ,'status'=>14 */])->whereIn('status',[14,15])->first();
                                            @endphp
                                                {{-- General --}}{{$new->admissionCategory->name??"NA"}}
                                            @else
                                                {{ $merit_list->admissionCategory->name }} 
                                            @endif
                                            
                                            @if($merit_list->is_pwd==1)
                                                <span class="label label-danger">PWD</span>   
                                            @elseif($merit_list->application->is_pwd==1)
                                               <span class="label label-success">PWD</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-primary">{{ $castes[$merit_list->application->caste_id] ?? "NA" }}</span>
                                        </td>
                                        <td>{{ $merit_list->gender }}</td>
                                        <td>{{ $merit_list->student_rank }} {{-- [{{$merit_list->cmr}}] --}}</td>
                                        <td>{{ $merit_list->hostel_required ? "Required" : "Not Required" }}</td>
                                        {{-- <td>{{ str_replace("_", " ", $merit_list->programm_type) }}</td> --}}
                                        <td>{{$merit_list/* ->admissionReceipt */->roll_number??""}}</td>
                                        <td>{{$merit_list->preference}}</td>
                                        <td>
                                        @if($merit_list->status == 1)
                                            <span class="label label-warning">
                                                Approved </span><br>
                                        @elseif ($merit_list->status == 2)
                                            <span class="label label-success">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                Provisionally admitted
                                            </span>
                                            @if ($merit_list->may_slide==3)
                                            <span class="label label-danger" aria-hidden="true">
                                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                slided 
                                            </span>    
                                            @endif
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
                                            </span>
                                            <br>
                                             <span class="label label-default"> Pending </span>
                                        @elseif ($merit_list->status == 8)
                                            <span class="label label-primary">
                                                <i class="fa fa-bell" aria-hidden="true"></i>
                                                Reported for counselling
                                            </span>
                                            @if($merit_list->is_payment_applicable)
                                                <span class="label label-success">
                                                    Payment allowed
                                                </span>
                                            @else
                                                @if(checkPermission(3)==true &&  auth("department_user")->check())
                                                <span class="label label-danger">
                                                    Payment not-allowed
                                                </span>
                                                @endif                          
                                            @endif
                                            <br>
                                        @elseif ($merit_list->status == 9)
                                            <span class="label label-danger">
                                                Seat declined by {{$merit_list->seat_declined_by}} </span>
                                                <a target="_blank" href="{{route(get_route_guard().".merit.decline-receipt", Crypt::encrypt($merit_list->id))}}">
                                                    <i class="glyphicon glyphicon-eye-open"></i>
                                                </a>
                                           
                                        @elseif ($merit_list->status == 12)
                                            <span class="label label-danger">
                                                category Changed </span>
                                        @elseif ($merit_list->status == 14)
                                        <span class="label label-danger">
                                            A.category slided </span>
                                            {{-- <a target="_blank" href="{{route(get_route_guard().".merit.admission-slide-receipt", Crypt::encrypt($merit_list->id))}}">
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>  --}}
                                        @elseif ($merit_list->status == 16)
                                        <span class="label label-danger">
                                           Sliding Denied </span>
                                        @else 
                                            <span class="label label-default"> Pending </span>
                                        @endif
                                        @if($merit_list->is_hold == 1 && $merit_list->attendance_flag==1)
                                        <span class="label label-danger">
                                           Temp on hold
                                        </span>
                                        @endif
                                        @if($merit_list->isValidTillExpired() &&  in_array($merit_list->status, [1,8]) && (!$merit_list->isReported() || $merit_list->isAvailableForPayment()))
                                        <span class="label label-danger"> Time Expired </span>
                                        @endif
                                        {{-- {{}} --}}
                                        @if($merit_list->admission_receipt_count)
                                        <a target="_blank" href="{{route(get_route_guard().".merit.admission-receipt", Crypt::encrypt($merit_list->id))}}">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        @endif

                                        @if($merit_list->hostel_required==4)
                                            <span class="label label-success">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                Hostel Alloted
                                            </span>
                                            <a href="{{ route(get_route_guard() . '.hostel-receipt', Crypt::encrypt($merit_list->id)) }}" class="btn btn-primary btn-xs" target="_blank"><i class="glyphicon glyphicon-home"></i></a>
                                        @endif

                                        @if (in_array($merit_list->hostel_required,[0,4,5,6]))
                                            <a href="{{ route(get_route_guard() . '.a-r-f', Crypt::encrypt($merit_list->id)) }}" class="btn btn-primary btn-xs">Print ARF</a>
                                        @endif


                                </td>
                                
                                </tr>
                            @empty 
                                <p>No users</p>
                                @endforelse 
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
                     var cl = '';
                    if(v.course_seats && v.course_seats.is_selection_active == 1){
                        cl = 'activeBg';
                    }
                    quota += '<td class="'+cl+'">'+v.name+'</td>';
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
        alert("okk");
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
    $('input:checkbox').click(function(){
    var $inputs = $('input:checkbox')
        if($(this).is(':checked')){
           $inputs.not(this).prop('disabled',true); // <-- disable all but checked one
        }else{
           $inputs.prop('disabled',false); // <--
        }
    })
</script>
@endsection 
