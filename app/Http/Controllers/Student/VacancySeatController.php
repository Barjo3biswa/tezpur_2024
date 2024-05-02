<?php

namespace App\Http\Controllers\Student;

use App\AppliedCourse;
use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MeritList;

class VacancySeatController extends Controller
{
    public function index()
    {
        // if(!config("vknrl.show_vacancy_positions")){
        //     abort(404);
        // }
        $all_courses = Course::withTrashed()->get();
        $courses = Course::withTrashed()->with('courseSeats.admissionCategory')->orderBy('name');
        $course_ids = AppliedCourse::where("student_id", auth("student")->id())
            ->pluck("course_id")
            ->toArray();
        $course_ids2 = MeritList::where("student_id", auth("student")->id())
            ->pluck("course_id")
            ->toArray();
        if($course_ids2){
            $course_ids = $course_ids2;
        }
        $courses->whereIn("id", $course_ids);
        $courses =  $courses->get();
        return view('student.vacancy.index',compact('courses','all_courses'));
    }
}
