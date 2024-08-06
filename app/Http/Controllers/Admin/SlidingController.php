<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\AdmissionReceipt;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\MeritMaster;
use Crypt;
use DB;

class SlidingController extends Controller
{
    public function index(Request $request){
        $course_id            = $request->course_id ?? 0;
        $merit_master_id      = $request->merit_master_id ?? 0;
        $courses              = Course::withTrashed()->where('sliding_possibility',1)->get();
        $admission_categories = AdmissionCategory::where('status', 1)->get();

        if ($request->submit == "Process") {
            return $this->performSliding($course_id,$merit_master_id);
        }
        $merit_lists          = MeritList::with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id", "is_pwd");
                }, 'meritMaster', 'admissionCategory', 'course', 'selectionCategory',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);
        $merit_lists = $merit_lists->where('course_id', $course_id);
        if ($request->merit_master_id) {
            $merit_lists = $merit_lists->where('merit_master_id', $merit_master_id);
        }
        $merit_lists = $merit_lists->paginate(200);

        $list=MeritMaster::where('course_id',$course_id )->where('sliding_possibility',1)->get();
        // if (auth("admin")->check()) {
            $layot = 'admin.layout.auth';
        // }else{      
        //     $layot = 'department-user.layout.auth';
        // }
        return view('admin.sliding.index', compact('courses', 'admission_categories', 'merit_lists','list','layot'));
    }

    public function performSliding($course_id,$merit_master_id){
        $course=Course::where('id',$course_id)->where('sliding_possibility',1)->withTrashed()->first();
        $merit_master=MeritMaster::where('id',$merit_master_id)->where('sliding_possibility',1)->first();
        if($course==null ||$merit_master==null){
            return redirect()->back()->with('error','select valid course & list');
        }



        $seatmatrix=CourseSeat::where('course_id',$course_id)->where('admission_category_id',1)->where('course_seat_type_id', $merit_master->course_seat_type_id??'')->first();
        $vacancy=$seatmatrix->total_seats-($seatmatrix->total_seats_applied+$seatmatrix->temp_seat_applied+$seatmatrix->temp_attendence_occupied);
        if($vacancy<=0){
            return redirect()->back()->with('error','sliding is processed please wait till students to cancel.');
        }
        
        //check for other category status
        //    $other_course_stat_count=CourseSeat::where('course_id',$course_id)->where('admission_category_id','!=',1)->where('admission_flag','open')->count();
        //    if($other_course_stat_count>0){
        //         return redirect()->back()->with('error','Please wait till category seat to fill out.');
        //     }
        //

        DB::beginTransaction();
        try{
           
            for($i=0; $i < $vacancy; $i++){
                $last_ur_student  =MeritList::where('merit_master_id',$merit_master_id)
                                            ->whereIN('status',[2,6,3])
                                            ->where(['admission_category_id'=>1,'is_pwd'=>0])
                                            ->orderBy('id','DESC')->first();
                
                $first_cat_student=MeritList::where('merit_master_id',$merit_master_id)->where('may_slide',0)
                                            ->where(['status'=>2,'is_pwd'=>0])->where('admission_category_id','!=',1)
                                            ->orderBy('id','ASC')->first();
                // dd($first_cat_student);
                $eligible_candidate=MeritList::where('merit_master_id',$merit_master_id)->where('may_slide',0)
                                            ->whereBetween('id',array($last_ur_student->id,$first_cat_student->id))
                                            ->where('id', '>', $last_ur_student->id)
                                            ->where('id', '<', $first_cat_student->id)
                                            ->whereNotIN('status',['2,3,4,6'])->orderBy('id','ASC')->first();
                
                // dump($first_cat_student->student_id);   
                // dd($eligible_candidate);

                if($eligible_candidate){
                    if($eligible_candidate->may_slide==1){
                        continue;
                    }

                    MeritList::where('id', $eligible_candidate->id)->update(['new_status' => 'can_call',
                                                                            'admission_category_id' => 1,
                                                                            'may_slide' => 1]);
                }else{
                    // dd("ok");
                    if($first_cat_student->may_slide==1){
                        continue;
                    }
                    MeritList::where('id', $first_cat_student->id)->update(['may_slide' => 1]);
                }
                CourseSeat::where('course_id',$course_id)->where('admission_category_id',1)->where('course_seat_type_id', $merit_master->course_seat_type_id??'')->increment('temp_attendence_occupied');
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Something went wrong');
        }
        // dd("ok");
        return redirect()->/* route('admin.merit.slide-category') */back()->with('success','Successfully Processed Sliding');

    }

    public function slideCategory($id){
        try {
            $decripted = Crypt::decrypt($id);          
        } catch (\Exception $e) {
            abort(404, "Invalid Request");
        }

        $meritlist=MeritList::where('id',$decripted)->first();
        $merit_master = MeritMaster::where('id',$meritlist->merit_master_id)->first();

            if($meritlist->admission_category_id==1){
                return redirect()->back()->with('error','Can`t Slide Admission category. As Student is already in Open category');
            }
            if(checkOpenAvailability2($meritlist->course_id,$merit_master->course_seat_type_id)==false){
                return redirect()->back()->with('error','Can`t Slide Admission category. As General Category is already Filled UP..');
            }
            DB::beginTransaction();
            try{
                CourseSeat::where(['course_id'=>$meritlist->course_id,'admission_category_id'=>$meritlist->admission_category_id])
                            ->where('course_seat_type_id', $merit_master->course_seat_type_id??'')
                            ->decrement('total_seats_applied');
                CourseSeat::where(['course_id'=>$meritlist->course_id,'admission_category_id'=>1])
                            ->where('course_seat_type_id', $merit_master->course_seat_type_id??'')
                            ->increment('total_seats_applied');

                $meritlist->update([
                                    'admission_category_id'=>1,
                                    "may_slide"=>2]);  
                AdmissionReceipt::where('id',$meritlist->admissionReceipt->id)->update(['category_id'=>1,'slide_date'=>date('Y-m-d H:m:s')]);
                
                CourseSeat::where('course_id',$meritlist->course_id)->where('admission_category_id',1)
                        ->where('course_seat_type_id', $merit_master->course_seat_type_id??'')
                        ->decrement('temp_attendence_occupied');

                DB::commit();
            }catch(\Exception $e){
                 dd($e);
                 DB::rollBack();
            }

            if(checkOpenAvailability($meritlist->course_id)==false){ 
                Course::where('id',$meritlist->course_id)->withTrashed()->update(['sliding_possibility'=>0]);
                MeritMaster::where('id',$meritlist->merit_master_id)->update(['sliding_possibility'=>0]);
            }
            return redirect()->back()->with('success','Admission category is successfully slided'); 
    }
}
