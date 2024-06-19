<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseSeatTypeMaster;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\MeritMaster;
use App\Notifications\StudentAnyMail;
use App\Services\MeritListService;
use Carbon\Carbon;
use Config;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\CommomMeritController;
use App\Models\AdmitCard;
use App\Traits\SeatSlideControll;
use Redirect;
use Session;
use Validator;

class MeritController extends CommomMeritController
{
    use  SeatSlideControll;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {

    //     // dd("ok");
    //     //
    //     $course_id             = $request->course_id;
    //     $merit_master_id       = $request->merit_master_id;
    //     $application_no        = $request->application_no;
    //     $status                = $request->status;
    //     $tuee_rank             = $request->tuee_rank;
    //     $admission_category_id = $request->admission_category_id;
    //     $merit                 = $request->merit;
    //     $undertaking_status    = $request->undertaking_status;
    //     $payment_option        = $request->payment_option;
    //     $courses               = Course::withTrashed()->get();
    //     $admission_categories  = AdmissionCategory::where('status', 1)->get();
    //     $merit_lists           = MeritList::/* where('status','!=','4')-> */with(
    //         [
    //             'application' => function ($query) {
    //                 $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id");
    //             }, 'meritMaster', 'admissionCategory', 'course',
    //             "undertakings",
    //         ])
    //         ->withCount(["admissionReceipt"]);
    //     if ($course_id) {
    //         $merit_lists->where('course_id', $course_id);
    //     }
    //     if ($merit_master_id) {
    //         $merit_lists->where('merit_master_id', $merit_master_id);
    //     }
    //     if ($application_no) {
    //         $merit_lists->where('application_no', $application_no);
    //     }
    //     if ($admission_category_id) {
    //         $merit_lists->where('admission_category_id', $admission_category_id);
    //     }
    //     if ($status) {
    //         $merit_lists->where('status', $status);
    //     }
    //     if (in_array($payment_option, [0,1]) && !is_null($payment_option)) {
    //         $merit_lists->where('is_payment_applicable', $payment_option);
    //     }
    //     if ($tuee_rank) {
    //         $merit_lists->orderBy('tuee_rank', $tuee_rank);
    //     }
    //     if ($merit) {
    //         if ($merit == "merit") {
    //             $merit_lists->where('selected_in_merit_list', 1);
    //         } elseif ($merit == "waiting") {
    //             $merit_lists->where('selected_in_merit_list', 0);
    //         }
    //     }
    //     if ($undertaking_status) {
    //         if ($undertaking_status == "pending") {
    //             $merit_lists->whereHas("active_undertaking");
    //         } elseif ($undertaking_status == "approved") {
    //             $merit_lists->whereHas("approved_undertaking");
    //         } elseif ($undertaking_status == "rejected") {
    //             $merit_lists->whereHas("rejected_undertaking");
    //             $merit_lists->whereDoesnthave("approved_undertaking");
    //             $merit_lists->whereDoesnthave("active_undertaking");
    //         } elseif ($undertaking_status == "not_uploaded") {
    //             $merit_lists->whereDoesnthave("undertakings");
    //         }
    //     }
    //     $sms_templates = config("vknrl.sms_templates");
    //     $merit_lists = $merit_lists->paginate(60);
    //     return view('admin.merit.index', compact('courses', 'merit_lists', 'admission_categories', "sms_templates"));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    

    
    public function create()
    {
        //
        $courses = Course::withTrashed()->get();
        $CourseSeatTypeMaster = CourseSeatTypeMaster::get();
        // dd($CourseSeatTypeMaster);
        return view('admin.merit.create', compact('courses','CourseSeatTypeMaster'));
    }

    public function meritMaster(Request $request)
    {
        //
        $courses              = MeritMaster::where('course_id', $request->course_id)->get();
        $course_id            = $request->course_id;
        $admission_categories = AdmissionCategory::with(['CourseSeats' => function ($query) use ($course_id) {
            $query->where('course_id', $course_id);
        }])->where('status', 1)->get();
        return response()->json(['success' => true, 'data' => $courses, 'admission_categories' => $admission_categories]);

    }

    public function meritMasterNew(Request $request)
    {
        //
        $courses              = MeritMaster::where('id', $request->merit_master)->first();
        $course_id            = $courses->course_id;
        $course_seat_type_id = $courses->course_seat_type_id;
        $admission_categories = AdmissionCategory::with(['CourseSeats' => function ($query) use ($course_id, $course_seat_type_id) {
            $query->where('course_id', $course_id)->where('course_seat_type_id', $course_seat_type_id);
        }])->where('status', 1)->get();
        return response()->json(['success' => true, 'data' => $courses, 'admission_categories' => $admission_categories]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'                 => 'required|max:255',
            'merit_list'           => 'required',
            'course_id'            => 'required|numeric|exists:courses,id',
            'merit_list'           => 'required|mimes:xls,xlsx',
            'date_from'            => 'required|date|date_format:Y-m-d H:i:s',
            'days'                 => 'required|integer|min:1|max:365',
            'processing_technique' => 'required|in:' . implode(",", MeritMaster::processging_status()),
            'admission_technique'  => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        }
        // dd("oj");
        // \DB::listen(function($query){
        //     // dump($query->sql);
        //     dumpQuery($query);
        // });
        // dd("ok");
        $session_year = Config::get('constant.session_year');
        if ($request->hasFile('merit_list')) {
            try {
                
                $merit_list_data = collect();
                $path            = $request->file('merit_list')->getRealPath();
                // dd($path);
                $data            = Excel::load($path)->get();
                // dd($data);
                
                if ($data->count()) {
                    // valiation excel data
                    // new validation logic implimented based on excel sheet data
                    // dd($data->toArray());
                    $validator = Validator::make($data->toArray(), MeritList::getMeritListRules());
                    if ($validator->fails()) {
                        // dd("ok");
                        dd($validator->errors()->all());
                        return redirect()
                            ->back()
                            ->withErrors($validator->errors());
                    }
                    // dd($data);
                    DB::beginTransaction();
                    $message                        = [];
                    $master                         = [];
                    $master['name']                 = $request->name;
                    $master['course_seat_type_id']     = $request->course_seat_type;
                    $master['session_year']         = $session_year;
                    $master['course_id']            = $request->course_id;
                    $master['initial_opening_date'] = $request->date_from;
                    $master['closing_after_days']   = $request->days;
                    $master['processing_technique'] = $request->processing_technique;
                    $master['merit_or_waiting']     = $request->list_type;
                    $master['admission_by_whom']    = $request->admission_technique;
                    // dd($master);
                    $merit_master                   = MeritMaster::create($master);
                    // dd("ok");
                    //MeritList::where('course_id',$request->course_id)->where('status','!=',2)->where('status','!=',3)->update(['status'=>'4']);
                    foreach ($data as $key => $value) {
                        $check_merit_list = MeritList::where([['student_id', $value->roll_no], ['course_id', $request->course_id], ['application_no', $value->application_no], ['status', 2]])->first();

                        $uploaded_category_id = admissionCategoryID($value->category);                        
                            $merit_list                          = [];
                            $merit_list['student_id']            = $value->roll_no;
                            $merit_list['merit_master_id']       = $merit_master->id;
                            $merit_list['application_no']        = $value->application_no;
                            $merit_list['course_id']             = $request->course_id;
                            $merit_list['admission_category_id'] = $uploaded_category_id;
                            $merit_list['shortlisted_ctegory_id']= $uploaded_category_id;
                            $merit_list['gender']                = $value->gender;
                            $merit_list['ask_hostel']            = $value->askhostel ? true : false;
                            $merit_list['hostel_required']       = $value->hostelrequired ? true : false;
                            $merit_list['programm_type']         = MeritList::$programm_types_store[$value->cdtype]; // array index wise
                            $merit_list['tuee_rank']             = $value->tuee_rank;
                            $merit_list['is_pwd']                = $value->is_pwd;
                            $merit_list['processing_technique']  = $request->processing_technique;
                            $merit_list['student_rank']          = $value->student_rank;
                            $merit_list['cmr']                   = $value->marks;
                            $merit_list['selection_category']    = $value->prov_category!=null?admissionCategoryID($value->prov_category):null;
                        if($request->list_type=="waiting"){
                            $uploaded_prov_category_id = admissionCategoryID($value->prov_category);
                            $merit_list['admission_category_id'] = $uploaded_prov_category_id;
                        }
                        if (!$check_merit_list) {                            
                            $merit_list_obj                      = MeritList::create($merit_list);
                            $merit_list_data->push($merit_list_obj);
                        } else {                           
                            // $merit_list['attendance_flag']       = 1;
                            // $merit_list['may_slide']             = 1;
                            $merit_list_obj                      = MeritList::create($merit_list);
                            $merit_list_data->push($merit_list_obj);
                        }

                        

                    }
                    if (!empty($message)) {
                        Session::flash('warning', $message);
                        return Redirect::back()->withInput($request->all());
                    }
                } else {
                    Session::flash('error', 'No record found in excelsheet');
                    return Redirect::back()->withInput($request->all());
                }
                $date_from = Carbon::createFromFormat("Y-m-d H:i:s", $request->date_from);
                // only process if processing technique is automatic
                if($merit_master->processing_technique  === MeritMaster::$PROCESSING_AUTO_STATUS && $merit_master->admission_by_whom==1){
                    $closing_day = config("vknrl.ADMISSION_REPORTING_EXPIRY_HOUR") / 24;
                    $service   = new MeritListService($merit_list_data, $request->course_id, $date_from, $closing_day);
                    $service->processData();
                }
            } catch (\Exception $e) {
                DB::rollback();
                //dd("ok");
                  dd($e->getMessage());
                Log::error($e->getMessage());
                Session::flash('error', $e->getMessage());
                return Redirect::back()->withInput($request->all());
            }
            // die("reached the exit line.");
            DB::commit();
            Session::flash('success', 'Merit List Uploaded Successfully');

            return Redirect::back();
        }

    }

    public function sendSMS(Request $request)
    {
        $template_ids = collect(config("vknrl.sms_templates"))->pluck("template_id")->toArray();
        $rules = [
            "sms"           => "required|min:1",
            "merit_list_id" => "required|array|min:1",
            // "template_id"   => "sometimes|in:". implode(",", $template_ids)
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            \Log::error($validator->errors());
            return redirect()
                ->back()
                ->withError($validator->errors())
                ->withInput($request->all())
                ->with("error", "Whoops! looks like you have missed something.");
        }
        $merit_list_ids = $request->get("merit_list_id");
        $merit_lists    = MeritList::with("application.student", "admissionCategory", "course.department")->whereIn("id", $merit_list_ids)->get();
        $sent_counter   = 0;
        $last_id        = "";
        $failed_counter = 0;
        try {
            if ($merit_list_ids) {
                foreach ($merit_lists as $key => $merit_list) {
                    $sms = $request->get("sms");
                    $sms = str_replace("##name##", $merit_list->application->fullname, $sms);
                    $sms = str_replace("##app_no##", $merit_list->application->application_no, $sms);
                    $sms = str_replace("##application_id##", $merit_list->application->id, $sms);
                    $sms = str_replace("##roll_no##", $merit_list->application->student_id, $sms);
                    $sms = str_replace("##school_name##", env("APP_NAME", ""), $sms);
                    $sms = str_replace("##category##", $merit_list->admissionCategory->name, $sms);
                    $sms = str_replace("##date_from##", date("Y-m-d h:i a", strtotime($merit_list->valid_from)), $sms);
                    $sms = str_replace("##date_to##", date("Y-m-d h:i a", strtotime($merit_list->valid_till)), $sms);
                    $sms = str_replace("##dept_name##", $merit_list->course->department->name ?? "", $sms);
                    $sms = str_replace("##programme_name##", $merit_list->course->name ?? "", $sms);

                    try {
                        if($request->template_id){
                            sendSMS($merit_list->application->student->mobile_no, $sms, $request->template_id ?? "1207162521403708453");
                        }
                        if ($request->send_email) {
                            $merit_list->application->student->notify(new StudentAnyMail($sms, "Admission Notification"));
                        }
                        $last_id = $merit_list->application->id;
                        $sent_counter++;
                    } catch (\Exception $e) {
                        \Log::error($e);
                        $failed_counter++;
                    }
                }
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with("success", "Message sent total {$sent_counter} & Failed {$failed_counter} last message sent application no {$last_id}");
        }
        return redirect()
            ->back()
            ->with("success", "Message Successfully Sent to {$sent_counter} Applicants and failed {$failed_counter}.");
    }

    // public function approve(Request $request)
    // {
    //     DB::beginTransaction();
    //     if ($request->input('submit') == "Approve") {
    //         $validator = Validator::make($request->all(), [
    //             'valid_from' => 'required',
    //             'valid_to'   => 'required',
    //             "hour"       => "sometimes|numeric|min:0|max:500"
    //         ]);
    //         if ($validator->fails()) {
    //             return Redirect::back()->withErrors($validator)->withInput($request->all());
    //         }

    //         try {
    //             foreach ($request->merit_list_id as $key => $value) {
    //                 $update_data = [
    //                     'status' => 1, 
    //                     'valid_from' => $request->valid_from, 
    //                     'valid_till' => $request->valid_to,
    //                     'reported_at' => null,
    //                 ];
    //                 if($request->has("hour")){
    //                     $update_data["expiry_hour"] = $request->get("hour");
    //                 }
    //                 // dd($update_data);
    //                 MeritList::where([['id', $value], ['status', '!=', 2]])
    //                     ->update($update_data);
    //             }
    //             $all_mmerit_list_students = MeritList::whereIn('id', $request->merit_list_id)
    //                 ->with(["application.student"])
    //                 ->get();
    //             if ($all_mmerit_list_students->count()) {
    //                 foreach ($all_mmerit_list_students as $merit_list) {
    //                     $sms = "Your application is approved for reporting. You are requested to report for counseling by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)).".";
    //                     // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
    //                     // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
    //                     try {
    //                         // sendSMS($merit_list->application->student->mobile_no, $sms, "1207162521403708453");
    //                         $merit_list->application->student->notify(new StudentAnyMail($sms));
    //                     } catch (\Throwable $th) {
    //                         Log::error($th);
    //                     }
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             DB::rollback();
    //             Log::error($e->getMessage());
    //             Session::flash('error', $e->getMessage());
    //             return Redirect::back()->withInput($request->all());
    //         }
    //         Session::flash('success', 'Approved Successfully');
    //     }elseif ($request->input('submit') == "Approve for payment") {
    //         $validator = Validator::make($request->all(), [
    //             'approve_valid_from' => 'required',
    //             'approve_valid_to'   => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             return Redirect::back()->withErrors($validator)->withInput($request->all());
    //         }
    //         try {
    //             foreach ($request->merit_list_id as $key => $value) {
    //                 $update_data = [
    //                     // 'status' => 1, 
    //                     'valid_from' => $request->approve_valid_from, 
    //                     'valid_till' => $request->approve_valid_to,
    //                     'is_payment_applicable' => 1
    //                 ];
    //                 // dd($update_data);
    //                 MeritList::where([['id', $value], ['status', '!=', 2]])
    //                     ->update($update_data);
    //             }
    //             $all_mmerit_list_students = MeritList::whereIn('id', $request->merit_list_id)
    //                 ->with(["application.student"])
    //                 ->get();
    //             if ($all_mmerit_list_students->count()) {
    //                 foreach ($all_mmerit_list_students as $merit_list) {
    //                     // $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
    //                     try {
    //                         $sms = "You application is approved for payment. Please make the admission payment before " . date("d-m-Y h:i a", strtotime($merit_list->valid_till))." to confirm your provisional seat booking.";
    //                         // sendSMS($merit_list->application->student->mobile_no, $sms, "1207162521403708453");
    //                         $merit_list->application->student->notify(new StudentAnyMail($sms));
    //                     } catch (\Throwable $th) {
    //                         Log::error($th);
    //                     }
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             DB::rollback();
    //             Log::error($e->getMessage());
    //             Session::flash('error', $e->getMessage());
    //             return Redirect::back()->withInput($request->all());
    //         }
    //         Session::flash('success', 'Approved for payment successfully.');
    //     } else if ($request->input('submit') == "Decline for Admission") {
    //         try {
    //             foreach ($request->merit_list_id as $key => $value) {
    //                 MeritList::where([['id', $value], ['status', '!=', 2]])->update(['status' => 4]);
    //             }
    //         } catch (\Exception $e) {
    //             DB::rollback();
    //             Log::error($e->getMessage());
    //             Session::flash('error', $e->getMessage());
    //             return Redirect::back()->withInput($request->all());
    //         }
    //         Session::flash('success', 'Declined Successfully');
    //     } else if ($request->input('submit') == "Send SMS") {
    //         return $this->sendSMS($request);

    //     }

    //     DB::commit();
    //     return Redirect::back();
    // }

    public function admissionPaymentReceipt(Request $request, $encrypted_id)
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
        $receipt     = $merit_list->admissionReceipt->load("collections.feeHead");
        $application = $merit_list->application;
        
        // dd($merit_list->admissionReceipt->admission_category->name);
        return view($this->getAdmissionReceiptView(), compact("application", "merit_list", "receipt"));
    }

    public function admissionSlidePaymentReceipt(Request $request, $encrypted_id)
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
        $oldmerit_list = MeritList::where(['student_id'=>$merit_list->student_id,
                                           'course_id' =>$merit_list->course_id,
                                           'status'    =>2])->first();
        $receipt     = $oldmerit_list->admissionReceipt->load("collections.feeHead");
        $application = $oldmerit_list->application;
        // dd($application);
        return view("student.admission.single-receipt", compact("application", "merit_list", "receipt"));
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
    

    public function getAdmissionReceiptView()
    {
        $guard = get_guard();
        if ($guard == "admin") {
            return "student.admission.single-receipt";
        } elseif ($guard == "student") {
            return "student.admission.single-receipt";
        } elseif ($guard == "department_user") {
            return "student.admission.single-receipt";
        }

    }

    public function admissionCategoryList(Request $request)
    {
        //
        $course_id             = $request->course_id;
        $merit_master_id       = $request->merit_master_id;
        $application_no        = $request->application_no;
        $status                = $request->status;
        $tuee_rank             = $request->tuee_rank;
        $admission_category_id = $request->admission_category_id;
        $merit                 = $request->merit;
        $undertaking_status    = $request->undertaking_status;
        $courses               = Course::withTrashed()->get();
        $admission_categories  = AdmissionCategory::where('status', 1)->get();
        //$merit_lists           = MeritList::whereIn('status', [0, 1])->with(
            $merit_lists           = MeritList::with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', 'caste_id', "application_no");
                }, 'application.caste', 'meritMaster', 'admissionCategory', 'course',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);

        if ($course_id) {
            $merit_lists->where('course_id', $course_id);
        }
        if ($merit_master_id) {
            $merit_lists->where('merit_master_id', $merit_master_id);
        }
        if ($application_no) {
            $merit_lists->where('application_no', $application_no);
        }
        if ($admission_category_id) {
            $merit_lists->where('admission_category_id', $admission_category_id);
        }
        if ($status) {
            $merit_lists->where('status', $status);
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
        $merit_lists = $merit_lists->paginate(60);
        return view('admin.merit.admission-category-update', compact('courses', 'merit_lists', 'admission_categories'));
    }

    public function admissionCategoryUpdate(Request $request)
    {
        $admission_category_id = $request->category_update_id;
        DB::beginTransaction();
        try {
            foreach ($request->merit_list_id as $key => $value) {
                MeritList::where('id', $value)->update(['selected_in_merit_list' => $request->merit, 'admission_category_id' => $admission_category_id]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            Session::flash('error', $e->getMessage());
            return Redirect::back()->withInput($request->all());
        }
        DB::commit();
        Session::flash('success', 'Updated Successfully');
        return Redirect::back();
    }

    public function undertakingList(Request $request)
    {
        //
        $course_id             = $request->course_id;
        $merit_master_id       = $request->merit_master_id;
        $application_no        = $request->application_no;
        $status                = $request->status;
        $tuee_rank             = $request->tuee_rank;
        $admission_category_id = $request->admission_category_id;
        $merit                 = $request->merit;
        $undertaking_status    = $request->undertaking_status;
        $courses               = Course::withTrashed()->get();
        $admission_categories  = AdmissionCategory::where('status', 1)->get();
        $merit_lists           = MeritList::whereIn('status', [0, 1])->with(
            [
                'application' => function ($query) {
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', 'caste_id', "application_no");
                }, 'application.caste', 'meritMaster', 'admissionCategory', 'course',
                "undertakings",
            ])
            ->withCount(["admissionReceipt"]);

        if ($course_id) {
            $merit_lists->where('course_id', $course_id);
        }
        if ($merit_master_id) {
            $merit_lists->where('merit_master_id', $merit_master_id);
        }
        if ($application_no) {
            $merit_lists->where('application_no', $application_no);
        }
        if ($admission_category_id) {
            $merit_lists->where('admission_category_id', $admission_category_id);
        }
        if ($status) {
            $merit_lists->where('status', $status);
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
        $merit_lists = $merit_lists->paginate(60)->appends(request()->query());
        return view('admin.merit.undertaking-update', compact('courses', 'merit_lists', 'admission_categories'));
    }

    public function undertakingListUpdate(Request $request)
    {

        DB::beginTransaction();
        try {
            foreach ($request->merit_list_id as $key => $value) {
                MeritList::where('id', $value)->update(['allow_uploading_undertaking' => $request->undertaking]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            Session::flash('error', $e->getMessage());
            return Redirect::back()->withInput($request->all());
        }
        DB::commit();
        Session::flash('success', 'Updated Successfully');
        return Redirect::back();
    }

    public function changeCourse(MeritList $meritList)
    {
        if ($meritList) {
            $validator = Validator::make(request()->all(), [
                "new_program_id" => "required|exists:courses,id",
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->with("error", "selected program us invalid.");
            }
            $records = [
                "old_course_id" => $meritList->course_id,
                "new_course_id" => request("new_program_id"),
            ];
            $meritList->update([
                "course_id" => request("new_program_id"),
            ]);
            $meritList->courseChanges()->create($records);
            return redirect()
                ->back()
                ->with("success", "Successfully changed.");
        } else {
            abort(404);
        }
    }
    public function transferSeat(MeritList $meritList)
    {
        $meritList->load("admissionReceipt");
        if ($meritList) {
            $validator = Validator::make(request()->all(), [
                "new_program_id" => "required|exists:courses,id",
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->with("error", "selected program is invalid.");
            }
            DB::beginTransaction();
            try {
                $records = [
                    "old_course_id" => $meritList->course_id,
                    "new_course_id" => request("new_program_id"),
                ];
                $new_programm_seat = CourseSeat::where("course_id", request("new_program_id"))
                    ->where("admission_category_id", $meritList->admission_category_id)
                    ->first();

                // checking seat available
                if (!$new_programm_seat || ($new_programm_seat->total_seats - $new_programm_seat->total_seats_applied) <= 0) {
                    return redirect()
                        ->back()
                        ->with("error", "Seat not available for the selected program for the same category.");
                }
                $old_programm_seat = CourseSeat::where("course_id", $meritList->course_id)
                    ->where("admission_category_id", $meritList->admission_category_id)
                    ->first();
                if (!$old_programm_seat) {
                    return redirect()
                        ->back()
                        ->with("error", "selected program is invalid.");
                }
                // deduct form new programm
                $new_programm_seat->increment("total_seats_applied");
                $old_programm_seat->decrement("total_seats_applied");
                // add seat to new programm

                $meritList->update([
                    "course_id" => request("new_program_id"),
                ]);
                $meritList->admissionReceipt()->update([
                    "roll_number" => "UPDATED",
                    "course_id"   => request("new_program_id"),
                ]);
                // $meritList->student()->update([
                //     "roll_number"   => "UPDATED"
                // ]);
                $meritList->courseChanges()->create($records);
            } catch (\Throwable $th) {
                DB::rollback();
                Log::error($th);
                return redirect()
                    ->back()
                    ->with("error", "Whoops! something went wrong.");
            }
            DB::commit();
            return redirect()
                ->back()
                ->with("success", "Successfully transfered to new course.");
        } else {
            abort(404);
        }
    }
    public function approveSystemGenerated(MeritList $merit_list)
    {
        // if status is not system generated no need to change
        if ($merit_list->status !== 7) {
            return response()->json([
                "message" => "Failed! Merit is already " . $merit_list->getStatusText(),
                "status"  => false,
            ]);
        }

        $merit_list->update([
            "status" => 1,
            "expiry_hour"   => config("vknrl.ADMISSION_EXPIRY_HOUR")
        ]);
        $sms = "You application is approved for admission. last date of payment is " . date("d-m-Y h:i a", strtotime($merit_list->valid_till));
        $sms = "Please login to the panel and make the fees payment by " . date("d-m-Y h:i a", strtotime($merit_list->valid_till)) . " for completing the provisional admission.";
        try {
            // sendSMS($merit_list->application->student->mobile_no, $sms, "1207162521403708453");
            // $merit_list->application->student->notify(new StudentAnyMail($sms));
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                "message" => "Failed!.",
                "status"  => false,
            ]);
        }
        return response()->json([
            "message"     => "Successfully approved. Please filter again by selecting status as approved.",
            "status"      => true,
            "button_text" => $merit_list->getStatusText(),
        ]);
    }
    private function processUploadedData(Collection $merit_list_data, $course_id)
    {
        /**
         * @var \App\Models\CourseSeat $pwd_candidate_limit_in_selected_course
         */
        $pwd_candidate_limit_in_selected_course = CourseSeat::query()
            ->courseFilter($course_id)
            ->pwdFilter()
            ->first();
        // may be need to sort by rank
        $pwd_candidates = $merit_list_data->where("admission_category_id", AdmissionCategory::$PWD_CATEOGORY_ID)->take(10);
        // if pwd candidate found in uploaded excel list and pwd seat is available
        // then only change status of the candidates available booking for pwd candidates
        $available_seat = $pwd_candidate_limit_in_selected_course->availableSeat();
        if ($pwd_candidate_limit_in_selected_course && $available_seat && $pwd_candidates->isNotEmpty()) {
            foreach ($pwd_candidates->take($$available_seat) as $merit_list) {
                // date from and date to is needed.
                $merit_list->partiallyApproveToTakeAdmission();
            }
        } else {
            $course_seat_details_for_selected_course = CourseSeat::query()
                ->courseFilter($course_id)
                ->get();
            foreach ($course_seat_details_for_selected_course as $course_seat) {
                if ($course_seat->availableSeat()) {
                    $merit_list = $merit_list_data->where("course_id", $course_id)
                        ->where("admission_category_id", $course_seat->admission_category_id)
                        ->take($course_seat->availableSeat());
                    $merit_list->each(function () {

                    });
                }
            }
            // change status of the remaining category candidates acording to remaining seat counts
        }
    }


}
