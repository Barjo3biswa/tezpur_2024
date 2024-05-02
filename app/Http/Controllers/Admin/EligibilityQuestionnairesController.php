<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Controllers\Controller;
use App\Models\EligibilityQuestion;

class EligibilityQuestionnairesController extends Controller
{
    public function index()
    {
        $courses = Course::select("id", "name", "code")->withTrashed()->get();
        $questions = EligibilityQuestion::query();
        $questions->when(request("course_id"), function($query){
            return $query->where("course_id", request("course_id"));
        });
        $questions->when(request("type"), function($query){
            return $query->where("type", request("type"));
        });
        $questions = $questions->with("course")->latest()
            ->paginate(50);
        return view("admin.questionnaires.index", compact("questions", "courses"));
    }
    public function create()
    {
        $courses = Course::select("id", "name", "code")->get();
        return view("admin.questionnaires.create", compact("courses"));
    }
}
