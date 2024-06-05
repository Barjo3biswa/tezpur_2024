<?php

namespace App\Http\Controllers\Admin;

use App\AppliedCourse;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\AdmissionReceipt;
use App\Course;

use App\Http\Controllers\Controller;
use App\Models\ExamCenter;
use App\Models\Session;
use DB;

class DashboardCntroller extends Controller
{
    public function home()
    {
        $sessions = Session::all();
        $active_session = Session::active()->first();
        if(request("session")){
            $active_session = Session::where("id", request("session"))->first();
        }
        if(!$active_session){
            $active_session = $sessions->sortByDesc("id")->first();
        }
        $gender_wise_application_count = getGenderWiseApplication($active_session->id);
        // dd($gender_wise_application_count);
        return view("admin.home", compact("sessions", "active_session", "gender_wise_application_count"));
    }
    public function getsessioncount(Request $request){
        $sessin = $request->sessin;
        $states = Application::where('session_id', $sessin)->count();

        return response()->json($states);
        return response()->json( $sessin);

    }
    public function getcastcount(Request $request){
        $cast = $request->cast;
        $states = Application::where('cast_id',  $cast)->count();

        return response()->json($states);

    }
    public function getsessionregisterApplications(Request $request){
        $cast = $request->cast;
        $states = Application::where('cast_id',  $cast)->count();

        return response()->json($states);

    }
    public function program(Request $request){
        $sessions = Session::all();
        $center = ExamCenter::get();
        // dd($center);
        $active_session = Session::active()->first();
        if(request("session")){
            $active_session = Session::where("id", request("session"))->first();
        }
        if(!$active_session){
            $active_session = $sessions->sortByDesc("id")->first();
        }

        // $course = AppliedCourse::with('application')
        //                         ->whereHas('application', function ($query) use ($active_session) {
        //                             $query->where('is_mba', 0)
        //                                 ->where('session_id', '=', $active_session->id)
        //                                 ->whereNotNull('application_no')
        //                                 ->when(request('exam_center'), function ($q) use ($active_session) {
        //                                     $q->where('exam_center_id', request('exam_center'));
        //                                 })->when(request('type'), function ($q) use ($active_session) {
        //                                     $q->where('exam_through', request('type'));
        //                                 });
        //                         })
        //                         ->count();
        $exam_type= request('type');
        if($exam_type == "QNLT"){
            $exam_type ="TUEE";
        }
        $course = AppliedCourse::with('application')
                ->whereHas('application', function ($query) use ($active_session, $exam_type) {
                    $query->where('is_mba', 0)
                        ->where('session_id', '=', $active_session->id)
                        ->whereNotNull('application_no')
                        ->when(request('exam_center'), function ($q) use ($exam_type) {
                            $q->where('exam_center_id', request('exam_center'));
                        })
                        ->when(request('type'), function ($q) use ($exam_type) {
                            $q->where('exam_through', $exam_type);
                        });
                })
                ->where(function ($query) {
                    if (request('type') === "TUEE") {
                        $query->whereIn('application_id', function ($subQuery) {
                            $subQuery->select('id')
                                ->from('applications')
                                ->whereIn('net_jrf', [0, 2]);
                        });
                    }elseif(request('type') === "QNLT"){
                        $query->whereIn('application_id', function ($subQuery) {
                            $subQuery->select('id')
                                ->from('applications')
                                ->whereIn('net_jrf', [1]);
                        });
                    } else {
                        $query->whereIn('application_id', function ($subQuery) {
                            $subQuery->select('id')
                                ->from('applications')
                                ->whereIn('net_jrf', [0,1,2]);
                        });
                    }
                })
                ->count();


                // $course = AppliedCourse::with('application')
                //                 ->whereHas('application', function ($query) use ($active_session) {
                //                     $query->where('is_mba', 0)
                //                         ->where('session_id', '=', $active_session->id)
                //                         ->whereNotNull('application_no')
                //                         ->when(request('exam_center'), function ($q) use ($active_session) {
                //                             $q->where('exam_center_id', request('exam_center'));
                //                         })->when(request('type'), function ($q) use ($active_session) {
                //                             $q->where('exam_through', request('type'));
                //                         });
                //                 })
                //                 ->count();

        
        $categories = AppliedCourse::whereHas('application', function ($query) use ($active_session,$exam_type) {
                                        $query->where('is_mba', 0)->where('is_btech',0)->whereIn('is_direct',[0,1])
                                            ->where('session_id',$active_session->id)
                                            ->whereNotNull('application_no')
                                            ->when(request('exam_center'), function ($q) {
                                                $q->where('exam_center_id', request('exam_center'));
                                            })->when(request('type'), function ($q) use ($exam_type) {
                                                $q->where('exam_through', $exam_type);
                                            });
                                    })->where(function ($query) {
                                        if (request('type') === "TUEE") {
                                            $query->whereIn('application_id', function ($subQuery) {
                                                $subQuery->select('id')
                                                    ->from('applications')
                                                    ->whereIn('net_jrf', [0, 2]);
                                            });
                                        }elseif(request('type') === "QNLT"){
                                            $query->whereIn('application_id', function ($subQuery) {
                                                $subQuery->select('id')
                                                    ->from('applications')
                                                    ->whereIn('net_jrf', [1]);
                                            });
                                        } else {
                                            $query->whereIn('application_id', function ($subQuery) {
                                                $subQuery->select('id')
                                                    ->from('applications')
                                                    ->whereIn('net_jrf', [0,1,2]);
                                            });
                                        }
                                    })->select('course_id',DB::raw('count(applied_courses.id) as count'))
                                    ->groupBy('course_id')
                                    ->join('courses','courses.id','=','course_id')
                                    ->orderBy('courses.name')
                                    ->get();
        
        $total_applicant= Application::where('is_mba', 0)
                                    ->where('session_id',$active_session->id)
                                    ->whereNotNull('application_no')
                                    ->when(request('exam_center'), function ($q) use ($active_session) {
                                        $q->where('exam_center_id', request('exam_center'));
                                    })->count();
        
        return view('admin.program',compact('course','categories', 'sessions', 'active_session','center','total_applicant'));

    }

    public function center()
    {
        
        $sessions = Session::all();
        $active_session = Session::active()->first();
        if (request("session")) {
            $active_session = Session::where("id", request("session"))->first();
        }
        if (!$active_session) {
            $active_session = $sessions->sortByDesc("is_active",1)->first();
        }
        
        //////update is phd through net/jrf/etc
        $application = Application::where('is_phd',1)->where('session_id',13)->WhereNotNull('application_no')->where('net_jrf',0)->get();
        
        foreach($application as $app){
            if ($app->isNetJrfthird || $app->isNetJrfQualifiedSecond){
                Application::where('id',$app->id)->update(['net_jrf'=>1]);
                // $app->update(['net_jrf',1]);
            }else{
                Application::where('id',$app->id)->update(['net_jrf'=>2]);
                // $app->update(['net_jrf',2]);
            }
        }
        // dd("ok");
        /////Ends
        // dd($active_session);
        $query = ExamCenter::with(['application' => function ($query) use($active_session) {
            $query->where('session_id', '=',$active_session->id);
        }])->count();
        $query2 = ExamCenter::with(['applied_courses' => function ($query) use ($active_session) {
            $query->where('session_id', '=', $active_session->id)->where('is_mba',0)
            ->where('is_btech',0)->whereIn('is_direct',[0,1])->whereIn('net_jrf', [0, 2])->whereNotNull('application_no');
        }])->withCount('application')->orderBy('center_name');

        // dd($query2->applied_courses->count());
        

        $center = $query2->count();
        $categories = $query2->get();
        // dd($categories);
        return view('admin.center', compact('center', 'categories','sessions','active_session'));
    }
}
