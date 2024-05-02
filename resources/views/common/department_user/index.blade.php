<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Mobile No</th>
                <th>Email</th>
                <th>Departments</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($department_users as $key => $user)
            <tr>
                <td>{{ $key+ 1 + ($department_users->perPage() * ($department_users->currentPage() - 1)) }}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->mobile}}</td>
                <td>{{$user->email}}</td>
                <td>
                    @if($user->departments)
                        @foreach ($user->departments as $department)
                            <span class="label label-info">{{$department->name}}</span>
                        @endforeach
                    @else
                        NA
                    @endif
                </td>
            <td> 
                <button type="button" class="btn btn-sm btn-danger" onclick="resetPassword('{{Crypt::encrypt($user->id)}}')"><i class="fa fa-key"></i> Reset Password</button>
                @if($user->deleted_at)
                    <a href="{{route('admin.department-users.activate', Crypt::encrypt($user->id))}}" onclick="confirm('Are you sure ?')"><button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Activate</button></a>
                @else
                    <a href="{{route("admin.department-users.delete", Crypt::encrypt($user->id))}}" onclick="confirm('Are you sure ?')"><button type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Deactivate</button></a>
                @endif
            </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-danger text-center">No Records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$department_users->appends(request()->all())->links()}}
</div>