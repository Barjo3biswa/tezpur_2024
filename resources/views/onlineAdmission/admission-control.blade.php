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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="admission_categories"></div>                     
                        <table class="table">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>Course Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $key=>$cou)
                                <tr>
                                    <th>{{++$key}}</th>
                                    <th>{{$cou->name}}</th>
                                    <th>
                                        @if($cou->admission_status==1)
                                            <span class="btn btn-success btn-xs">Open</span>
                                        @else
                                            <span class="btn btn-danger btn-xs">Close</span>
                                        @endif
                                    </th>
                                    <th>
                                        <a href="{{route(get_route_guard().".merit.admission-control-save",Crypt::encrypt($cou->id))}}" class="btn btn-warning btn-sm">STOP/START</a>
                                    </th>
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
@section ('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
@endsection 
