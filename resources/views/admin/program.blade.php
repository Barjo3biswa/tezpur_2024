@extends('admin.layout.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Filter-->
                <div style="margin-left=0px" class="panel panel-default ">
                    <div class="panel-heading">
                        Filter:
                    </div>
                    <div class="panel-body " style="padding-bottom:0px;">
                        <form action="" method="get" style=" margin-bottom:0px;">
                            <div class="filter dont-print">
                                <div class="row ">
                                    <div class="clearfix"></div>
                                    <div class="col-sm-2">
                                        <label for="country" class="label-control">
                                            Select Session
                                        </label>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-sm-3">
                                        <select name="session" id="session" class="form-control">
                                            <option value="" selected="selected">--SELECT--</option>
                                            @foreach ($sessions as $session)
                                                <option value="{{ $session->id }}"
                                                    {{ isset($active_session) && $active_session->id == $session->id ? 'selected' : '' }}>
                                                    {{ $session->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="exam_center" id="exam_center" class="form-control">
                                            <option value="" selected="selected">--SELECT--</option>
                                            @foreach ($center as $cen)
                                                <option value="{{ $cen->id }}"
                                                    {{ Request('exam_center') == $cen->id ? 'selected' : '' }}>
                                                    {{ $cen->center_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <select name="type" id="type" class="form-control">
                                            <option value="">--SELECT--</option>
                                            <option value="TUEE" {{ Request('type') == 'TUEE' ? 'selected' : '' }}>TUEE</option>
                                            <option value="CUET" {{ Request('type') == 'CUET' ? 'selected' : '' }}>CUET</option>
                                            <option value="QNLT" {{ Request('type') == 'CUET' ? 'selected' : '' }}>QNLT</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="submit" class="label-control"
                                            style="visibility: hidden;">Search</label>
                                        <br>
                                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i>
                                            Filter</button>

                                    </div>

                                    {{-- <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="widget">
                                        <div class="widget-heading clearfix">
                                            <div class="pull-left">Overall Registered Applicants</div>
                                            <div class="pull-right"></div>
                                        </div>
                                        <div style="font-size: 28px;" class="widget-body clearfix">
                                            <div class="pull-left">
                                                <i class="fa fa-users"></i>
                                            </div>
                                            <div class="pull-right number"><a href="">{{totalRegisterdUser($active_session->id)}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="widget">
                                        <div class="widget-heading bg-danger clearfix">
                                            <div class="pull-left">Total Collection</div>
                                            <div class="pull-right"></div>
                                        </div>
                                        <div style="font-size: 28px;" class="widget-body clearfix">
                                            <div class="pull-left">
                                                <i class="fa fa-rupee"></i>
                                            </div>
                                            <div class="pull-right number"><a
                                                    href="#">{{number_format(getTotalCollection($active_session->id),
                                                    2)}}</a></div>
                                        </div>

                                    </div>
                                </div> --}}
                                </div>
                            </div>
                    </div>
                    <br>
                    <div class="row">

                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--End filter-->

        {{-- <div class="panel-heading">Welcome to VKNRL Admission Portal </div> --}}
        <div class="panel panel-default">
            <div class="panel-heading">Total Programs: <strong>{{ $course }} records found</strong></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name of Program</th>
                                {{-- <th>CUET</th>
                                <th>TUEE</th> --}}
                                <th>Total</th>
                                <!-- <th>Total seats</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 0;
                            @endphp
                            @forelse($categories as $key =>$c)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $c->course->name }}</td>
                                    {{-- <td></td>
                                    <td></td> --}}
                                    <td class="text-right">
                                        @if (Request('type'))
                                        <a href="{{ route('admin.application.index', ['session' => $active_session->id ?? '', 'program' => $c->course->id ?? '', 'exam_center_id' => Request('exam_center') ?? '', 'type' => Request('type')]) }}"
                                            target="_blank">{{ $c->count }}</a>
                                        @else
                                        <a href="{{ route('admin.application.index', ['session' => $active_session->id ?? '', 'program' => $c->course->id ?? '', 'exam_center_id' => Request('exam_center') ?? '']) }}"
                                            target="_blank">{{ $c->count }}</a>
                                        @endif
                                        {{-- @if ($c->course->program_id == "")
                                            
                                        @endif --}}
                                        
                                    </td>
                                </tr>
                                @php
                                    $count = $count+$c->count;
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="6" class="text-danger text-center">No Records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <strong>Total Applications</strong>
                                </td>
                                <td class="text-right">{{ $count }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <strong>Total Applicants</strong>
                                </td>
                                <td class="text-right">{{ $total_applicant}}</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>






    </div>
    </div>
    </div>
    </div>
@endsection
@section('js')
    <script>
        //$(document).ready(function() {
        $('#sessin').change(function() {
            var sessin = $('#sessin').val();
            console.log(sessin);
            if (sessin) {
                $.ajax({
                    method: "GET",
                    url: "{{ route('count.session') }}?sessin=" + sessin,
                    dataType: 'json',
                    success: function(res) {
                        if (res) {
                            console.log(res);
                            $('#sessionvalue').text(res);





                        }
                    }

                });
            }


        });
        // });
    </script>
    <script>
        $(document).ready(function() {
            $('#cast').change(function() {
                var cast = $('#cast').val();
                console.log(cast);
                if (cast) {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('count.cast') }}?cast=" + cast,
                        dataType: 'json',
                        success: function(res) {
                            if (res) {
                                console.log(res);
                                $('#sessionvalue').text(res);





                            }
                        }

                    });
                }


            });
        });
    </script>
@endsection
