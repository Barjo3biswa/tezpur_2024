<?php

use App\AppliedCourse;
use App\Country;
use App\Course;
use App\Department;
use App\DepartmentalPermission;
use App\DepartmentAssignedPermission;
use App\DepartmentAssignedUser;
use App\GenerateAppno;
use App\Models\AdmitCard;
use App\Models\Application;
use App\Models\Caste;
use App\Models\CourseSeat;
use App\Models\EligibilityQuestion;
use App\Models\MeritList;
use App\Models\MeritMaster;
use App\Models\OnlinePaymentSuccess;
use App\Models\RePaymentSuccess;
use App\Models\Setting;
use  App\Models\Session;
use App\Models\User;
use App\Models\ExamCenter;

use Illuminate\Database\Eloquent\Builder;

function asset_public($path, $secure = null)
{
    return asset($path, $secure);
    return asset("public/" . $path, $secure);
}

function dateFormat($dateTime, $format = "d-m-Y")
{
    if ($dateTime == "0000-00-00" || $dateTime == "0000-00-00 00:00:00") {
        return " ";
    }
    $date = strtotime($dateTime);
    if (date('d-m-Y', $date) != '01-01-1970') {
        return date($format, $date);
    } else {
        return " ";
    }
}

function sendSMS($mobile_no, $message, $template_id = null, $use_second_credentials = false)
{
    if (env("OTP_DRIVER") == "log") {
        \Log::info(["message" => $message, "mobile_no" => $mobile_no]);
        return true;
    }
    // ?data=<message-submit-request><username>numaligarhrefinery</username><password>123456</password><sender-id>NRLSMS</sender-id><MType>TXT</MType><message-text><text>" + message + "</text><to>" + ph + "</to></message-text></message-submit-request>
    $user = env('SMS_USER');
    $password = env('SMS_PASSWORD');
    $senderid = env('SMS_SENDERID');
    if($use_second_credentials){
        $user = env('SMS_USER_2');
        $password = env('SMS_PASSWORD_2');
        $senderid = env('SMS_SENDERID_2');
    }
    $url = env('SMS_URL');
    $app_name = env('APP_NAME');
    $message = urlencode($message);
    // if(is_array($mobile_no)){
    //     $mobile_no = "91".implode(",91", $mobile_no);
    // }else
    if($mobile_no > 10){
        $mobile_no = $mobile_no;
    }elseif($mobile_no == 10){
        $mobile_no = "91" .$mobile_no;
    }
    //$curl_url = $url . "?user=$user&password=$password&mobiles=$mobile_no&sms=" . $message . "&senderid=" . $senderid."&tempid={$template_id}";
    $curl_url = $url.env('SMS_SENDERID')."/".$mobile_no."/".$message."/TXT?apikey=".env('SMS_API_KEY')."&dltentityid=".env('SMS_ENTITY_ID')."&dlttempid=".$template_id;
    $smsInit = curl_init($curl_url);
    // $smsInit = curl_init($url . "?data=<message-submit-request><username>" . $user . "</username><password>" . $password . "</password><sender-id>" . $senderid . "</sender-id><MType>TXT</MType><message-text><text>" . $message . "</text><to>" . $mobile_no . "</to></message-text></message-submit-request>");
   
    curl_setopt($smsInit, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($smsInit);
    \Log::info("SMS Sending to: ".$mobile_no);
    \Log::info("Message: ".$message);
    \Log::info($res);
    return $res;
}



function saveLogs($user_id, $username, $guard, $activity, $save_to_log = false)
{
    $log = [];
    $log['user_id'] = $user_id;
    $log['username'] = $username;
    $log['guard'] = $guard;
    $log['activity'] = $activity;
    $log['url'] = substr(\Request::fullUrl(), 0, 250);
    $log['method'] = \Request::method();
    $log['ip'] = \Request::ip();
    $log['agent'] = \Request::header('user-agent');
    if ($save_to_log) {
        return \Log::info($log);
    }
    \App\Models\DailyLog::create($log);
}
function getActiveSession()
{
    $session = \App\Models\Session::where("is_active", 1)->first();
    if ($session) {
        return $session;
    }
    abort(400, "Session not active.");
    return (object) ["name" => "NA"];
}
function getActiveSessionApplication()
{
    $session = getActiveSession();
    $other_active_sessions = config("vknrl.optional_active_session_ids");
    $ids = [];
    if($other_active_sessions){
        $ids = explode(",", $other_active_sessions);
    }
    $ids[] = $session->id;
    if ($session->name !== "NA") {
        $application = \App\Models\Application::whereIn("session_id", $ids)
            ->where("student_id", auth()->guard("student")->user()->id)
            ->first();
        return $application;
    }
    return [];
}
function current_date_time()
{
    return date("Y-m-d h:i:s");
}
function get_guard()
{
    if (Auth::guard('admin')->check()) {return "admin";} elseif (Auth::guard('student')->check()) {return "student";}elseif(Auth::guard('department_user')->check()){return "department_user";} else {
        return "";
    }

}
function get_route_guard()
{
    if (Auth::guard('admin')->check()) {return "admin";} elseif (Auth::guard('student')->check()) {return "student";}elseif(Auth::guard('department_user')->check()){return "department";} elseif(Auth::guard('finance')->check()){return "finance";}else {
        return "";
    }

}
function getApplicationPermanentAddress($application)
{
    return "Vill/Town:" . $application->permanent_village_town . '</br> PS: ' . $application->permanent_ps . '</br> PO: ' . $application->permanent_po . '</br> Dist: ' . $application->permanent_district . '</br> State: ' . $application->permanent_state . '- ' . $application->permanent_pin;
}
function getCorrespondencePermanentAddress($application)
{
    return "Vill/Town:" . $application->correspondence_village_town . '</br> PS: ' . $application->correspondence_ps . '</br> PO: ' . $application->correspondence_po . '</br> Dist: ' . $application->correspondence_district . '</br> State: ' . $application->correspondence_state . '- ' . $application->correspondence_pin;
}
function totalRegisterdUser($session_id)
{
    return \App\Models\User::where('session_id',$session_id)->count();
}



function getTotalApplicationCountDepartmentOld($session_id, $progtype, $status )
{
    $statuses = \App\Models\Application::$statuses_for_admin;
    $applications = \App\Models\Application::query();
    if($progtype=="UG"){
        $applications->where('is_cuet_ug',1);
    }
    if($progtype=="PG"){
        $applications->where('is_cuet_pg',1);
    }

    $program_array=programmes_array();
    $programs=[];
    foreach($program_array as $key=>$prog){
        if($key!=""){
            array_push($programs, $key);
        }
    }
    $statusarr=['accepted','on_hold','rejected', "null"];
    if($status == "accepted" || $status == "on_hold" || $status == "rejected"){
        $statusarr=[$status];
    }
    $application = $applications->whereHas("applied_courses", function($query) use($statusarr, $programs){
        return $query->whereIn("status", $statusarr)
            ->whereIn('course_id', $programs);
        })->with(["applied_courses"=>function($q) use($statusarr, $programs){
            return $q->whereIn("status", $statusarr)->whereIn('course_id', $programs);
        }]);
    return $applications->where('session_id',$session_id)->count();
}


function getTotalApplicationCount($session_id)
{
    $statuses = \App\Models\Application::$statuses_for_admin;
    $applications = \App\Models\Application::where('session_id',$session_id);
    // $session= DB::table('sessions')->where('is_active',1)->first();
    
    

    if(auth("department_user")->check()){
        $applications->whereIn("status", $statuses);
        $applications = application_dept_filters($applications);
    }
    // if(auth("department_user")->check()){
    //     $applications->whereIn("status", $statuses);
    // }
    return $applications->count();
}


function applicatinEditPermission($application)
{
    $guard = get_guard();
    if ($guard == "student") {
        if ($application->student_id != auth($guard)->user()->id) {
            return false;
        }
    } elseif ($guard == "web") {
        return false;
    }
    return true;
}
function isEditAvailable($application)
{
    if (auth("admin")->check()) {
        return true;
    }
    if ($application->status == "application_submitted" || $application->is_editable) {
        return true;
    } else {
        return false;
    }
}
function isDeleteAvailable($application){
    if(isEditAvailable($application) && $application->status == "application_submitted"){
        return true;
    }
    if($application->status == "payment_pending"){
        return true;
    }
    return false;
}
function checkAnmDataEntered($application)
{
    $application = collect($application->toArray());
    $common_class = new \App\Http\Controllers\CommonApplicationController();
    return $common_class->checkAnmDataEntered($application);
}

function getIndianCurrency(float $number)
{
    if ($number == 0) {
        return 'Zero only';
    }
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else {
            $str[] = null;
        }

    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise . " only";
}
function findDateDiff($date_from, $date_two)
{
    $date_from = dateFormat($date_from, "Y-m-d H:i:s");
    $date_two = dateFormat($date_two, "Y-m-d H:i:s");
    $date1 = new DateTime($date_from);
    $date2 = $date1->diff(new DateTime($date_two));
    return [
        "years" => $date2->y,
        "days" => $date2->m,
        "months" => $date2->d,
    ];
    // echo $date2->days.'Total days'."\n";
    // echo $date2->y.' years'."\n";
    // echo $date2->m.' months'."\n";
    // echo $date2->d.' days'."\n";
    // echo $date2->h.' hours'."\n";
    // echo $date2->i.' minutes'."\n";
    // echo $date2->s.' seconds'."\n";
}

/**
 * Helper library for CryptoJS AES encryption/decryption
 * Allow you to use AES encryption on client side and server side vice versa
 *
 * @author BrainFooLong (bfldev.com)
 * @link https://github.com/brainfoolong/cryptojs-aes-php
 */
/**
 * Decrypt data from a CryptoJS json encoding string
 *
 * @param mixed $passphrase
 * @param mixed $jsonString
 * @return mixed
 */
function cryptoJsAesDecrypt($passphrase, $jsonString)
{
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv = hex2bin($jsondata["iv"]);
    } catch (Exception $e) {
        return null;
    }
    $ct = base64_decode($jsondata["ct"]);
    $concatedPassphrase = $passphrase . $salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return $data;
    return json_decode($data, true);
}
/**
 * Encrypt value to a cryptojs compatiable json encoding string
 *
 * @param mixed $passphrase
 * @param mixed $value
 * @return string
 */
function cryptoJsAesEncrypt($passphrase, $value)
{
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx . $passphrase . $salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv = substr($salted, 32, 16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}
function dumpp($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
function QrCode()
{
    return new \CodeItNow\BarcodeBundle\Utils\QrCode();
}
function BarCode()
{
    return new \CodeItNow\BarcodeBundle\Utils\BarcodeGenerator();
}
function detectBrowser($agent)
{
    if (strpos($agent, 'MSIE') !== false) {
        echo 'Internet explorer';
    } elseif (strpos($agent, 'Trident') !== false) //For Supporting IE 11
    {
        echo 'Internet explorer';
    } elseif (strpos($agent, 'Firefox') !== false) {
        echo 'Mozilla Firefox';
    } elseif (strpos($agent, 'Chrome') !== false) {
        echo 'Google Chrome';
    } elseif (strpos($agent, 'Opera Mini') !== false) {
        echo "Opera Mini";
    } elseif (strpos($agent, 'Opera') !== false) {
        echo "Opera";
    } elseif (strpos($agent, 'Safari') !== false) {
        echo "Safari";
    } else {
        echo 'Something else';
    }

}
function isd_codes(){
    return [
        "+91", "+975", "+977", "+880"
    ];
}

function isd_codesNew($id){
    if($id == "FOREIGN"){
        // return [
        //     "+975", "+977", "+880"
        // ];
       return [
            "+975", "+977", "+880", "+66", "+95"
        ];
    }else{
        return [
            "+91"
        ];
    }
    
}

function generateApplicationNoOld(Application $model/* , OnlinePaymentSuccess $current_payment */)
{
    \Log::info('Application No generating for id '.$model->id);
    $year                  = date("Y");
    $current_payment = $model->online_payments_succeed()
        ->where("payment_type", "application")
        ->whereYear("created_at", date("Y"))
        ->orderBy("id", "DESC")
        ->first();
    $application_count     = OnlinePaymentSuccess::where("payment_type", "=", "application")
        ->whereYear("created_at", date("Y"))
        ->where("status", 1)
        ->where("id", "<=", $current_payment->id)
        ->count();
    $applied_course        = $model->applied_courses()->with("course")->first();
    $application_serial    = str_pad($application_count, 5, "0", STR_PAD_LEFT);
    // $last_two_of_year      = substr($year, -2) + $applied_course->course->course_duration;
    // if MBA course
    if(isMbaStudent($model)){
        $year = $year + 1;
    }
    // $application_no        = $applied_course->course->code . "/" . $year . "/" . $application_serial;

    $application_no        =  "TU/" . $year . "/" . $application_serial;
    

    //indivisual application_no for each applied course
    if(!isMbaStudent($model)){
        foreach($model->applied_courses as $key=>$course){
           $course_details = Course::where('id',$course->course_id)->first();
           $application_number = $course_details->code.'/'.$application_no;
           AppliedCourse::where('id',$course->id)->update(['application_number' => $application_number]);
        }
    }
    //Ends

    \Log::info("Application no generated ".$application_no." for id ".$model->id);
    return $application_no;
    $model->application_no = $application_no;
    $model->save();
    return $model;
}


function generateApplicationNo_bef020224(Application $model/* , OnlinePaymentSuccess $current_payment */)
{
    if($model->is_mba==1){
        // \Log::info('Application No generating for id '.$model->id);
        // $year                  = date("Y");
        // $current_payment = $model->online_payments_succeed()
        //     ->where("payment_type", "application")
        //     ->whereYear("created_at", date("Y"))
        //     ->orderBy("id", "DESC")
        //     ->first();
        // $application_count     = OnlinePaymentSuccess::where("payment_type", "=", "application")
        //     ->whereYear("created_at", date("Y"))
        //     ->where("status", 1)
        //     ->where("id", "<=", $current_payment->id)
        //     ->count();
        // $applied_course        = $model->applied_courses()->with("course")->first();
        // $application_serial    = str_pad($application_count, 5, "0", STR_PAD_LEFT);
        // if(isMbaStudent($model)){
        //     $year = $year + 1;
        // }
        // $application_no        =  "TU/" . $year . "/" . $application_serial;
        // if(!isMbaStudent($model)){
        //     foreach($model->applied_courses as $key=>$course){
        //     $course_details = Course::where('id',$course->course_id)->first();
        //     $application_number = $course_details->code.'/'.$application_no;
        //     AppliedCourse::where('id',$course->id)->update(['application_number' => $application_number]);
        //     }
        // }
        // \Log::info("Application no generated ".$application_no." for id ".$model->id);
        // return $application_no;
        // $model->application_no = $application_no;
        // $model->save();
        // return $model;

        $year = date("Y");
        $flag=0;
        while($flag < 1){
            $currentsession= Session::where('is_active',1)->pluck('id');
            $max_application_no = Application::/* where('session_id', $currentsession[0])
                                -> */select(DB::raw("SUBSTRING_INDEX(application_no, '/', -1) AS application_no_num"))
                                // ->where('is_mba',0)
                                ->orderBy('application_no_num', 'desc')
                                ->value('application_no_num');
            $new_application_no_num = sprintf("%05d", $max_application_no !== null ? intval($max_application_no) + 1 : 1);
            $application_no = "TU/" . $year . "/" . $new_application_no_num;
            $check=Application::where('application_no',$application_no)->count();
            if($check==0){
                $flag=1;
            }
        }


        // if(!isMbaStudent($model)){ 
            foreach($model->applied_courses as $key=>$course){
                $course_details = Course::where('id',$course->course_id)->withTrashed()->first();
                $application_number = $course_details->code.'/'.$application_no;
                AppliedCourse::where('id',$course->id)->update(['application_number' => $application_number]);
            }
        // }
        \Log::info("Application no generated ".$application_no." for id ".$model->id);
        return $application_no;
        $model->application_no = $application_no;
        $model->save();
        return $model;
    }else{

        $year = date("Y");
        $flag=0;
        while($flag < 1){
            $currentsession= Session::where('is_active',1)->pluck('id');
            $max_application_no = Application::/* where('session_id', $currentsession[0])
                                -> */select(DB::raw("SUBSTRING_INDEX(application_no, '/', -1) AS application_no_num"))
                                // ->where('is_mba',0)
                                ->orderBy('application_no_num', 'desc')
                                ->value('application_no_num');

            // Increment the numerical part of the application number by 1
            $new_application_no_num = sprintf("%05d", $max_application_no !== null ? intval($max_application_no) + 1 : 1);
            // Generate the new application number by concatenating the parts
            $application_no = "TU/" . $year . "/" . $new_application_no_num;
            $check=Application::where('application_no',$application_no)->count();
            // dd($application_no);
            if($check==0){
                $flag=1;
            }
        }
        
        // $currentsession= Session::where('is_active',1)->pluck('id');
        // // dd($currentsession[0]);
        // $max_application_no = Application::where('session_id', $currentsession[0])
        //                     ->select(DB::raw("SUBSTRING_INDEX(application_no, '/', -1) AS application_no_num"))
        //                     ->where('is_mba',0)
        //                     ->orderBy('application_no_num', 'desc')
        //                     ->value('application_no_num');

        // // Increment the numerical part of the application number by 1
        // $new_application_no_num = sprintf("%05d", $max_application_no !== null ? intval($max_application_no) + 1 : 1);
        // // Generate the new application number by concatenating the parts
        // $application_no = "TU/" . date('Y') . "/" . $new_application_no_num;
        if(!isMbaStudent($model)){ 
            foreach($model->applied_courses as $key=>$course){
                $course_details = Course::where('id',$course->course_id)->withTrashed()->first();
                $application_number = $course_details->code.'/'.$application_no;
                AppliedCourse::where('id',$course->id)->update(['application_number' => $application_number]);
            }
        }
        \Log::info("Application no generated ".$application_no." for id ".$model->id);
        return $application_no;
        $model->application_no = $application_no;
        $model->save();
        return $model;
    }
}




function document_uploaded_check($documents, $doc_name)
{
    if($documents){
        if($documents->where("doc_name", $doc_name)->count()){
            return true;
        }
    }
    return false;
}
function applicant_global_filter(Builder $query)
{
    return  $query->when(request("aplicant_name"), function ($name_filter) {
            $return_query = $name_filter;
            $return_query->where(function ($where_query) {
                $name         = request("aplicant_name");
                $name_array   = [];
                $name_array[] = $name;
                $name_array[] = str_replace(" ", "  ", $name);
                foreach ($name_array as $name) {
                    $where_query->orWhereRaw(DB::raw("CONCAT_WS(' ',first_name, middle_name, last_name) LIKE  '%" . $name . "%'"));
                }
            });
            return $return_query;
        });
}
function countries_array()
{
    return ["" => "--SELECT--"] + Country::pluck("name", "id")->toArray();
}
function departments_array()
{
    return ["" => "--SELECT--"] + Department::orderBy("name", "ASC")->pluck("name", "id")->toArray();
}
function programmes_array()
{
    $programmes = Course::query();
    $department_wise = departments_user_wise();
    // dd($department_wise);
    $programmes->where('FilterFlag',1)->withTrashed()->when(auth("department_user")->check(), function($query){
        if(in_array(auth("department_user")->id(), [1, 35])){
            return $query->whereIn("id", btechCourseIds());
            // all english except chinese
        }elseif(in_array(auth("department_user")->id(),[41])){
            $query->where(function($query){
                return $query->where("id", 2);
            });
            // all english except chinese
        }elseif(in_array(auth("department_user")->id(),[40])){
            $query->where(function($query){
                return $query->where("id", "!=", 2);
            });
        }elseif(in_array(auth("department_user")->id(),[42])){
            $query->whereIn("id", [89,90,91,92,93,94,95]);
        }elseif(in_array(auth("department_user")->id(),[178,180,181,182])){
            $query->whereIn("id", [72,73,74,75,76,77,83,111]);
        }
        // else{            
        //     $query->whereNotIn("id", btechCourseIds());
        // }
        return $query->whereIn("department_id", departments_user_wise());
    });
    $programmes->when(!auth("student")->check(), function($query){
        return $query->withTrashed();
    });
    return ["" => "--SELECT--"] + $programmes->orderBy("name", "ASC")->pluck("name", "id")->toArray();
}


function courseCode(Application $application, $show_first = false){
    
    $applied_courses = $application->applied_courses;
    $course = $application->applied_courses->first()->course;
    if(!$show_first){
        
        $applied_courses = $application->applied_courses->sortBy("preference");
        // $course_code = $course->name." (".$course->code.")<br/>";
        // if($applied_courses->count() > 1){
            $course_code = implode(" ", $applied_courses->map(function($item) use ($course){
                // if($course->id != $item->course->id){
                    return "(".$item->preference.") ".$item->course->name ." (".$item->course->code.")<br/>";
                // }
            })->toArray());
        // }
        return $course_code;
    }
    if(!$course->sub_preference){
        if($course->preference){
            $applied_courses = $application->applied_courses->sortBy("preference");
            $course_code = $course->name." (".$course->code.")";
            $course_code .= " Preference:" .implode(",", $applied_courses->map(function($item) use ($course){
                if($course->id != $item->course->id){
                    return /* "(".$item->preference.") ". */$item->course->name ." (".$item->course->code.")";
                }
            })->toArray());
            return $course_code;
        }
        return $course->name." (".$course->code.")";
    }
    $child_with_parents = $course->child()->count();
    if($application->applied_courses->count() == $child_with_parents){
        return $course->name." (".$course->code.")";
    }
    $applied_courses = $application->applied_courses->sortBy("preference");
    $course_code = implode(",", $applied_courses->map(function($item) use ($course){
        if($course->id != $item->course->id){
            return $item->course->name ." (".$item->course->code.")";
        }
    })->toArray());
    return $course_code;
}

function courseCodeOnly(Application $application, $show_first = false){
    
    $applied_courses = $application->applied_courses;
    $course = $application->applied_courses->first()->course;
    if(!$show_first){
        
        $applied_courses = $application->applied_courses->sortBy("preference");
        // $course_code = $course->name." (".$course->code.")<br/>";
        // if($applied_courses->count() > 1){
            $course_code = implode(" ", $applied_courses->map(function($item) use ($course){
                // if($course->id != $item->course->id){
                    return $item->course->code;
                // }
            })->toArray());
        // }
        return $course_code;
    }
    if(!$course->sub_preference){
        if($course->preference){
            $applied_courses = $application->applied_courses->sortBy("preference");
            $course_code = $course->name." (".$course->code.")";
            $course_code .= " Preference:" .implode(",", $applied_courses->map(function($item) use ($course){
                if($course->id != $item->course->id){
                    return $item->course->code;
                }
            })->toArray());
            return $course_code;
        }
        return $course->code;
    }
    $child_with_parents = $course->child()->count();
    if($application->applied_courses->count() == $child_with_parents){
        return $course->code;
    }
    $applied_courses = $application->applied_courses->sortBy("preference");
    $course_code = implode(",", $applied_courses->map(function($item) use ($course){
        if($course->id != $item->course->id){
            return $item->course->code;
        }
    })->toArray());
    return $course_code;
}


function courseRollNo(Application $application, $show_first = false){
    
    $applied_courses = $application->applied_courses;
    $course = $application->applied_courses->first()->course;
    if(!$show_first){
        
        $applied_courses = $application->applied_courses->sortBy("preference");
        // $course_code = $course->name." (".$course->code.")<br/>";
        // if($applied_courses->count() > 1){
            $course_code = implode(" ", $applied_courses->map(function($item) use ($course){
                // if($course->id != $item->course->id){
                    return $item->admitcard->roll_no??"NA";
                // }
            })->toArray());
        // }
        return $course_code;
    }
    if(!$course->sub_preference){
        if($course->preference){
            $applied_courses = $application->applied_courses->sortBy("preference");
            $course_code = $course->name." (".$course->code.")";
            $course_code .= " Preference:" .implode(",", $applied_courses->map(function($item) use ($course){
                if($course->id != $item->course->id){
                    return $item->admitcard->roll_no??"NA";
                }
            })->toArray());
            return $course_code;
        }
        return $course->admitcard->roll_no??"NA";
    }
    $child_with_parents = $course->child()->count();
    if($application->applied_courses->count() == $child_with_parents){
        return $course->admitcard->roll_no??"NA";
    }
    $applied_courses = $application->applied_courses->sortBy("preference");
    $course_code = implode(",", $applied_courses->map(function($item) use ($course){
        if($course->id != $item->course->id){
            return $item->admitcard->roll_no??"NA";
        }
    })->toArray());
    return $course_code;
}
function coursePreference(Application $application, $show_first = false){
    
    $applied_courses = $application->applied_courses;
    $course = $application->applied_courses->first()->course;
    if(!$show_first){
        
        $applied_courses = $application->applied_courses->sortBy("preference");
        // $course_code = $course->name." (".$course->code.")<br/>";
        // if($applied_courses->count() > 1){
            $course_code = implode(" ", $applied_courses->map(function($item) use ($course){
                // if($course->id != $item->course->id){
                    return $item->preference/* .$item->course->name */ /* ." (".$item->course->code.")" */;
                // }
            })->toArray());
        // }
        return $course_code;
    }
    if(!$course->sub_preference){
        if($course->preference){
            $applied_courses = $application->applied_courses->sortBy("preference");
            $course_code = $course->name." (".$course->code.")";
            $course_code .= " Preference:" .implode(",", $applied_courses->map(function($item) use ($course){
                if($course->id != $item->course->id){
                    return $item->preference/* .$item->course->name  ." (".$item->course->code.")"*/;
                }
            })->toArray());
            return $course_code;
        }
        return $course->name." (".$course->code.")";
    }
    $child_with_parents = $course->child()->count();
    if($application->applied_courses->count() == $child_with_parents){
        return $course->name." (".$course->code.")";
    }
    $applied_courses = $application->applied_courses->sortBy("preference");
    $course_code = implode(",", $applied_courses->map(function($item) use ($course){
        if($course->id != $item->course->id){
            return $item->course->name ." (".$item->course->code.")";
        }
    })->toArray());
    return $course_code;
}

function getSiteSettingValue($field_name = "currency")
{
    $record = Setting::select(["name", "value", "value_type"])->where("name", $field_name)->first();
    if(!$record){
        throw new Exception("Setting value {$field_name} not found.", 300);
    }
    if($record->value_type =="float"){
        return (float)$record->value;
    }elseif($record->value_type =="string"){
        return (string)$record->value;
    }else{
        return (int)$record->value;
    }
}

function getTotalCollection($active_session_id){
    $collections1 = OnlinePaymentSuccess::where("biller_status", "captured")
        ->whereHas("application", function($query) use ($active_session_id){
            $query->where("session_id", $active_session_id)->where('is_mba',0)->where('is_btech',0);
        })->where('payment_type','application')->sum("amount");
    $collections2 = RePaymentSuccess::where("biller_status", "captured")
        ->whereHas("application", function($query) use ($active_session_id){
            $query->where("session_id", $active_session_id)->where('is_mba',0)->where('is_btech',0);
        })
        ->sum("amount");
    return $collections1 + $collections2;
}

function getTotalCollectionMBA($active_session_id){
    $collections1 = OnlinePaymentSuccess::where("biller_status", "captured")
        ->whereHas("application", function($query) use ($active_session_id){
            $query->where("session_id", $active_session_id)->where('is_mba',1)->orWhere('is_btech',1);
        })->where('payment_type','application')->sum("amount");
    $collections2 = RePaymentSuccess::where("biller_status", "captured")
        ->whereHas("application", function($query) use ($active_session_id){
            $query->where("session_id", $active_session_id)->where('is_mba',1)->orWhere('is_btech',1);
        })
        ->sum("amount");
    return $collections1 + $collections2;
}


function getcategories(){
    return \App\Models\Application::groupBy('caste_id')->whereNotNull('application_no')->count();

}

function departments_user_wise(){
    return DepartmentAssignedUser::where("department_user_id", auth("department_user")->id())
                                    ->pluck("department_id", "department_id")->toArray();
}
function btechCourseIds(){
    return [72, 73, 74, 75, 76, 77, 111];
}
function application_dept_filters($application){

    $department_ids = departments_user_wise();
    // dd($department_ids);
    $program_array=programmes_array();
    $programs=[];
        foreach($program_array as $key=>$prog){
                if($key!=""){
                array_push($programs, $key);
                }
        }
    // dd($programs);
    return $application->whereHas("applied_courses", function($query) use ($department_ids,$programs){
        // for btech department only selected applied courese

        // return $query->whereIn("course_id", $programs);

        if(in_array(auth("department_user")->id(),[1,35])){
            return $query->whereIn("course_id", btechCourseIds());
        }elseif(in_array(auth("department_user")->id(),[42])){
            // for phd ADF 
            $query->whereIn("course_id", [89,90,91,92,93,94,95]);
        }elseif(in_array(auth("department_user")->id(),[41])){
            // all english except chinese
            $query->whereIn("course_id", [2]);
        }elseif(in_array(auth("department_user")->id(),[40])){
            $query->whereNotIn("course_id", [2]);
        }
        //else{
        //    $query->whereNotIn("course_id", btechCourseIds())/* ->whereIn("course_id",$programs) */;
        //}
        return $query->whereHas("course", function($course_query) use ($department_ids){
            return $course_query->whereIn("department_id", $department_ids);
        });
    });
   /*  return $application->whereHas("courses", function($query) use($department_ids){
        $query->whereIn("department_id", $department_ids);
        if(in_array(auth("department_user")->id(),[1,35])){
            $query->where(function($query){
                foreach(btechCourseIds() as $btech_course_id){
                    $query->orWhere("courses.id", $btech_course_id);
                }
            });
        // for certificaate in chinse course
        }elseif(in_array(auth("department_user")->id(),[41])){
            $query->where(function($query){
                return $query->where("courses.id", 2);
            });
        // all english except chinese
        }elseif(in_array(auth("department_user")->id(),[40])){
            $query->where(function($query){
                return $query->where("courses.id", "!=", 2);
            });
        }else{
            $query->where(function($query){
                foreach(btechCourseIds() as $btech_course_id){
                    $query->orWhere("courses.id", "!=",$btech_course_id);
                }
            });
        }
        return $query;
    }); */
}
function errorFixing()
{
    $applications = Application::whereNull("application_no")
    ->whereHas("online_payments_succeed", function($query){
        return $query->where("biller_status", "captured")->where("status", 1);
    })
    ->with("online_payments_succeed")
    ->get();
    $counter = 0;
    $application_ids = [];
    foreach($applications as $key => $application){
        $application->application_no = generateApplicationNo($application);
        $application->payment_status = 1;
        $application->save();
        $application_ids[] = $application->id;
        $counter++;
        sendApplicationNoSMSNew($application);
    }
    $message = $counter." Application is fixed. ".json_encode($application_ids);
    Log::info($message);
    return $message;
}
function sendApplicationNoSMSNew($application)
    {
        $message = "Your application number is ".$application->application_no." generated.";
        $mobile = $application->student->isd_code.$application->student->mobile_no;
        try {
            sendSMS($mobile, $message);
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
function admissionCategoryID($name){
    $admission = '\App\Models\AdmissionCategory'::select('id')->where('name',$name)->first();
    return  $admission->id;
}
function isAvailableForFileUpload($application){
    
    if (!$application->is_extra_doc_uploaded) {
        return true;
    }
    return false;

}
function getTotaMale($session_id){
    //$session=  \App\Models\Session::where('is_active ',1)->first();
    // $session= DB::table('sessions')->where('is_active',1)->first();

    //dd($session->id);die;
    return \App\Models\Application::where('gender','Male')->where('session_id',$session_id)->whereNotNull('application_no')->count();

}
function getTotafeMale($session_id){
    // $session= DB::table('sessions')->where('is_active',1)->first();


  return \App\Models\Application::groupBy('gender')->where('session_id',$session_id)->whereNotNull('application_no')->count();


}
function getGenderWiseApplication($session_id){
    return \App\Models\Application::groupBy('gender')
        ->where('session_id',$session_id)
        ->select( DB::raw("gender, count(*) as total"))
        ->whereNotNull('application_no')
        ->get();
}
function totalRegistersession($session_id)
{
    // $totalRegister = DB::table('users')->get()->pluck('id')->toArray();

    //$totalRegister= \App\Models\User::get();
    // $session= DB::table('sessions')->where('is_active',1)->first();
    // return DB::table('applications')->whereIn('student_id',$totalRegister)->where('session_id',$session->id)->count();
    //dump($session);

    return User::where("session_id",$session_id)
    // ->where('is_mba',0)
        // ->withTrashed()
        ->count();
}


function getTotalCompletedApplicationCount($session_id)
{
    $statuses = \App\Models\Application::$statuses_for_admin;
    $applications = \App\Models\Application::query();

    // $session= DB::table('sessions')->where('is_active',1)->first();
    return $applications->whereIn("status", $statuses)->where('session_id',$session_id)->whereNotNull('application_no')->count();
}

function getTotalUGCompletedApplicationCount($session_id)
{
    $statuses = \App\Models\Application::$statuses_for_admin;
    $applications = \App\Models\Application::query();

    // $session= DB::table('sessions')->where('is_active',1)->first();
    return $applications->where('is_cuet_ug',1)->whereIn("status", $statuses)->where('session_id',$session_id)->whereNotNull('application_no')->count();
}

function getTotalPGCompletedApplicationCount($session_id, $exam_through)
{
    $statuses = \App\Models\Application::$statuses_for_admin;
    $applications = \App\Models\Application::query();

    // $session= DB::table('sessions')->where('is_active',1)->first();
    return $applications->where('is_cuet_pg',1)->whereIn("status", $statuses)->where('session_id',$session_id)->where('exam_through',$exam_through)->whereNotNull('application_no')->count();
}
function getgencast($session_id)
{
    // $session= DB::table('sessions')->where('is_active',1)->first();

    return \App\Models\Application::where('caste_id',1)
        ->completed()
        ->where('session_id',$session_id)
        ->count();

}
function getewscast($session_id)
{
    // $session= DB::table('sessions')->where('is_active',1)->first();

    return \App\Models\Application::completed()
        ->where('caste_id',2)
        ->where('session_id',$session_id)
        ->count();

}
function getobcCLcast($session_id)
{
    // $session= DB::table('sessions')->where('is_active',1)->first();

    return \App\Models\Application::completed()
        ->where('caste_id',3)
        ->where('session_id',$session_id)
        ->count();

}
function getobcNCLcast($session_id)
{
    // $session= DB::table('sessions')->where('is_active',1)->first();

    return \App\Models\Application::completed()
        ->where('caste_id',4)
        ->where('session_id',$session_id)
        ->count();

}
function getobsccast($session_id)
{
    // $session= DB::table('sessions')->where('is_active',1)->first();

    return \App\Models\Application::completed()
        ->where('caste_id',5)
        ->where('session_id',$session_id)
        ->count();

}
function getstcast($session_id)
{
    // $session= DB::table('sessions')->where('is_active',1)->first();

    return \App\Models\Application::completed()
        ->where('caste_id',6)
        ->where('session_id',$session_id)
        ->count();

}
function getFNcast($session_id)
{
    // $session= DB::table('sessions')->where('is_active',1)->first();

    return \App\Models\Application::completed()
        ->where('caste_id',7)
        ->where('session_id',$session_id)
        // ->withTrashed()
        ->count();

}
function gettotalCouser()
{
     return \App\Course::/* withTrashed()-> */count();

}
    
    function isMbaStudent(Application $application)
    {
        if($application->applied_courses->where("course_id", 80)->count()){
            return true;
        }
        return false;
    }
    function isIntegratedMCOM(Application $application)
    {
        if($application->applied_courses->whereIn("course_id", [7])->count()){
            return true;
        }
        return false;
    }
    function isIntegratedProgramm(Application $application)
    {
        $integrated_courses = [3,4,5,6,7,8,9,10,11,12,61,62];
        if($application->applied_courses->whereIn("course_id", $integrated_courses)->count()){
            return true;
        }
        return false;
    }
    function isIntegratedEnglish(Application $application)
    {
        if($application->applied_courses->whereIn("course_id", [6])->count()){
            return true;
        }
        return false;
    }
    function isMBBT(Application $application)
    {
        if($application->applied_courses->where("course_id", 35)->count()){
            return true;
        }
        return false;
    }
    function getMBAExams(){
        
        return $mba_exam_names = [
            "cat_score_card", "atma_score_card", "mat_score_card", "xat_score_card", "gmat_score_card", "cmat_score_card"
        ];
    }
    function isPHDCourse($coure_id)
    {
        $phd_course_ids = [42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,64,65,67,68,85,86,87,88,89,90,91,92,93,94,95,96];
        return in_array($coure_id, $phd_course_ids);
    }

    // value checking based on operator
    function dynamic_value_check($operator, $value1, $value2){
        $operators = EligibilityQuestion::$OPERATORS;
        if(!in_array($operator, $operators)){
            throw new Exception("{$operator} Operators does not exists.", 1);
        }
        if($operator == $operators[0]){
            return $value1 == $value2;
        }elseif($operator == $operators[1]){
            return $value1 >= $value2;
        }elseif($operator == $operators[2]){
            return $value1 <= $value2;
        }elseif($operator == $operators[3]){
            return $value1 < $value2;
        }elseif($operator == $operators[4]){
            return $value1 > $value2;
        }elseif($operator == $operators[5]){
            // any means always return true
            return true;
        }
        return false;
    }
    function dynamic_layout(){
        if(auth("student")->check())
            return 'student.layouts.auth';
        elseif(auth("admin")->check())
            return 'admin.layout.auth';
        elseif(auth("department_user")->check())
            return 'department-user.layout.auth';
    }
    function isMasterInDesign(Application $application, Array $course_id_array = []){
        $master_in_design_course_id = 84;
        if($course_id_array){
            return in_array($master_in_design_course_id, $course_id_array);
        }
        return in_array($master_in_design_course_id, $application->applied_courses->pluck("course_id")->toArray());
    }
    function dumpQuery($query){
        $sql = $query->sql;
        foreach($query->bindings as $key => $binding){
            $sql = preg_replace('/\?/', "'$binding'", $sql, 1);
        }
        dump($sql);
    }
    function showAdmissionOldLogic(MeritList $merit_list)
    {
        return !$merit_list->processing_technique || $merit_list->processing_technique == MeritMaster::$PROCESSING_MANUAL_STATUS;
    }
    function returnTermsAndCond($application, $bold_required = false) : String
    {
        $string = config("vknrl.TERMS_AND_COND");
        $string = str_replace("##name##", $bold_required ? "<strong>".$application->full_name."</strong>" : $application->full_name, $string);
        $string = str_replace("##application_no##", $bold_required ? "<strong>".$application->application_no."</strong>" : $application->application_no, $string);
        return $string;
    }
    function mbaExamNames(){
    	return [ "CAT", "MAT","ATMA", "XAT", "GMAT", "CMAT"];
    }


    function checkPermission($per_id)
    {
        if(auth("department_user")->check()){
            $id=auth()->guard("department_user")->user()->id;
            $permission=DepartmentAssignedPermission::where(['department_id'=>$id,'permission_id'=>$per_id])->first();
            if($permission==null){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }


    // function getTotalApplicationCountDepartmentII($session_id, $progtype, $status )
    // {
    //     $application = Application::with("caste", "attachments", "student", "admit_card_published", "rePaymentReceipt", "applied_courses", "applied_courses.course", "merit_list");

    //     if($progtype == "UG"){
    //         $application->where('is_cuet_ug',1);
    //     }elseif($progtype == "PG"){
    //         $application->where('is_cuet_pg',1);
    //     }

    //     // $this->applicationStatusFilter($request, $application);

    //     $application->whereHas("courses", function($course_query){
    //         return $course_query->where("department_id", request("department"));
    //     });
        
    
    //     $statusarr=['accepted','on_hold','rejected',null];
    //     if($request->has("status")&& $request->get("status") == "accepted" || $request->get("status") == "on_hold" || $request->get("status") == "rejected"){
    //         $statusarr=[$request->get("status")];
    //     }
    //     // dd($statusarr);
    //     if($request->has("program") && $request->get("program")){
    //                 $program = request("program");
    //                 $arr=[$program];
    //                 // dd($arr);
    //                 $application = $application->whereHas("applied_courses", function($query) use ($arr,$statusarr){
    //                         $query->whereIn("course_id", $arr)->whereIn('status',$statusarr);
    //                     })->with(["applied_courses"=>function($q) use($arr,$statusarr){
    //                         return $q->whereIn("course_id", $arr)->whereIn('status',$statusarr);
    //                 }]);
    //             }
    
    // $applications = $application->paginate(100);
    // }


    function applicationStatusFilter($status, Builder $application,  $programstest)
    {

        if($status === "accepted"){
                $application = $application->whereHas("applied_courses", function($query) use($programstest) {
                    return $query->where("status", "accepted")->whereIn("course_id", $programstest);
                })->with(["applied_courses"=>function($q) use($programstest){
                    return $q->where("status", "accepted")->whereIn("course_id", $programstest);
            }]);
        }elseif($status === "rejected"){
            $application = $application->whereHas("applied_courses", function($query) use($programstest){
                return $query->where("status", "rejected")->whereIn("course_id", $programstest);
            })->with(["applied_courses"=>function($q) use($programstest){
                return $q->where("status", "rejected")->whereIn("course_id", $programstest);
            }]);
        }elseif($status === "on_hold"){
            // dd($request->all());
            $application = $application->whereHas("applied_courses", function($query) use($programstest){
                return $query->where("status", "on_hold")->whereIn("course_id", $programstest);
            })->with(["applied_courses"=>function($q) use($programstest){
                return $q->where("status", "on_hold")->whereIn("course_id", $programstest);
            }]);
        }
    }


    function getTotalApplicationCountDepartment($session_id, $progtype, $status, $type )
    {
        // dd($request->all());
        if(in_array(auth("department_user")->id(), [168, 170, 171,172])){
            // dd("test");
            $session=[$session_id-1,$session_id];
        }else{
            $session=[$session_id];
        }
        $application = Application::with( "student", "applied_courses", "applied_courses.course")->whereIn('session_id',$session);
           
        if($progtype == "UG"){
            $application->where('is_cuet_ug',1);
        }elseif($progtype == "PG"){
            $application->where('is_cuet_pg',1);
        }elseif($progtype == "B.Tech"){
            $application->where('is_btech',1);
        }elseif($progtype == "B.Tech Lateral"){
            $application->where('is_laterall',1);
        }elseif($progtype == "Ph.D."){
            $application->where('is_phd',1)->where('is_visves',0);
        }elseif($progtype=="BDES"){
            if($type=="JEE"){
                $application->where('is_btech',1);
            }else{
                $application->where('is_bdes',1);
            }
        }elseif($progtype=="MDES"){
            $application->where('is_mdes',1);
        }elseif($progtype =="Visvesvaraya"){
            $application->where('is_visves',1);
        }

        $exam_type = [$type];
        if($type == 'TUEE'){
            $exam_type = ['TUEE','GATE'];
        }
        if($type!="ALL"){
            $application->whereIn('exam_through',$exam_type);
        }

        $program_array=programmes_array();
        
        $programstest=[];
        foreach($program_array as $key=>$prog){
                if($key!=""){
                array_push($programstest, $key);
                }
        }
        // dd($programstest);
        if($status!="all"){

            applicationStatusFilter($status, $application, $programstest);
        }else{
            // dd($programstest);
            $application = $application->whereHas("applied_courses", function($query) use($programstest){
                return $query->whereIn("course_id", $programstest);
            })->with(["applied_courses"=>function($q) use($programstest){
                return $q->whereIn("course_id", $programstest);
            }]);
            
            $application = $application->whereIn('status',["payment_done", "rejected", "on_hold", "accepted", "qualified", "document_resubmitted"]);
        }
        
        return $application->count();
    
    }

    function checkAvailableSeatNew($id)
    {
        $merit_list=MeritList::where('id',$id)->first();//new
        $course_seat=$merit_list->course_seat();
        $approved_count=MeritList::where(['admission_category_id'=>$merit_list->admission_category_id, 'course_id'=>$merit_list->course_id])
                                   ->whereIn('status',[1,8])
                                   /* ->where('may_slide',0) */->count();
        // dd($approved_count);
        if($course_seat->total_seats <= $course_seat->temp_seat_applied + $course_seat->total_seats_applied+$approved_count){
            return false;
        }
        return true;
    }

    function checkOpenAvailability($course_id)
    {
        $course_seat=CourseSeat::where(['course_id'=>$course_id,'admission_category_id'=>1])->first();
        // $approved_count=MeritList::where(['admission_category_id'=>1, 'status'=>1,'course_id'=>$course_id])->count();
        // dump($approved_count);
        // dd($course_seat);
        if($course_seat->total_seats <= $course_seat->temp_seat_applied + $course_seat->total_seats_applied/* +$approved_count */){
            return false;
        }
        return true;
    }

    //latest

    function getTotalPHDApplicants($session_id){
        return \App\Models\Application::completed()
        ->where('is_phd', 1)
        ->whereNotNull('application_no')
        ->where('session_id', $session_id)
        ->count();
    }

    function getTotalPHDApplicantsProff($session_id){
        return \App\Models\Application::completed()
        ->where('is_phd_prof', 1)
        ->whereNotNull('application_no')
        ->where('session_id', $session_id)
        ->count();
    }

    function getTotalMBAApplicants($session_id){
        return \App\Models\Application::completed()
        ->where('is_mba', 1)
        ->whereNotNull('application_no')
        ->where('session_id', $session_id)
        ->count();
    }


    function getBtechLateral($session_id)
    {
        return \App\Models\Application::completed()
            ->where('is_laterall', 1)
            ->whereNotNull('application_no')
            ->where('session_id', $session_id)
            ->count();
    }

    function getMDeshReg($session_id, $exam_through)
    {
        return \App\Models\Application::completed()
            ->where('is_mdes', 1)
            ->where('exam_through', $exam_through)
            ->whereNotNull('application_no')
            ->where('session_id', $session_id)
            ->count();
    }

    function getBDeshReg($session_id, $exam_through)
    {
        return \App\Models\Application::completed()
            ->where('is_bdes', 1)
            ->where('exam_through', $exam_through)
            ->whereNotNull('application_no')
            ->where('session_id', $session_id)
            ->count();
    }

    function getTotalBtech($session_id){
    return \App\Models\Application::completed()
        ->where('is_btech', 1)
        ->whereNotNull('application_no')
        ->where('session_id', $session_id)
        ->count();
    }

    function getTotalchinese($session_id){
        return \App\Models\Application::completed()
            ->where('is_chinese', 1)
            ->whereNotNull('application_no')
            ->where('session_id', $session_id)
            ->count();
        }

//centers count
function gettotalExamCenter()
{
    return \App\Models\ExamCenter::with('application')->count();
}

function getTotalApplicationCount2023($session_id)
{
    $user_id = auth("department_user")->id();
    $department_id = DepartmentAssignedUser::where('department_user_id',$user_id)->pluck("department_id");
    $count = Application::where('session_id', $session_id)
                        ->whereHas('applied_courses', function ($query) use ($department_id) {
                            return $query->whereHas('course', function ($q) use ($department_id) {
                                return $q->where('department_id', $department_id[0]);
                            });
                        })
                        ->whereNotNull('application_no')
                        ->withCount('applied_courses')
                        ->get()
                        ->sum('applied_courses_count');
    // dd($count);
    return $count;
}

function IsItTimeToRelease($id){
    $user_id=Auth::user()->id;
    $is_another_seat = MeritList::where('student_id',$user_id)->whereIn('new_status',['called','time_extended'])->count();
    if($is_another_seat >=1){
        return true;
    }else{
        false;
    }
    // return false;
}


function sendSMSNew($mobile_no, $message, $template_id = null, $use_second_credentials = false)
{
    if (env("OTP_DRIVER") == "log") {
        \Log::info(["message" => $message, "mobile_no" => $mobile_no]);
        return true;
    }
   
    $url = env('SMS_URL');
    // $app_name = env('APP_NAME');
    $message = urlencode($message);
    
    if(strlen($mobile_no) > 10){
        $mobile_no = $mobile_no;
    }elseif(strlen($mobile_no) == 10){
        $mobile_no = "91" .$mobile_no;
    }
    $sender_id_new="TUUNIV";
    $curl_url = $url.$sender_id_new."/".$mobile_no."/".$message."/TXT?apikey=da5645-8d05f3-7660d5-f838c8-f19c76&dltentityid=1701158071021957414&dlttempid=".$template_id;
    // dd($curl_url);
    $smsInit = curl_init($curl_url);
     
    curl_setopt($smsInit, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($smsInit);
    // dd($res);
    \Log::info("SMS Sending to: ".$mobile_no);
    \Log::info("Message: ".$message);
    \Log::info($res);
    return $res;
}

function generateApplicationNo(Application $model/* , OnlinePaymentSuccess $current_payment */)
{
    if($model->is_mba==1){
        $year = date("Y");
        $flag=0;
        while($flag < 1){
            //$currentsession= Session::where('is_active',1)->pluck('id');
            $max_application_no = Application::/* where('session_id', $currentsession[0])
                                -> */select(DB::raw("SUBSTRING_INDEX(application_no, '/', -1) AS application_no_num"))
                                ->where('is_mba',1)
                                ->where('session_id',13)
                                ->orderBy('application_no_num', 'desc')                               
                                ->value('application_no_num');
            $new_application_no_num = sprintf("%05d", $max_application_no !== null ? intval($max_application_no) + 1 : 1);
            $application_no = "TU/" . $year . "/" . $new_application_no_num;
            $check=Application::where('application_no',$application_no)->count();
            if($check==0){
                $flag=1;
            }
        }
        foreach($model->applied_courses as $key=>$course){
            $course_details = Course::where('id',$course->course_id)->withTrashed()->first();
            $application_number = $course_details->code.'/'.$application_no;
            AppliedCourse::where('id',$course->id)->update(['application_number' => $application_number]);
        }
        \Log::info("Application no generated ".$application_no." for id ".$model->id);
        return $application_no;
        $model->application_no = $application_no;
        $model->save();
        return $model;
    }else{

        $year = date("Y");
        $data = [
           'application_id' => $model->id,
           'status'         => 'Active',
        ];
        $flag = GenerateAppno::where('application_id',$model->id)->first();
        if(!$flag){
            $inserted = GenerateAppno::create($data);
            $new_number = $inserted->id;
        }else{
            $new_number = $flag->id;
        }      
        $application_no = "TU/" . $year . "/" . $new_number;
        // $flag=0;
        // while($flag < 1){
        //     $currentsession= Session::where('is_active',1)->pluck('id');
        //     $max_application_no = Application::select(DB::raw("SUBSTRING_INDEX(application_no, '/', -1) AS application_no_num"))
        //                         ->orderBy('application_no_num', 'desc')
        //                         ->value('application_no_num');
        //     $new_application_no_num = sprintf("%05d", $max_application_no !== null ? intval($max_application_no) + 1 : 1);
        //     $application_no = "TU/" . $year . "/" . $new_application_no_num;
        //     $check=Application::where('application_no',$application_no)->count();
        //     if($check==0){
        //         $flag=1;
        //     }
        // }
        // if(!isMbaStudent($model)){ 
        //     foreach($model->applied_courses as $key=>$course){
        //         $course_details = Course::where('id',$course->course_id)->withTrashed()->first();
        //         $application_number = $course_details->code.'/'.$application_no;
        //         AppliedCourse::where('id',$course->id)->update(['application_number' => $application_number]);
        //     }
        // }
        \Log::info("Application no generated ".$application_no." for id ".$model->id);
        return $application_no;
        $model->application_no = $application_no;
        $model->save();
        return $model;
    }

    
}

function checkIsMaster(Application $model/* , OnlinePaymentSuccess $current_payment */)
    {
        $student_id = $model->student_id;
        $applied = Application::where('student_id',$student_id)->whereNotNull('application_no')->first();
        if(!$applied){
            return 1;
        }else{
            return 0;
        }
    }

function getGroupNCenterCount($group, $center)
{
    return AdmitCard::where('exam_group',$group)->where('sub_exam_center_id',$center)->count();
}

