<?php

namespace App\Http\Controllers\Student;

use App\AppliedCourse;
use App\Course;
use App\CourseSeatTypeMaster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MeritList;

class VacancySeatController extends Controller
{
    public function index2()
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

    public function index()
    {      
        $all_courses = Course::withTrashed()->get();
        $courses = Course::withTrashed()->with('courseSeats.admissionCategory')->orderBy('name');
        $course_ids2 = MeritList::where("student_id", auth("student")->id())
            ->pluck("course_id")
            ->toArray();
        $course_ids = $course_ids2;
        $courses->whereIn("id", $course_ids);
        $courses =  $courses->get();
        $merit_list = MeritList::with('meritMaster')->where("student_id", auth("student")->id())->get();
        $ids=[];
        foreach($merit_list as $ml){
            if(in_array($ml->course_id,[72,73,74,75,76,77,83,111])){
                array_push($ids,72,73,74,75,76,77,111);
            }else{
                array_push($ids,$ml->meritMaster->course_seat_type_id);
            }
            
        }
        $course_seat_type = CourseSeatTypeMaster::whereIn('id',$ids)->get();
        return view('student.vacancy.index',compact('courses','all_courses','course_seat_type'));
    }
}
