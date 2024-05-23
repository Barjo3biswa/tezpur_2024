<?php

namespace App\Http\Controllers\Admin;

use App\AppliedCourse;
use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmitCard;
use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Models\Caste;
use App\Models\ExamCenter;
use App\Models\Program;
use App\Models\Session;
use App\Models\User;
use App\Notifications\sendEmailToAGroup;
use App\Notifications\sendToZZZ;
use App\ZzzMailControl;
use App\ZzzMailSendTo;
use Crypt;
use DB;
use Exception;
use Log;
use PDF;
use ZipArchive;

class AdmitCardControllerNew extends Controller
{
   public function index(Request $request)
   {
        $limit          = 20;
        $castes         = Caste::all();
        $admit_cards = AdmitCard::query();
        $exam_centers = ExamCenter::all();
        if($request->exam_center){
            $admit_cards=$admit_cards->where('exam_center_id',$request->exam_center);
        }
        if($request->program){
            $admit_cards=$admit_cards->where('course_id',$request->program);
        }
        if($request->status){
            $admit_cards=$admit_cards->where('publish',$request->status);
        }
        if($request->student_id){
            $admit_cards=$admit_cards->where('student_id',$request->student_id);
        }
        $count=$admit_cards->count();
        $admit_cards=$admit_cards->paginate($limit);
        return view("admin.admit_card_new.index",compact('castes','exam_centers','admit_cards','count'));
   }



//    public function generateAdmitCard2023bkup()
//    {
//        set_time_limit(0);
//        $date=[
//            'one'   => '11-06-2024',
//            'two'   => '11-06-2024',
//            'three' => '11-06-2024',
//            'four'  => '12-06-2024',
//            'five'  => '12-06-2024',
//            'six'   => '12-06-2024',
//            'seven' => '13-06-2024',
//            'eight' => '13-06-2024',
//            'nine'  => '13-06-2024',
//        ];

//        $shift=[
//            'one'   => '9:00 AM 10:30 AM',
//            'two'   => '12:00 PM 1:30 PM',
//            'three' => '3:00 PM 4:30 PM',
//            'four'  => '9:00 AM 10:30 AM',
//            'five'  => '10 AM to 12 Noon',
//            'six'   => '3:00 PM 4:30 PM',
//            'seven' => '9:00 AM 10:30 AM',
//            'eight' => '12:00 PM 1:30 PM',
//            'nine'  => '3:00 PM 4:30 PM',
//        ];
       

       
//            $active_session = Session::where('is_active',1)->first()->id;
//            $exam_centers= ExamCenter::with(['applied_courses' => function ($query) use ($active_session) {
//                                $query->where('session_id', '=', $active_session)->where('is_mba',0)->where('is_btech',0)->where('is_direct',0)->whereNotNull('application_no')
//                                        ->WhereDoesntHave('admitcard')->orderby('first_name')->orderby('middle_name')->orderby('last_name');
//                            }])->orderBy('center_name')->get(); 
//            foreach($exam_centers as $exam){
//                $center_code=$exam->center_code;
//                $center_id = $exam->id;
//                foreach($exam->applied_courses as $applied){
//                    if($applied->status!='rejected'){
//                        $group = $applied->course->group;
//                        $prefix=null;
//                        if(in_array($applied->course_id,[22,28])){
//                            $last_rollNo=AdmitCard::where('exam_center_id',$center_id)->whereIn('course_id',[22,28])->count();
//                        }else{
//                            $last_rollNo=AdmitCard::where(['exam_center_id'=>$center_id,'course_id'=>$applied->course_id])->count();
//                        }            
//                        DB::beginTransaction();
//                        try{         
//                            $course_code = $applied->course->code;
//                            if(in_array($course_code,['322A','320A','318A'])){
//                                if($course_code=='322A'){
//                                    $course_code=322;
//                                    $prefix=9;
//                                }elseif($course_code=='320A'){
//                                    $course_code=320;
//                                    $prefix=8;
//                                }elseif($course_code=='318A'){
//                                    $course_code=318;
//                                    $prefix=7;
//                                }
//                                $roll_no = sprintf("%03d", $last_rollNo + 1);
//                                $formated_roll_no = $course_code.$center_code.$prefix.$roll_no;
//                            }else{
//                                $roll_no = sprintf("%04d", $last_rollNo + 1);
//                                $formated_roll_no = $course_code.$center_code.$prefix.$roll_no;
//                            }

//                            $data=[
//                                    'applied_course_id'=> $applied->id,
//                                    'application_id'   => $applied->application_id,
//                                    'student_id'       => $applied->student_id,
//                                    'roll_no'          => $formated_roll_no,
//                                    'roll_no_uf'       => $last_rollNo,
//                                    'course_id'        => $applied->course_id,
//                                    'exam_center_id'   => $exam->id,	
//                                    'exam_time'        => $shift[$group],
//                                    'exam_date'        => $date[$group],
//                                    'session'          => $active_session,
//                            ];
//                            AdmitCard::create($data);   
//                            DB::commit();
//                        }catch(\Exception $e){
//                            DB::rollBack();
//                            // dd($applied);
//                            dd($e);
//                        }  
//                    }              
//                }
//            }              

        
       
//        return redirect()->back()->with('success','Successfully Generatyed');
//   }

//     public function generateAdmitCardwithoutSubcenter2024()
//     {
        
//         set_time_limit(0);
//         $date=[
//             'one'   => '11-06-2024',
//             'two'   => '11-06-2024',
//             'three' => '11-06-2024',
//             'four'  => '12-06-2024',
//             'five'  => '12-06-2024',
//             'six'   => '12-06-2024',
//             'seven' => '13-06-2024',
//             'eight' => '13-06-2024',
//             'nine'  => '13-06-2024',
//         ];

//         $shift=[
//             'one'   => '9:00 AM 10:30 AM',
//             'two'   => '12:00 PM 1:30 PM',
//             'three' => '3:00 PM 4:30 PM',
//             'four'  => '9:00 AM 10:30 AM',
//             'five'  => '10 AM to 12 Noon',
//             'six'   => '3:00 PM 4:30 PM',
//             'seven' => '9:00 AM 10:30 AM',
//             'eight' => '12:00 PM 1:30 PM',
//             'nine'  => '3:00 PM 4:30 PM',
//         ];
        
//         $active_session = Session::where('is_active',1)->first()->id;
//         $exam_centers= ExamCenter::with(['applied_courses' => function ($query) use ($active_session) {
//                                            return $query->where('session_id', '=', $active_session)
//                                             ->where('is_mba',0)->where('is_btech',0)/* ->where('is_direct',0) */
//                                             ->whereNotNull('application_no')
//                                             ->WhereDoesntHave('admitcard')
//                                             ->where('net_jrf','!=',1)
//                                             ->orderby('first_name')->orderby('middle_name')->orderby('last_name');
//                                        }])/* ->where('id',9) */->orderBy('center_name')->get(); 
//         // dd($exam_centers);
//         // dd($exam_centers);  SELECT  count(*),course_id, exam_center_id   FROM `admit_cards` GROUP by course_id, exam_center_id having exam_center_id=1 order by count(*) DESC, course_id
//         foreach($exam_centers as $exam){
//             $center_code=$exam->center_code;
//             $center_id = $exam->id;
//             foreach($exam->applied_courses as $applied){
//                 if($applied->status!='rejected'){
//                     // dump($applied);
//                     $group = $applied->course->exam_group;
//                     $prefix=null;
//                     $last_rollNo=AdmitCard::where(['exam_center_id'=>$center_id,'course_id'=>$applied->course_id])->count();    
//                     DB::beginTransaction();
//                     try{      
//                         //Roll no should have: Centre code/school code/ dept. code/subject code/number (begin from 001)   
//                         // dump($applied->student_id);
//                         $course_code = $applied->course->code;
//                         $department_code = $applied->course->department->code;
//                         $school_code = $applied->course->department->school->code;
//                         $roll_no = sprintf("%03d", $last_rollNo + 1);
//                         $formated_roll_no = $center_code.$school_code.$department_code.$course_code.$roll_no;
//                         $data=[
//                                 'applied_course_id'=> $applied->id,
//                                 'application_id'   => $applied->application_id,
//                                 'student_id'       => $applied->student_id,
//                                 'roll_no'          => $formated_roll_no,
//                                 'roll_no_uf'       => $last_rollNo,
//                                 'course_id'        => $applied->course_id,
//                                 'exam_center_id'   => $exam->id,	
//                                 'exam_time'        => $shift[$group],
//                                 'exam_date'        => $date[$group],
//                                 'session'          => $active_session,
//                         ];
//                         AdmitCard::create($data);   
//                         DB::commit();
//                     }catch(\Exception $e){
//                         DB::rollBack();
//                         dd($e);
//                     }  
//                 }              
//             }
//         }
//         return redirect()->back()->with('success','Successfully Generatyed');
//    }


   public function generateAdmitCard()
    {
        // update `sub_exam_centers` set one=0, two=0, three=0, four=0, five=0, six=0, seven=0, eight=0, nine=0
        set_time_limit(0);
        $date=[
            'one'   => '11-06-2024',
            'two'   => '11-06-2024',
            'three' => '11-06-2024',
            'four'  => '12-06-2024',
            'five'  => '12-06-2024',
            'six'   => '12-06-2024',
            'seven' => '13-06-2024',
            'eight' => '13-06-2024',
            'nine'  => '13-06-2024',
        ];

        $shift=[
            'one'   => '9:00 AM 10:30 AM',
            'two'   => '12:00 PM 1:30 PM',
            'three' => '3:00 PM 4:30 PM',
            'four'  => '9:00 AM 10:30 AM',
            'five'  => '10 AM to 12 Noon',
            'six'   => '3:00 PM 4:30 PM',
            'seven' => '9:00 AM 10:30 AM',
            'eight' => '12:00 PM 1:30 PM',
            'nine'  => '3:00 PM 4:30 PM',
        ];
        
        $active_session = Session::where('is_active',1)->first()->id;
        $exam_centers= ExamCenter::with(['applied_courses' => function ($query) use ($active_session) {
                                           return $query->where('session_id', '=', $active_session)
                                            ->where('is_mba',0)->where('is_btech',0)/* ->where('is_direct',0) */
                                            ->whereNotNull('application_no')
                                            ->WhereDoesntHave('admitcard')
                                            ->where('net_jrf','!=',1)
                                            ->orderby('first_name')->orderby('middle_name')->orderby('last_name');
                                       }])/* ->where('id',9) */->orderBy('center_name')->get(); 
        // dd($exam_centers);
        // dd($exam_centers);  SELECT  count(*),course_id, exam_center_id   FROM `admit_cards` GROUP by course_id, exam_center_id having exam_center_id=1 order by count(*) DESC, course_id
        foreach($exam_centers as $exam){
            $center_code=$exam->center_code;
            $center_id = $exam->id;
            foreach($exam->applied_courses as $applied){
                if($applied->status!='rejected'){
                    // dump($applied);
                    $group = $applied->course->exam_group;
                    $prefix=null;
                    $last_rollNo=AdmitCard::where(['exam_center_id'=>$center_id,'course_id'=>$applied->course_id])->count();   
                    DB::beginTransaction();
                    try{      
                        //distribute to Sub center
                        $sub_center_id = null;
                        $total_student_this_group = $exam->applied_courses->filter(function ($course) use ($group) {
                            return $course->course->exam_group === $group;
                        })->count();
                        // dump('total_student_this_group: '.$total_student_this_group); 

                        foreach($exam->subExamCenter as $sub_centers){
                            $total_capacity = $exam->subExamCenter->sum('capacity'); 
                            // dump('total_capacity: '.$total_capacity);
                            $percentage_of_distribution =  ceil(($total_student_this_group/$total_capacity)*100);
                            $to_filled_out = ceil(($percentage_of_distribution*$sub_centers->capacity)/100);
                            // dump('percentage_of_distribution: '.$percentage_of_distribution);
                            // dump('to_filled_out '.$to_filled_out);
                            // dump($sub_centers->$group);
                            if(/* $sub_centers->capacity */$to_filled_out > $sub_centers->$group){
                                $sub_center_id = $sub_centers->id;
                                $sub_centers->increment($group);
                                break;
                            }
                        }
                        if($sub_center_id == null){ 
                            dump('total_student_this_group: '.$total_student_this_group); 
                            dump('total_capacity: '.$total_capacity);
                            dump('percentage_of_distribution: '.$percentage_of_distribution);
                            dump('to_filled_out '.$to_filled_out);
                            dump($sub_centers->$group);
                            dump($group);
                            dump($sub_centers->capacity);
                            dump($sub_centers->$group);
                            dd($applied->student_id);
                        }
                        //distribution ends
                        $course_code = $applied->course->code;
                        $department_code = $applied->course->department->code;
                        $school_code = $applied->course->department->school->code;
                        $roll_no = sprintf("%03d", $last_rollNo + 1);
                        $formated_roll_no = $center_code.$school_code.$department_code.$course_code.$roll_no;
                        $data=[
                                'applied_course_id'=> $applied->id,
                                'application_id'   => $applied->application_id,
                                'student_id'       => $applied->student_id,
                                'roll_no'          => $formated_roll_no,
                                'roll_no_uf'       => $last_rollNo,
                                'course_id'        => $applied->course_id,
                                'exam_center_id'   => $exam->id,	
                                'sub_exam_center_id' => $sub_center_id,
                                'exam_time'        => $shift[$group],
                                'exam_date'        => $date[$group],
                                'session'          => $active_session,
                        ];
                        AdmitCard::create($data);   
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollBack();
                        dd($e);
                    }  
                }              
            }
        }
        return redirect()->back()->with('success','Successfully Generatyed');
   }



   public function viewAdmitCard($id)
   {
        try {
            $decrypted_id = Crypt::decrypt($id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.admit-card.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {      
            $admit_card = AdmitCard::with(["application.caste","application.attachments", "exam_center","applied_course_details"])->findOrFail($decrypted_id);
            // dd($admit_card->course->ExamGroup);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong please try again later .");
        }
        return view("admin.admit_card_new.show", compact("admit_card"));
   }

//    public function downloadPdfAdmin(Request $request, $encrypted_id) {
//         // dd("ok");
//         // dd($encrypted_id);
//         try {
//             $decrypted_id = Crypt::decrypt($encrypted_id);
//             // dd($decrypted_id);
//         } catch (Exception $e) {
//             Log::error($e);
//             return redirect()->route("admin.admit-card.index")
//                     ->with("error", "Whoops! Something went wrong please try again later.");
//         }
//         try {
//             $admit_card = AdmitCard::with(["active_application.caste","active_application.attachments", "exam_center"])->findOrFail($decrypted_id);
//             // if(!$admit_card->publish){
//             //    return  redirect()->back()->with("error", "Admit card is not published yet.");
//             // }
//         } catch (Exception $e) {
//             // dd($e);
//             Log::error($e);
//             return redirect()
//                 ->route("admin.admit-card.index")
//                 ->with("error", "Whoops! Something went wrong please try again later.");
//         }

//         if (auth("student")->check()) {
//             AdmitCard::where('id',$decrypted_id)->update(['is_downloaded'=>1]);
//         }
//         // dd($decrypted_id);
//         // return view("common/application/admit_card/admit_card_download", compact("admit_card"));
//         saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
//         $pdf = PDF::loadView("common/application/admit_card/admit_card_download", compact("admit_card"));
//         $pdf->setPaper('legal', 'portrait');
//         return $pdf->download("Admit-card-".$admit_card->application->id.'.pdf');
//     }

    public function publishAdmitCard(Request $request)
    {
        dd("ok");
        if($request->exam_center==null){
            return redirect()->back()->with('error','You have to choose examination center.');
        }
        DB::beginTransaction();
        try{
            
            if($request->exam_center!=null && $request->program!=null){
                Admitcard::where(['exam_center_id'=>$request->exam_center,'course_id'=>$request->program,'publish'=>0])->update(['publish'=>1]);
            }elseif($request->student_id!=null){
                Admitcard::where(['exam_center_id'=>$request->exam_center,'student_id'=>$request->student_id,'publish'=>0])->update(['publish'=>1]);
            }
            else{
                Admitcard::where(['exam_center_id'=>$request->exam_center,'publish'=>0])->update(['publish'=>1]);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Published All Successfully');
        }
        
        return redirect()->back()->with('success','Published All Successfully');
    }

    public function OpenClose()
    {
        $programs=Program::withTrashed()->get();
        return view("open-close-a-program",compact('programs'));
    }

    public function OpenCloseSave($id)
    {
        $decrypted=Crypt::decrypt($id);
        return redirect()->back()->with('error','Denied!!');
        Program::where('id',$decrypted)->delete();
        $Course=Course::where('program_id',$decrypted)->withTrashed()->delete();
        return redirect()->back()->with('success','Done');
       
    }

    public function removeDuplicateAttachments()
    {
        $attachments_cnt = ApplicationAttachment::withTrashed()->whereNotNull('deleted_at')->where('is_removed',0)->count();
        return view('remove-duplicate-attachment',compact('attachments_cnt'));
    }

    public function removeDuplicateAttachmentsDelete()
    {
        set_time_limit(0);
        $attachments = ApplicationAttachment::withTrashed()->with('application')
                                            ->whereNotNull('deleted_at')->where('is_removed',0)->get()/* ->take(5000) */;
        foreach($attachments as $attach){
            
            if($attach->application){
                //$path=public_path('uploads/' . $attach->application->student_id . "/" . $attach->application_id."/".$attach->file_name);
                $path = $attach->destination_path."/".$attach->file_name;
                if(file_exists($path)){
                    unlink($path);
                }
                ApplicationAttachment::withTrashed()->where('id',$attach->id)->update(['is_removed'=>1]);
            }
        }
        return redirect()->back()->with('success','successfully Deleted');
    }

    public function SendMail(Request $request)
    {
        // dd("ok");
        $sendable_mail=ZzzMailSendTo::where('is_send',0)->count();
        if($request->has('count')){
            // if($sendable_mail < $request->count){
            //     return redirect()->back()->with('error','Enter less then or equal to total number');
            // }
           
            // $mail_control=ZzzMailControl::first();
            // $message = $mail_control->message;
            $sendable_person = ZzzMailSendTo::where('is_send',0)->get()/* ->take($request->count) */;
            
            foreach($sendable_person as $mail){
                // if($mail->application_no!=null){
                    $message=$mail->message;
                // }
                $cc=json_encode($mail->cc);
                $student_id = $mail->student_id;
                $user = User::where('id',$student_id)->first();
                
                $user->notify(new sendToZZZ($user, $message, $cc));
                ZzzMailSendTo::where('id',$mail->id)->update(['is_send'=>1]);
            }
            // dd("ok");
        }
        return view('admin.send_mail_to_students',compact('sendable_mail'));
    }

    public function MCJFIX()
    {
        dd("Closed");
        $delete_dup=DB::table('vw_dulicate_mass')->get();
        foreach($delete_dup as $dup){
            $count=AppliedCourse::where('application_id',$dup->application_id)->count();
            if($count>1){
                $applied_course=AppliedCourse::where('application_id',$dup->application_id)->first();
                AppliedCourse::where('id',$applied_course->id)->delete();
                DB::table('ZZZ_deleted_mcj_ids')->insert(['applied_course_id'=>$applied_course->id]);
            }
        }
       
    }

    public function exportOthers(){
        //http://127.0.0.1:8000/admin/export_others
        //https://www.tezuadmissions.in/public/admin/export_others
        $application=Application::where([
                                    'is_phd'=>1,
                                    // 'application_no'!=null,
                                    'session_id' =>11,
                                ])->whereNotNull('application_no')->get();
        // dd($application);
        try{
            foreach($application as $app){
                $qualifications=[];
                foreach($app->other_qualifications as $key=>$oth){
                    $qualification = [
                        'exam_' . ($key + 1) => [
                            'exam_name' => $oth->exam_name,
                            'marks' => $oth->marks_percentage
                        ]
                    ];
                    array_push($qualifications, $qualification);
                }
                // dump(json_encode($qualifications));
                $data=[
                'student_id'     =>	$app->student_id,
                'application_id' => $app->id,	
                'application_no' =>	$app->application_no,
                'qualification' =>json_encode($qualifications)
                ];

                DB::table('zzz_other_qualifications')->insert($data);
                //  dump($data);
            }
        }catch(\Exception $ex){
            dd($ex);
        }
        
        dd("ok");
    }

    public function downloadZip() {
        $admit_cards = AdmitCard::get();
        $zip = new ZipArchive();
        $zipFileName = 'admit_cards.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);
        dd($zipFilePath);
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($admit_cards as $card) {
                $photo = $card->roll_no . '_p.jpg';
                $sigg = $card->roll_no . '_s.jpg';
                $photoPath = $card->application->passport_photo()->destination_path . '/' . $card->application->passport_photo()->file_name;
                $sigPath = $card->application->signature()->destination_path . '/' . $card->application->signature()->file_name;
    
                if (file_exists($photoPath) && is_readable($photoPath)) {
                    $zip->addFile($photoPath, 'photos/' . $photo);
                }
                if (file_exists($sigPath) && is_readable($sigPath)) {
                    $zip->addFile($sigPath, 'signatures/' . $sigg);
                }
            }
            $zip->close();
    
            ob_end_clean(); // Clear output buffer to avoid corruption
    
            return response()->download($zipFilePath, $zipFileName, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
            ])->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Unable to create zip file'], 500);
        }
    }
    
    
}
