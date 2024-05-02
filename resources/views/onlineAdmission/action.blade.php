@if ($list->new_status=='queue')
    <button type="button" class='btn btn-sm btn-info'>..Waiting</button>
@elseif($list->new_status=='can_call' && auth()->guard('admin')->check())
     <a href="{{route(get_route_guard().'.merit.cancel-by-admin',Crypt::encrypt($list->id))}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to Cancel this applicant?');">Cancel By Admin</a>
@elseif(in_array($list->new_status,['called','time_extended']))
    @if ($list->is_warning_send == 0)
        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#warningModal"
            onclick="assignIdTo({{$list->id}})">
            Send Warning Mail
        </button>
    @endif
    <button type="button" class="btn btn-danger btn-sm"  style="margin-top:.2rem;" data-toggle="modal" data-target="#cancelModal" onclick="assignIdTo({{$list->id}})">
        Cancel This Candidate
    </button>
    {{-- <a href="{{route(get_route_guard().'.merit.cancel-for-admission',Crypt::encrypt($list->id))}}" class='btn btn-sm btn-danger' onclick="return confirm('Are you sure you want to cancel this candidate to proceed in examination process?');">Cancel This Candidate</a> --}}
    @if($list->new_status=='called')
        <a href="{{route(get_route_guard().'.merit.hold-seat-online',Crypt::encrypt($list->id))}}" class='btn btn-sm btn-warning' style="margin-top:.2rem;">Hold & Extend</a>
    @endif
@endif

@if ($list->status == 2)
    <a href="{{ route(get_route_guard().'.admission.payment-receipt', Crypt::encrypt($list->id)) }}" target="_blank" class="btn btn-sm btn-primary">Admission Receipt</a>
@endif

@if (auth("admin")->check() && in_array($list->new_status,['called','time_extended']))
    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#newTime" onclick="assignIdToNew({{$list->id}})">Assign New Time</button>
@endif

