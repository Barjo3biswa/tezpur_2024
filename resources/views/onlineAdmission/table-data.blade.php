<td>
    {{ $list->student_id }}</td>
<td>{{ $list->application_no }}
    <br />
    Ph.-{{ $list->student->mobile_no }}
</td>
<td>{{ $list->course->name }}
    @if ($list->freezing_floating != null)
        <br /><b style="color: rgb(150, 35, 35)">{{ $list->freezing_floating }}</b>
    @endif
    @if ($list->status==3)
        <br /><span style="color:rgb(155, 91, 19)">{{$list->student->admitedCourse->course->name??"NA"}}</span>
    @endif
</td>
<td>{{ $list->application->getFullNameAttribute() }}</td>
<td>
    @if ($list->meritMaster->semi_auto == 1)
        @if ($list->selectionCategory->id == 1)
            Unreserved
        @else
            {{ $list->selectionCategory->name }}
        @endif
    @else
        @if ($list->status == 0)
            NA
        @else
            @if ($list->admission_category_id == 1)
                Unreserved
            @else
                {{ $list->admissionCategory->name }}
            @endif
        @endif
    @endif
</td>
<td>{{ $castes[$list->application->caste_id] ?? 'NA' }}@if ($list->is_pwd)
        <span class="btn btn-danger btn-xs">PWD</span>
    @endif
</td>
<td>{{ $list->gender }}</td>
<td>
    @if ($list->meritMaster->semi_auto == 0)
        {{ $list->tuee_rank }} ({{ $list->student_rank }})
    @endif
</td>
<td>{{ $list->hostel_required ? 'Required' : '-' }}</td>
<td>{{ str_replace('_', ' ', $list->programm_type) }}</td>
<td>{{$list->preference??"NA"}}</td>
