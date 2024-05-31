@extends('department-user.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                {{-- <div class="panel-heading">Welcome to VKNRL Admission Portal </div> --}}
@if(auth("department_user")->id()!=178)
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total Applicants</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        {{-- <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>"PG"])}}">{{getTotalApplicationCount2023($session->id ?? "")}}</a> --}}
                                        <a href="{{-- {{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>"PG"])}} --}}#">{{-- {{getTotalApplicationCount($session->id ?? "")}} --}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
@endif
                    @php
                        $user_id = auth("department_user")->id();
                        // app\DepartmentAssignedUser.php
                        $department_id = DB::table("department_assigned_users")->where('department_user_id',$user_id)->pluck("department_id");
                        if(in_array(auth("department_user")->id(),[178,180,181,182])){
                            $courses=["B.Tech"];
                        }if(in_array(auth("department_user")->id(),[184])){
                            $courses=["Visvesvaraya"];
                        }elseif(in_array($department_id[0], [8,9,10,12,13,25])){
                            $courses=["UG","PG","Ph.D.","B.Tech","B.Tech Lateral"];
                        }elseif(in_array($department_id[0], [26])){
                            $courses=["BDES","MDES","Ph.D."];
                        }elseif(in_array($department_id[0], [7])){
                            $courses=["UG","MBBT","Ph.D."];
                        }
                        else{
                            $courses=["UG","PG","Ph.D."];
                        }
                        
                    @endphp
                    @foreach($courses as $cour)
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="widget">
                                    <div class="widget-heading clearfix">
                                        <div class="pull-left">Total {{$cour}} Applications</div>
                                        <div class="pull-right"></div>
                                    </div>
                                    <div class="widget-body clearfix">
                                        <div class="pull-left">
                                            <i class="fa fa-list"></i>
                                        </div>
                                        <div class="pull-right number">
                                            @if(in_array($cour,['PG']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CUET:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"CUET"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","CUET")}}</a>
                                            @elseif(in_array($cour,['Visvesvaraya']))
                                                <span style="font-size: xx-small;">VISVES:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"Visvesvaraya"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","Visvesvaraya")}}</a>
                                            @elseif(in_array($cour,['MDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"CEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","CEED")}}</a>
                                            @elseif(in_array($cour,['BDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","TUEE")}}</a>
                                                <span style="font-size: xx-small;">UCEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"UCEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","UCEED")}}</a>
                                            @elseif(in_array($cour,['MBBT']))
                                                <span style="font-size: xx-small;">GATE-B:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour, "EXAM_THROUGH"=>"MBBT"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","MBBT")}}</a>
                                            @else
                                                <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>$cour])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"all","ALL")}}</a>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="widget">
                                    <div class="widget-heading clearfix">
                                        <div class="pull-left">Total {{$cour}} Accepted</div>
                                        <div class="pull-right"></div>
                                    </div>
                                    <div class="widget-body clearfix">
                                        <div class="pull-left">
                                            <i class="fa fa-list"></i>
                                        </div>
                                        <div class="pull-right number">
                                            @if(in_array($cour,['PG']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CUET:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>$cour, "EXAM_THROUGH"=>"CUET"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","CUET")}}</a>
                                            @elseif(in_array($cour,['Visvesvaraya']))
                                                <span style="font-size: xx-small;">VISVES:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted", "CUET"=>$cour, "EXAM_THROUGH"=>"Visvesvaraya"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","Visvesvaraya")}}</a>
                                            @elseif(in_array($cour,['MDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>$cour, "EXAM_THROUGH"=>"CEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","CEED")}}</a>
                                            @elseif(in_array($cour,['BDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","TUEE")}}</a>
                                                <span style="font-size: xx-small;">UCEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>$cour, "EXAM_THROUGH"=>"UCEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","UCEED")}}</a>
                                            @elseif(in_array($cour,['MBBT']))
                                                <span style="font-size: xx-small;">GATE-B:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted", "CUET"=>$cour, "EXAM_THROUGH"=>"MBBT"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","MBBT")}}</a>
                                            @else
                                                <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>$cour])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"accepted","ALL")}}</a>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="widget">
                                    <div class="widget-heading clearfix">
                                        <div class="pull-left">Total {{$cour}} Hold</div>
                                        <div class="pull-right"></div>
                                    </div>
                                    <div class="widget-body clearfix">
                                        <div class="pull-left">
                                            <i class="fa fa-list"></i>
                                        </div>
                                        <div class="pull-right number">
                                            {{-- <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold")}}</a> --}}
                                            @if(in_array($cour,['PG','MDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CUET:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour, "EXAM_THROUGH"=>"CUET"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","CUET")}}</a>
                                            @elseif(in_array($cour,['Visvesvaraya']))
                                                <span style="font-size: xx-small;">VISVES:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold", "CUET"=>$cour, "EXAM_THROUGH"=>"Visvesvaraya"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","Visvesvaraya")}}</a>
                                            
                                            @elseif(in_array($cour,['MDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour, "EXAM_THROUGH"=>"CEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","CEED")}}</a>
                                            @elseif(in_array($cour,['BDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","TUEE")}}</a>
                                                <span style="font-size: xx-small;">UCEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour, "EXAM_THROUGH"=>"UCEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","UCEED")}}</a>
                                            @elseif(in_array($cour,['MBBT']))
                                                <span style="font-size: xx-small;">GATE-B:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold", "CUET"=>$cour, "EXAM_THROUGH"=>"MBBT"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","MBBT")}}</a>
                                            @else
                                                <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>$cour])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"on_hold","ALL")}}</a>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="widget">
                                    <div class="widget-heading clearfix">
                                        <div class="pull-left">Total {{$cour}} Rejected</div>
                                        <div class="pull-right"></div>
                                    </div>
                                    <div class="widget-body clearfix">
                                        <div class="pull-left">
                                            <i class="fa fa-list"></i>
                                        </div>
                                        <div class="pull-right number">
                                            {{-- <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected")}}</a> --}}
                                            @if(in_array($cour,['PG','MDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CUET:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour, "EXAM_THROUGH"=>"CUET"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","CUET")}}</a>
                                            @elseif(in_array($cour,['Visvesvaraya']))
                                                <span style="font-size: xx-small;">VISVES:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected", "CUET"=>$cour, "EXAM_THROUGH"=>"Visvesvaraya"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","Visvesvaraya")}}</a>
                                            
                                            @elseif(in_array($cour,['MDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","TUEE")}}</a>
                                                <span style="font-size: xx-small;">CEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour, "EXAM_THROUGH"=>"CEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","CEED")}}</a>
                                            @elseif(in_array($cour,['BDES']))
                                                <span style="font-size: xx-small;">TUEE:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour, "EXAM_THROUGH"=>"TUEE"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","TUEE")}}</a>
                                                <span style="font-size: xx-small;">UCEED:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour, "EXAM_THROUGH"=>"UCEED"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","UCEED")}}</a>
                                            @elseif(in_array($cour,['MBBT']))
                                                <span style="font-size: xx-small;">GATE-B:</span> <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected", "CUET"=>$cour, "EXAM_THROUGH"=>"MBBT"])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","MBBT")}}</a>
                                            @else
                                                <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>$cour])}}">{{getTotalApplicationCountDepartment($session->id ?? "",$cour,"rejected","ALL")}}</a>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- <div class="panel-body"> --}}
                        {{-- <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="widget">
                                    <div class="widget-heading clearfix">
                                        <div class="pull-left">Total UG Applications</div>
                                        <div class="pull-right"></div>
                                    </div>
                                    <div class="widget-body clearfix">
                                        <div class="pull-left">
                                            <i class="fa fa-list"></i>
                                        </div>
                                        <div class="pull-right number">
                                            <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all", "CUET"=>"UG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","UG","all")}}</a>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total UG Accepted</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>"UG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","UG","accepted")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total UG Hold</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>"UG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","UG","on_hold")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total UG Rejected</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>"UG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","UG","rejected")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total PG Applications</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "all" , "CUET"=>"PG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","PG","all")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total PG Accepted</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "accepted" , "CUET"=>"PG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","PG","accepted")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total PG Hold</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "on_hold" , "CUET"=>"PG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","PG","on_hold")}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total PG Rejected</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="pull-right number">
                                        <a href="{{route(get_route_guard().".application.index", ["session" => $session->id ?? "", "status" => "rejected" , "CUET"=>"PG"])}}">{{getTotalApplicationCountDepartment($session->id ?? "","PG","rejected")}}</a>
                                    </div>
                                </div>

                            </div> 
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
