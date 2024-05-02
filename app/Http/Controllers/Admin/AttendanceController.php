<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Caste;
use App\Models\MeritList;
use App\Models\MeritMaster;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // dd($request->all());
        $course_id             = $request->course_id;
        $merit_master_id       = $request->merit_master_id;
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
                    $query->select('id', 'student_id', 'first_name', 'middle_name', 'last_name', "application_no", "caste_id");
                }, 'meritMaster', 'admissionCategory', 'course',
                "undertakings",
            ]
        )
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
            if($status == '17'){
                $merit_lists->where('status', 0)->where('attendance_flag',1)->where('may_slide','!=',1);
            }
            elseif ($status == '18') {
                $merit_lists->whereIn('status',[2, 14])->where('attendance_flag', 1);
            }else{
                $merit_lists->where('status', $status);
            }
           
        }
        if (in_array($payment_option, [0, 1]) && !is_null($payment_option)) {
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
        if (request('export') == 'excel') {
            return  $this->exportToExcel($merit_lists);
        }
        $merit_lists = $merit_lists->orderBy('admission_category_id')->orderBy('cmr')->paginate(60);
        // dd($merit_lists);
        return view('admin.attendance.index',compact('courses', 'admission_categories' ,'merit_lists'));
    }
    public function exportToExcel($merit_lists)
    {
        $query = $merit_lists;
        $name = "Merit List Report.csv";
        $headers = [
            'Content-Disposition' => 'attachment; filename=' . $name,
            'Content-Type' => 'text/csv',
        ];
        $columns = [
           "#","Reg. No","App. No", "Prog. Name", "Name", "A. Category", "S. Category", "Gender", "Rank", "Attendance"
        ];
      
        // dd($query->get())
        return response()->stream(function () use ($columns, $query) {
            $file = fopen('php://output', 'w+');
            fputcsv($file, $columns);
            $data = $query->cursor();

            $castes = Caste::pluck('name', 'id')->toArray();
            foreach ($data as $key => $value) {
                // $is_admited = MeritList::with('course')
                //     ->where('student_id', $value->student_id)
                //     ->where('status', 2)
                //     ->get(); 
                // foreach($is_admited as $key=>$name){
                //     $course = $name->course->name;
                //     $floating = $name->freezing_floating;

                //     $val = $course .'('. $floating .')';
                // }
													
                if ($value->is_pwd == 1) {
                  $category = "PWD";  # code...
                }else{
                    $category = " ";
                }
                $scat = $castes[$value->application->caste_id] ?? 'NA';

                $attendance = $value->attendance_flag;
                if ($attendance == 1){
                    $att =  "Present" ;
                } elseif ($attendance == 2) {
                    $att =  "Absent";
                }else{
                    $att =  "Not Processed";
                }
													
                $row = [
                    $key + 1,
                    $value->student_id ,
                    $value->application_no ,
                    $value->course->name ,
                    $value->application->first_name .' '. $value->application->middle_name . ' ' .$value->application->last_name ,
                    $value->admissionCategory->name .' '.$category,
                    $scat,
                    $value-> gender ,
                    $value-> student_rank ,
                    $att ,
                ];
                fputcsv($file, $row);
            }
            $blanks = array("\t", "\t", "\t", "\t");
            fputcsv($file, $blanks);
            $blanks = array("\t", "\t", "\t", "\t");
            fputcsv($file, $blanks);
            $blanks = array("\t", "\t", "\t", "\t");
            fputcsv($file, $blanks);
            fclose($file);
        }, 200, $headers);
    }


    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
