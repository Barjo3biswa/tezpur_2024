

@extends('admin.layout.auth')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            <div class="filter dont-print">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="applicant_name_starting" class="label-control">Center Name</label>
                                        <select name="center_name" class="form-control">
                                            <option value="">--select--</option>
                                            @foreach ($exam_centers as $exam)
                                                <option value="{{ $exam->id }}"
                                                    {{ Request()->get('center_name') == $exam->id ? 'selected' : '' }}>
                                                    {{ $exam->center_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <div class="row">

                                    <div class="col-sm-3">
                                        <label for="submit" class="label-control"
                                            style="visibility: hidden;">Search</label><br>
                                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i>
                                            Filter</button>
                                        <button type="reset" class="btn btn-danger"><i class="fa fa-remove"></i>
                                            Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <table width="100%" class="table table-bordered">
                        @foreach ($exam_center as $center)
                            @foreach ($center->subExamCenter as $sub_cen)
                                    @foreach ($group as $grp)
                                    <tr>
                                        <td>{{$sub_cen->center_name}}</td>
                                        <td>{{$grp->group_name}} </td>
                                        <td>{{getGroupNCenterCount($grp->group_name, $sub_cen->id)}}</td>
                                        <td><a href="{{ route('admin.attendence-view-new',['group'=>$grp->group_name, 'cen_id'=>$sub_cen->id]) }}">View</a></td>
                                    </tr>        
                                    @endforeach
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
