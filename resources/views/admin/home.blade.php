@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12" >
            <!-- Filter-->
            @if(auth("admin")->id()==1)
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
                                <div class="row " >
                                        <div class="col-sm-3">
                                            <select name="session" id="session" class="form-control">
                                                <option value="" selected="selected">--SELECT--</option>
                                                @foreach ($sessions as $session)
                                                    <option value="{{$session->id}}"
                                                        {{isset($active_session) && $active_session-> id == $session->id ? "selected" : ""}}
                                                    >{{$session->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                             <label for="submit" class="label-control" style="visibility: hidden;">Search</label>
                                            <br>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>

                                        </div>

                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                        <div class="widget">
                                            <div class="widget-heading clearfix">
                                                <div class="pull-left">Overall Registered Applicants</div>
                                                <div class="pull-right"></div>
                                            </div>
                                            <div  style="font-size: 28px;"class="widget-body clearfix">
                                                <div class="pull-left">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                            <div class="pull-right number"><a href="{{route("admin.applicants.list")}}">{{totalRegisterdUser($active_session->id)}}</a></div>
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
                                            <div class="pull-right number"><a href="#">{{number_format(getTotalCollection($active_session->id), 2)}}</a></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div class="widget">
                                            <div class="widget-heading bg-danger clearfix">
                                                <div class="pull-left">Total Collection(MBA/BTECH)</div>
                                                <div class="pull-right"></div>
                                            </div>
                                            <div style="font-size: 28px;" class="widget-body clearfix">
                                                <div class="pull-left">
                                                    <i class="fa fa-rupee"></i>
                                                </div>
                                            <div class="pull-right number"><a href="#">{{number_format(getTotalCollectionMBA($active_session->id), 2)}}</a></div>
                                            </div>

                                        </div>
                                    </div>
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
            <div class="panel panel-default">
                {{-- <div class="panel-heading">Welcome to VKNRL Admission Portal </div> --}}

                <div class="container-fluid">
                    <div class="row" style="padding-top:15px">

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left"> Registered Applicants</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div  style="font-size: 28px;"class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-users"></i>
                                    </div>
                                <div class="pull-right number"><a href="{{route("admin.applicants.list", ["session" => $active_session->id ?? ""])}}">{{totalRegistersession($active_session->id ?? "")}}</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Completed Applications MBA</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix ">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_mba"=>1])}}">
                                        {{getTotalMBAApplicants($active_session->id ?? "")}}</span>
                                    </div>
                                </div>
                        
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Completed Applications Ph.D.</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix ">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_phd"=>1])}}">
                                        <span style="font-size: xx-small;">TUEE:</span>{{getTotalPHDApplicants($active_session->id ?? "")}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Completed Ph.D. Professionals</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix ">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_phd_prof"=>1])}}">
                                        <span style="font-size: xx-small;">TUEE:</span>{{getTotalPHDApplicantsProff($active_session->id ?? "")}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Completed Applications PG </div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix ">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index",  ["session" => $active_session->id ?? "", "CUET"=>"PG", "EXAM_THROUGH"=>"TUEE"])}}">
                                        <span style="font-size: xx-small;">TUEE:</span>{{getTotalPGCompletedApplicationCount($active_session->id ?? "",'TUEE')}}</a>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index",  ["session" => $active_session->id ?? "", "CUET"=>"PG", "EXAM_THROUGH"=>"CUET"])}}">
                                        <span style="font-size: xx-small;">CUET:</span>{{getTotalPGCompletedApplicationCount($active_session->id ?? "",'CUET')}}</a>
                                    </div>
                                </div>
                        
                            </div>
                        </div>
                        
                    {{-- </div>
                    <div class="row" style="padding-top:15px"> --}}
                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left" style="font-size:10px;">Completed Applications B.Tech-Lateral</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix ">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_laterall"=>1])}}">
                                        <span style="font-size: xx-small;">TUEE:</span>{{getBtechLateral($active_session->id ?? "")}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Completed Applications B-Tech</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix ">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_btech"=>1])}}">
                                        {{getTotalBtech($active_session->id ?? "")}}</a>
                                    </div>
                                </div>
                    
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Completed BA in Chinese </div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix ">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_chinese"=>1])}}">
                                        {{getTotalchinese($active_session->id ?? "")}}</a>
                                    </div>
                                </div>
                    
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget" >
                                <div class="widget-heading clearfix" >
                                    <div class="pull-left">Completed Applications CUET-UG</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix " >
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                     <div class="pull-right number" ><a href="{{route("admin.application.index",  ["session" => $active_session->id ?? "", "CUET"=>"UG"])}}">
                                        <span style="font-size: xx-small;">CUET:</span>{{getTotalUGCompletedApplicationCount($active_session->id ?? "")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    
                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget" >
                                <div class="widget-heading clearfix" >
                                    <div class="pull-left">Completed Applications M. Des </div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix " >
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number" ><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_mdes"=>1])}}">
                                        <span style="font-size: xx-small;">TUEE:</span>{{getMDeshReg($active_session->id ?? "", "TUEE")}}</a>
                                    </div>
                                    <div class="pull-right number" ><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_mdes"=>1])}}">
                                        <span style="font-size: xx-small;">CUET:</span>{{getMDeshReg($active_session->id ?? "", "CUET")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="widget" >
                                <div class="widget-heading clearfix" >
                                    <div class="pull-left">Completed Applications B. Des </div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix " >
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number" ><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_bdes"=>1])}}">
                                        <span style="font-size: xx-small;">TUEE:</span>{{getBDeshReg($active_session->id ?? "", "TUEE")}}</a>
                                    </div>
                                    <div class="pull-right number" ><a href="{{route("admin.application.index", ["session" => $active_session->id ?? "","is_bdes"=>1])}}">
                                        <span style="font-size: xx-small;">CUET:</span>{{getBDeshReg($active_session->id ?? "", "CUET")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    
                        
                    </div>
                    <div class="row" style="padding-top:15px">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="widget">
                                <div class="widget-heading bg-danger clearfix">
                                    <div class="pull-left"> All Programs</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-book"></i>
                                    </div>
                                <div class="pull-right number"><a href="{{route("admin.program")}}">{{number_format(gettotalCouser($active_session->id))}}</a></div>

                                </div>

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="widget">
                                <div class="widget-heading bg-danger clearfix">
                                    <div class="pull-left">Centers</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div style="font-size: 28px;" class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="pull-right number"><a href="{{route('admin.center')}}">{{number_format(gettotalExamCenter($active_session->id))}}</a></div>
                        
                                </div>
                        
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div  class="widget">
                                <div id="">
                                    <canvas id="graphCanvas"></canvas>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div  class="widget">
                                <div id="">
                                    <canvas id="graphpie"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                </div>
            </div>
            @endif
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
                url: "{{route('count.session')}}?sessin="+sessin,
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
                        url: "{{route('count.cast')}}?cast="+cast,
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

<script>
    $(document).ready(function () {
        
        showGraph();
    });


    function showGraph()
    {
        {


                 var name = [];
                 var marks = [];


                    name.push('General');
                    name.push('EWS');
                    name.push('OBC (CL)');
                    name.push('OBC (NCL)');
                    name.push('SC');
                    name.push('ST');
                    name.push('FN');
                    marks.push({{number_format(getgencast($active_session->id))}});
                    marks.push({{number_format(getewscast($active_session->id))}});
                    marks.push({{number_format(getobcCLcast($active_session->id))}});
                    marks.push({{number_format(getobcNCLcast($active_session->id))}});
                    marks.push({{number_format(getobsccast($active_session->id))}});
                    marks.push({{number_format(getstcast($active_session->id))}});
                    marks.push({{number_format(getFNcast($active_session->id))}});


                var chartdata = {
                    labels: name,
                    datasets: [
                        {
                            label: 'Caste',
                            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850","#DC143C","#FF0000"],
                            borderColor: '#46d5f1',
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data: marks
                        }
                    ]
                };

               

                var graphTarget = $("#graphCanvas");

                var barGraph = new Chart(graphTarget, {
                    type: 'bar',
                    data: chartdata
                });
                console.log(barGraph);
        }
    }
    </script>
    <script>
        $(document).ready(function () {
            showGraph1();
        });


        function showGraph1()
        {
            var chartdata = {
                labels: {!!json_encode(sizeof($gender_wise_application_count->pluck("gender")->toArray()) ? $gender_wise_application_count->pluck("gender")->toArray() : ["Male", "Female", "Other"])!!},
                datasets: [
                    {
                        label: 'Genders',
                        backgroundColor: ["#c45850","#8e5ea2","#3cba9f"],
                        hoverBackgroundColor: '#CCCCCC',
                        hoverBorderColor: '#666666',
                        data: {!!json_encode(sizeof($gender_wise_application_count->pluck("total")->toArray()) ? $gender_wise_application_count->pluck("total")->toArray() : [0,0,0])!!}
                    }
                ]
            };

            var graphTarget = $("#graphpie");

            var barGraph = new Chart(graphTarget, {
                type: 'pie',
                data: chartdata
            });
        }
    </script>

@endsection

