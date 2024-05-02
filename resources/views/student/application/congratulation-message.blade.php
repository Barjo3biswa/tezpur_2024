            
@php
    $merit_lists = \App\Models\MeritList::with("course")->where("student_id", auth("student")->id())->get();
@endphp
@if ($merit_lists->count())
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong class="blink">Congratulations! 👏</strong> You are shortlisted in the common rank list for the program you have applied for. Please go to <strong>'Admission seat details'</strong> for more information.
        <strong>
            {{-- @php 
                echo $merit_lists->map(function($merit){
                    return $merit->course->name;
                })->implode(", ");
            @endphp --}}
        </strong>.
    </div>
@endif