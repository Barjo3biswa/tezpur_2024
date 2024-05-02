@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Fee Head</strong>
                    <span class="pull-right"><a href="{{route('admin.fee-head.index')}}"><button
                                class="btn btn-sm btn-primary"> Fee Head List</button></a></span>
                </div>
                <div class="panel-body">
                    <form name="application" id="application" method="post" action="{{route('admin.fee-head.store')}}">
                        {{ csrf_field() }}
                        <div class="row">
                          <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                              <label class="form-label">Name</label>
                              <input type="text" class="form-control input-sm" name="name" placeholder="Name" autocomplete="off" required>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                              <label class="form-label">Applicable On</label>
                              <select class="form-control input-sm" name="applicable_on" required>
                                <option value="">Select Applicable On</option>
                                <option value="1">Only at the admission time</option>
                                <option value="2">On every semester/year</option>
                              </select>
                            </div>
                          </div>
                        </div>

                      <div class="card-footer text-center">
                        <div class="d-flex">
                          <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                      </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection