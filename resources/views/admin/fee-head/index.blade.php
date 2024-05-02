@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Fee Head</strong>
                    <span class="pull-right"><a href="{{route('admin.fee-head.create')}}"><button
                                class="btn btn-sm btn-primary"> Add New</button></a></span>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Applicable On</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse($fee_heads as $key => $fee_head)
                              <tr>
                                <td>{{ $key+ 1 + ($fee_heads->perPage() * ($fee_heads->currentPage() - 1)) }}</td>
                                <td>{{ $fee_head->name }}</td>
                                <td>{{ $fee_head->applicable_on=='1'?'Only at admission time':'On every semester/year' }}</td>
                                <td>
                                  <div class="btn-group">
                                    <a href="{{ route('admin.fee-head.edit',$fee_head->uuid) }}" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                    <a href="{{ route('admin.fee-head.delete',$fee_head->uuid) }}" class="btn btn-danger" onclick="return confirm('Are you sure to Delete?');"><i class="fa fa-trash"></i></a>
                                  </div>
                                </td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="10">No Data</td>
                              </tr>
                              @endforelse
          
                            </tbody>
                          </table>
                          {{$fee_heads->render()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection