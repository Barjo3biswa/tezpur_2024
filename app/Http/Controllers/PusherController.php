<?php

namespace App\Http\Controllers;

use App\Course;
use App\Models\AdmissionCategory;
use App\Models\CourseSeat;
use App\Models\User;
use Illuminate\Http\Request;
use Pusher\Pusher;
class PusherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $courseSeats = CourseSeat::where('course_id',$request->course_id)
        ->where('admission_category_id',$request->admission_category_id)->first();
        $courseSeats->increment('total_seats_applied');
        $courseSeats->save();
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => false
        ];
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $user = User::where('id',$request->user_id)->first();
        $course = Course::where('id',$request->course_id)->withTrashed()->first();
        $admission_category = AdmissionCategory::where('id',$request->admission_category_id)->first();
        $display_message = $user->name." has taken admission in ".$course->name." under ".$admission_category->name;
        $data['id'] = $courseSeats->id;
        $data['course_id'] = $request->course_id;
        $data['admission_category_id'] = $request->admission_category_id;
        $data['total_seats_applied'] = $courseSeats->total_seats_applied;
        $data['total_seats'] = $courseSeats->total_seats;
        $data['vacant_seats'] = intval($courseSeats->total_seats)-intval($courseSeats->total_seats_applied);
        $data['display_message'] = $display_message;
        $pusher->trigger('tezu-admission-channel', 'course-seat', ['message' => "A New record inserted",'response'=>$data]);
        return response()->json(['success'=>$data]);
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
