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
                            @include('admin/admin_card/filter')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="align-center">
                <div class="panel panel-default">
                    <a href="{{ route('admin.new-admit-generate') }}" class="btn btn-primary">Generate</a>
                    {{-- <a href="{{ route('admin.new-admit-publish') }}" class="btn btn-primary"
                        onclick="return confirm('Are you sure you want to Publish all Admit Card?');">Publish</a> --}}
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Publish
                    </button>

                    <a href="{{ route('admin.download-photo-sigg') }}" class="btn btn-primary">Download File</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th colspan=10>
                                    Total Record Found:{{$count}}
                                </th>   
                            </tr>                            
                            <tr>
                                <th>#</th>
                                <th>Roll No</th>
                                <th>Regist. No</th>
                                <th>Center</th>
                                <th>course</th>
                                <th>Date</th>
                                <th>Shift</th>
                                <th>Status</th>
                                <th>Applicant Name</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach ($admit_cards as $key => $cards)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $cards->roll_no }}</td>
                                    <td>{{ $cards->student_id }}</td>
                                    <td>{{ $cards->exam_center->center_name }}</td>
                                    <td>{{ $cards->course->name }}</td>
                                    <td>{{ $cards->exam_date }}</td>
                                    <td>{{ $cards->exam_time }}</td>
                                    <td>
                                        @if ($cards->publish == 1)
                                            <button class="btn btn-success btn-xs">Published</button>
                                        @else
                                            <button class="btn btn-danger btn-xs">Not Published</button>
                                        @endif
                                    </td>
                                    <td>{{ $cards->application->getFullNameAttribute() }}</td>
                                    <td><a href="{{ route('admin.new-admit-view', Crypt::encrypt($cards->id)) }}"
                                            target="_blank" class="btn btn-primary btn-xs">View</td>
                                </tr>
                            @endforeach

                        </tbody> --}}
                    </table>
                    {{-- {{ $admit_cards->appends(request()->all())->links() }} --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.new-admit-publish') }}" method="post">
                    {{ csrf_field() }}
                   
                        <div class="modal-header">                       
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="exampleModalLabel">Publish Admit Card</h5>
                        </div>
                    
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="exam_center" class="label-control">Exam Center:</label>
                                    <select name="exam_center" class="form-control" required>
                                        <option value="">--select--</option>
                                        @foreach ($exam_centers as $exm)
                                            <option value="{{$exm->id}}" {{ request()->get("exam_center") == $exm->id ? "selected" : "" }}>{{$exm->center_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="program" class="label-control">Program:</label>
                                    <select name="program" id="program" class="form-control ">
                                        <option value="" selected>All</option>
                                        @foreach (programmes_array() as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ request()->get("program") == $id ? "selected" : "" }}>
                                                {{ $name }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-sm-6">
                                    <label for="student_id" class="label-control">Exam Registration No:</label>
                                    <input type="number" name="student_id" class="form-control">                          
                                </div>
                            </div>  --}}
                        </div>                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-xs">Save changes</button>
                    </div>
                </form>
            </div>      
        </div>
    </div>
@endsection

@section('js')
@endsection
