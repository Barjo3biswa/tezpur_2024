<li class=""><a href="{{route("student.home")}}">Home </a></li>
<li><a href="{{route("student.application.index")}}">Application</a></li>
{{-- <li><a target="_blank" href="{{asset('Final Prospectus _final 03032020.pdf')}}" style="cursor:help;"><i class="fa fa-question-circle"></i> Prospectus</a></li> --}}
{{-- @if (Auth::user()->is_mba==0)
<li><a target="_blank" href="{{asset("notifications/2023/Prospectus_2023.pdf")}}" style="cursor:help;"><i class="fa fa-question-circle"></i> Prospectus 2023-24</a></li>
@endif --}}

@if (Auth::user()->program_name=='MBA')
<li><a target="_blank"  href="{{asset("notifications/2024/Prospectus 2024.pdf")}}" style="cursor:help;"><i class="fa fa-question-circle"></i> Prospectus(MBA) 2022-23</a></li>
@else
<li><a target="_blank" href="{{ asset('notifications/2024/Prospectus-4-upload.pdf') }}" style="cursor:help;"><i class="fa fa-question-circle"></i> Prospectus 2023-24</a></li>
@endif
{{-- <li><a href="#" style="cursor:help;"><i class="fa fa-question-circle"></i> Fee Structure</a></li> --}}
{{-- <li><a target="_blank" href="{{asset('Entrance Exam_Schedule_2020c.pdf')}}" style="cursor:help;"><i class="fa fa-question-circle"></i> New Examination Schedule</a></li> --}}
<li><a target="_blank" href="{{route("student.download.formates")}}"><i class="fa fa-cloud-download"></i> Download formats</a></li>
{{-- <li><a target="_blank" href="{{asset("Guide for B.Tech application form 2020.pdf")}}" > Guide for filling B.Tech
        application form 2020.</a> </li> --}}
{{-- @if (config("vknrl.show_vacancy_positions")) --}}
{{-- <li><a href="{{route("student.vacancy.index")}}" style="background: red;color:white !important;"> Vacancy Positions.<span class="label label-default blink">New</span></a> </li> --}}
{{-- @endif --}}