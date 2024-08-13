<?php

namespace App\Http\Controllers\DepartmentUser;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\Caste;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\Session as ModelsSession;
use DB;
use Illuminate\Support\Facades\Crypt;
use Session;
use Validator;

class JossaController extends Controller
{
    public function index(){
        // dd("ok");
        $session = ModelsSession::where('is_active',1)->first()->id;
        $application = Application::where('is_btech',1)
                                    ->where('session_id',$session)
                                    ->where('exam_through','JOSSA')
                                    // ->whereHas('JossaStatus')
                                    ->where('status','payment_done')->get();
        return view('department-user.jossa.index',compact('application'));
        // dd($application);
    }

    public function assignBranch($id){
        $decr=Crypt::decrypt($id);
        // dd($decr);
        $application=Application::where('id',$decr)->first();
        $caste=Caste::get();
        $filter_array=[];
        if(in_array($application->caste_id,[1,3])){
            $filter_array=[1];
        }elseif($application->caste_id==2){
            $filter_array=[1,6];
        }elseif($application->caste_id==4){
            $filter_array=[1,4];
        }elseif($application->caste_id==5){
            $filter_array=[1,3];
        }elseif($application->caste_id==6){
            $filter_array=[1,2];
        }

        // $filter_array=[8,10,11];

        $course = Course::/* with(['course_seats_new'=>function($q) use($filter_array){
            $q->whereIn('admission_category_id',$filter_array);
        }])
        -> */where('program_id',7)
        ->where('id','!=',83)
        ->withTrashed()->get();
        
        

        $branch=Course::where('program_id',7)
                        ->where('id','!=',83)
                        ->withTrashed()->get();
        $admission_categorymodal=AdmissionCategory::where('status',1)->where('id','!=',5)->get();
        return view('department-user.jossa.assign-branch-jossa',compact('caste','course','branch','admission_categorymodal','id','application'));
    }

    public function changeBranch(Request $request,$id){
        dd("OK");
        try {
            $decrypted = Crypt::decrypt($id);
            $application=Application::where('id',$decrypted)->first();
        } catch (\Exception $e) {
            dd("error");
        }
        $validator = Validator::make($request->all(), [
                'branch_name'   => 'required',
            ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error','Check branch.');
        }
        $array = unserialize(urldecode($request->branch_name));
        $branch_name=$array[0];
        $admission_category = $array[1];

        if($request->s_cat==1){
            $social_cat=1;
        }elseif($request->s_cat==3){
            $social_cat=5;
        }elseif($request->s_cat==2){
            $social_cat=6;
        }elseif($request->s_cat==4){
            $social_cat=4;
        }elseif($request->s_cat==5){
            $social_cat=3;
        }elseif($request->s_cat==6){
            $social_cat=2;
        }

       

    
        $data=[
            'merit_master_id'	=> 334,
            'student_id'	    => $application->student_id,
            'admission_category_id'	=> $admission_category,
            'is_pwd'    => 0,
            'course_id'	=> $branch_name,
            'application_no'	=> $application->application_no,
            'shortlisted_ctegory_id'	=> $social_cat,
            'gender'	=> $application->gender,
            'tuee_rank'	=> $application->id,
            'status' => 8,
	        'may_slide' => 0,
            'valid_from'	=> null,
            'valid_till'	=> null,
            'programm_type' => 0,
            'full_time'=> null,
            'part_time'=> null,
            'hostel_required'	=> null,
            'ask_hostel'	=> 1,
            'student_rank'	=> null,  
            'attendance_flag' => 1,
            'selection_category'	=> null,
            'freezing_floating'	=> 'Floating',
            'cmr'	=> null,
            'new_status'	=> 'branch_assigned',
            'hostel_required' =>0,
            'processing_technique' =>1,
            'payment_mode'  => 'offline'
        ];
        // dd
        DB::beginTransaction();
        try{
            MeritList::create($data);
            Application::where('id',$decrypted)->update(['caste_id'=>$request->s_cat]);
            // dd($data);
            // CourseSeat::where('course_id',$branch_name)
            //         ->where('admission_category_id',$admission_category)
            //         ->increment('temp_seat_applied');

            // CourseSeat::where('course_id',$branch_name)
            //     ->where('admission_category_id',$admission_category)
            //     ->increment('total_seats');

            DB::commit();
        }catch(\Exception $e){
            dd($e);
            DB::rollback();
        }
        return redirect()
            ->route('department.jossa.index')
            ->with('success', 'Branch Assigned');

        // $array = unserialize(urldecode($request->branch_name));
        // $branch_name=$array[0];
        // $admission_category = $array[1];
        // // dd($array);
        // //check is seat available
        // if(!$request->below_sixty){
        //     $unreserve_seat= CourseSeat::where('course_id',$branch_name)->where('admission_category_id',1)->first();
        //     $unreserve_seat_count = $unreserve_seat->total_seats -($unreserve_seat->total_seats_applied+$unreserve_seat->temp_seat_applied);
        //     if($unreserve_seat_count>0 && $admission_category!=1 && $merit_list->is_pwd==0){
        //         return redirect()->back()->with('error','Please assign this candidate to Unreserved Category.');
        //     }
        // }
        
        // // dd($unreserve_seat_count);

        // $course_seat=CourseSeat::where('course_id',$branch_name)->where('admission_category_id',$admission_category)->first();
        // $avail_seat = $course_seat->total_seats -($course_seat->total_seats_applied+$course_seat->temp_seat_applied);
        // if($avail_seat<=0){
        //    return redirect()->back()->with('error','seat capacity exceeded !!');
        // }
        
        // // dd($decrypted);
        // if($merit_list->new_status=="branch_assigned"){
        //     return redirect()
        //     ->route('department.merit.index', ['course_id' => 83, 'merit_master_id' => $merit_list->merit_master_id])
        //     ->with('error', 'Branch Already Assigned');
    
        // }

        // DB::beginTransaction();
        // try{
        //     MeritList::where('id',$decrypted)->update([
        //         'admission_category_id' =>  $admission_category,
        //         'course_id'     => $branch_name,
        //         'new_status'    => 'branch_assigned',
        //         'status'        => 8,
        //     ]);

        //     CourseSeat::where('course_id',$branch_name)
        //             ->where('admission_category_id',$admission_category)
        //             ->increment('temp_seat_applied');
        //     if($merit_list->is_pwd==1){
        //         CourseSeat::where('course_id',$branch_name)
        //             ->where('admission_category_id',7)
        //             ->increment('temp_seat_applied');
        //     }
        //     DB::commit();
        // }catch(\Exception $e){
        //     DB::rollBack();
        // }
        
        // return redirect()
        //     ->route('department.merit.index', ['course_id' => 83, 'merit_master_id' => $merit_list->merit_master_id])
        //     ->with('success', 'Branch Assigned');
    }
}
