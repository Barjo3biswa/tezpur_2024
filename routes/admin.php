<?php

Route::get('/home', [
	"uses" => "Admin\DashboardCntroller@home",
])->name('home');
// Route::group(["prefix" => "application", "as" => "application."],function(){
    // Route::get("/", [
    //     "uses" => "Admin\ApplicationController@index", 
    //     "as" => "index"
    // ]);
    Route::post("applicants/change_pass", ["uses" => "Admin\AdminController@changePass", "as" =>"applicants.changepass"]);
    Route::resource("application", "Admin\ApplicationController", ['except' => ["update"]]);
    // });
    Route::resource("notification", "Admin\WebsiteNotificationController", ['except' => []]);
    Route::get("applicants/list", ["uses" => "Admin\AdminController@applicantList", "as" =>"applicants.list"]);
    Route::group(['namespace' => 'Admin'], function () {
        Route::applicationRoutes("admin");
    });
Route::resource("exam-center", "Admin\ExamCenterController", ["except" => ["show"]]);
Route::resource("admit-card", "Admin\AdmitCardController",["except" => ["destroy", "edit"]]);
Route::group(["prefix" => "admit-card"], function(){
    Route::get("/download/{admit_id}", ["uses" => "Admin\AdmitCardController@downloadPdfAdmin", "as" => "admit-card.download.pdf"]);
    Route::post("/publish", ["uses" => "Admin\AdmitCardController@publishAdmit", "as" => "admit-card.publish-all"]);
});
Route::resource('department-users', 'Admin\DepartmentUserController')->except(["destroy", "show"]);
Route::get('department-users/destroy/{id}', 'Admin\DepartmentUserController@destroy')->name("department-users.delete");
Route::get('department-users/activate/{id}', 'Admin\DepartmentUserController@activate')->name("department-users.activate");
Route::resource('department-users', 'Admin\DepartmentUserController');
Route::group(['prefix' => 'logs'], function () {
    Route::get("/",["uses" => "Admin\LogController@index", "as" => "log.index"]);
});
Route::get("/exta-document-view/{encryptedValue}", [
    "uses" => "Admin\ApplicationController@uploadExtraDocumentShow",
    "as"   => "application.doc-upload.view",
]);

Route::group(['prefix' => 'merit'], function () {
    Route::get("/create",["uses" => "Admin\MeritController@create", "as" => "merit.create"]);
    Route::post("/store",["uses" => "Admin\MeritController@store", "as" => "merit.store"]);
    Route::get("/index",["uses" => "Admin\MeritController@index", "as" => "merit.index"]);
	Route::post("/merit-master",["uses" => "Admin\MeritController@meritMaster", "as" => "merit.master"]);
	Route::get("/admission-receipt/{id}", [
		"uses"	=> "Admin\MeritController@admissionPaymentReceipt",
		"as"	=> "merit.admission-receipt"
	]);

	Route::get("/admission-slide-receipt/{id}", [
		"uses"	=> "Admin\MeritController@admissionSlidePaymentReceipt",
		"as"	=> "merit.admission-slide-receipt"
	]);
	Route::post("/change-programm/{meritList}", [
		"uses"	=> "Admin\MeritController@changeCourse",
		"as"	=> "merit.change-programm"
	]);
	Route::get("seat-withdrawal-requests", [
		"uses"	=> "Admin\WithdrawalRequestController@index",
		"as"	=> "merit.withdrawal-request"
	]);
	Route::post("seat-withdrawal-requests/{w_request}", [
		"uses"	=> "Admin\WithdrawalRequestController@approve",
		"as"	=> "merit.withdrawal-request.approve"
	]);
	Route::post("seat-withdrawal-requests/reject/{w_request}", [
		"uses"	=> "Admin\WithdrawalRequestController@reject",
		"as"	=> "merit.withdrawal-request.reject"
	]);
	Route::get("/decline-receipt/{id}", [
		"uses"	=> "MeritController@declineSeatReceipt",
		"as"	=> "merit.decline-receipt"
	]);

});

Route::group(['prefix' => 'merit'], function () {
    Route::get("/create",["uses" => "Admin\MeritController@create", "as" => "merit.create"]);
    Route::post("/store",["uses" => "Admin\MeritController@store", "as" => "merit.store"]);
    Route::get("/index",["uses" => "Admin\MeritController@index", "as" => "merit.index"]);
	Route::post("/merit-master",["uses" => "Admin\MeritController@meritMaster", "as" => "merit.master"]);
	Route::post("/approve",["uses" => "Admin\MeritController@approve", "as" => "merit.approve"]);
	Route::post("/decline",["uses" => "Admin\MeritController@decline", "as" => "merit.decline"]);
	Route::get("/admission-category-update",["uses" => "Admin\MeritController@admissionCategoryList", "as" => "merit.admission_category_update"]);
	Route::post("/admission-category-update",["uses" => "Admin\MeritController@admissionCategoryUpdate", "as" => "merit.admission_category_update"]);
	Route::post("/approve-system-generated/{merit_list}",["uses" => "Admin\MeritController@approveSystemGenerated", "as" => "merit.approve-system-generated"]);
});

Route::group(['prefix' => 'fee-head'], function () {
	Route::get('/',[
		'as'=>'fee-head.index',
		'uses' => 'Admin\FeeHeadController@index'
	]);
	Route::get('/create',[
		'as'=>'fee-head.create',
		'uses' => 'Admin\FeeHeadController@create'
	]);
	Route::post('/create',[
		'as'=>'fee-head.store',
		'uses' => 'Admin\FeeHeadController@store'
	]);
	Route::get('/show/{fee_head}',[
		'as'=>'fee-head.show',
		'uses' => 'Admin\FeeHeadController@show'
	]);
	Route::get('/edit/{fee_head}',[
		'as'=>'fee-head.edit',
		'uses' => 'Admin\FeeHeadController@edit'
	]);
	Route::post('/edit/{fee_head}',[
		'as'=>'fee-head.update',
		'uses' => 'Admin\FeeHeadController@update'
	]);
	Route::get('/delete/{fee_head}',[
		'as'=>'fee-head.delete',
		'uses' => 'Admin\FeeHeadController@destroy'
    ]);
    
});

Route::group(['prefix' => 'fee'], function () {
	Route::get('/',[
		'as'=>'fee.index',
		'uses' => 'Admin\FeeController@index'
	]);
	Route::get('/create',[
		'as'=>'fee.create',
		'uses' => 'Admin\FeeController@create'
	]);
	Route::post('/create',[
		'as'=>'fee.store',
		'uses' => 'Admin\FeeController@store'
	]);
	Route::get('/show/{fee}',[
		'as'=>'fee.show',
		'uses' => 'Admin\FeeController@show'
	]);
	Route::get('/edit/{fee}',[
		'as'=>'fee.edit',
		'uses' => 'Admin\FeeController@edit'
	]);
	Route::post('/edit/{fee}',[
		'as'=>'fee.update',
		'uses' => 'Admin\FeeController@update'
	]);
	Route::get('/delete/{fee}',[
		'as'=>'fee.delete',
		'uses' => 'Admin\FeeController@destroy'
	]);

	Route::get('other-category',[
		'as'=>'fee.other_category',
		'uses' => 'Admin\FeeController@otherCategory'
	]);
	Route::get('report',[
		'as'=>'fee.reports',
		'uses' => 'Admin\ReportsController@collectionReports'
	]);
	Route::get('report-breakup',[
		'as'=>'fee.reports.breakup',
		'uses' => 'Admin\ReportsController@ajaxBreakup'
	]);
	Route::get('report-application-wise',[
		'as'=>'fee.reports.applications',
		'uses' => 'Admin\ReportsController@ajaxAplicationWise'
	]);
});

Route::group(['prefix' => 'vacancy'], function () {
	Route::get('/index',[
		'as'=>'vacancy.index',
		'uses' => 'Admin\VacancySeatController@index'
	]);

	Route::get('/booked',[
		'as'=>'vacancy.booked',
		'uses' => 'Admin\VacancySeatController@booked'
	]);
});
Route::get("/exta-document-view/{encryptedValue}", [
    "uses" => "Admin\ApplicationController@uploadExtraDocumentShow",
    "as"   => "application.doc-upload.view",
]);
Route::get("/undertaking-view/{meritList}", [
    "uses" => "Admin\ApplicationController@undertakingView",
	"as"   => "application.undertaking-view",
	"middleware" => [
		"auth:admin,department_user"
	]
]);
Route::get("/approve-undertakng/{undertaking}", [
    "uses" => "Admin\ApplicationController@approveUndertaking",
	"as"   => "application.approve-undertaking"
]);
Route::post("/transfer-seat/{merit_list}", [
    "uses" => "Admin\MeritController@transferSeat",
	"as"   => "application.transfer-seat",
	"middleware"	=> "auth:admin"
]);
Route::prefix('eligibilities_questionnaires')->group(function () {
	Route::get('/', [
		"uses" => "Admin\EligibilityQuestionnairesController@index",
		"as" => "questionnaires.index"
	]);
	Route::get('/create', [
		"uses" => "Admin\EligibilityQuestionnairesController@create",
		"as" => "questionnaires.create"
	]);
	Route::post('/create', [
		"uses" => "Admin\EligibilityQuestionnairesController@store",
		"as" => "questionnaires.store"
	]);
});
Route::middleware(['auth:admin'])->prefix('reports')->group(function () {
	Route::get("application-payments", [
		"uses"	=> "Admin\ReportsController@applicationFeeReport",
		"as"	=> "reports.application-payments"
	]);
	Route::get("admission-payments", [
		"uses"	=> "Admin\ReportsController@admissionFeeReport",
		"as"	=> "reports.admission-payments"
	]);
});


Route::get("/slide-seat/{merit_list_id}", [
	"uses"  =>  "Admin\MeritController@admissionSeatSlide",
	"as"    => "admission.slide-seat"
]);

Route::get("/slide-seat-deny/{merit_list_id}", [
	"uses"  => "AdmissionController@admissionSeatSlideDeny",
	"as"    => "admission.slide-seat-deny"
]);

// app\Http\Controllers\Admin\AdmissionReportController.php
Route::get("/admission-report", [
	"uses"  => "Admin\AdmissionReportController@index",
	"as"    => "admission-report"
]);

Route::get("/generate-view", [
	"uses"  => "Admin\AdmitCardControllerNew@index",
	"as"    => "generate-view"
]);

Route::get("/new-admit-generate", [
	"uses"  => "Admin\AdmitCardControllerNew@generateAdmitCard",
	"as"    => "new-admit-generate"
]);  

Route::get("/new-admit-view/{id}", [
	"uses"  => "Admin\AdmitCardControllerNew@viewAdmitCard",
	"as"    => "new-admit-view"
]);

Route::get("/download-admit-card/{id}", [
	"uses"  => "Admin\ApplicationController@downloadPdfAdmin",
	"as"    => "download-admit-card"
]);

Route::get("/open-close", [
	"uses"  => "Admin\AdmitCardControllerNew@OpenClose",
	"as"    => "open-close"
]);

Route::get("/open-close-change/{id}", [
	"uses"  => "Admin\AdmitCardControllerNew@OpenCloseSave",
	"as"    => "open-close-change"
]);

Route::get("/duplicate-attachments", [
	"uses"  => "Admin\AdmitCardControllerNew@removeDuplicateAttachments",
	"as"    => "duplicate-attachments"
]);  

Route::get("/duplicate-attachments-delete", [
	"uses"  => "Admin\AdmitCardControllerNew@removeDuplicateAttachmentsDelete",
	"as"    => "duplicate-attachments-delete"
]); 


Route::get("/send-mail", [
	"uses"  => "Admin\AdmitCardControllerNew@SendMail",
	"as"    => "send-mail"
]);

Route::get("/attendence", [
	"uses"  => "Admin\ApplicationController@attendenceSheet",
	"as"    => "attendence"
]);

Route::get("/print-view-attendence", [
	"uses"  => "Admin\ApplicationController@printViewAttendenceSheet",
	"as"    => "print-view-attendence"
]);

Route::get("/print-view-attendence-admit", [
	"uses"  => "Admin\ApplicationController@printViewAdmitTwo",
	"as"    => "print-view-attendence-admit"
]);


Route::get("/new-admit-publish", [
	"uses"  => "Admin\AdmitCardControllerNew@publishAdmitCard",
	"as"    => "new-admit-publish"
]);

Route::get("/mcj_fix", [
	"uses"  => "Admin\AdmitCardControllerNew@MCJFIX",
	"as"    => "mcj_fix"
]);

Route::get("/export_others", [
	"uses"  => "Admin\AdmitCardControllerNew@exportOthers",
	"as"    => "export_others"
]);
Route::post("/reason",["uses" => "Admin\ApplicationController@reasonOf", "as" => "reason"]);



//////// newadmisiion Online Processs  
Route::get("/reporting",["uses" => "Admin\OnlineAdmissionController@reportingBefore", "as" => "merit.reporting"]);
Route::get("/admission_new",["uses" => "Admin\OnlineAdmissionController@index", "as" => "merit.admission_new"]);
Route::get("/admission_Track",["uses" => "Admin\OnlineAdmissionController@trackProcess", "as" => "merit.admission_Track"]);
Route::post("/automate",["uses" => "Admin\OnlineAdmissionController@automateProcess", "as" => "merit.automate"]);

Route::post("/load-category",["uses" => "Admin\OnlineAdmissionController@loadCategory", "as" => "merit.load-category"]);

// Route::get("/call-for-admission/{id}",["uses" => "Admin\OnlineAdmissionController@callForAdmission", "as" => "merit.call-for-admission"]);

Route::post("/call-for-admission",["uses" => "Admin\OnlineAdmissionController@callForAdmission", "as" => "merit.call-for-admission"]);
Route::post("/invite-for-admission",["uses" => "Admin\OnlineAdmissionController@inviteForAdmission", "as" => "merit.invite-for-admission"]);

Route::post("/cancel-for-admission",["uses" => "Admin\OnlineAdmissionController@cancelForAdmission", "as" => "merit.cancel-for-admission"]);
Route::get("/hold-seat-online/{id}",["uses" => "Admin\OnlineAdmissionController@holdSeatOnline", "as" => "merit.hold-seat-online"]);

Route::get("/cancel-by-admin/{id}",["uses" => "Admin\OnlineAdmissionController@cancelByAdmin", "as" => "merit.cancel-by-admin"]);



Route::get("/admission-control",["uses" => "Admin\OnlineAdmissionController@AdmissionControl", "as" => "merit.admission-control"]);

Route::get("/admission-control-save/{id}",["uses" => "Admin\OnlineAdmissionController@AdmissionControlSave", "as" => "merit.admission-control-save"]);

Route::post("/assign-new-time",["uses" => "Admin\OnlineAdmissionController@assignNewTime", "as" => "merit.assign-new-time"]);


Route::post("/send-warning", ["uses" => "Admin\OnlineAdmissionController@sendWarning", "as" => "merit.send-warning"]);



Route::get("/payment-receipt/{application_id}", [
	"uses" => "Admin\MeritController@admissionPaymentReceipt", 
	"as" => "admission.payment-receipt"
]);


Route::get("/hostel-allotment", ["uses" => "HostelAllotmentController@index", "as" => "merit.hostel-allotment"]);
Route::post("/assign-hostel", ["uses" => "HostelAllotmentController@assignHostel", "as" => "merit.assign-hostel"]);

Route::get("/hostel-process-payment/{id}", ["uses" => "HostelAllotmentController@hostelProcessPayment", "as" => "merit.hostel-process-payment"]);

Route::post("/hostel-payment-response/{merit_list_id}", [
    "uses"  => "HostelAllotmentController@hostelPaymentRecieved",
    "as"    => "hostel-payment-response"
]);

Route::get("/hostel-receipt/{merit_list_id}", [
    "uses"  => "Admin\OnlineAdmissionController@hostelPaymentReceipt",
    "as"    => "hostel-receipt"
]);

Route::get("/hostel-receipt-re/{id}",[
	"uses" => "Admin\OnlineAdmissionController@hostelRePaymentReceipt", 
	"as" => "hostel-receipt-re"
]);

Route::get("/a-r-f/{merit_list_id}", [
    "uses"  => "Admin\OnlineAdmissionController@ARF",
    "as"    => "a-r-f"
]);


Route::get("/no-hostel/{merit_list_id}", [
    "uses"  => "HostelAllotmentController@noHostel",
    "as"    => "merit.no-hostel"
]);


Route::post("/reason",["uses" => "Admin\ApplicationController@reasonOf", "as" => "reason"]);


//Sliding 
Route::get("/slide-category",["uses" => "Admin\SlidingController@index", "as" => "merit.slide-category"]);


Route::get("/slide-admission-cat/{id}",["uses" => "Admin\SlidingController@slideCategory", "as" => "merit.slide-admission-cat"]);

Route::get("/failed-payments", [
	"uses"  => "Admin\ApplicationController@failedPayments",
	"as"    => "failed-payments"
]);