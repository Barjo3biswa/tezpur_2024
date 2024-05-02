<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading">Admission Process For {{env("APP_NAME_2")}}
            </div>

                <div class="panel-body">
                    <div class="alert alert-danger" role="alert">
                        <strong><span class="blink">Note :</span> Applicants can proceed to payment after completion of all the stages. Incomplete application will not be eligible for payments.</strong><br/>                                         
                        <strong><span class="blink">Note :</span> Applicants are requested to upload document related to conversion formula for CGPA/SGPA separately on the appropriate fields in STEP-4.  </strong><br/>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Registration No</th>
                                    <th>Application No</th>
                                    <th>Applicant Name</th>
                                    <th>Program Name</th>
                                    <th>Category</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($merit_list as $key=>$admission)
                                    <tr>
                                        <th>{{$key}}</th>
                                        <th>{{$admission->student_id}}</th>
                                        <th>{{$admission->application->application_no}}</th>
                                        <th>{{$admission->application->getFullNameAttribute()}}</th>
                                        <th>{{$admission->course->name}}</th>
                                        <th>{{$admission->application->caste->name}}</th>
                                        <th>{{$admission->gender}}</th>
                                        <th>..working On It</th>
                                        <th>
                                           @if ($admission->new_status=="called")
                                                {{-- @if($application->merit_list->count())
                                                    <a href="{{route("student.admission.book.seat", Crypt::encrypt($admission->application->id))}}">
                                                        <button class="btn btn-danger btn-sm">Admission Seat Details</button>
                                                    </a>
                                                @endif --}}
                                               <a href="{{route("student.online-admission.process",Crypt::encrypt($admission->id))}}" class="btn btn-primary ">Proceed For Admission</a>
                                           @endif

                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>