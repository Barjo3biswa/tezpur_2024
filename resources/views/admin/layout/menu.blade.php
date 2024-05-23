<li class=""><a href="{{ route(get_route_guard() . '.home') }}">Home</a></li>
@if (auth()->guard('admin')->check() && auth("admin")->id()==1)
    <li><a href="{{ route('admin.applicants.list') }}">Applicant's</a></li>
@endif

@if(auth("admin")->id()!=2)
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
        aria-expanded="false">Reports <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => '']) }}">List of Application All</a>
        </li>
        @if (!auth('department_user')->check())
            {{-- department user not able to see incomplete applications --}}
            <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'application_submitted']) }}">Incomplete
                    Applications</a></li>
        @else
            @php
                $user_id = auth("department_user")->id();
                $department_id = DB::table("department_assigned_users")->where('department_user_id',$user_id)->pluck("department_id");
            @endphp
            @if ($department_id[0] == 20)
                <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'application_submitted']) }}">Incomplete
                Applications</a></li>
            @endif
        @endif
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'payment_done']) }}">List of
                Application Submitted</a></li>
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'document_resubmitted']) }}">List of
                Application Doc. Re-Submitted</a></li>
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'accepted']) }}">List of Application
                Accepted</a></li>
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'rejected']) }}">List of Application
                Rejected</a></li>
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'on_hold']) }}">List of Application
                Hold</a></li>
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'qualified']) }}">List of WT
                qualified Applicants</a></li>
        <li><a href="{{ route(get_route_guard() . '.application.index', ['status' => 'admitted_student']) }}">List of
                Admitted Student</a></li>

        <li> <a href="{{ route('admin.reports.application-payments') }}">Application fee collection</a> </li>
        <li> <a href="{{ route('admin.reports.admission-payments') }}">Admission fee collection</a> </li>
        @if (auth()->guard('department_user')->check())
            @if (in_array(auth('department_user')->id(), [178, 180, 181, 182]))
                <li> <a href="{{ route(get_route_guard() . '.jossa.index') }}">List Of JOSSA Applications</a> </li>
            @endif
        @endif
    </ul>
</li>
@endif

@if (auth()->guard('department_user')->check())
    @if (in_array(auth('department_user')->id(), [178, 180, 181, 182]))
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Admission Btech 2023 <span class="caret"></span></a>
            <ul class="dropdown-menu">
                @if (checkPermission(1) == true)
                    <li><a href="{{ route(get_route_guard() . '.merit.attendence') }}">Attendence</a></li>
                @endif
                @if (checkPermission(2) == true)
                    <li><a href="{{ route(get_route_guard() . '.merit.index') }}">List/Admission-Process</a></li>
                @endif
                {{-- @if (checkPermission(3) == true)
            <li><a href="{{route(get_route_guard().".merit.payment")}}">Payment-Process</a></li>
            @endif --}}
                <li><a href="{{ route(get_route_guard() . '.vacancy.index') }}">Seat Vacancy Position</a></li>

                <li><a href="{{ route(get_route_guard() . '.merit.hostel-allotment') }}">Hostel Allotment</a></li>
            </ul>
        </li>
    @else
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Admission Process 2023<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route(get_route_guard() . '.merit.reporting') }}">Invite For Reporting</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.admission_new') }}">Admission</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.admission_Track') }}">Track Process</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.hostel-allotment') }}">Hostel Allotment</a></li>
            </ul>
        </li>
    @endif


    <li><a href="{{ route('department.seat-positions') }}">Vacancy positions</a></li>
    @if (config('vknrl.withdrawal_seat_module'))
        <li><a href="{{ route('department.merit.withdrawal-request') }}">Withdrawal requests</a></li>
    @endif

    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
            aria-expanded="false">Attendence <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('department.attendence-sheet') }}">Attendence Sheet</a></li>
        </ul>
    </li>
@endif

@if (auth()->guard('admin')->check())
    @if(auth("admin")->id()==1)
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Merit List <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route(get_route_guard() . '.merit.create') }}">Upload</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.index') }}">List</a></li>
                <li><a href="{{ route(get_route_guard() . '.vacancy.index') }}">Vacancy Seat Position</a></li>
                <li><a href="{{ route(get_route_guard() . '.vacancy.booked') }}">Booked Seats</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.admission_category_update') }}">Update Admission
                        Category</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Admission Process 2023 <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route(get_route_guard() . '.merit.admission-control') }}">Admission Control</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.reporting') }}">Invite For Reporting</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.admission_new') }}">Admission</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.admission_Track') }}">Track Process</a></li>
                <li><a href="{{ route(get_route_guard() . '.merit.slide-category') }}">Slide Admission Cat.</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Fee Structure <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li> <a href="{{ route('admin.fee-head.index') }}">Fee Head</a></li>
                <li><a href="{{ route('admin.fee.create') }}">Add new Fee</a></li>
                <li> <a href="{{ route('admin.fee.index') }}">All Fees</a> </li>
                <li> <a href="{{ route('admin.fee.reports') }}">Fees Collections</a> </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Miscellaneous <span class="caret"></span></a>
            <ul class="dropdown-menu">
                {{-- <li><a href="{{route("admin.notification.index")}}">Notification's</a></li> --}}
                <li><a href="{{ route('admin.department-users.index') }}">Department Users</a></li>
                <li><a href="{{ route('admin.exam-center.index') }}">Exam Center</a></li>
                {{-- <li><a href="{{route("admin.admit-card.index")}}">Admit Card</a></li> --}}
                <li><a href="{{ route('admin.questionnaires.index') }}">Questionnaires</a></li>
                {{-- <li><a href="{{route("admin.application.upload.student.qualified")}}">Upload Qualified Student</a></li> --}}
                <li><a href="{{ route('admin.log.index') }}">Daily Logs</a></li>
                {{-- @if (config('vknrl.withdrawal_seat_module')) --}}
                    <li><a href="{{ route('admin.merit.withdrawal-request') }}">Withdrawal requests</a></li>
                {{-- @endif --}}
                <li><a href="{{ route('index') }}">Attendance</a></li>
                <li><a href="{{ route('admin.admission-report') }}">Admission report</a></li>
                <li><a href="{{ route('admin.open-close') }}">Open/Close A program</a></li>
                <li><a href="{{ route('admin.duplicate-attachments') }}">Duplicate Attachments</a></li>

                <li><a href="{{ route('admin.send-mail') }}">Send MAIL</a></li>

                <li><a href="{{ route('admin.failed-payments') }}">Failed Payments</a></li>

            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Admit Card<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.generate-view') }}">Generate/View</a></li>
                <li><a href="{{ route('admin.attendence') }}">Attendence</a></li>
                <li><a href="{{ route('admin.attendence-new') }}">Attendence New</a></li>
                {{-- <li><a href="{{route("admin.mcj_fix")}}">MCJ FIX</a></li> --}}
                <li><a href="{{ route('admin.export_others') }}">Others</a></li>

            </ul>
        </li>
    @elseif(auth("admin")->id()==2)
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Admission Process 2023 <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route(get_route_guard() . '.merit.admission_Track') }}">Track Process (Hostel)</a></li>
                <li><a href="{{ route('admin.admission-report') }}">Admission report</a></li>
            </ul>
        </li>
    @endif

@endif
