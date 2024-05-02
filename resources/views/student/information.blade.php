@extends('student.layouts.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Download formats</div>

                <div class="panel-body">
                    @if(session()->has("status"))
                        <div class="alert alert-warning">
                            <strong>Notice:</strong> {{session()->get("status")}}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>

                                    @if (Auth::user()->is_mba==0)
                                    Prospectus for Autumn Season 2023-24 <span class="pull-right label label-danger">New</span>
                                    @endif

                                    @if (Auth::user()->is_mba==1)
                                    Prospectus for MBA 2022-23 <span class="pull-right label label-danger">New</span>
                                    @endif
                                    
                                </th>
                                <th>
                                    @if (Auth::user()->is_mba==0)
                                    <a target="_blank" href="{{asset("notifications/2022/Prospectus_2022.pdf")}}"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Download</button></a>
                                    
                                    @endif

                                    @if (Auth::user()->is_mba==1)
                                    <a target="_blank" href="{{asset("notifications/2022/Prospectus_2023(mba).pdf")}}"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Download</button></a>
                                    
                                    @endif
                                    
                                </th>
                            </tr>
                            @include('student.common_download_format')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection