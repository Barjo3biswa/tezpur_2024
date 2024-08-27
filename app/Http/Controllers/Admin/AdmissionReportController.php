<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\Caste;
use App\Models\CuetSubject;
use App\Models\MeritList;
use App\Models\Program;
use Maatwebsite\Excel\Facades\Excel;

class AdmissionReportController extends Controller
{
    public function index(Request $request)
    {

        // dd($request->all());

        //
        $course_id             = $request->course_id ??0;
        $merit_master_id       = $request->merit_master_id??0;
        $application_no        = $request->application_no;
        $status                = $request->status;
        $admission_category_id = $request->admission_category_id;
        $merit                 = $request->merit;
        $gender                = $request->gender;
        $courses               = Course::withTrashed()->get();
        $programs = Program::get();
        $admission_categories  = AdmissionCategory::where('status', 1)->get();
        $merit_lists           = MeritList::/* whereIn('status',[2,3,6])-> */select('merit_lists.*','admission_receipts.receipt_no','admission_receipts.roll_number','admission_receipts.year','admission_receipts.total','admission_receipts.course_id','admission_receipts.previous_received_amount')
                // ->join('admission_receipts','admission_receipts.student_id','=','merit_lists.student_id')
                // ->where('merit_lists.course_id',$course_id)
                ->join('admission_receipts', function($join)
                        {
                            $join->on('admission_receipts.merit_list_id','=','merit_lists.id');
                            $join->on('admission_receipts.student_id','=','merit_lists.student_id');
                            $join->on('admission_receipts.course_id','=','merit_lists.course_id');
                        })
        ->with(
            [
                'application', 'meritMaster', 'admissionCategory', 'course',
                "undertakings","student" ,"admissionReceipt"=>function($q) use($course_id){
                    $q->where('course_id',$course_id);
                }
            ])
            ->withCount(["admissionReceipt"]);
        // $merit_lists->join('admission_receipts','admission_receipts.student_id','=','merit_lists.student_id')
        //         ->where('admission_receipts.course_id','=','admission_receipts.course_id');

        if($request->admission_date){
            $merit_lists->whereDate('admission_receipts.created_at', $request->admission_date);
        }
        
        if($course_id){
            $merit_lists->where('merit_lists.course_id', $course_id);
        }
        // dd($merit_lists->get());
        if(isset($request->program_group) && $course_id==0){
            // dd($request->program_group);
             $course_ids = Course::where('program_id',$request->program_group)->withTrashed()->pluck('id')->toArray();
             $merit_lists->whereIn('merit_lists.course_id', $course_ids);
        }
          

        if ($application_no) {
            $merit_lists->where('application_no', $application_no);
        }
        if ($admission_category_id) {
            if($admission_category_id==7){
                $merit_lists->where('is_pwd', 1);
            }else{
                $merit_lists->where('admission_category_id', $admission_category_id);
            }
            
        }
        if ($status) {      
            // if($status==2){
            //     $merit_lists->whereIn('merit_lists.status', [2,14])->where('may_slide','!=',3);
            // }else{
                $merit_lists->where('merit_lists.status', $status);
            // }
        }
        
        if ($merit) {
            if ($merit == "merit") {
                $merit_lists->where('selected_in_merit_list', 1);
            } elseif ($merit == "waiting") {
                $merit_lists->where('selected_in_merit_list', 0);
            }
        }

        if ($gender) {
            $merit_lists->where('gender', $gender);
        }
        // dd($merit_lists->/* orderBy('MeritList.admissionReceipt.roll_number')-> */get());
        $sms_templates = config("vknrl.sms_templates");
            if($request->has("export-data")){
                if($request['export-data']==1){
                    // dd("1st");
                    return $this->ExportApplicationData($merit_lists, $request);
                }else if($request['export-data']==2){
                    // dd("2nd");
                    return $this->ExportApplicationDataII($course_id, $request);
                }
                
            }

            if($request->btech_fil){
                if($request->btech_fil=='jossa'){
                    $merit_lists = $merit_lists->whereIn('merit_master_id',[344]);
                }else if($request->btech_fil=='ne'){
                    $merit_lists = $merit_lists->whereNotIn('merit_master_id',[344]);
                }else if($request->btech_fil=='None'){
                    $merit_lists = $merit_lists->WhereNotIn('merit_lists.course_id',[72,73,74,75,76,77,83,111]);
                }
            }
            // if ($status) {
                $merit_lists = $merit_lists->/* orderBy('admission_category_id')-> */orderby('admission_receipts.roll_number')->paginate(100);
            // }else{
            //     $merit_lists = $merit_lists->paginate(100);
            // } 
            
            return view('admin.reports.admission-report', compact('courses', 'merit_lists', 'admission_categories', "sms_templates","programs"));  
       
    }

    public function ExportApplicationData($merit_list, $request)
    {
        // dd("ok");
        // dd($merit_list->get());
        $castes = Caste::pluck("name","id")->toArray();
        $excel    = $merit_list->orderBy('admission_receipts.roll_number')->get();
        $fileName = $merit_list->first()->course->name.'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        );
        $columns = array('SL', 
                        'id',	
                        'Receipt No',	
                        'Roll No',	
                        'Through',
                        'Reg ID',	
                        'App ID',	
                        'App No',
                        'CMR',	
                        'Name',	
                        'Program',	
                        'Program Code',	
                        'Gender',	
                        'PWD',
                        'Year',	
                        'Admitted Category',
                        'Hostel Name',
                        'Room No',
                        // 'Admitted On',	
                        'Mobile No',	
                        'Email',	
                        'Amount',	
                        'Fathers Name',
                        'Mothers Name',	
                        'Permanent Address',
                        'Permanent PO',	
                        'Permananent Dist',	
                        'Permanent State',	
                        'Permanent PIN',	
                        'Correspondence Address',	
                        'Correspondence PO',	
                        'Correspondence Dist',	
                        'CorrespondenceState',	
                        'Correspondence PIN',
                        'Religion',	
                        'Social Category',	
                        'Sub Caste',	
                        // 'IS_PWD',	
                        'DOB',
                        'Minority',	
                        'Place of Residence',
                        'Annual Income',
                        'Admission Date',
                        'Hostal Status',
                        'Admission Trans Id',
                        'Admission Amount',
                        'Hostal Trans Id',
                        'Hostal Amount'

                    );
        $callback = function () use ($excel, $columns,$castes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $count = 0;
            foreach ($excel as $key=>$task) {                
                $row['SL']               = ++$key;
                $row['id']               = $task->id;	
                $row['Receipt No']       = $task/* ->admissionReceipt */->receipt_no ??"NA";	
                $row['Roll No']          = $task/* ->admissionReceipt */->roll_number ??"NA";	
                $ml_details = MeritList::where(['student_id'=>$task->student_id,'course_id'=>$task->course_id,'status'=>2])->first();
                $row['Through']          = $ml_details->meritMaster->courseSeatType->name ?? "NA";	
                $row['Reg ID']           = $task->student_id;	
                $row['App ID']           = $task->id;	
                $row['App No']           = $task->application_no;
                $row['CMR']              = $task->student_rank;
                $row['Name']             = $task->application->FullName?? "NA";
                                                // $task->application->middle_name ?? "NA".
                                                // $task->application->last_name ?? "NA";	
                $row['Program']          = $task->course->name;	
                $row['Program Code']     = $task->course->code;
                $row['Gender']           = $task->gender;	
                $row['PWD']              = $task->is_pwd==1?'Yes':'No';
                $row['Year']             = $task/* ->admissionReceipt */->year ?? "NA";
                if($task->may_slide==3){
                    $new=MeritList::where(['student_id'=>$task->student_id,'course_id'=>$task->course_id,'status'=>14])->first();
                    $row['Admitted Category']= $new->admissionCategory->name_in_excel . ($task->is_pwd == 1 ? '(PWD)' : '');
                }else{
                    $row['Admitted Category']= $task->admissionCategory->name_in_excel . ($task->is_pwd == 1 ? '(PWD)' : '');
                }	

                $row['Hostel Name'] = $task->hostel_required==4?$task->hostel_name:"NA";
                $row['Room No']     = $task->hostel_required==4?$task->room_no:"NA";
                // $row['Admitted Category']= $task->may_slide==3 && $task->admission_category_id!=11?'General':$task->admissionCategory->name;
                // $row['Admitted Category']= $task->admissionCategory->name ?? "NA";	
                // $row['Admitted On']      = $task/* ->admissionReceipt */->created_at??"NA";	
                $row['Mobile No']        = $task->student->mobile_no;	
                $row['Email']            = $task->student->email;	
                $row['Amount']           = $task/* ->admissionReceipt */->total+$task->previous_received_amount;	
                $row['Fathers Name']     = $task->application->father_name;	
                $row['Mothers Name']     = $task->application->mother_name;	
                $row['Permanent Address']= $task->application->permanent_village_town;
                $row['Permanent PO']     = $task->application->permanent_po;	
                $row['Permananent Dist'] = $task->application->per_district->district_name??$task->application->permanent_district;	
                $row['Permanent State']  = $task->application->per_state->name??$task->application->permanent_state;	
                $row['Permanent PIN']    = $task->application->permanent_pin;	
                $row['Correspondence Address']= $task->application->correspondence_village_town;	
                $row['Correspondence PO']  = $task->application->correspondence_po;	
                $row['Correspondence Dist']= $task->application->cor_district->district_name??$task->application->correspondence_district;	
                $row['CorrespondenceState']= $task->application->cor_state->name??$task->application->correspondence_state;	
                $row['Correspondence PIN'] = $task->application->correspondence_pin;
                $row['Religion']           = $task->application->religion;	
                $row['Social Category']    = $castes[$task->application->caste_id];
                $row['Sub Caste']          = $task->application->sub_caste;	
                // $row['IS_PWD']             = $task->application->is_pwd;	
                $row['DOB']                = $task->application->dob;
                $row['Minority']           = $task->application->is_minority;	
                $row['Place of Residence'] = $task->application->place_residence;
                $raw['Annual Income']      = $task->application->family_income_range->min.'-to-'.$task->application->family_income_range->max;
                $raw['Admission Date'] = $task->application->admission_receipt->created_at??"NA";
                
                $hostel = 'Not Required';
                if($task->hostel_required==0){
                    $hostel = 'Not Required';
                }else if($task->hostel_required==1){
                    $hostel = 'Required';
                }else if($task->hostel_required==3){
                    $hostel = 'Payment Pending';
                }else if($task->hostel_required==4){
                    $hostel = 'Assigned';
                }else if($task->hostel_required==5){
                    $hostel = 'Not Allowed';
                }else if($task->hostel_required==6){
                    $hostel = 'Will Be Assigned Later';
                }
                $raw['Hostal Status'] = $hostel;
                $raw['Admission Trans Id'] = $task->application->admission_receipt->transaction_id??"NA";
                $raw['Admission Amount'] = $task->application->admission_receipt->total??"NA";
                $raw['Hostal Trans Id'] = $task->hostelReceipt->transaction_id??"NA";
                $raw['Hostal Amount'] = $task->hostelReceipt->total??"NA";
                fputcsv($file, array(
                                                $row['SL'],
                                                $row['id'],	
                                                $row['Receipt No'],	
                                                $row['Roll No'],
                                                $row['Through'],	
                                                $row['Reg ID'],	
                                                $row['App ID'],	
                                                $row['App No'],	
                                                $row['CMR'],
                                                $row['Name'],	
                                                $row['Program'],	
                                                $row['Program Code'],	
                                                $row['Gender'],	
                                                $row['PWD'],
                                                $row['Year'],	
                                                $row['Admitted Category'],	
                                                // $row['Admitted On'],	
                                                $row['Hostel Name'],
                                                $row['Room No'],
                                                $row['Mobile No'],	
                                                $row['Email'],	
                                                $row['Amount'],	
                                                $row['Fathers Name'],
                                                $row['Mothers Name'],	
                                                $row['Permanent Address'],
                                                $row['Permanent PO'],	
                                                $row['Permananent Dist'],	
                                                $row['Permanent State'],	
                                                $row['Permanent PIN'],	
                                                $row['Correspondence Address'],	
                                                $row['Correspondence PO'],	
                                                $row['Correspondence Dist'],	
                                                $row['CorrespondenceState'],	
                                                $row['Correspondence PIN'],
                                                $row['Religion'],	
                                                $row['Social Category'],	
                                                $row['Sub Caste'],	
                                                // $row['IS_PWD'],	
                                                $row['DOB'],
                                                $row['Minority'],	
                                                $row['Place of Residence'] ,
                                                $raw['Annual Income'],
                                                $raw['Admission Date'],
                                                $raw['Hostal Status'],  
                                                $raw['Admission Trans Id'],
                                                $raw['Admission Amount'],
                                                $raw['Hostal Trans Id'],
                                                $raw['Hostal Amount'],   
                        ));
            }
            fclose($file);
            // dd($file);
        };
        // dd("ok");
        return response()->stream($callback, 200, $headers);
    }

    public function ExportApplicationDataII($course_id, $request)
    {
        // dd($course_id);

        // $arr = [];

        $applications = Application::/* select('applications.*','admission_receipts.roll_number')
                -> */with(["extraExamDetails", "application_academic", "admission_receipt", "admission_receipt.admission_category", "admission_receipt.student"]);
        
        
        if (!$applications->count()) {
            return redirect()->back()->with("error", "Sorry records not found.");
        }
        $applications->withCount(['attachments' => function ($query) {
            $query->where("doc_name", "gate_score_card");
            }]);
        
        $applications/* ->join('merit_lists','merit_lists.application_no','=','applications.application_no') */
                     ->join('admission_receipts','admission_receipts.student_id','=','applications.student_id')
                     ->when($course_id!='all',function ($q) use($course_id){
                          return $q->where('admission_receipts.course_id','=',$course_id);
                     })
                    //  ->where('admission_receipts.course_id','=',$course_id)
                     ->where('applications.application_no','!=',null);
        // dd($applications->get());    
        // $applications->with(["extraExamDetails", "application_academic", "admission_receipt", "admission_receipt.admission_category", "admission_receipt.student"]);
        // dd($applications->is_cuet_ug);
        $applications->orderby('admission_receipts.roll_number');
        
        $cuet_subjects = CuetSubject::get();
        // dd($applications);
        Excel::create('Applications ' . getActiveSession()->name, function ($excel) use ($applications, $cuet_subjects) {
            $excel->sheet('Applications ' . getActiveSession()->name, function ($sheet) use ($applications, $cuet_subjects) {
                $sheet->setTitle('Applications');

                $sheet->cells('A1:BG1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
				$sheet->setAutoSize(false);
                // $sheet->fromArray($arr, null, 'A1', false, true);
               
                $arr = [
                    "Sl. No."               => "Sl. No.",
                    "Application No."       => "Application No.",
                    "Student No"            => "Student No",
                    "Roll No"               => "Roll No",
                    "Admission Category"    => "Admission Category",
                    "Name of the Applicant" => "Name of the Applicant",
                    "Gender"                => "Gender",
                    "Annual Income"         => "Annual Income",
                    "Programm Applied"      => "Programm Applied",
                    "Father's Name"         => "Father's Name",
                    "Address"               => "Address",
                    "Email"                 => "Email",
                    "Contact No."           => "Contact No.",
                    "D.O.B"                 => "D.O.B",
                    "Category"              => "Category",
                    "PWD"                   => "PWD",
                    
                    "English 10th Marks"    => "English 10th Marks",
                    "English 10th Grade"    => "English 10th Grade",
                    "English 10th mark obtained"    => "English 10th mark obtained",
                    
                    "Physics 10+2 Marks"    => "Physics 10+2 Marks",
                    "Physics 10+2 Grade"    => "Physics 10+2 Grade",
                    "Physics 10+2 mark obtained"    => "Physics 10+2 mark obtained",

                    "Chemistry 10+2 Marks"    => "Chemistry 10+2 Marks",
                    "Chemistry 10+2 Grade"    => "Chemistry 10+2 Grade",
                    "Chemistry 10+2 mark obtained"    => "Chemistry 10+2 mark obtained",
                    
                    "Mathematics 10+2 Marks"    => "Mathematics 10+2 Marks",
                    "Mathematics 10+2 Grade"    => "Mathematics 10+2 Grade",
                    "Mathematics 10+2 mark obtained"    => "Mathematics 10+2 mark obtained",

                    "English 10+2 Marks"    => "English 10+2 Marks",
                    "English 10+2 Grade"    => "English 10+2 Grade",
                    "English 10+2 mark obtained"    => "English 10+2 mark obtained",

                    "Statistics 10+2 Mark"    => "Statistics 10+2 Mark",
                    "Statistics 10+2 Mark"    => "Statistics 10+2 Mark",
                    "Statistics 10+2 Mark"    => "Statistics 10+2 Mark",

                    "Biology 10+2 Marks"    => "Biology 10+2 Marks",
                    "Biology 10+2 Grade"    => "Biology 10+2 Grade",
                    "Biology 10+2 mark obtained"    => "Biology 10+2 mark obtained",
                    
                    "10th total marks"         => "10th total marks",
                    "10th marks obtained"      => "10th marks obtained",
                    "10th % / CGPA"         => "10th % / CGPA",

                    "HS Stream"             => "HS Stream",
                    "HS Total mark"       => "HS Total mark",
                    "HS mark obtained"    => "HS mark obtained",
                    "HS % / CGPA"           => "HS % / CGPA",

                    "Graduation Course"     => "Graduation Course",
                    "Graduation Total mark"       => "Graduation Total mark",
                    "Graduation mark obtained"    => "Graduation mark obtained",
                    "Graduation % / CGPA"   => "Graduation % / CGPA",

                    "PG Course"             => "PG Course",
                    "PG Total mark"       => "PG Total mark",
                    "PG mark obtained"    => "PG mark obtained",
                    "PG % / CGPA"           => "PG % / CGPA",
                    "Gate Score Card"           => "Gate Score Card",
                    "Statement of Purpose"           => "Statement of Purpose",
                ];
                for ($val=1; $val <=8; $val++) { 
                    $arr[] = "Exam name $val";
                    $arr[] = "Total Mark $val";
                    $arr[] = "Mark Obtn $val";
                    $arr[] = "CGPA $val";
                    $arr[] = "% Perc $val";
               }
               for ($val=1; $val <=5; $val++) { 
                    $arr[] = "Question $val";
                    $arr[] = "$val total if (available)";
                    $arr[] = "Answer $val";
                }
                 foreach(mbaExamNames() as $exam_name){
					$arr[]=  "$exam_name Reg. No.";
					$arr[]=  "$exam_name Date.";
					$arr[]=  "$exam_name Score";
                }
                $sheet->appendRow($arr);
				$additional_no = 0;
				$arr=[];
                
               	$applications->chunk(500,  function($new_applications)use($sheet, &$additional_no, &$arr, &$cuet_subjects) {                  
                    // dd($applications->get());
                    foreach($new_applications as $key =>$application){
                    $row = [
                        "Sl. No."               => ($key + $additional_no + 1),
                        "CUET-UG/CUET-PG"       => $application->is_cuet_ug==1 ? "CUET-UG":''/* . $application->is_cuet_pg==1 ? "CUET-PG":'' */,
                        "Application No."       => $application->application_no,
                        "Student No"            => $application->student_id,
                        "Roll No"               => $application->admission_receipt ? $application->admission_receipt->roll_number : "",
                        "Admission Category"    => ( $application->admission_receipt && $application->admission_receipt->admission_category )? $application->admission_receipt->admission_category->name : "",
                        "Name of the Applicant" => $application->fullname,
                        "Gender"                => $application->gender,
                        "Annual Income"         => $application->family_income_range->min.'-to-'.$application->family_income_range->max,
                        "Preference"            => coursePreference($application),
                        "Programm Applied"      => courseCode($application),
                        "Father's Name"         => $application->father_name,
                        "Address"               => $this->getApplicationPermanentAddress($application),
                        "Email"                 => $application->student->email,
                        "Contact No."           => $application->student->mobile_no,
                        "D.O.B"                 => dateFormat($application->dob),
                        "Category"              => $application->caste->name ?? "NA",
                        "PWD"                   => $application->is_pwd ? "Yes" : "No",

                        "English 10th Marks"    => $application->application_academic->english_mark_10_total_mark ?? "NA",
                        "English 10th Grade"    => $application->application_academic->english_mark_10_grade ?? "NA",
                        "English 10th mark obtained"    => $application->application_academic->english_mark_10 ?? "NA",
                        
                        "Physics 10+2 Marks"    => $application->application_academic->physics_total_mark ?? "NA",
                        "Physics 10+2 Grade"    => $application->application_academic->physics_grade ?? "NA",
                        "Physics 10+2 mark obtained"    => $application->application_academic->physics_mark ?? "NA",

                        "Chemistry 10+2 Marks"    => $application->application_academic->chemistry_total_mark ?? "NA",
                        "Chemistry 10+2 Grade"    => $application->application_academic->chemistry_grade ?? "NA",
                        "Chemistry 10+2 mark obtained"    => $application->application_academic->chemistry_mark ?? "NA",
    
                        "Mathematics 10+2 Marks"    => $application->application_academic->mathematics_total_mark ?? "NA",
                        "Mathematics 10+2 Grade"    => $application->application_academic->mathematics_grade ?? "NA",
                        "Mathematics 10+2 mark obtained"    => $application->application_academic->mathematics_mark ?? "NA",
    
                        "English 10+2 Marks"    => $application->application_academic->english_total_mark ?? "NA",
                        "English 10+2 Grade"    => $application->application_academic->english_grade ?? "NA",
                        "English 10+2 mark obtained"    => $application->application_academic->english_mark ?? "NA",
    
                        "Statistics 10+2 Mark"    => $application->application_academic->statistics_total_mark ?? "NA",
                        "Statistics 10+2 Grade"    => $application->application_academic->statistics_grade ?? "NA",
                        "Statistics 10+2 mark obtained"    => $application->application_academic->statistics_mark ?? "NA",
    
                        "Biology 10+2 Marks"    => $application->application_academic->biology_total_mark ?? "NA",
                        "Biology 10+2 Grade"    => $application->application_academic->biology_grade ?? "NA",
                        "Biology 10+2 mark obtained"    => $application->application_academic->biology_mark ?? "NA",
                        
                        "10th total marks"         => $application->application_academic->academic_10_total_mark ?? "NA",
                        "10th marks obtained"      => $application->application_academic->academic_10_mark_obtained  ?? "NA",
                        "10th % / CGPA"         => $application->application_academic->academic_10_percentage ?? "NA",

                        "HS Stream"             => $application->application_academic->academic_12_stream ?? "NA",
                        "HS total marks"             => $application->application_academic->academic_12_total_mark ?? "NA",
                        "HS marks obtained"          => $application->application_academic->academic_12_mark_obtained ?? "NA",
                        "HS % / CGPA"           => $application->application_academic->academic_12_percentage ?? "NA",

                        "Graduation Course"     => $application->application_academic->academic_bachelor_degree ?? "NA",
                        "Graduation total marks"     => $application->application_academic->academic_graduation_total_mark ?? "NA",
                        "Graduation  marks obtained" => $application->application_academic->academic_graduation_mark_obtained ?? "NA",
                        "Graduation % / CGPA"   => $application->application_academic->academic_graduation_percentage ?? "NA",

                        "PG Course"             => $application->application_academic->academic_post_graduation_degree ?? "NA",
                        "PG total marks"    => $application->application_academic->academic_post_graduation_total_mark ?? "NA",
                        "PG marks obtained" => $application->application_academic->academic_post_graduation_mark_obtained ?? "NA",
                        "PG % / CGPA"           => $application->application_academic->academic_post_graduation_percentage ?? "NA",
                        "Gate Score Card"       => $application->attachments_count ? "Yes": "No",
                        "Statement of Purpose"   => $application->application_academic->statement_of_purpose ?? "NA",
                    ];
                    
                    

                    $index1 = 1;
                    foreach($application->other_qualifications as $key => $other_qual){
                        $row["Exam name ".$key] = $other_qual->exam_name;
                        $row["Total Mark ".$key] = $other_qual->total_mark;
                        $row["Mark Obtn ".$key] = $other_qual->mark_obtained;
                        $row["CGPA ".$key] = $other_qual->cgpa;
                        $row["% Perc ".$key] = $other_qual->marks_percentage;
                        $index1++;
                    }
                    // for remaining fillup
                    // foreach(range(($index1), 6) as $val){
                    for ($val= $index1; $val <=8; $val++) { 
                        $row["Exam name $val"]= "";
                        $row["Total Mark $val"]= "";
                        $row["Mark Obtn $val"]= "";
                        $row["CGPA $val"]= "";
                        $row["% Perc $val"]= "";
                    }
                    $index = 1;
                    foreach($application->eligibility_answers as $key => $eligibility){
                        $row["Question ".($key + 1)] = $eligibility->question;
                        $row[($key + 1)." total if (available)"] = $eligibility->total;
                        $row["Answer ".($key + 1)] = $eligibility->answer;
                        $index ++;
                    }
                     // for remaining fillup
                    // foreach(range(($index), 4) as $val){
                    for ($val= $index ; $val <=5; $val++) { 
                        $row["Question $val"] ="";
                        $row["$val total if (available)"] ="";
                        $row["Answer $val"] ="";
                    }
                    foreach(mbaExamNames() as $exam_name){
                    	$extra_exam_details = $application->extraExamDetails->where("name_of_the_exam", $exam_name)->first();
                    	if($extra_exam_details){
							$row["$exam_name Reg. No."] = $extra_exam_details->registration_no;
							$row["$exam_name Date."] = $extra_exam_details->date_of_exam;
							$row["$exam_name Score"] = $extra_exam_details->score_obtained;
                    	}else{
							$row["$exam_name Reg. No."] ="";
							$row["$exam_name Date."] ="";
							$row["$exam_name Score"] ="";
                    	}
                    }
                    
                    // $sheet->appendRow($arr);
                    foreach($cuet_subjects as $key=>$sub){
                        $row[$sub->subject_name] = $sub->subject_name;
                        $flag=0;                
                        foreach($application->cuet_exam_details as $cuet){
                            if($cuet->subjects == $sub->subject_name){
                                $row["Score ".$key] = $cuet->marks;
                                $row["Percentile ".$key] = $cuet->percentile;
                                $flag=1;
                                break;
                            }                           
                        } 
                        if($flag==0){
                            $row["Score ".$key] = "NA";
                            $row["Percentile ".$key] = "NA"; 
                        }                       
                    }
                    
                    // $index1 = 1;
                    // foreach($application->cuet_exam_details as $key => $cuet){
                    //     $row["Subect ".$key] = $cuet->subjects;
                    //     $row["Score ".$key] = $cuet->marks;
                    //     $row["Percentile ".$key] = $cuet->percentile;
                    //     $index1++;
                    // }
                    $arr[] = $row;
                 }
					$additional_no = $additional_no + 500;
                });
                $sheet->fromArray($arr, null, 'A1', false, true);

            });
        })->download('csv');
    }

    public function getApplicationPermanentAddress($application)
    {
        return str_replace("</br>", "\n", getApplicationPermanentAddress($application));
    }
}
