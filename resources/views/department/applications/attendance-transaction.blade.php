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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong> Name :: {{$merit_list->application->full_name}}</strong></div>
                <div class="panel-heading"><strong> Reg. No :: {{$merit_list->application->student_id}}</strong></div>
                <div class="panel-heading"><strong> App. No. :: {{$merit_list->application->application_no}}</strong></div>
                <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            @foreach ($merit_list->attandanceTransaction as $key=>$trans)
                                <tr>
                                    <th>{{++$key}}</th>
                                    <th>{{date('d-m-Y h:m:s', strtotime($trans->attendance_time))}}</th>
                                    <th>{{$trans->status}}</th>
                                    <th>{{$trans->comment??"NA"}}</th>
                                </tr>
                            @endforeach                          
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
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