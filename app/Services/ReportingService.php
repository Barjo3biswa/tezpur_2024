<?php
namespace App\Services;
use App\Models\AdmissionCategory;
use App\Models\CourseSeat;
use App\Models\MeritList;
use Illuminate\Support\Collection;
use PDF;

class ReportingService
{
	public function __construct($merit_list_id){
		$this->merit_list_id = $merit_list_id;
	}
    public function checkValidation()
    {
        foreach($this->merit_list_id as $id){
            $meritlist = MeritList::find($id);
            $general_course_seat = CourseSeat::where('course_id',$meritlist->course_id)
                            ->where('session_year',date('Y'))
                            ->where('admission_category_id',1)->first();
            $where = [];
            $where[] = ['course_id',$meritlist->course_id];
            $where[] = ['id','!=',$id];
            $where[] = ['is_hold',0];
            $where[] = ['merit_master_id',$meritlist->merit_master_id];
            $where[] = ['is_pwd',0];
            if($general_course_seat->total_seats_applied >= $general_course_seat->total_seats){
                $where[] = ['admission_category_id',$meritlist->admission_category_id];
            }
            $pwd_seat = CourseSeat::where('course_id',$meritlist->course_id)
                        ->where('session_year',date('Y'))
                        ->where('admission_category_id',7)->first();
            $pwd = true;
            if($pwd_seat->total_seats_applied >= $pwd_seat->total_seats){
                $pwd = false;
            }
            
            
            if($meritlist->is_pwd == 0){
                $all_list = MeritList::where($where)->whereIn('status',[0,7])->get();
                $all_tuee_rank = [];
                foreach($all_list as $key=>$val){
                    array_push($all_tuee_rank,$val->tuee_rank);
                }
                // dd($all_tuee_rank);
                if(!empty($all_tuee_rank)){
                    if($meritlist->tuee_rank >= min($all_tuee_rank)){
                        return false;
                    }
                }
            }
            return true;
        }
    }

    public function checkAvailableSeat(){
        foreach($this->merit_list_id as $id)
        {
            $meritlist = MeritList::find($id);
            $course_seat_total = CourseSeat::where('course_id',$meritlist->course_id)
                                    ->where('session_year',date('Y'))
                                    ->where('is_selection_active',1)->first();
            if(!$course_seat_total){
                return false;
            }

            // if($meritlist->is_pwd != 1 && ($course_seat_total->admission_category_id != $meritlist->admission_category_id)){
            //     return false;
            // }
            $where = [];
            $where[] = ['course_id',$meritlist->course_id];
            $where[] = ['is_hold',0];
            $where[] = ['id','!=',$id];
            $where1[] = ['course_id',$meritlist->course_id];
            $where1[] = ['session_year',date('Y')];
            if($course_seat_total->admission_category_id != 1){
                $where[] = ['admission_category_id',$meritlist->admission_category_id];
                $where1[] = ['admission_category_id',$meritlist->admission_category_id];
            }
            else{
                $where[] = ['is_pwd',0];
            }
            $all_list = MeritList::where($where)->whereNotIn('status',[3,0,7,4,9])->get();
            // dd($all_list);
            if($all_list->count() >= $course_seat_total->total_seats){
                return false;
            }
            $course_seat = CourseSeat::where($where1)->first();
            if($course_seat){
                if($course_seat->total_seats_applied >= $course_seat->total_seats){
                    return false;
                }
            }
        }
        return true;            
    }

    public function checkForPwdCandidate(){
        foreach($this->merit_list_id as $id){
            $merit_list = MeritList::find($id);
            $reg_no = [];
            if($merit_list->is_pwd == 0){
                $all_list = MeritList::select('student_id')->where('course_id',$merit_list->course_id)
                        ->where('id','!=',$id)
                        ->where('merit_master_id',$merit_list->merit_master_id)
                        ->where('is_pwd',1)
                        ->whereIn('status',[0,7,4])->where('is_hold',0)->get();
                $course_seat = CourseSeat::where('course_id',$merit_list->course_id)
                            ->where('admission_category_id',7)
                            ->where('session_year',date('Y'))->first();
                if($course_seat){
                    if($course_seat->total_seats_applied >= $course_seat->total_seats){
                        return false;
                    }
                    else{
                        if(!$all_list->isEmpty()){
                            foreach($all_list as $listValue){
                                array_push($reg_no,$listValue->student_id);
                            }
                            return $reg_no;
                        }
                    }
                }
            }
        }
        return $reg_no;
    }

    public function checkAvailableSeatNew($id)
    {
        $merit_list=MeritList::where('id',$id)->first();//new
                    $course_seat=$merit_list->course_seat();
        if($course_seat->total_seats <= $course_seat->temp_seat_applied + $course_seat->total_seats_applied){
            return false;
        }
        return true;
    }
}
