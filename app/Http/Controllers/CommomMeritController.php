<?php

namespace App\Http\Controllers;

use App\Course;
use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\Caste;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\MeritMaster;
use App\Notifications\StudentAnyMail;
use App\Services\ReportingService;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Redirect;
use Session;
use Validator;

class CommomMeritController extends Controller
{
    public function index(Request $request)
    {
        // dd("ok");
        // dd($request->all());

        //
        $course_id             = $request->course_id ??0;
        $merit_master_id       = $request->merit_master_id??0;
        $application_no        = $request->application_no;
        $status                = $request->status;
        $tuee_rank             = $request->tuee_rank;
        $admission_category_id = $request->admission_category_id;
        $merit                 = $request->merit;
        $undertaking_status    = $request->undertaking_status;
        $payment_option        = $request->payment_option;
        $courses               = Course::withTrashed()->get();
        $admission_categories  = AdmissionCategory::where('status', 1)->get();
        $merit_lists           = MeritList::/* where('status','!=','4')-> */with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id","is_pwd");
                }, 'meritMaster', 'admissionCategory', 'course',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);
        if($course_id!=0){
            $course_id=[72,73,74,75,76,77,83];
        }else{
            $course_id=[0];
        }
        // if ($course_id) {
            $merit_lists->whereIn('course_id', $course_id);
        // }
        if (!$status) {
            $merit_lists->where('merit_master_id', $merit_master_id);
        }
        if ($application_no) {
            $merit_lists->where('application_no', $application_no);
        }
        if ($admission_category_id) {
            $merit_lists->where('admission_category_id', $admission_category_id);
        }
        if ($status) { 
            // dd($status);         
            if($status==2){
                $merit_lists->whereIn('status', [2/* ,14 */])/* ->where('may_slide','!=',3) */;
            }elseif($status==5){
                $merit_lists->where('is_hold', 1)->where('attendance_flag',1);
            }else{
                $merit_lists->where('status', $status);
            }
        }
        if (in_array($payment_option, [0,1]) && !is_null($payment_option)) {
            $merit_lists->where('is_payment_applicable', $payment_option);
        }
        if ($tuee_rank) {
            $merit_lists->orderBy('tuee_rank', $tuee_rank);
        }
        if ($merit) {
            if ($merit == "merit") {
                $merit_lists->where('selected_in_merit_list', 1);
            } elseif ($merit == "waiting") {
                $merit_lists->where('selected_in_merit_list', 0);
            }
        }
        if ($undertaking_status) {
            if ($undertaking_status == "pending") {
                $merit_lists->whereHas("active_undertaking");
            } elseif ($undertaking_status == "approved") {
                $merit_lists->whereHas("approved_undertaking");
            } elseif ($undertaking_status == "rejected") {
                $merit_lists->whereHas("rejected_undertaking");
                $merit_lists->whereDoesnthave("approved_undertaking");
                $merit_lists->whereDoesnthave("active_undertaking");
            } elseif ($undertaking_status == "not_uploaded") {
                $merit_lists->whereDoesnthave("undertakings");
            }
        }
        $sms_templates = config("vknrl.sms_templates");
        // dd($merit_lists->get());
        
        // return view('admin.merit.index', compact('courses', 'merit_lists', 'admission_categories', "sms_templates"));
        
        if(auth("admin")->check()){ 
            if($request->has("export-data")){
                // $applications = $application->get();
                return $this->ExportApplicationData($merit_lists, $request);
            }
            if ($status) {
                $merit_lists = $merit_lists->orderBy('admission_category_id')->orderBy('id')->paginate(200);
            }else{
                $merit_lists = $merit_lists->paginate(100);
            }          
            return view('admin.merit.admin-merit', compact('courses', 'merit_lists', 'admission_categories', "sms_templates"));  
        }


        if(auth("department_user")->check()){
            $program_array=programmes_array();
            $programs=[];
            foreach($program_array as $key=>$prog){
                 if($key!=""){
                    array_push($programs, $key);
                 }
            }
            // dd($programs);
            // if(checkPermission(2)==true){
            //     // admission Open Close Maintain
            //     $merit_lists->whereHas("meritMaster", function($query) use($merit_master_id) {
            //         return $query->where("allow_admission", 1);
            //     })->with(["meritMaster"=>function($q) use($merit_master_id){
            //         return $q->where("allow_admission", "1");
            //     }]);
            //     // ends here
            // }

            // if(checkPermission(3)==true){
            //     // payment Open Close Maintain
            //     $merit_lists->whereHas("meritMaster", function($query) use($merit_master_id) {
            //         return $query->where("allow_payment", 1);
            //     })->with(["meritMaster"=>function($q) use($merit_master_id){
            //         return $q->where("allow_payment", "1");
            //     }]);
            //     // ends here
            // }

            $merit_lists = $this->commonMeritFunction($request);
            
            if ($status) {
                $merit_lists = $merit_lists->whereIn('course_id',$programs)->where("attendance_flag",1)->orderBy('id')->orderBy('admission_category_id')->paginate(50);
            }else{
                $merit_lists = $merit_lists->whereIn('course_id',$programs)->where("attendance_flag",1)->orderBy('id')->paginate(50);
            }
            // dd($merit_lists);
            $branch=Course::where('program_id',7)->withTrashed()->get();
            $admission_categorymodal=AdmissionCategory::where('status',1)->get();
            // foreach($admission_category as $ac){
            //    dump($ac->id);
            // }
            // dd($admission_category);
            $list=MeritMaster::whereIn('course_id',$programs )->get();
            $merit_lists_filter = MeritList::whereIn('course_id',[72,73,74,75,76,77,83])->get();
            return view('admin.merit.department-merit', compact('list','merit_lists_filter','admission_categorymodal','branch','courses', 'merit_lists', 'admission_categories', "sms_templates"));
        }
    }

    public function declineSeatReceipt(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()
                ->with("error", "Whoops! something went wrong. Try again later.");
        }

        $merit_list  = MeritList::findOrFail($decrypted_id);
        $application = $merit_list->application;
        return view("student.admission.seat-decline-recept",compact('merit_list','application'));
    }
    
    public function approve(Request $request)
    {
        // dd($request->all());
        // if()
        // $course_id=MeritList::where('id',$request->merit_list_id[0])->first()->course_id;
        // if($course_id==83){
        //    return redirect()->back()->with('error','Assign the admission category & branch.');
        // }
        DB::beginTransaction();
        if ($request->input('submit') == "Approve") {
            // $validator = Validator::make($request->all(), [
            //     // 'valid_from' => 'required',
            //     // 'valid_to'   => 'required',
            //     // "hour"       => "sometimes|numeric|min:0|max:500"
            // ]);
            // if ($validator->fails()) {
            //     return Redirect::back()->withErrors($validator)->withInput($request->all());
            // }

            $reporting_service = new ReportingService($request->merit_list_id);
            $check_if_true = $reporting_service->checkValidation();
            // $check_seat_available = $reporting_service->checkAvailableSeat();
            $check_pwd_seat = $reporting_service->checkForPwdCandidate();
            try {
                
                // if($check_seat_available == false){
                //     Session::flash('error', "Seat Quota exceeded ! Please set another admission category");
                //     return Redirect::back()->withInput($request->all());
                // }
                
                if($check_pwd_seat != false){
                    Session::flash('error', "Please fill the pwd seat first");
                    return Redirect::back()->with('reg_id',$check_pwd_seat);
                }
                if($check_if_true){
                    foreach ($request->merit_list_id as $key => $value) {
                        if(checkAvailableSeatNew($value)==false){
                            return redirect()->back()->with('error', 'Seat Quota exceeded ! Please set another admission category');
                        }
                        // $merit_list=MeritList::where('id',$value)->first();//new
                        // $course_seat=$merit_list->course_seat();
                        // $course_seat->increment('temp_seat_applied');//new
                        $update_data = [
                            'status' => 1, 
                            'valid_from' => date('Y-m-d H:i:s'), 
                            'valid_till' => date('Y-m-d H:i:s', strtotime(' +1 day')),
                            'reported_at' => null,
                        ];
                        // dd($update_data);
                        // if($request->has("hour")){
                            $update_data["expiry_hour"] = 24;
                        // }
                        // dd($update_data);
                        MeritList::where([['id', $value], ['status', '!=', 2]])
                            ->update($update_data);
                    }
                    $all_mmerit_list_students = MeritList::whereIn('id', $request->merit_list_id)
                        ->with(["application.student"])
                        ->get();
                    // if ($all_mmerit_list_students->count()) {
                    //     foreach ($all_mmerit_list_students as $merit_list) {
                    //         // $sms = "Your application is approved for reporting. You are requested to report for counseling by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)).".";
                    //         // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
                    //         // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
                    //         try {
                    //             $sms="";
                    //             // sendSMS($merit_list->application->student->mobile_no, $sms, "1207162521403708453");
                    //             $merit_list->application->student->notify(new StudentAnyMail($sms));
                    //         } catch (\Throwable $th) {
                    //             Log::error($th);
                    //         }
                    //     }
                    // }
                }
                else{
                    Session::flash('error', "Please Follow the Queue or put in hold prevous candidate.");
                    return Redirect::back()->withInput($request->all());
                }
                
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                Session::flash('error', $e->getMessage());
                return Redirect::back()->withInput($request->all());
            }
            Session::flash('success', 'Approved Successfully');
        }elseif ($request->input('submit') == "Approve for payment") {
            // $validator = Validator::make($request->all(), [
            //     // 'approve_valid_from' => 'required',
            //     // 'approve_valid_to'   => 'required',
            // ]);
            // if ($validator->fails()) {
            //     return Redirect::back()->withErrors($validator)->withInput($request->all());
            // }
            try {
                foreach ($request->merit_list_id as $key => $value) {
                    $meritlist = MeritList::find($value);
                    if($meritlist->status != 8 ){
                        Session::flash('error', "Please approve for reporting first");
                        return Redirect::back()->withInput($request->all());
                    }
                    $update_data = [
                        // 'status' => 1, 
                        'valid_from' => date('Y-m-d H:i:s'), 
                        'valid_till' => date('Y-m-d H:i:s', strtotime(' +1 day')),
                        'is_payment_applicable' => 1
                    ];
                    // dd($update_data);
                    MeritList::where([['id', $value], ['status', '!=', 2]])
                        ->update($update_data);
                }
                $all_mmerit_list_students = MeritList::whereIn('id', $request->merit_list_id)
                    ->with(["application.student"])
                    ->get();
                // if ($all_mmerit_list_students->count()) {
                //     foreach ($all_mmerit_list_students as $merit_list) {
                //         // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
                //         try {
                //             $sms="";
                //             // $sms = "You application is approved for payment. Please make the admission payment before " . date("d-m-Y h:i a", strtotime($merit_list->valid_till))." to confirm your provisional seat booking.";
                //             // sendSMS($merit_list->application->student->mobile_no, $sms, "1207162521403708453");
                //             // $merit_list->application->student->notify(new StudentAnyMail($sms));
                //         } catch (\Throwable $th) {
                //             Log::error($th);
                //         }
                //     }
                // }
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                Session::flash('error', $e->getMessage());
                return Redirect::back()->withInput($request->all());
            }
            Session::flash('success', 'Approved for payment successfully.');
        } else if ($request->input('submit') == "Decline for Admission") {
            try {
                foreach ($request->merit_list_id as $key => $value) {
                    MeritList::where([['id', $value], ['status', '!=', 2]])->update(['status' => 4]);
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                Session::flash('error', $e->getMessage());
                return Redirect::back()->withInput($request->all());
            }
            Session::flash('success', 'Declined Successfully');
        }
        else if ($request->input('submit') == "Hold Seat") {
            try {
                foreach ($request->merit_list_id as $key => $value) {
                    
                    // dump($course_seat->total_seats);
                    // dump($course_seat->temp_seat_applied + $course_seat->total_seats_applied);
                    // dd($course_seat);
                    
                    $merit_list=MeritList::where('id',$value)->first();//new
                    if($merit_list->is_hold==1){
                        return redirect()->back()->with('error','Seat is already on hold..');
                    } 
                    if(checkAvailableSeatNew($value)==false){
                        return redirect()->back()->with('error', 'Seat Quota exceeded ! Please set another admission category');
                     }                  
                    $course_seat=$merit_list->course_seat();
                    $course_seat->increment('temp_seat_applied');//new
                    MeritList::where([['id', $value], ['status', '!=', 2]])->update(['is_hold' => 1]);
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                Session::flash('error', $e->getMessage());
                return Redirect::back()->withInput($request->all());
            }
            Session::flash('success', 'Hold Successfully');
        }
        else if ($request->input('submit') == "Unhold Seat") {

            try {
                foreach ($request->merit_list_id as $key => $value) {
                    $merit_list=MeritList::where('id',$value)->first();//new
                    if($merit_list->is_hold==1){
                        $merit_list->course_seat()->decrement('temp_seat_applied');//new
                        MeritList::where([['id', $value], ['status', '!=', 2]])->update(['is_hold' => 0]);
                    }else{
                        return redirect()->back()->with('error','Seat is not on hold..');
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                Session::flash('error', $e->getMessage());
                return Redirect::back()->withInput($request->all());
            }
            Session::flash('success', 'Unhold Successfully');
        }
        else if ($request->input('submit') == "Send SMS") {
            return $this->sendSMS($request);

        }

        DB::commit();
        return Redirect::back();
    }

    public function setAdmissionCategory(Request  $request){
        $validator = Validator::make($request->all(), [
            'course_id'            => 'required|numeric|exists:courses,id',
            'admission_category_id' => 'required|integer|exists:admission_categories,id'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        }
        // $check_general_seat = $this->checkGeneralCategory($request->course_id);
        // if($check_general_seat){
        //     return Redirect::back()->with('error','Please complete general category first');
        // }
        $course_seat =  CourseSeat::where('course_id',$request->course_id)
        ->where('admission_category_id',$request->admission_category_id)
        ->where('session_year',date('Y'))
        ->first();
        if($course_seat){
            if($course_seat->total_seats_applied >= $course_seat->total_seats){
                return Redirect::back()->with('error','Quota exceeded for admission');
            }
            try{
                $all_course_seat =  CourseSeat::where('course_id',$request->course_id)->where('session_year',date('Y'))->update(['is_selection_active'=>'0']);
                $course_seat->is_selection_active = 1;
                $course_seat->save();
                return Redirect::back()->with('success','Admission Category Set');
            }
            catch(\Exception $e){
                return Redirect::back()->with('error',$e->getMessage());
            }
           
        }
    }

    public function checkGeneralCategory($course_id){
        $course_seat =  CourseSeat::where('course_id',$course_id)
        ->where('admission_category_id',1)
        ->where('session_year',date('Y'))
        ->first();
        if($course_seat->total_seats_applied >= $course_seat->total_seats)
            return false;
        else
            return true;

    }


    public function ExportApplicationData($merit_list, $request)
    {
        // dd($merit_list->get());
        $castes = Caste::pluck("name","id")->toArray();
        $excel    = $merit_list->orderBy('admission_category_id')->get();
        $fileName = $merit_list->first()->course->name.'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        );

        $columns = array('SL', 
                         'Student Id', 
                         'Application No', 
                         'Program Name', 
                         'Student Name', 
                         'Admission category',
                         'pwd', 
                         'Social category', 
                         'Gender', 
                         'Rank', 
                         'Hostel');
        
        $callback = function () use ($excel, $columns,$castes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $count = 0;
            foreach ($excel as $key=>$task) {
                $row['SL']               = ++$key;
                $row['Student Id']       = $task->student_id;
                $row['Application No']   = $task->application_no;
                $row['Program Name']     = $task->course->name;
                $row['Student Name']     = $task->application->first_name ?? "NA".
                                            $task->application->middle_name ?? "NA".
                                            $task->application->last_name ?? "NA";
                $row['Admission category'] = $task->admissionCategory->name ?? "NA";
                $row['pwd']                = $task->is_pwd==1 ? "PWD" : 'NA';
                $row['Social category']    = $castes[$task->application->caste_id];
                $row['Gender']             = $task->gender;
                $row['Rank']               = $task->student_rank;
                $row['Hostel']             = $task->hostel_required ? "Required" : "Not Required";

                fputcsv($file, array(
                                                $row['SL'],            
                                                $row['Student Id'],
                                                $row['Application No'],
                                                $row['Program Name'],     
                                                $row['Student Name'],                              
                                                $row['Admission category'],
                                                $row['pwd'],                
                                                $row['Social category'],   
                                                $row['Gender'],            
                                                $row['Rank'],              
                                                $row['Hostel']          
                        ));
            }
            fclose($file);
            // dd($file);
        };
        // dd("ok");
        return response()->stream($callback, 200, $headers);
    }
}
