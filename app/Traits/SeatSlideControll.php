<?php
namespace App\Traits;

use App\Models\AdmissionReceipt;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Services\ReportingService;
use Crypt;
use Illuminate\Http\Request;
use Log, Exception, DB, Excel;

trait SeatSlideControll {
    public function admissionSeatSlide($encrypted_id)
    {
        try {
            $dec_data = Crypt::decrypt($encrypted_id);          
            $MeritListDetailsNew=MeritList::find($dec_data['merit_list_id']);
            $course_id = $dec_data['course_id'];
            $merit_master_id= $dec_data['merit_master_id'];
        } catch (Exception $e) {
            abort(404, "Invalid Request");
        }

        // $reporting_service = new ReportingService([$MeritListDetailsNew->id]);
        // $check_if_true = $reporting_service->checkValidation();
        // if(!$check_if_true){
        //     return redirect()->back()->with('error', "Please Follow the Queue or put in hold prevous candidate.");
        // }


        $previousCounseling=MeritList::where(['student_id'=> $MeritListDetailsNew->student_id,
                                              'status'    => 2,
                                              'course_id' => $MeritListDetailsNew->course_id,])->first();
        if($previousCounseling!=null){

            if($previousCounseling->admission_category_id==1){
                return redirect()->back()->with('error','Can`t Slide Admission category. As Student is already in Open category');
            }
            $seatMatrix=CourseSeat::where(['course_id'=>$course_id,'admission_category_id'=>1])->first();
            if(checkOpenAvailability($course_id)==false){
                return redirect()->back()->with('error','Can`t Slide Admission category. As General Category is already Filled UP..');
            }
            DB::beginTransaction();
            try{
                $is_slideable=$this->CheckOrderByCMR($course_id,$MeritListDetailsNew->merit_master_id,$MeritListDetailsNew->cmr);
                
                if($is_slideable!=null){
                    return redirect()->back()->with('error','Can`t Slide Admission category.'.$is_slideable->application_no.'is in Higher rank');
                }
                // dd($is_slideable);
                $seatMatrix->increment('total_seats_applied');
                CourseSeat::where(['course_id'=>$course_id,'admission_category_id'=>$previousCounseling->admission_category_id])->decrement('total_seats_applied');
                $previousCounseling->update([/* 'admission_category_id'=>1, */"may_slide"=>3]);
                $MeritListDetailsNew->update([
                                    'admission_category_id'=>1,
                                    'status'=>14,
                                    "may_slide"=>2]);  
                $admission_receipt=AdmissionReceipt::where(
                                                        [   'student_id'   => $previousCounseling->student_id,
                                                            'course_id'    => $previousCounseling->course_id,
                                                            'merit_list_id'=> $previousCounseling->id
                                                            ])->first();  
                $admit = $admission_receipt->toArray();
                $admit['created_at'] = $admission_receipt->getOriginal('created_at'); 
                DB::table('admission_receipts_log')->insert($admit);
                AdmissionReceipt::where('id',$admission_receipt->id)->update(['category_id'=>1,'slide_date'=>date('Y-m-d H:m:s')]);
                
                DB::commit();
            }catch(Exception $e){
                 dd($e);
                 DB::rollBack();
            }
            return redirect()->back()->with('success','Admission category is successfully slided'); 
        }else{
            return redirect()->back()->with('error','Can`t Slide Admission category. As it is his first..');
        }
    }

    public function CheckOrderByCMR($course_id, $merit_master_id, $cmr)
    {
        return $availability=MeritList::where(['course_id'=> $course_id,'merit_master_id'=> $merit_master_id,'may_slide'=>1])
                                ->where('cmr','<', $cmr)
                                ->first();
    }

    public function admissionSeatSlideDeny($encrypted_id)
    {
        try {
            $dec_data = Crypt::decrypt($encrypted_id);          
            $MeritListDetailsNew=MeritList::find($dec_data['merit_list_id']);
            $MeritListDetailsNew->update([
                'status'=>16,
                "may_slide"=>4]); 
            
            return redirect()->back()->with('success','Successfully Denied Sliding Admission category'); 
        } catch (Exception $e) {
            abort(404, "Invalid Request");
        }
    }
}