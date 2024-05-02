<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Session</th>
                <th>Registration No</th>
                <th>Mobile No</th>
                <th>Email</th>
                <th>Change Password</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applicants as $key => $applicant)
            <tr>
                <td>{{ $key+ 1 + ($applicants->perPage() * ($applicants->currentPage() - 1)) }}</td>
                <td>{{$applicant->name}}</td>
                <td>{{$applicant->session->name}}</td>
                <td>{{$applicant->id}} ({{$applicant->otp}})</td>
                <td>{{$applicant->isd_code}}-{{$applicant->mobile_no}}</td>
                <td>{{$applicant->email}}</td>
            <td> <button type="button" class="btn btn-sm btn-danger" onclick="resetPassword('{{Crypt::encrypt($applicant->id)}}')"><i class="fa fa-key"></i> Reset Password</button></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-danger text-center">No Records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$applicants->appends(request()->all())->links()}}
</div>