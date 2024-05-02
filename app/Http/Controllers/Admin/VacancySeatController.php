<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use App\Models\CourseSeat;
use App\Models\Application;
use App\Models\MeritMaster;
use App\Models\MeritList;
use App\Models\AdmissionCategory;
use Excel,Config,Redirect,Session,Log,DB;
class VacancySeatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $course_id = $request->course_id;
        $all_courses = Course::withTrashed()->get();
        $courses = Course::withTrashed()->with('courseSeats.admissionCategory')->orderBy('name');
        if($course_id)
            $courses = $courses->where('id',$course_id);

        $courses =  $courses->get();
        return view('admin.vacancy.index',compact('courses','all_courses'));
    }

    public function booked(Request $request)
    {
        //
        $course_id = $request->course_id;
        $booked_lists = MeritList::where('status',2)->with('application.student','course','admissionCategory',"admissionReceipt:id,merit_list_id,total");
        if($course_id)
            $booked_lists = $booked_lists->where('course_id',$course_id);
        $all_courses =  Course::withTrashed()->get();
        $booked_lists = $booked_lists->orderBy("course_id", "ASC")->orderBy("tuee_rank", "ASC")->get();
        return view('admin.vacancy.booked',compact('booked_lists','all_courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //tezu@2020#
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
