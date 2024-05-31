@extends('admin.layout.auth')

@section('css')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <table class="table table-responsive" id="example">
                        <thead>                         
                            <tr>
                                <th>#</th>
                                <th>City Name</th>
                                <th>Center Name</th>
                                <th>Max Capacity</th>
                                <th>One</th>
                                <th>Two</th>
                                <th>Three</th>
                                <th>Four</th>
                                <th>Five</th>
                                <th>Six</th>
                                <th>Seven</th>
                                <th>Eight</th>
                                <th>Nine</th>
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach ($sub_center as $key=>$sub)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $sub->city_name }}</td>
                                    <td>{{ $sub->center_name }}</td>
                                    <td>{{ $sub->capacity }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'one')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'two')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'three')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'four')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'five')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'six')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'seven')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'eight')->count() }}</td>
                                    <td>{{ $sub->students->where('exam_group', 'nine')->count() }}</td>
                                </tr>
                            @endforeach                       
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
