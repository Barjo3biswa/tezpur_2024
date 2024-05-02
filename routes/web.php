
<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use App\Http\Controllers\CronController;

Route::get('/cron-check', function () {
    \Log::info("Cron checked started.");
    $obj = new CronController;
    $obj->verifyAll();
    \Log::info("Cron checked end");
});
Route::get('/', function () {
    return view('welcome2');
    // welcome for old new
});


Route::get("qrcode/{id}", [
    'uses'  => "AdminAuth\LoginController@qrView",
    "as"    => "student.qr-verify"
    
]);

Route::get("admit/{id}", [
    'uses'  => "AdminAuth\LoginController@qrviewAdmitCard",
    "as"    => "student.admit"
    // 'uses'  => "AdminAuth\LoginController@qrView",
    // "as"    => "student.qr-verify"
    
]);



Route::get('/exam_centers',function(){
    return view('exam_centers');
})->name("exam_centers");

Route::get('/refund.html', function () {
    return view('refund');
})->name("refund");
Route::get('/privacy.html', function () {
    return view('privacy');
})->name("privacy");
Route::get('/terms.html', function () {
    return view('terms');
})->name("terms");
Route::get('/archive.html', function () {
    return view('archive');
})->name("archive");
Route::get('/faqs.html', function () {
    return view('faqs');
})->name("faq");
Route::get('/how-to-apply.html', function () {
    return view('how-to-apply');
})->name("how-to-apply");

Route::group(['prefix' => 'admin', "as" => "admin.", "middleware" => "web"], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

    Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'AdminAuth\RegisterController@register');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request')->middleware("throttle:3,1");
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});
Route::group(["prefix" => "student", "as" => "student.", "middleware" => "web"], function () {
    Auth::routes();
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email')->middleware("throttle:2,1");
});
Route::get("/application/attachment/{student_id}/{application_id}/{url}", [
    "uses" => "Common\ApplicationAttachmentController@show",
    "as" => "common.download.image"
]);

//load data
/* Route::get("/pass_cg", function(){
    return bcrypt("Rajabari@123");
}); */

/*
Route::get("/send_sms_incomplete_application", function(){
    return "closed";
    $message = "Your status of application for admission into GNM course session 2019-20 in VKNRL School of Nursing is incomplete. Kindly complete it by 31/07/2019.";
    $mobile_nos = \App\Models\User::whereHas("application",function($query){
        return $query->where("payment_status", 0)->whereDate("updated_at", "<=", date("Y-m-d", strtotime(date("Y-m-d")." -1 days")));
    });
    $user_details = [];
    $records = $mobile_nos->get();
    if($records){
        foreach($records as $record){

            $user_details[] = [
                "name"       => $record->name,
                "mobile_no"  => $record->mobile_no,
                "registration no"  => $record->id,
                "application_id"  => $record->application->first()->id,
                "message"   => $message
            ];
        }
    }
    $mobile_nos     = $mobile_nos->pluck("mobile_no")->toArray();
    \Log::notice(json_encode($user_details));
    if($mobile_nos){
        foreach($mobile_nos as $mobile_no){
            sendSMS($mobile_no, $message);
        }
    }
    return "Done";
}); */

// Email verification and resend routes
Route::get("student/email-verify", [
    'uses'  => "HomeController@verifyEmail",
    "as"    => "student.email-verify"
]);
Route::group(['prefix' => 'department' , "as" => "department.", "middleware" => "web"], function () {

    Route::redirect('/', 'department/login', 301);
    Route::get('/login', 'DepartmentUserAuth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'DepartmentUserAuth\LoginController@login');
    Route::post('/logout', 'DepartmentUserAuth\LoginController@logout')->name('logout');

    Route::get('/register', 'DepartmentUserAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'DepartmentUserAuth\RegisterController@register');

    Route::post('/password/email', 'DepartmentUserAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
    Route::post('/password/reset', 'DepartmentUserAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'DepartmentUserAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'DepartmentUserAuth\ResetPasswordController@showResetForm');
});
Route::get('error_fixign_001', function () {
    abort(404);
    return errorFixing();
});
Route::get('/refresh-captcha', 'HomeController@refreshCapcha')->name("refresh.captcha");
Route::get("generate-report-EFL", [
    // "as"    => "test.efl",
    "uses"    => "TestingController@reportEFL",
]);

Route::get("generate-report-EFL-updated", [
    // "as"    => "test.efl",
    "uses"    => "TestingController@newReportEFL",
]);
//Admin dashboard
Route::get("/getsessioncount",["uses" => "Admin\DashboardCntroller@getsessioncount", "as" => "count.session"]);
Route::get("/getcastcount",["uses" => "Admin\DashboardCntroller@getcastcount", "as" => "count.cast"]);
// Route::get('/home/program', function () {

//     return view('admin.program');
// });
Route::get("/home/program",["uses" => "Admin\DashboardCntroller@program", "as" => "admin.program"]);
//centers
Route::get("/home/centers", ["uses" => "Admin\DashboardCntroller@center", "as" => "admin.center"]);

Route::get("download-undertakings-001", [
    "uses"    => "TestingController@arvhiceUndertaking",
]);
Route::get("generate-admission-receipts", [
    "as" => "report.admission-receipts",
    "uses"  => "TestingController@admittedStudents"
]);
Route::get("/update-undertaking",["uses" => "Admin\MeritController@undertakingList", "as" => "merit.undertaking_update"]);
Route::post("/update-undertaking",["uses" => "Admin\MeritController@undertakingListUpdate", "as" => "merit.undertaking_update"]);
Route::get("common/application-eligibility/{application}", ["uses" => "Student\EligibilityCheckController@show"])
    ->name("application.eligibility.show");

Route::group(['prefix' => 'finance'], function () {
  Route::get('/login', 'FinanceAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'FinanceAuth\LoginController@login');
  Route::post('/logout', 'FinanceAuth\LoginController@logout')->name('logout');

//   Route::get('/register', 'FinanceAuth\RegisterController@showRegistrationForm')->name('register');
//   Route::post('/register', 'FinanceAuth\RegisterController@register');

//   Route::post('/password/email', 'FinanceAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
//   Route::post('/password/reset', 'FinanceAuth\ResetPasswordController@reset')->name('password.email');
//   Route::get('/password/reset', 'FinanceAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
//   Route::get('/password/reset/{token}', 'FinanceAuth\ResetPasswordController@showResetForm');
});

Route::get('/contact', function () {
    return view('contact');
})->name('contact-static');
Route::get('/formates', function () {
    return view('download_formates');
})->name('formates-static');

// Route::get('/shortlist-candidates', function () {
//     return view('shortlisted-candidate');
// })->name('shortlist-candidates');
Route::get("/shortlist-candidates", ["uses" => "TestingController@shortlistedCandidate", "as" => "shortlist-candidates"]);

Route::group(['prefix' => 'attendance'], function () {
    Route::get("/index", ["uses" => "Admin\AttendanceController@index", "as" => "index"]);

});

//release receipt route
Route::group(['prefix' => 'release'], function () {
    Route::get("/release-receipt/{merit_list_id}", ["uses" => "DepartmentUser\AdmissionController@releaseReceipt", "as" => "admission.release-receipt"]);
});

Route::get("/tezu-important-data", ["uses" => "TestingController@missedData", "as" => "tezu-important-data"]);
Route::post("/tezu-important-data-save", ["uses" => "TestingController@missedDataSave", "as" => "tezu-important-data-save"]);
Route::get('/pusher',["uses" => "PusherController@store", "as" => "pusher"]);
Route::post('/notify',["uses" => "NotifyController@store", "as" => "notify"]);



