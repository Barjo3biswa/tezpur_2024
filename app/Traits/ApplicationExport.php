<?php
namespace App\Traits;

use App\Models\Application;
use App\Models\CuetSubject;
use App\Services\ApplicationService;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Log;
use ZipArchive;

/**
 * Trait for handling Export (Applications)
 * date: 05-07-2019
 */
trait ApplicationExport
{
    public function ExportApplicationData($applications, $request)
    {
       
        // $arr = [];
        // dd($applications->get());
        if (!$applications->count()) {
            return redirect()->back()->with("error", "Sorry records not found.");
        }
        // $applications->withCount(['attachments' => function ($query) {
        //     $query->where("doc_name", "gate_score_card");
        // }]);
        
        $applications->with(["extraExamDetails", "application_academic", "admission_receipt", "admission_receipt.admission_category", "admission_receipt.student"]);
        // dd($applications->is_cuet_ug);
        $cuet_subjects = CuetSubject::get();
        // dd($applications->first());
        Excel::create('Applications'/*  . getActiveSession()->name */, function ($excel) use ($applications, $cuet_subjects) {
            $excel->sheet('Applications'/*  . getActiveSession()->name */, function ($sheet) use ($applications, $cuet_subjects) {
                // dd("ok");
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
                    "Admit Roll No"         => "Admit Roll No",
                    "Roll No"               => "Roll No",  
                    "Jee Application No"    => "Jee Application No",  
                    "Jee Roll No"           => "Jee Roll No", 
                    "GAT B Score"           => "GAT B Score",
                    "Admission Category"    => "Admission Category",
                    "Name of the Applicant" => "Name of the Applicant",
                    "Gender"                => "Gender",
                    "Course_code"           => "Course_code",
                    "Programm Applied"      => "Programm Applied",
                    "Application Status"    => "Application Status",
                    "Exam center code"      => "Exam center code",
                    "Exam center Name"      => "Exam center Name",
                    "Father's Name"         => "Father's Name",
                    "Address"               => "Address",
                    "Email"                 => "Email",
                    "Contact No."           => "Contact No.",
                    "D.O.B"                 => "D.O.B",
                    "Category"              => "Category",
                    "PWD"                   => "PWD",
                    
                    // "English 10th Marks"    => "English 10th Marks",
                    // "English 10th Grade"    => "English 10th Grade",
                    // "English 10th mark obtained"    => "English 10th mark obtained",
                    
                    // "Physics 10+2 Marks"    => "Physics 10+2 Marks",
                    // "Physics 10+2 Grade"    => "Physics 10+2 Grade",
                    // "Physics 10+2 mark obtained"    => "Physics 10+2 mark obtained",

                    // "Chemistry 10+2 Marks"    => "Chemistry 10+2 Marks",
                    // "Chemistry 10+2 Grade"    => "Chemistry 10+2 Grade",
                    // "Chemistry 10+2 mark obtained"    => "Chemistry 10+2 mark obtained",
                    
                    // "Mathematics 10+2 Marks"    => "Mathematics 10+2 Marks",
                    // "Mathematics 10+2 Grade"    => "Mathematics 10+2 Grade",
                    // "Mathematics 10+2 mark obtained"    => "Mathematics 10+2 mark obtained",

                    // "English 10+2 Marks"    => "English 10+2 Marks",
                    // "English 10+2 Grade"    => "English 10+2 Grade",
                    // "English 10+2 mark obtained"    => "English 10+2 mark obtained",

                    // "Statistics 10+2 Mark"    => "Statistics 10+2 Mark",
                    // "Statistics 10+2 Mark"    => "Statistics 10+2 Mark",
                    // "Statistics 10+2 Mark"    => "Statistics 10+2 Mark",

                    // "Biology 10+2 Marks"    => "Biology 10+2 Marks",
                    // "Biology 10+2 Grade"    => "Biology 10+2 Grade",
                    // "Biology 10+2 mark obtained"    => "Biology 10+2 mark obtained",
                    
                    // "10th total marks"         => "10th total marks",
                    // "10th marks obtained"      => "10th marks obtained",
                    // "10th % / CGPA"         => "10th % / CGPA",

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
                    "Proposed Area Of Resarch"    => "Proposed Area Of Resarch",
                    "qualified_national_level_test"  => "qualified_national_level_test",
                    "qualified_national_level_test_mark" => "qualified_national_level_test_mark",
                    "cuet_roll_no" => "cuet_roll_no",
                    "cuet_form_no" => "cuet_form_no",
                    "cuet_year" => "cuet_year",
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
                foreach($new_applications as $key =>$application){
                    $row = [
                        "Sl. No."               => ($key + $additional_no + 1),
                        "CUET-UG/CUET-PG"       => $application->is_cuet_ug==1 ? "CUET-UG":''/* . $application->is_cuet_pg==1 ? "CUET-PG":'' */,
                        "Application No."       => $application->application_no,
                        "Student No"            => $application->student_id,
                        "Admit Roll No"         => courseRollNo($application),
                        "Roll No"               => $application->admission_receipt ? $application->admission_receipt->roll_number : "",                       
                        
                        "Jee Application No"    => $application->application_academic->jee_roll_no,  
                        "Jee Roll No"           => $application->application_academic->jee_form_no, 
                        "GAT B Score"           => $application->application_academic->gat_b_score,

                        "Admission Category"    => ( $application->admission_receipt && $application->admission_receipt->admission_category )? $application->admission_receipt->admission_category->name : "",
                        "Name of the Applicant" => $application->fullname,
                        "Gender"                => $application->gender,
                        "Course_code"           => courseCodeOnly($application),
                        "Preference"            => coursePreference($application),
                        "Programm Applied"      => courseCode($application),
                        "Application Status"    => courseStatus($application),
                        "Exam center code"      => $application->ExamCenter->center_code??"NA",
                        "Exam center Name"      => $application->ExamCenter->center_name??"NA",
                        "Father's Name"         => $application->father_name,
                        "Address"               => $this->getApplicationPermanentAddress($application),
                        "Email"                 => $application->student->email,
                        "Contact No."           => $application->student->mobile_no,
                        "D.O.B"                 => dateFormat($application->dob),
                        "Category"              => $application->caste->name ?? "NA",
                        "PWD"                   => $application->is_pwd ? "Yes" : "No",  
                        "Permanent Address"     => "CO:".$application->permanent_co."/ House No:".$application->permanent_house_no."/ Locaity:".$application->permanent_street_locality."/ Village or Town".$application->permanent_village_town."/ P.O:".$application->permanent_po."/ State:".$application->permanent_state."/ District:".$application->permanent_district."/ Pin:".$application->permanent_pin,
                        "Correspondence Address"=> "CO:".$application->correspondence_co."/ House No:".$application->correspondence_house_no."/ Locaity:".$application->correspondence_street_locality."/ Village or Town".$application->correspondence_village_town."/ P.O:".$application->correspondence_po."/ State:".$application->correspondence_state."/ District:".$application->correspondence_district."/ Pin:".$application->correspondence_pin,
                        // "English 10th Marks"    => $application->application_academic->english_mark_10_total_mark ?? "NA",
                        // "English 10th Grade"    => $application->application_academic->english_mark_10_grade ?? "NA",
                        // "English 10th mark obtained"    => $application->application_academic->english_mark_10 ?? "NA",
                        
                        // "Physics 10+2 Marks"    => $application->application_academic->physics_total_mark ?? "NA",
                        // "Physics 10+2 Grade"    => $application->application_academic->physics_grade ?? "NA",
                        // "Physics 10+2 mark obtained"    => $application->application_academic->physics_mark ?? "NA",

                        // "Chemistry 10+2 Marks"    => $application->application_academic->chemistry_total_mark ?? "NA",
                        // "Chemistry 10+2 Grade"    => $application->application_academic->chemistry_grade ?? "NA",
                        // "Chemistry 10+2 mark obtained"    => $application->application_academic->chemistry_mark ?? "NA",
    
                        // "Mathematics 10+2 Marks"    => $application->application_academic->mathematics_total_mark ?? "NA",
                        // "Mathematics 10+2 Grade"    => $application->application_academic->mathematics_grade ?? "NA",
                        // "Mathematics 10+2 mark obtained"    => $application->application_academic->mathematics_mark ?? "NA",
    
                        // "English 10+2 Marks"    => $application->application_academic->english_total_mark ?? "NA",
                        // "English 10+2 Grade"    => $application->application_academic->english_grade ?? "NA",
                        // "English 10+2 mark obtained"    => $application->application_academic->english_mark ?? "NA",
    
                        // "Statistics 10+2 Mark"    => $application->application_academic->statistics_total_mark ?? "NA",
                        // "Statistics 10+2 Grade"    => $application->application_academic->statistics_grade ?? "NA",
                        // "Statistics 10+2 mark obtained"    => $application->application_academic->statistics_mark ?? "NA",
    
                        // "Biology 10+2 Marks"    => $application->application_academic->biology_total_mark ?? "NA",
                        // "Biology 10+2 Grade"    => $application->application_academic->biology_grade ?? "NA",
                        // "Biology 10+2 mark obtained"    => $application->application_academic->biology_mark ?? "NA",
                        
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
                        // "Proposed Area Of Resarch" => ($application->application_academic->proposed_area_of_research) ?
                        // implode(",", $application->application_academic->proposed_area_of_research) :"NA",
                        "Proposed Area Of Research" => is_array($application->application_academic->proposed_area_of_research) ? implode(", ", $application->application_academic->proposed_area_of_research) : "NA",

                       
                        "qualified_national_level_test" => $application->application_academic->qualified_national_level_test?? "NA",
                        'qualified_national_level_test_mark' => $application->application_academic->qualified_national_level_test_mark??"NA",
                        "cuet_roll_no" => $application->application_academic->cuet_roll_no?? "NA",
                        "cuet_form_no" => $application->application_academic->cuet_form_no?? "NA",
                        "cuet_year" => $application->application_academic->cuet_year?? "NA",
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
    public function ExportApplicationAsZip(Builder $applications)
    {
        if (!$applications->count()) {
            return redirect()->back()->with("error", "Sorry records not found.");
        }
        
        $zip = new ZipArchive;

        $fileName         ='applications.zip';
        $public_file_name = public_path($fileName);
        $archive_flag = file_exists($public_file_name) ? ZipArchive::OVERWRITE : ZipArchive::CREATE;
        if ($zip->open($public_file_name, $archive_flag) === true) {
            $applications->chunk(500,  function($new_applications) use (&$zip) {
                foreach($new_applications as $key => $application){
                    [$pdf, $filename] = ApplicationService::getPDF($application);
                    // dd("reached--> LINE --> ".__LINE__);
                    $zip->addFromString($filename,$pdf) ;
                    // unlink($pdf);
                }
            });
            
            $zip->close();
        } else {
            die("unable to create/modify the file");
        }
        
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . "applications-".time() . ".zip");
        header('Content-Length: ' . filesize($public_file_name));
        readfile($public_file_name);
        unlink($public_file_name);
        exit;
        // die("reached");
        return response()->download($public_file_name);
    }
}
