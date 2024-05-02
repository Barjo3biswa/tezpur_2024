<?php

namespace App\Http\Controllers;

use App\AdditionalAcademicInformation;
use App\Course;
use App\Models\AdmissionCategory;
use App\Models\AdmissionReceipt;
use App\Models\AdmitCard;
use App\Models\Application;
use App\Models\Caste;
use App\Models\ExamCenter;
use App\Models\MeritList;
use App\Models\Program;
use App\OtherQualification;
use App\Traits\ApplicationExport;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestingController extends Controller
{
    use ApplicationExport;

    private $courses;
    private $castes;
    private $examinationCeners;
    private $admission_category;

    public function __construct()
    {
        $this->castes            = Caste::get();
        $this->courses           = Course::withTrashed()->get();
        $this->admission_category= AdmissionCategory::pluck("name", "id")->toArray();
        $this->examinationCeners = ExamCenter::get();
    }

    public function reportEFL()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2G');
        /*  \DB::listen(function($query){
        \Log::info($query->sql);
        }); */
        $applications = Application::where([
            ["payment_status", 1]
            // you can add payment status also
        ])
        ->whereIn("session_id", [10])
        ->withoutGlobalScopes()
        ->with(["applied_courses", "application_academic", "student", "attachments", "applied_courses", "other_qualifications", "online_payments_succeed:application_id,id,created_at,payment_type"])->limit(1); // change this to limit the number of records
        Excel::create('EFL ', function ($excel) use ($applications) {
            $excel->sheet('EFL ' . getActiveSession()->name, function ($sheet) use ($applications) {
                $sheet->setTitle('EFL');

                $sheet->cells('A1:DC1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setAutoSize(false);
                // $sheet->fromArray($arr, null, 'A1', false, true);
                $arr = [
                    "Name"               => "Name",
                    "Programme"          => "Programme",
                    // programme
                    "Pref1"              => "Pref1",
                    "Pref2"              => "Pref2",
                    "Pref3"              => "Pref3",
                    "Pref4"              => "Pref4",
                    "Pref5"              => "Pref5",
                    "Pref6"              => "Pref6",
                    // jee details
                    "JEERollNo"          => "JEERollNo",
                    "JEEFormNo"          => "JEEFormNo",
                    "JEEYear"            => "JEEYear",
                    //
                    "Caste"              => "Caste",
                    "PWD"                => "PWD",
                    "KM"                 => "KM",
                    // guardians
                    "GuardianName"       => "GuardianName",
                    "GuardianOcp"        => "GuardianOcp",
                    "MotherName"         => "MotherName",
                    "MotherOcp"          => "MotherOcp",
                    "CO(O)"              => "CO(O)",
                    "Village(O)"         => "Village(O)",
                    "PO(O)"              => "PO(O)",
                    "PIN(O)"             => "PIN(O)",
                    "District(O)"        => "District(O)",
                    "State(O)"           => "State(O)",
                    "HNo(O)"             => "HNo(O)",
                    "Street(O)"          => "Street(O)",
                    "CO(P)"              => "CO(P)",
                    "Village(P)"         => "Village(P)",
                    "PO(P)"              => "PO(P)",
                    "PIN(P)"             => "PIN(P)",
                    "District(P)"        => "District(P)",
                    "State(P)"           => "State(P)",
                    "HNo(P)"             => "HNo(P)",
                    "Street(P)"          => "Street(P)",
                    "Nationality"        => "Nationality",
                    "Domicile"           => "Domicile",
                    "Residence"          => "Residence",
                    "DOB"                => "DOB",
                    "Religion"           => "Religion",
                    "Gender"             => "Gender",
                    "MaritalStatus"      => "MaritalStatus",
                    "Organization"       => "Organization",
                    "JobNature"          => "JobNature",
                    "JobType"            => "JobType",
                    "DOA"                => "DOA",
                    "Hostel"             => "Hostel",
                    "10thBoard"          => "10thBoard",
                    "10thYear"           => "10thYear",
                    "10thGrade"          => "10thGrade",
                    "10thSubTaken"       => "10thSubTaken",
                    "10thCGPA"           => "10thCGPA",
                    "10thPer"            => "10thPer",
                    "10thRemarks"        => "10thRemarks",
                    "12thBoard"          => "12thBoard",
                    "12thYear"           => "12thYear",
                    "12thGrade"          => "12thGrade",
                    "12thSubTaken"       => "12thSubTaken",
                    "12thCGPA"           => "12thCGPA",
                    "12thPer"            => "12thPer",
                    "12thRemarks"        => "12thRemarks",
                    "DegreeExam"         => "DegreeExam",
                    "DegreeBoard"        => "DegreeBoard",
                    "DegreeYear"         => "DegreeYear",
                    "DegreeGrade"        => "DegreeGrade",
                    "DegreeSubTaken"     => "DegreeSubTaken",
                    "DegreeCGPA"         => "DegreeCGPA",
                    "DegreePer"          => "DegreePer",
                    "DegreeRemarks"      => "DegreeRemarks",
                    "DegreeMajor"        => "DegreeMajor",
                    "DegreeCGPAMajor"    => "DegreeCGPAMajor",
                    "DegreeCGPAPer"      => "DegreeCGPAPer",
                    "PGExam"             => "PGExam",
                    "PGBoard"            => "PGBoard",
                    "PGYear"             => "PGYear",
                    "PGGrade"            => "PGGrade",
                    "PGSubTaken"         => "PGSubTaken",
                    "PGCGPA"             => "PGCGPA",
                    "PGPer"              => "PGPer",
                    "PGRemarks"          => "PGRemarks",
                    "OtherExam"          => "OtherExam",
                    "OtherBoard"         => "OtherBoard",
                    "OtherYear"          => "OtherYear",
                    "OtherGrade"         => "OtherGrade",
                    "OtherSubTaken"      => "OtherSubTaken",
                    "OtherCGPA"          => "OtherCGPA",
                    "OtherPer"           => "OtherPer",
                    "OtherRemarks"       => "OtherRemarks",
                    "Medal"              => "Medal",
                    "Training"           => "Training",
                    "OtherInfo"          => "OtherInfo",
                    "Punished"           => "Punished",
                    "DetailsFurnished"   => "DetailsFurnished",
                    "MobileNo"           => "MobileNo",
                    "Email"              => "Email",
                    "FormNo"             => "FormNo",
                    "DateOfVerification" => "DateOfVerification",
                    "RollNo"             => "RollNo",
                    "RegistrationDate"   => "RegistrationDate",
                    "DispatchDate"       => "DispatchDate",
                    "QualifyingExam"     => "QualifyingExam",
                    "QRollNo"            => "QRollNo",
                    "QYear"              => "QYear",
                    "PRC"                => "PRC",
                    "AdmitJEE"           => "AdmitJEE",
                    "GATE"               => "GATE",
                    "Center"             => "Center",
                    "TransactionDate"    => "TransactionDate",
                    "ApplicationId"    => "ApplicationId",
                    "RegistrationId"    => "RegistrationId",
                    "FamilyIncome" => "FamilyIncome",
                    "FatherIncome" => "FatherIncome",
                    "MotherIncome" => "MotherIncome"


                ];
                $sheet->appendRow($arr);
                $additional_no = 0;
                $arr           = [];

                $applications->chunkById(1000, function ($new_applications) use ($sheet, &$additional_no, &$arr) {
                    // foreach($applications->cursor() as $application){
                    $otherObj = new OtherQualification();
                    foreach ($new_applications as $key => $application) {
                        $other_qualifications = $application->other_qualifications->count() ? $application->other_qualifications->first() : $otherObj;
                        try {
                            $obj                  = [
                                "Name"               => $application->full_name,
                                "Programme"          => "", //$this->getCourseName($application->applied_courses),
                                // programme
                                "Pref1"              => "",
                                "Pref2"              => "",
                                "Pref3"              => "",
                                "Pref4"              => "",
                                "Pref5"              => "",
                                "Pref6"              => "",
                                // jee details
                                "JEERollNo"          => $application->application_academic->jee_roll_no ? $this->convertNull($application->application_academic->jee_roll_no):'null',
                                "JEEFormNo"          => $application->application_academic->jee_form_no ? $this->convertNull($application->application_academic->jee_form_no):'null',
                                "JEEYear"            => $application->application_academic->jee_year ? $this->convertNull($application->application_academic->jee_year) : 'null',
                                //
                                "Caste"              => $this->getCasteName($application),
                                "PWD"                => $application->is_pwd ? "Yes" : "No",
                                "KM"                 => $application->is_kmigrant ? "Yes" : "No",
                                // guardians
                                "GuardianName"       => $this->convertNull($application->guardian_name),
                                "GuardianOcp"        => $this->convertNull($application->guardian_occupation),
                                "MotherName"         => $this->convertNull($application->mother_name),
                                "MotherOcp"          => $this->convertNull($application->mother_occupation),
                                "CO(O)"              => $this->convertNull($application->correspondence_co),
                                "Village(O)"         => $this->convertNull($application->correspondence_village_town),
                                "PO(O)"              => $this->convertNull($application->correspondence_po),
                                "PIN(O)"             => $this->convertNull($application->correspondence_pin),
                                "District(O)"        => $this->convertNull($application->correspondence_district),
                                "State(O)"           => $this->convertNull($application->correspondence_state),
                                "HNo(O)"             => $this->convertNull($application->correspondence_house_no),
                                "Street(O)"          => $this->convertNull($application->correspondence_street_locality),
                                "CO(P)"              => $this->convertNull($application->permanent_co),
                                "Village(P)"         => $this->convertNull($application->permanent_village_town),
                                "PO(P)"              => $this->convertNull($application->permanent_po),
                                "PIN(P)"             => $this->convertNull($application->permanent_pin),
                                "District(P)"        => $this->convertNull($application->permanent_district),
                                "State(P)"           => $this->convertNull($application->permanent_state),
                                "HNo(P)"             => $this->convertNull($application->permanent_house_no),
                                "Street(P)"          => $this->convertNull($application->permanent_street_locality),
                                "Nationality"        => $this->convertNull($application->nationality),
    
                                "Domicile"           => $this->convertNull($application->permanent_state),
    
                                "Residence"          => $this->convertNull($application->place_residence),
                                "DOB"                => $this->convertNull($application->dob),
                                "Religion"           => $this->convertNull($application->religion),
                                "Gender"             => $this->convertNull($application->gender),
                                "MaritalStatus"      => $this->convertNull($application->marital_status),
                                "Organization"       => $this->convertNull($application->employment_details),
                                "JobNature"          => "NA",
                                "JobType"            => "NA",
                                "DOA"                => "01/01/1970",
                                "Hostel"             => $application->is_accomodation_needed ? "Yes" : "No",
    
                                // 10th information
                                "10thBoard"          => $this->convertNull($application->application_academic->academic_10_board),
                                "10thYear"           => $this->convertNull($application->application_academic->academic_10_year),
                                "10thGrade"          => $this->convertNull($application->application_academic->academic_10_grade),
                                "10thSubTaken"       => $this->convertNull($application->application_academic->academic_10_subject),
                                "10thCGPA"           => $this->convertNull($application->application_academic->academic_10_cgpa),
                                "10thPer"            => $this->convertNull($application->application_academic->academic_10_percentage),
                                "10thRemarks"        => $this->convertNull($application->application_academic->academic_10_remarks),
    
                                // HS information
                                "12thBoard"          => $this->convertNull($application->application_academic->academic_12_board),
                                "12thYear"           => $this->convertNull($application->application_academic->academic_12_year),
                                "12thGrade"          => $this->convertNull($application->application_academic->academic_12_grade),
                                "12thSubTaken"       => $this->convertNull($application->application_academic->academic_12_grade),
                                "12thCGPA"           => $this->convertNull($application->application_academic->academic_12_cgpa),
                                "12thPer"            => $this->convertNull($application->application_academic->academic_12_percentage),
                                "12thRemarks"        => $this->convertNull($application->application_academic->academic_12_remarks),
    
                                // Degree gardauation information
                                "DegreeExam"         => $this->convertNull($application->application_academic->academic_bachelor_degree),
                                "DegreeBoard"        => $this->convertNull($application->application_academic->academic_graduation_board),
                                "DegreeYear"         => $this->convertNull($application->application_academic->academic_graduation_year),
                                "DegreeGrade"        => $this->convertNull($application->application_academic->academic_graduation_grade),
                                "DegreeSubTaken"     => $this->convertNull($application->application_academic->academic_graduation_subject),
                                "DegreeCGPA"         => $this->convertNull($application->application_academic->academic_graduation_cgpa),
                                "DegreePer"          => $this->convertNull($application->application_academic->academic_graduation_percentage),
                                "DegreeRemarks"      => $this->convertNull($application->application_academic->academic_graduation_remarks),
                                "DegreeMajor"        => $this->convertNull($application->application_academic->acadmeic_graduation_major),
                                "DegreeCGPAMajor"    => $this->convertNull($application->application_academic->academic_graduation_cgpa),
                                "DegreeCGPAPer"      => $this->convertNull($application->application_academic->academic_graduation_percentage),
    
                                // PG Degree information
                                "PGExam"             => $this->convertNull($application->application_academic->academic_post_graduation_degree),
                                "PGBoard"            => $this->convertNull($application->application_academic->academic_post_graduation_board),
                                "PGYear"             => $this->convertNull($application->application_academic->academic_post_graduation_year),
                                "PGGrade"            => $this->convertNull($application->application_academic->academic_post_graduation_grade),
                                "PGSubTaken"         => $this->convertNull($application->application_academic->academic_post_graduation_subject),
                                "PGCGPA"             => $this->convertNull($application->application_academic->academic_post_graduation_cgpa),
                                "PGPer"              => $this->convertNull($application->application_academic->academic_post_graduation_percentage),
                                "PGRemarks"          => $this->convertNull($application->application_academic->academic_post_graduation_remarks),
    
                                // other examination details
                                "OtherExam"          => $other_qualifications->exam_name ?? "",
                                "OtherBoard"         => $other_qualifications->board_name ?? "",
                                "OtherYear"          => $other_qualifications->passing_year ?? "",
                                "OtherGrade"         => $other_qualifications->class_grade ?? "",
                                "OtherSubTaken"      => $other_qualifications->subjects_taken ?? "",
                                "OtherCGPA"          => $other_qualifications->cgpa ?? "",
                                "OtherPer"           => $other_qualifications->marks_percentage ?? "",
                                "OtherRemarks"       => $other_qualifications->remarks ?? "",
    
                                "Medal"              => $application->application_academic->is_academic_prizes ? "Yes" : "",
                                "Training"           => "",
                                "OtherInfo"          => $application->application_academic->other_information ? $this->convertNull($application->application_academic->other_information ):'null',
                                "Punished"           => $application->application_academic->is_punishedm ? "Yes" : "No",
                                "DetailsFurnished"   => $this->convertNull($application->application_academic->furnish_details),
                                "MobileNo"           => $application->student->mobile_no,
                                "Email"              => $application->student->email,
                                "FormNo"             => $application->application_no,
                                "DateOfVerification" => $application->accepted_at ? date("d/m/Y", strtotime($application->accepted_at)) : "",
                                "RollNo"             => $application->student->roll_number,
                                "RegistrationDate"   => date("d/m/Y", strtotime($application->student->created_at)),
                                "DispatchDate"       => "",
                                "QualifyingExam"     => "",
                                "QRollNo"            => "",
                                "QYear"              => "",
                                "PRC"                => $application->attachments->where("doc_name", "prc_certificate")->count() ? "Yes" : "no",
                                "AdmitJEE"           => $application->attachments->where("doc_name", "jee_admit_card")->count() ? "Yes" : "no",
                                "GATE"               => $application->attachments->where("doc_name", "gate_score_card")->count() ? "Yes" : "no",
                                "Center"             => $this->getCenterName($application),
                                "TransactionDate"    => $application->online_payments_succeed->first()->created_at ? date("d/m/Y", strtotime($application->online_payments_succeed->first()->created_at)) : 'N/A',
                                "ApplicationId"    => $application->id ?? 'N/A',
                                "RegistrationId"    => $application->student->id ?? 'N/A',
                                "FamilyIncome" => $application->family_income ?? 'N/A',
                                "FatherIncome" => $application->father_income ?? 'N/A',
                                "MotherIncome" => $application->mother_income ?? 'N/A',
                            ];
                        } catch (\Throwable $th) {
                            dd($th);
                        }
                        foreach ($application->applied_courses as $key1 => $applied_cours) {
                            if ($key1 == 0) {
                                $obj["Programme"] = $this->getCourseName($applied_cours);
                            } else {
                                $obj["Pref" . $key1] = $this->getCourseName($applied_cours);
                            }
                        }
                        // $arr[] = $obj;
                        $sheet->appendRow($obj);
                        // $additional_no += 500;
                        // }
                    }
                });
                // $sheet->fromArray($arr, null, 'A1', false, true);

            });
        })->download('xlsx');
        // debug($applications);
        // $applications->dd();
        return "welcome";
    }
    private function getCourseName($applied_course)
    {
        return $this->courses->where("id", $applied_course->course_id)->first()->name;
    }
    private function getCasteName(Application $application)
    {
        return $this->castes->where("id", $application->caste_id)->first()->name ?? "";
    }
    private function getCenterName(Application $application)
    {
        return $this->examinationCeners->where("id", $application->exam_center_id)->first()->center_name ?? "";
    }
    private function convertNull($value)
    {
        return $value == 'null' ? "" : $value;
    }

    public function arvhiceUndertaking()
    {
    	 set_time_limit(360);
        ini_set('memory_limit', '2G');
        
        $merit_lists = MeritList::whereIn("course_id", \btechCourseIds())
            ->with(["undertakings:merit_list_id,destination_path,doc_name,status,undertaking_link"])
            ->select(["id", "student_id", "application_no"])
            ->whereHas("undertakings")
            ->get();
        // dd($merit_lists);
        $zip_file = storage_path('undertakings.zip'); // Name of our archive to download
        // Initializing PHP class
        $zip = new \ZipArchive();
        if($zip->open($zip_file, \ZipArchive::CREATE || \ZipArchive::OVERWRITE)!==TRUE){
            exit("cannot open <$zip_file>\n");
        }
        foreach ($merit_lists as $key => $merit) {
            foreach($merit->undertakings as $file){
                // Adding file: second parameter is what will the path inside of the archive
                // So it will create another folder called "storage/" inside ZIP, and put the file there.
                $zip->addFile($this->convertPath($file->destination_path."/".$file->undertaking_link), $this->convertPath("undertakings/".str_replace("/","-",$merit->application_no)."/".$file->undertaking_link));
                // dd("welcome");
                // dump($this->convertPath($file->destination_path."/".$file->undertaking_link));
                // dump($this->convertPath("undertakings/".str_replace("/","-",$merit->application_no)."/".$file->undertaking_link));
                // dd("reached");
            }
        }
        $zip->close();
        // dd("reached");
        // We return the file immediately after download
        $zip_file = str_replace("\\", "/", $zip_file);
        return response()->download($zip_file);
    }
    private function convertPath($path){
    	return str_replace("\\", "/", $path);
    }
    public function admittedStudents()
    {
        $course_ids = env("AD_COURSE_IDS", []);
        $course_ids = explode(",", $course_ids);
        $receipts = AdmissionReceipt::query();
        $receipts->whereIn("course_id", $course_ids);
        $receipts->whereHas("application");
        $receipts = $receipts->with(["application", "course", "student"]);
        Excel::create('Admitted Sudents List ', function ($excel) use ($receipts) {
            $excel->sheet(env("ADMITTED_SHEET_NAME"), function ($sheet) use ($receipts) {
                $sheet->setTitle(env("ADMITTED_SHEET_NAME"));

                $sheet->cells('A1:N1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setAutoSize(false);
                $arr = [
                    "Recipt No"             => "Recipt No",
                    "Application No."       => "Application No.",
                    "Reg No"                => "Reg No",
                    "Roll No"               => "Roll No",
                    "Name of the Applicant" => "Name of the Applicant",
                    "Admitted Programm"     => "Admitted Programm",
                    "Father's Name"         => "Father's Name",
                    "Address"               => "Address",
                    "Email"                 => "Email",
                    "Contact No."           => "Contact No.",
                    "D.O.B"                 => "D.O.B",
                    "Admission Category"    => "Admission Category",
                    "S. Category"           => "S.Category",
                    "Total Fee"             => "Total Fee",
                    "Admitted At"           => "Admitted At",
                ];

                $sheet->appendRow($arr);

                foreach ($receipts->cursor() as $key => $receipt) {
                    $arr = [
                        "Recipt No"             => $receipt->receipt_no,
                        "Application No."       => $receipt->application->application_no,
                        "Reg No"                => $receipt->student_id,
                        "Roll No"               => $receipt->roll_number,
                        "Name of the Applicant" => $receipt->application->fullname,
                        "Admitted Programm"     => $this->getCourseNameNew($receipt->course_id),
                        "Father's Name"         => $receipt->application->father_name,
                        "Address"               => $this->getApplicationPermanentAddress($receipt->application),
                        "Email"                 => $receipt->student->email,
                        "Contact No."           => $receipt->student->mobile_no,
                        "D.O.B"                 => dateFormat($receipt->application->dob),
                        "Admission Category"    => $this->getCategory($receipt->category_id),
                        "S.Category"            => $this->getCaste($receipt->application->caste_id),
                        "Total Fee"             => $receipt->total,
                        "Admitted At"           => date("Y-m-d", strtotime($receipt->created_at)),
                    ];

                    $sheet->appendRow($arr);

                }
            });
        })->download("xlsx");
    }
    private function getCategory($id){
        return $this->admission_category[$id] ?? "NA";
    }
    private function getCaste($id){
        return $this->castes->where("id",$id)->count() ? $this->castes->where("id",$id)->first()->name : "NA";
    }
    private function getCourseNameNew($id){
        return $this->courses->where("id",$id)->count() ? $this->courses->where("id",$id)->first()->name : "NA";
    }


    public function newReportEFL()
    {
        // $this->OtherExam(21205);
        $excel    = AdmitCard::where('is_report_reg',1) ->OrderBy('exam_center_id')->orderby('course_id')->get();
        $fileName = 'REPORT.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        );
        $columns = array('SL', 
                        'Roll No',	
                        'Student Name',
                        'P Code',
                        'Program Name',
                        'C Code',
                        'Center Name',

                        'gender',
                        'marital_status',
                        'religion',
                        'nationality',
                        'Is Migrant',
                        'Is Jammu & kashmir Student',
                        'is_ex_servicement',
                        'Caste Id',
                        'Sub Caste',
                        'dob',
                        'adhaar',           
                        'abc',
                        'father_name',
                        'father_occupation',
                        'father_qualification',
                        'father_income',
                        'father_mobile',
                        'father_email',
                        'mother_name',
                        'mother_occupation',
                        'mother_qualification',
                        'mother_income',
                        'permanent_co',
                        'permanent_house_no',
                        'permanent_street_locality',
                        'mother_email',
                        'mother_mobile',
                        'permanent_village_town',
                        'permanent_po',
                        'permanent_state',
                        'permanent_district',
                        'permanent_pin',
                        'same_address',
                        'permanent_contact_number',
                        'correspondence_co',
                        'correspondence_house_no',
                        'correspondence_street_locality',
                        'correspondence_village_town',
                        'correspondence_po',
                        'correspondence_state',
                        'correspondence_district',
                        'correspondence_pin',
                        'correspondence_contact_number',
                        'person_with_disablity',
                        'pwd_percentage',
                        'is_pwd',
                        'is_bpl',
                        'is_minority',
                        'is_accomodation_needed',
                        'is_employed',
                        'diclaration_accept',
                        'application_no',
                        'guardian_name',
                        'guardian_email',
                        'guardian_phone',
                        'guardian_occupation',
                        'is_aplly_defense_quota',
                        'family_income',
                        'bank_ac_no',
                        'account_holder_name',
                        'bank_name',
                        'branch_name',
                        'ifsc_code',
                        'bank_reg_mobile_no',

                        'academic_10_board',
                        'academic_10_year',
                        'academic_10_grade',
                        'academic_10_subject',
                        'academic_10_exam_type',
                        'academic_10_total_mark',
                        'academic_10_mark_obtained',
                        'academic_10_cgpa',
                        'academic_10_percentage',
                        'academic_10_remarks',
                        'academic_10_is_passed_appearing',
                        'academic_12_board',
                        'academic_12_year',
                        'academic_12_grade',
                        'academic_12_subject',
                        'academic_12_exam_type',
                        'academic_12_total_mark',
                        'academic_12_mark_obtained',
                        'academic_12_cgpa',
                        'academic_12_percentage',
                        'academic_12_remarks',
                        'academic_12_is_passed_appearing',
                        'academic_12_stream',                   
                        'academic_graduation_board',
                        'academic_graduation_year',
                        'academic_graduation_grade',
                        'academic_graduation_subject',
                        'academic_graduation_exam_type',
                        'academic_graduation_total_mark',
                        'academic_graduation_mark_obtained',
                        'acadmeic_graduation_major',
                        'academic_graduation_cgpa',
                        'academic_graduation_percentage',
                        'academic_graduation_remarks',
                        'academic_graduation_is_passed_appearing',
                        'academic_post_graduation_board',
                        'academic_post_graduation_year',
                        'academic_post_graduation_grade',
                        'academic_post_graduation_subject',
                        'academic_post_graduation_exam_type',
                        'academic_post_graduation_total_mark',
                        'academic_post_graduation_mark_obtained',
                        'academic_post_graduation_cgpa',
                        'academic_post_graduation_percentage',
                        'academic_post_graduation_remarks',
                        'academic_post_graduation_is_passed_appearing',
                        'is_sport_represented',
                        'is_debarred',
                        'is_academic_prizes',
                        'is_punished',
                        'sport_played',
                        'medel_type',
                            "OtherExam",
                            "OtherBoard",
                            "OtherYear",
                            "OtherGrade",                           
                            "OtherCGPA",
                            "OtherPer",
                            "OtherRemarks",
                        );
        $callback = function () use ($excel, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $count = 0;
            foreach ($excel as $key=>$task) {                
                
                $row['SL'] = ++$key;
                $row['Roll No'] = $task->roll_no;	
                $row['Student Name'] = $task->application->getFullNameAttribute();
                $row['P Code'] = $task->course->code;
                $row['Program Name'] = $task->course->name;
                $row['C Code'] = $task->exam_center->center_code;
                $row['Center Name'] = $task->exam_center->center_name;
                    $row['gender'] = $task->application->gender??"NA";
                    $row['marital_status'] = $task->application->marital_status??"NA";
                    $row['religion'] = $task->application->religion??"NA";
                    $row['nationality'] = $task->application->nationality??"NA";
                    $row['Is Migrant'] = $task->application->is_kmigrant==0?"Yes":"No";
                    $row['Is Jammu & kashmir Student'] = $task->application->is_jk_student==0?"Yes":"No";
                    $row['is_ex_servicement'] = $task->application->is_ex_servicement==0?"Yes":"No";
                    $row['Caste Id'] = $task->application->caste->name;
                    $row['Sub Caste'] = $task->application->sub_caste??"NA";
                    $row['dob'] = $task->application->dob??"NA";
                    $row['adhaar'] = $task->application->adhaar??"NA";
                    $row['abc'] = $task->application->abc??"NA";
                    $row['father_name'] = $task->application->father_name??"NA";
                    $row['father_occupation'] = $task->application->father_occupation??"NA";
                    $row['father_qualification'] = $task->application->fatherQualification->name??"NA";
                    $row['father_income'] = $task->application->father_income??"NA";
                    $row['father_mobile'] = $task->application->father_mobile??"NA";
                    $row['father_email'] = $task->application->father_email??"NA";
                    $row['mother_name'] = $task->application->mother_name??"NA";
                    $row['mother_occupation'] = $task->application->mother_occupation??"NA";
                    $row['mother_qualification'] = $task->application->motherQualification->name??"NA";
                    $row['mother_income'] = $task->application->mother_income??"NA";
                    $row['permanent_co'] = $task->application->permanent_co??"NA";
                    $row['permanent_house_no'] = $task->application->permanent_house_no??"NA";
                    $row['permanent_street_locality'] = $task->application->permanent_street_locality??"NA";
                    $row['mother_email'] = $task->application->mother_email??"NA";
                    $row['mother_mobile'] = $task->application->mother_mobile??"NA";
                    $row['permanent_village_town'] = $task->application->permanent_village_town??"NA";
                    $row['permanent_po'] = $task->application->permanent_po??"NA";
                    $row['permanent_state'] = $task->application->per_state->name??"NA";
                    $row['permanent_district'] = $task->application->per_district->name??"NA";
                    $row['permanent_pin'] = $task->application->permanent_pin??"NA";
                    $row['same_address'] = $task->application->same_address??"NA";
                    $row['permanent_contact_number'] = $task->application->permanent_contact_number??"NA";
                    $row['correspondence_co'] = $task->application->correspondence_co??"NA";
                    $row['correspondence_house_no'] = $task->application->correspondence_house_no??"NA";
                    $row['correspondence_street_locality'] = $task->application->correspondence_street_locality??"NA";
                    $row['correspondence_village_town'] = $task->application->correspondence_village_town??"NA";
                    $row['correspondence_po'] = $task->application->correspondence_po??"NA";
                    $row['correspondence_state'] = $task->application->cor_state->name??"NA";
                    $row['correspondence_district'] = $task->application->cor_district->name??"NA";
                    $row['correspondence_pin'] = $task->application->correspondence_pin??"NA";
                    $row['correspondence_contact_number'] = $task->application->correspondence_contact_number??"NA";
                    $row['person_with_disablity'] = $task->application->person_with_disablity??"NA";
                    $row['pwd_percentage'] = $task->application->pwd_percentage??"NA";
                    $row['is_pwd'] = $task->application->is_pwd==0?"Yes":"No";
                    $row['is_bpl'] = $task->application->is_bpl==0?"Yes":"No";
                    $row['is_minority'] = $task->application->is_minority==0?"Yes":"No";
                    $row['is_accomodation_needed'] = $task->application->is_accomodation_needed==0?"Yes":"No";
                    $row['is_employed'] = $task->application->is_employed==0?"Yes":"No";
                    $row['diclaration_accept'] = $task->application->diclaration_accept==0?"Yes":"No";
                    $row['application_no'] = $task->application->application_no??"NA";
                    $row['guardian_name'] = $task->application->guardian_name??"NA";
                    $row['guardian_email'] = $task->application->guardian_email??"NA";
                    $row['guardian_phone'] = $task->application->guardian_phone??"NA";
                    $row['guardian_occupation'] = $task->application->guardian_occupation??"NA";
                    $row['is_aplly_defense_quota'] = $task->application->is_aplly_defense_quota==0?"Yes":"No";
                    $row['family_income'] = $task->application->family_income??"NA";
                    $row['bank_ac_no'] = $task->application->bank_ac_no??"NA";
                    $row['account_holder_name'] = $task->application->account_holder_name??"NA";
                    $row['bank_name'] = $task->application->bank_name??"NA";
                    $row['branch_name'] = $task->application->branch_name??"NA";
                    $row['ifsc_code'] = $task->application->ifsc_code??"NA";
                    $row['bank_reg_mobile_no'] = $task->application->bank_reg_mobile_no??"NA"; 
                        $row['academic_10_board'] =$task->academic->academic_10_board??"NA";
                        $row['academic_10_year'] =$task->academic->academic_10_year??"NA";
                        $row['academic_10_grade'] =$task->academic->academic_10_grade??"NA";
                        $row['academic_10_subject'] =$task->academic->academic_10_subject??"NA";
                        $row['academic_10_exam_type'] =$task->academic->academic_10_exam_type??"NA";
                        $row['academic_10_total_mark'] =$task->academic->academic_10_total_mark??"NA";
                        $row['academic_10_mark_obtained'] =$task->academic->academic_10_mark_obtained??"NA";
                        $row['academic_10_cgpa'] =$task->academic->academic_10_cgpa??"NA";
                        $row['academic_10_percentage'] =$task->academic->academic_10_percentage??"NA";
                        $row['academic_10_remarks'] =$task->academic->academic_10_remarks??"NA";
                        $row['academic_10_is_passed_appearing'] =$task->academic->academic_10_is_passed_appearing==0?"Appeared":"Passed";
                        $row['academic_12_board'] =$task->academic->academic_12_board??"NA";
                        $row['academic_12_year'] =$task->academic->academic_12_year??"NA";
                        $row['academic_12_grade'] =$task->academic->academic_12_grade??"NA";
                        $row['academic_12_subject'] =$task->academic->academic_12_subject??"NA";
                        $row['academic_12_exam_type'] =$task->academic->academic_12_exam_type??"NA";
                        $row['academic_12_total_mark'] =$task->academic->academic_12_total_mark??"NA";
                        $row['academic_12_mark_obtained'] =$task->academic->academic_12_mark_obtained??"NA";
                        $row['academic_12_cgpa'] =$task->academic->academic_12_cgpa??"NA";
                        $row['academic_12_percentage'] =$task->academic->academic_12_percentage??"NA";
                        $row['academic_12_remarks'] =$task->academic->academic_12_remarks??"NA";
                        $row['academic_12_is_passed_appearing'] =$task->academic->academic_12_is_passed_appearing==0?"Appeared":"Passed";
                        $row['academic_12_stream'] =$task->academic->academic_12_stream??"NA";                   
                        $row['academic_graduation_board'] =$task->academic->academic_graduation_board??"NA";
                        $row['academic_graduation_year'] =$task->academic->academic_graduation_year??"NA";
                        $row['academic_graduation_grade'] =$task->academic->academic_graduation_grade??"NA";
                        $row['academic_graduation_subject'] =$task->academic->academic_graduation_subject??"NA";
                        $row['academic_graduation_exam_type'] =$task->academic->academic_graduation_exam_type??"NA";
                        $row['academic_graduation_total_mark'] =$task->academic->academic_graduation_total_mark??"NA";
                        $row['academic_graduation_mark_obtained'] =$task->academic->academic_graduation_mark_obtained??"NA";
                        $row['acadmeic_graduation_major'] =$task->academic->acadmeic_graduation_major??"NA";
                        $row['academic_graduation_cgpa'] =$task->academic->academic_graduation_cgpa??"NA";
                        $row['academic_graduation_percentage'] =$task->academic->academic_graduation_percentage??"NA";
                        $row['academic_graduation_remarks'] =$task->academic->academic_graduation_remarks??"NA";
                        $row['academic_graduation_is_passed_appearing'] =$task->academic->academic_graduation_is_passed_appearing==0?"Appeared":"Passed";
                        $row['academic_post_graduation_board'] =$task->academic->academic_post_graduation_board??"NA";
                        $row['academic_post_graduation_year'] =$task->academic->academic_post_graduation_year??"NA";
                        $row['academic_post_graduation_grade'] =$task->academic->academic_post_graduation_grade??"NA";
                        $row['academic_post_graduation_subject'] =$task->academic->academic_post_graduation_subject??"NA";
                        $row['academic_post_graduation_exam_type'] =$task->academic->academic_post_graduation_exam_type??"NA";
                        $row['academic_post_graduation_total_mark'] =$task->academic->academic_post_graduation_total_mark??"NA";
                        $row['academic_post_graduation_mark_obtained'] =$task->academic->academic_post_graduation_mark_obtained??"NA";
                        $row['academic_post_graduation_cgpa'] =$task->academic->academic_post_graduation_cgpa??"NA";
                        $row['academic_post_graduation_percentage'] =$task->academic->academic_post_graduation_percentage??"NA";
                        $row['academic_post_graduation_remarks'] =$task->academic->academic_post_graduation_remarks??"NA";
                        $row['academic_post_graduation_is_passed_appearing'] =$task->academic->academic_post_graduation_is_passed_appearing==0?"Appeared":"Passed";
                        $row['is_sport_represented'] =$task->academic->is_sport_represented==0?"No":"Yes";
                        $row['is_debarred'] =$task->academic->is_debarred==0?"No":"Yes";
                        $row['is_academic_prizes'] =$task->academic->is_academic_prizes==0?"No":"Yes";
                        $row['is_punished'] =$task->academic->is_punished==0?"No":"Yes";
                        $row['sport_played'] =$task->academic->sport_played??"NA";
                        $row['medel_type'] =$task->academic->medel_type??"NA";	
                            $row["OtherExam"]=  $this->OtherExam($task->application_id);
                            $row["OtherBoard"]= $this->OtherBoard($task->application_id);
                            $row["OtherYear"]=  $this->OtherYear($task->application_id);
                            $row["OtherGrade"]= $this->OtherGrade($task->application_id);                          
                            $row["OtherCGPA"]=      $this->OtherCGPA($task->application_id);
                            $row["OtherPer"]=       $this->OtherPer($task->application_id);
                            $row["OtherRemarks"]=   $this->OtherRemarks($task->application_id);
               fputcsv($file, array(
                                                $row['SL'],
                                                $row['Roll No'],	
                                                $row['Student Name'],
                                                $row['P Code'],
                                                $row['Program Name'],
                                                $row['C Code'],
                                                $row['Center Name'], 
                                                $row['gender'],
                                                $row['marital_status'],
                                                $row['religion'],
                                                $row['nationality'],
                                                $row['Is Migrant'],
                                                $row['Is Jammu & kashmir Student'],
                                                $row['is_ex_servicement'],
                                                $row['Caste Id'],
                                                $row['Sub Caste'],
                                                $row['dob'],
                                                $row['adhaar'],
                                                $row['abc'],
                                                $row['father_name'],
                                                $row['father_occupation'],
                                                $row['father_qualification'],
                                                $row['father_income'],
                                                $row['father_mobile'],
                                                $row['father_email'],
                                                $row['mother_name'],
                                                $row['mother_occupation'],
                                                $row['mother_qualification'],
                                                $row['mother_income'],
                                                $row['permanent_co'],
                                                $row['permanent_house_no'],
                                                $row['permanent_street_locality'],
                                                $row['mother_email'],
                                                $row['mother_mobile'],
                                                $row['permanent_village_town'],
                                                $row['permanent_po'],
                                                $row['permanent_state'],
                                                $row['permanent_district'],
                                                $row['permanent_pin'],
                                                $row['same_address'],
                                                $row['permanent_contact_number'],
                                                $row['correspondence_co'],
                                                $row['correspondence_house_no'],
                                                $row['correspondence_street_locality'],
                                                $row['correspondence_village_town'],
                                                $row['correspondence_po'],
                                                $row['correspondence_state'],
                                                $row['correspondence_district'],
                                                $row['correspondence_pin'],
                                                $row['correspondence_contact_number'],
                                                $row['person_with_disablity'],
                                                $row['pwd_percentage'],
                                                $row['is_pwd'],
                                                $row['is_bpl'],
                                                $row['is_minority'],
                                                $row['is_accomodation_needed'],
                                                $row['is_employed'],
                                                $row['diclaration_accept'],
                                                $row['application_no'],
                                                $row['guardian_name'],
                                                $row['guardian_email'],
                                                $row['guardian_phone'],
                                                $row['guardian_occupation'],
                                                $row['is_aplly_defense_quota'],
                                                $row['family_income'],
                                                $row['bank_ac_no'],
                                                $row['account_holder_name'],
                                                $row['bank_name'],
                                                $row['branch_name'],
                                                $row['ifsc_code'],
                                                $row['bank_reg_mobile_no'], 
                                                
                                                $row['academic_10_board'],
                                                $row['academic_10_year'],
                                                $row['academic_10_grade'],
                                                $row['academic_10_subject'],
                                                $row['academic_10_exam_type'],
                                                $row['academic_10_total_mark'],
                                                $row['academic_10_mark_obtained'],
                                                $row['academic_10_cgpa'],
                                                $row['academic_10_percentage'],
                                                $row['academic_10_remarks'],
                                                $row['academic_10_is_passed_appearing'],
                                                $row['academic_12_board'],
                                                $row['academic_12_year'],
                                                $row['academic_12_grade'],
                                                $row['academic_12_subject'],
                                                $row['academic_12_exam_type'],
                                                $row['academic_12_total_mark'],
                                                $row['academic_12_mark_obtained'],
                                                $row['academic_12_cgpa'],
                                                $row['academic_12_percentage'],
                                                $row['academic_12_remarks'],
                                                $row['academic_12_is_passed_appearing'],
                                                $row['academic_12_stream'],                   
                                                $row['academic_graduation_board'],
                                                $row['academic_graduation_year'],
                                                $row['academic_graduation_grade'],
                                                $row['academic_graduation_subject'],
                                                $row['academic_graduation_exam_type'],
                                                $row['academic_graduation_total_mark'],
                                                $row['academic_graduation_mark_obtained'],
                                                $row['acadmeic_graduation_major'],
                                                $row['academic_graduation_cgpa'],
                                                $row['academic_graduation_percentage'],
                                                $row['academic_graduation_remarks'],
                                                $row['academic_graduation_is_passed_appearing'],
                                                $row['academic_post_graduation_board'],
                                                $row['academic_post_graduation_year'],
                                                $row['academic_post_graduation_grade'],
                                                $row['academic_post_graduation_subject'],
                                                $row['academic_post_graduation_exam_type'],
                                                $row['academic_post_graduation_total_mark'],
                                                $row['academic_post_graduation_mark_obtained'],
                                                $row['academic_post_graduation_cgpa'],
                                                $row['academic_post_graduation_percentage'],
                                                $row['academic_post_graduation_remarks'],
                                                $row['academic_post_graduation_is_passed_appearing'],
                                                $row['is_sport_represented'],
                                                $row['is_debarred'],
                                                $row['is_academic_prizes'],
                                                $row['is_punished'],
                                                $row['sport_played'],
                                                $row['medel_type'],
                                                $row["OtherExam"],
                                                $row["OtherBoard"],
                                                $row["OtherYear"],
                                                $row["OtherGrade"],                                               
                                                $row["OtherCGPA"],
                                                $row["OtherPer"],
                                                $row["OtherRemarks"],
                        ));
            }
            fclose($file);
            // dd($file);
        };
        // dd("ok");
        return response()->stream($callback, 200, $headers);
    }

    public function OtherExam($id){
        $other = OtherQualification::where('application_id',$id)->orderBy('id')->get();
        $array = implode(" ", $other->map(function($item){
                return $item->exam_name."<br/>";
        })->toArray());
        return $array;
    }
    public function OtherBoard($id){
        $other = OtherQualification::where('application_id',$id)->orderBy('id')->get();
        $array = implode(" ", $other->map(function($item){
            return $item->board_name."<br/>";
        })->toArray());
        return $array;
    }
    public function OtherYear($id){
        $other = OtherQualification::where('application_id',$id)->orderBy('id')->get();
        $array = implode(" ", $other->map(function($item){
            return $item->passing_year."<br/>";
        })->toArray());
        return $array;
    }
    public function OtherGrade($id){
        $other = OtherQualification::where('application_id',$id)->orderBy('id')->get();
        $array = implode(" ", $other->map(function($item){
            return $item->class_grade."<br/>";
        })->toArray());
        return $array;
    }
    public function OtherCGPA($id){
        $other = OtherQualification::where('application_id',$id)->orderBy('id')->get();
        $array = implode(" ", $other->map(function($item){
            return $item->cgpa."<br/>";
        })->toArray());
        return $array;
    }
    public function OtherPer($id){
        $other = OtherQualification::where('application_id',$id)->orderBy('id')->get();
        $array = implode(" ", $other->map(function($item){
            return $item->marks_percentage."<br/>";
        })->toArray());
        return $array;
    }
    public function OtherRemarks($id){
        $other = OtherQualification::where('application_id',$id)->orderBy('id')->get();
        $array = implode(" ", $other->map(function($item){
            return $item->remarks."<br/>";
        })->toArray());
        return $array;
    }

    public function missedData(){

        return view('tezu-important-data');
    }
    public function missedDataSave(Request $request){
        $validate=AdditionalAcademicInformation::where('roll_no',$request->roll_no)->count();
        if($validate>0){
            return redirect()->back()->with('error','Already Inserted Your Records.');
        }
        $validatedData = $request->validate([
            "roll_no" => "required|max:255",
            "application_no" => "required|max:255",
            "class_x" => "required|numeric",
            "class_xii" => "required|numeric",
            "first" => "required|numeric",
            "second" => "required|numeric",
            "third" => "required|numeric",
            "fourth" => "required|numeric",
            "fifth" => "numeric",
            "sixth" => "numeric",
            "first_tot" => "required|numeric",
            "second_tot" => "required|numeric",
            "third_tot" => "required|numeric",
            "fourth_tot" => "required|numeric",
            "fifth_tot" => "numeric",
            "sixth_tot" => "numeric",
        ]);

        $data=[
            "roll_no" => $request->roll_no,
            "application_no" => $request->application_no,
            "class_x" => $request->class_x,
            "class_xii" => $request->class_xii,
            "first" => $request->first,
            "second" => $request->second,
            "third" => $request->third,
            "fourth" => $request->fourth,
            "fifth" => $request->fifth??"0",
            "sixth" => $request->sixth??"0",
            "first_total" => $request->first_tot,
            "second_total" => $request->second_tot,
            "third_total" => $request->third_tot,
            "fourth_total" => $request->fourth_tot,
            "fifth_total" => $request->fifth_tot??"0",
            "sixth_total" => $request->sixth_tot??"0",
        ];
        AdditionalAcademicInformation::create($data);
        return redirect()->back()->with('message','Successfully Saved Your Information.');
    }

    public function shortlistedCandidate(){
        $shortlisted = Program::whereHas('shortlistedCandidate')->withTrashed()->get();
        // dd($shortlisted);
        return view('shortlisted-candidate',compact('shortlisted'));
    }
}
