@extends('department-user.layout.auth')
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
                        @include ('admin/merit/filter-new')
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
                        <span class="pull-right">
                        </span>
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
                                    <th>A. Category</th>
                                    <th>S. Category</th>
                                    <th>Gender</th>
                                    <th>JEE Rank</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentPage = $merit_lists->currentPage();
                                    $itemsPerPage = $merit_lists->perPage();
                                    $startingSlNo = ($currentPage - 1) * $itemsPerPage;
                                @endphp
                                @forelse ($merit_lists as $key=>$merit_list)
                                    @php
                                        $tr_class_name = "";
                                        if($merit_list->attendance_flag == 1){
                                            $tr_class_name = "bg-success";
                                        }elseif($merit_list->attendance_flag == 2){
                                            // seat declined
                                            $tr_class_name = "bg-danger";
                                        }elseif($merit_list->attendance_flag == 3){
                                            // seat declined
                                            $tr_class_name = "bg-warning";
                                        }
                                    @endphp
                                    <tr class="{{ $tr_class_name}}">
                                        <td>
                                           {{++$key+$startingSlNo}}
                                        </td>
                                        <td>
                                            @if ($merit_list->btechPrevious)
                                                @if ($merit_list->btechPrevious->attendance_flag==1)
                                                    <span style="color: rgb(89, 170, 89)"><b>{{ $merit_list->student_id }}</b></span>
                                                @else
                                                    <span>{{ $merit_list->student_id }}</span>
                                                @endif
                                            @else
                                                <span>{{ $merit_list->student_id }}</span>
                                            @endif
                                            
                                        </td>
                                        <td>{{ $merit_list->application_no }}
                                            <br/>
                                            JEE:{{$merit_list->cmr}}
                                        </td>
                                        <td>
                                            {{ $merit_list->course->name }}
                                            @if(request("change_course") && in_array($merit_list->course_id, array_merge(btechCourseIds(), [83])))
                                                <!-- Trigger the modal with a button -->
                                                <button type="button" class="btn btn-danger btn-xs" onclick="showChangeCourseForm(this)" data-url="{{route(get_route_guard().".merit.change-programm", $merit_list->id)}}">Change</button>
                                            @endif
                                            @if(request("show_seat_transfer") && $merit_list->status == 2)
                                                <!-- Trigger the modal with a button -->
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
                                                <br/><span style="color:rgb(155, 91, 19)">{{++$key}}.&nbsp;{{$name->course->name}}<b>({{$name->freezing_floating}})({{$name->admissionCategory->name}})</b><span><br/>
                                            @empty
                                                
                                            @endforelse
                                        </td>

                                        <td>
                                            @if($merit_list->status==2 || $merit_list->new_status=="branch_assigned")
                                                {{ $merit_list->admissionCategory->name }}
                                            @else
                                                ----
                                            @endif
                                            @if($merit_list->is_pwd==1)
                                                <span class="label label-danger">PWD</span>   
                                            @elseif($merit_list->application->is_pwd==1)
                                               <span class="label label-success">PWD</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-primary">{{ $castes[$merit_list->application->caste_id] ?? "NA" }}
                                            </span>
                                        </td>
                                        <td>{{ $merit_list->gender }}</td>
                                        <td>{{ $merit_list->student_rank }}</td>
                                        <td>
                                            <div class="row">
                                                {{-- <div class="col-md-3">
                                                    <a href="{{route(get_route_guard().".application.show", Crypt::encrypt($merit_list->application->id))}}">
                                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                    </a>
                                                </div> --}}
                                                <div class="col-md-3">
                                                    <form action="{{route(get_route_guard().".merit.ab-pre", Crypt::encrypt($merit_list->id))}}" method="post">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="flag" value="pre">
                                                        <div id="popup{{$merit_list->id}}">
        
                                                        </div>
                                                        <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#myModal" onclick="callFunction('popup'+{{$merit_list->id}})"><i class="fa fa-check" aria-hidden="true"></i></button>
                                                    </form>
                                                </div>
                                                {{-- <div class="col-md-3">
                                                    <form action="{{route(get_route_guard().".merit.ab-pre", Crypt::encrypt($merit_list->id))}}" method="post">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="flag" value="abs">
                                                        <div id="uppop{{$merit_list->id}}">
        
                                                        </div>
                                                        <button type="button" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#myModal" onclick="callFunction('uppop'+{{$merit_list->id}})"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </form>
                                                </div> --}}
                                                <div class="col-md-3">
                                                    <form action="{{route(get_route_guard().".merit.ab-pre-undo", Crypt::encrypt($merit_list->id))}}" method="post">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="flag" value="undo">
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to undo this item?');"
                                                        ><i class="fa fa-undo" aria-hidden="true"></i></button>
                                                    </form>
                                                </div>

                                                <div class="col-md-3">
                                                    <a class="btn btn-success btn-sm"  href="{{route(get_route_guard().".merit.attendance_trans", Crypt::encrypt($merit_list->id))}}" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                </div>

                                            </div>
                                            
                                            
                                            
                                            {{-- <button type="button" class="btn btn-primary btn-sm"></button>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Comment</button> --}}
                                        
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
    {{-- <div class="row">
        <form action="{{route(get_route_guard().".merit.ab-pre-commit", Crypt::encrypt($merit_master_id))}}" method="post">
            {{csrf_field()}}
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <input type="submit" class="btn btn-primary" value="Close Attendence" onclick="return confirm('Are you sure you want to Close? Once you Close you can`t process for more attendence.');">
            </div>
        </form>
    </div> --}}
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

$('#course_id').change(function(){
    admissionCategoryList($(this).val());
})

$(document).ready(function(){
    // admissionCategoryList({{Request::get('course_id')}});
    var id=$('#course_id').val();
    $.ajax({
            url:'{{route(get_route_guard().".merit.master")}}',
            type:'post',
            data:{
                'course_id':id,
                '_token':"{{csrf_token()}}"
            },
            success:function(response){
                // console.log(response);
                // if(response.success == true){
                //     $('#merit_master_id').html('');
                //     $.each(response.data,function(k,v){
                //         master += "<option value='"+v.id+"'>"+v.name+"</option>";
                //     });
                //     $('#merit_master_id').append(master);
                // }
                // else{
                //     $('#merit_master_id').html('');
                //     toastr.error('No merit list found', 'Oops!')
                // }
                var quota = '<div class="col-md-8 table-responsive"><table class="table">';
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
});
    function callFunction(id){
        var html=`
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Attendence Coment</h4>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <textarea name="coment" class="form-control" placeholder="Type Your Attandence Note"></textarea>
                        </div>
                    </div><br/>

                    <!--<div class="row">
                        <div class="col-md-12">
                            <label>Is missed his call for admission</label><br/>
                            <input type="checkbox" id="is_missed" name="is_missed" value="yes"
                            onclick="return confirm('Are you sure you want to take admission at the end of other applicants?');"
                            >
                        </div>
                    </div>-->

                    <br/>
                    <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>   
        </div>
        </div>
        `;

        $('#'+id).empty();
        $('#'+id).append(html);

    }

    
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
</script>
@endsection
{{-- @section('scripts')

@endsection --}}