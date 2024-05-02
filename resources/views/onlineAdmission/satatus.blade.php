@if($list->expiry_hour==2)
    <button class="btn btn-danger btn-xs">waiting list..</button>
@endif
@if ($list->new_status=='queue')
    <span class="btn btn-info btn-xs">Not Invited</span> <br/>
@elseif($list->new_status=='can_call')
    <span class="btn btn-warning btn-xs">Can Call For Admission </span> <br/>
@elseif($list->new_status=='called' && $list->status == 7)
    <span class="btn btn-success btn-xs">Called</span> <br/>
@elseif($list->new_status=='cancel')
    <span class="btn btn-danger btn-xs">Cancelled</span> <br/>
@elseif($list->new_status=='invited' && $list->attendance_flag==0 && now() <= $list->valid_till)
    <span class="btn btn-success btn-xs">Invited for Reporting</span> <br/>
@elseif($list->new_status=='time_extended')
    <span class="btn btn-warning btn-xs">Time Is Extended</span> <br/>
@endif

@if (!in_array($list->status,[2,3,6]))
    @if ($list->attendance_flag==1)
        <span class="btn btn-success btn-xs">Reported</span> <br/>
    @elseif ($list->attendance_flag==2)
        <span class="btn btn-danger btn-xs"> Denied Reporting Request</span> <br/>
    @elseif ($list->attendance_flag==0 && $list->new_status=='invited' && now() >= $list->valid_till)
        <span class="btn btn-warning btn-xs">Invited but not respond</span> <br/>
    @elseif ($list->attendance_flag==0 && in_array($list->new_status, ['called']))
        <span class="btn btn-warning btn-xs">Invited but not respond</span> <br/>
    @endif
@endif


@if ($list->status == 1)
<span class="label label-warning">
    Approved </span> <br/>
@elseif ($list->status == 2)
    <span class="label label-success">
        <i class="fa fa-check" aria-hidden="true"></i>
        Provisionally Admitted
    </span> <br/>
@elseif ($list->status == 3)
    <span class="label label-danger">
        <i class="fa fa-times" aria-hidden="true"></i>
        Seat Transferred
    </span> <br/>
@elseif ($list->status == 6)
    <span class="label label-danger">
        <i class="fa fa-times" aria-hidden="true"></i>
        Withdrawal
    </span> <br/>
@elseif($list->status == 8)
    <span class="label label-primary"> Reported for counselling. </span> <br/><br />
@elseif($list->status == 9)
    <span class="label label-danger"> Seat declined by {{$list->seat_declined_by}}. </span> <br/>
@endif
@if (!in_array($list->status, [2,3,6,9]) && $list->valid_from!=null)
    @if($list->attendance_flag==0)
    <span class="btn btn-info btn-xs">{{date('d-m-Y H:i a', strtotime($list->valid_from))}} to {{date('d-m-Y H:i a', strtotime($list->valid_till))}}</span>
    @endif
@endif