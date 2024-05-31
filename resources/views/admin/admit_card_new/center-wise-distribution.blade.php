@extends('admin.layout.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
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
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load DataTables and buttons extension -->
<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection
