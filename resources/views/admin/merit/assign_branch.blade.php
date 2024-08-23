@extends('department-user.layout.auth')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/css/bootstrap/zebra_datepicker.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    {{-- <style>
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
    
</style> --}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Assign Branch & Admission Category </div>
                    @php
                        $castes = \App\Models\Caste::pluck("name","id")->toArray();
                        $btech_programs = \App\Course::whereIn("id", btechCourseIds())->withTrashed()->get();
                    @endphp
                    <div class="panel-body">
                        <h4>Applicants Name: {{$merit_list->application->FullName}}</h4>
                        <h4>Social Category: {{ $castes[$merit_list->application->caste_id] ?? "NA" }}@if($merit_list->is_pwd==1)
                                                                                                        <span class="btn btn-danger btn-xs">PWD</span>
                                                                                                    @endif
                        </h4>
                        <form action="{{ route('department.merit.save-btech-changes',$id) }}" method="POST">
                            {{ csrf_field() }}
                            <table class="table table-bordered">
                                {{-- <thead>
                                    <tr>
                                        <th>Branch

                                        </th>
                                        <th>Unreserve

                                        </th>
                                        <th>Category

                                        </th>
                                    </tr>
                                </thead> --}}
                                <tbody>
                                    @php
                                        function checkButton($total,$field,$temp){
                                            if($total-$field-$temp==0){
                                                return 'danger';
                                            }else{
                                                return 'success';
                                            }
                                            
                                        }
                                    @endphp
                                
                                        @foreach ($course as $cou)
                                            <tr>
                                                <th width=30%>{{$cou->name}}</th>
                                                @foreach ($cou->course_seats_new as $seat)
                                                @php
                                                    $array=[$cou->id,$seat->admission_category_id];
                                                    $encodedArray = urlencode(serialize($array));
                                                @endphp
                                                    @if ($seat->admission_category_id==1)
                                                        <th width=15%>Unreserved @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">PWD</span>@endif
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif
                                                    @if ($seat->admission_category_id==2)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">PWD</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif
                                                    @if ($seat->admission_category_id==3)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">PWD</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif
                                                    @if ($seat->admission_category_id==4)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">PWD</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif
                                                    @if ($seat->admission_category_id==6)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">PWD</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif
                                                    @if ($seat->admission_category_id==7)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">PWD</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif

                                                    @if ($seat->admission_category_id==8)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">Employee</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif

                                                    @if ($seat->admission_category_id==9)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">Supernumerary</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif

                                                    @if ($seat->admission_category_id==10)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">Rankholder</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif

                                                    @if ($seat->admission_category_id==11)
                                                        <th>{{$seat->admissionCategory->name}} @if($merit_list->is_pwd==1)<span class="btn btn-danger btn-xs">Defence</span>@endif 
                                                            : <button class="btn btn-{{checkButton($seat->total_seats,$seat->total_seats_applied,$seat->temp_seat_applied)}} btn-sm">
                                                                {{$seat->total_seats-$seat->total_seats_applied-$seat->temp_seat_applied}}</button> 
                                                            <input type="radio" id="html" name="branch_name" value="{{$encodedArray}}">
                                                        </th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        
                                    
                                </tbody>
                                
                            </table>
                            <div class="col-sm-12">  
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="online">Payment Mode</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="online" name="online_ofline" required value="online">
                                        <label for="online">Online</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="offline" name="online_ofline" required value="offline" checked>
                                        <label for="offline">Offline</label>
                                    </div>
                                </div> </br>                                   
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="checkbox" id="below_sixty" name="below_sixty" value="Bike">
                                        <label for="below_sixty"> Below 60%</label><br>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" style="float: right" class="btn btn-success">Assign Branch to the candidate</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" style="float: right" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="AssignId({{$merit_list->id}})">
                                            Decline the candidate
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    {{-- <div class="col-sm-3">                                      
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this candidate?');">Cancel</button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('department.merit.btech-cancel')}}" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cancel this Candidate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="ml_id" name="ml_id" value={{$merit_list->id}}>
                    <input type="hidden"  name="type" value="deny">
                    <label for="">Reason Of Cancellation</label>
                    <textarea name="reason" id="reason" cols="10" rows="3" class="form-control" required>Since seats in preferred branch is not available, therefore I decline to take admission.</textarea>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary" 
                onclick="return confirm('Are you sure you want to Cancel?');">Submit</button>
                </div>
            </form>
        </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
@endsection
