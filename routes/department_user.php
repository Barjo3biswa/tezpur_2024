<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('department_user')->user();

    //dd($users);
    $session = getActiveSession();
    return view('department-user.home', compact("session"));
})->name('home');
Route::get("/exta-document-view/{encryptedValue}", [
    "uses" => "ApplicationController@uploadExtraDocumentShow",
    "as"   => "application.doc-upload.view",
]);

Route::applicationRoutes("department-user");
Route::get("/download/{admit_id}", ["uses" => "ApplicationController@downloadPdfAdmin", "as" => "admit-card.download.pdf"]);
Route::group(['prefix' => 'merit'], function () {
    Route::get("/create",["uses" => "MeritController@create", "as" => "merit.create"]);
    Route::post("/approve",["uses" => "MeritController@approve", "as" => "merit.approve"]);


    Route::get("/index",["uses" => "MeritController@index", "as" => "merit.index"]);
    Route::get("/attendence",["uses" => "MeritController@attendence", "as" => "merit.attendence"]);
    Route::post("/ab-pre/{id}",["uses" => "MeritController@presentAbsent", "as" => "merit.ab-pre"]);

    Route::post("/ab-pre-undo/{id}",["uses" => "MeritController@presentAbsentUndo", "as" => "merit.ab-pre-undo"]);


    Route::post("/ab-pre-commit/{id}",["uses" => "MeritController@presentAbsentCommit", "as" => "merit.ab-pre-commit"]);
	Route::post("/merit-master",["uses" => "MeritController@meritMaster", "as" => "merit.master"]);
    Route::get("/payment",["uses" => "MeritController@payment", "as" => "merit.payment"]);
    Route::post("/checklist",["uses" => "MeritController@saveChecklist", "as" => "merit.checklist"]);
    


    Route::post("/set-admission-category",["uses" => "MeritController@setAdmissionCategory", "as" => "merit.set_category"]);
    Route::post("/hold",["uses" => "MeritController@holdSeat", "as" => "merit.hold-seat"]);
    Route::post("/un-hold",["uses" => "MeritController@unHoldSeat", "as" => "merit.unhold-seat"]);
});

Route::post("/approve-system-generated/{merit_list}",["uses" => "MeritController@approveSystemGenerated", "as" => "merit.approve-system-generated"]);


Route::get('vacancy-positions', [
    "uses" => "MeritController@seatPositions",
    "as" => "seat-positions",
]);
Route::group(['prefix' => 'merit'], function () {
	Route::get("/admission-receipt/{id}", [
		"uses"	=> "MeritController@admissionPaymentReceipt",
		"as"	=> "merit.admission-receipt"
	]);

    Route::get("/admission-slide-receipt/{id}", [
		"uses"	=> "MeritController@admissionSlidePaymentReceipt",
		"as"	=> "merit.admission-slide-receipt"
	]);

    Route::get("/decline-receipt/{id}", [
		"uses"	=> "MeritController@declineSeatReceipt",
		"as"	=> "merit.decline-receipt"
	]);


    Route::get("/seat-withdrawal-requests", [
        "uses"	=> "WithdrawalRequestController@index",
        "as"	=> "merit.withdrawal-request"
    ]);
    Route::post("seat-withdrawal-requests/{w_request}", [
		"uses"	=> "WithdrawalRequestController@approve",
		"as"	=> "merit.withdrawal-request.approve"
	]);
	Route::post("seat-withdrawal-requests/reject/{w_request}", [
		"uses"	=> "WithdrawalRequestController@reject",
		"as"	=> "merit.withdrawal-request.reject"
	]);
    Route::post("set-active-admission-category", [
		"uses"	=> "MeritController@setAdmissionCategory",
		"as"	=> "merit.set-active-admission-category"
	]);
});




// Route::group(['prefix' => 'admission', "as" => "admission."], function () {
    Route::get("/book-seat/{application_id}", [
        "uses"  => "AdmissionController@bookSeat",
        "as"    => "admission.book.seat"
    ]);
    Route::get("/proceed-admission/{merit_list_id}", [
        "uses"  => "AdmissionController@admissionProcessPayment",
        "as"    => "admission.proceed"
    ]);
    Route::post("/payment-response/{merit_list_id}", [
        "uses"  => "AdmissionController@admissionPaymentRecieved",
        "as"    => "payment-response"
    ]);

    Route::post("/cash-payment-response/{merit_list_id}", [
        "uses"  => "AdmissionController@cashAdmissionPaymentRecieved",
        "as"    => "cash-payment-response"
    ]);

    Route::get("/cash-payment/{merit_list_id}", [
        "uses"  => "AdmissionController@cashFrom",
        "as"    => "cash-payment"
    ]);
    Route::get("/receipt/{merit_list_id}", [
        "uses"  => "MeritController@admissionPaymentReceipt",
        "as"    => "receipt"
    ]);
    Route::get("/release/{merit_list_id}", [
        "uses"  => "AdmissionController@admissionSeatRelease",
        "as"    => "admission.release"
    ]);

    Route::get("/slide-seat/{merit_list_id}", [
        "uses"  => "AdmissionController@admissionSeatSlide",
        "as"    => "admission.slide-seat"
    ]);

    Route::get("/slide-seat-deny/{merit_list_id}", [
        "uses"  => "AdmissionController@admissionSeatSlideDeny",
        "as"    => "admission.slide-seat-deny"
    ]);

    


    Route::post("/choose-hostel/{meritList}", [
        "uses"  => "AdmissionController@chooseHostel",
        "as"    => "admission.choose-hostel"
    ]);
    Route::post("/upload-undertaking/{meritList}", [
        "uses"  => "AdmissionController@uploadingUnderTaking",
        "as"    => "admission.upload-undertaking"
    ]);
    Route::post("/report-counselling/{merit_list}", [
        "uses"  => "AdmissionController@reportCounselling",
        "as"    => "admission.report-counselling"
    ]);
    Route::post("/decline-seat/{merit_list}", [
        "uses"  => "AdmissionController@declineSeat",
        "as"    => "admission.declined-seat"
    ]);
    Route::post("/withdraw-seat/{merit_list}", [
        "uses"  => "AdmissionController@withdrawSeat",
        "as"    => "admission.withdraw-seat"
    ]);


    Route::get("/payment-receipt/{application_id}", [
        "uses" => "MeritController@admissionPaymentReceipt", 
        "as" => "admission.payment-receipt"
    ]);



Route::group(['prefix' => 'fee'], function () {
	Route::get('report',[
		'as'=>'fee.reports',
		'uses' => 'AdmissionController@collectionReports'
	]);
});
Route::post("applicants/change_pass", ["uses" => "AdmissionController@changePass", "as" =>"applicants.changepass"]);

Route::get("/process-payment/{application_id}", ["uses" => "AdmissionController@processPayment", "as" => "application.process-payment"]);
Route::post("/process-payment/{application_id}", ["uses" => "AdmissionController@paymentRecieved", "as" => "application.process-payment-post"]);
    
Route::get("/download-admit-card/{id}", [
	"uses"  => "ApplicationController@downloadPdfAdmin",
	"as"    => "download-admit-card"
]);

Route::get("/attendence-sheet", [
	"uses"  => "ApplicationController@attendenceSheet",
	"as"    => "attendence-sheet"
]);

Route::get("/print-view-attendence", [
	"uses"  => "ApplicationController@printViewAttendenceSheet",
	"as"    => "print-view-attendence"
]);

Route::post("/reason",["uses" => "ApplicationController@reasonOf", "as" => "reason"]);


//////// newadmisiion Online Processs  
Route::get("/reporting",["uses" => "onlineAdmissionController@reportingBefore", "as" => "merit.reporting"]);
Route::get("/admission_new",["uses" => "onlineAdmissionController@index", "as" => "merit.admission_new"]);
Route::get("/admission_Track",["uses" => "onlineAdmissionController@trackProcess", "as" => "merit.admission_Track"]);
Route::post("/automate",["uses" => "onlineAdmissionController@automateProcess", "as" => "merit.automate"]);

Route::post("/load-category",["uses" => "onlineAdmissionController@loadCategory", "as" => "merit.load-category"]);

// Route::get("/call-for-admission/{id}",["uses" => "onlineAdmissionController@callForAdmission", "as" => "merit.call-for-admission"]);

Route::post("/call-for-admission",["uses" => "onlineAdmissionController@callForAdmission", "as" => "merit.call-for-admission"]);
Route::post("/invite-for-admission",["uses" => "onlineAdmissionController@inviteForAdmission", "as" => "merit.invite-for-admission"]);



Route::post("/cancel-for-admission",["uses" => "onlineAdmissionController@cancelForAdmission", "as" => "merit.cancel-for-admission"]);
Route::get("/hold-seat-online/{id}",["uses" => "onlineAdmissionController@holdSeatOnline", "as" => "merit.hold-seat-online"]);
Route::get("/vacancy/index", ["uses" => "onlineAdmissionController@vacancy", "as" => "vacancy.index"]);
Route::post("/send-warning", ["uses" => "onlineAdmissionController@sendWarning", "as" => "merit.send-warning"]);

Route::post("/save-btech-changes/{id}", ["uses" => "MeritController@changeBranch", "as" => "merit.save-btech-changes"]);
Route::get("/assign-branch/{id}", ["uses" => "MeritController@AssignBranch", "as" => "merit.assign-branch"]);

Route::get("/btech-recpt/{id}", ["uses" => "MeritController@admissionPaymentReceiptNEW", "as" => "merit.btech-recpt"]);

Route::get("/btech-recpt-canceled/{id}", ["uses" => "MeritController@admissionCanceledReceiptNEW", "as" => "merit.btech-recpt-canceled"]);

Route::post("/btech-cancel", ["uses" => "MeritController@admissionCancel", "as" => "merit.btech-cancel"]);

Route::get("/assign-branch-absent/{id}", ["uses" => "MeritController@absentAtAssignBranch", "as" => "merit.assign-branch-absent"]);

Route::get("/release/{merit_list_id}", [
    "uses"  => "MeritController@admissionSeatRelease",
    "as"    => "admission.release"
]);

Route::get("/hostel-allotment", ["uses" => "HostelAllotmentController@index", "as" => "merit.hostel-allotment"]);
Route::post("/assign-hostel", ["uses" => "HostelAllotmentController@assignHostel", "as" => "merit.assign-hostel"]);

Route::get("/hostel-process-payment/{id}", ["uses" => "HostelAllotmentController@hostelProcessPayment", "as" => "merit.hostel-process-payment"]);

Route::post("/hostel-payment-response/{merit_list_id}", [
    "uses"  => "HostelAllotmentController@hostelPaymentRecieved",
    "as"    => "hostel-payment-response"
]);

Route::get("/hostel-receipt/{merit_list_id}", [
    "uses"  => "HostelAllotmentController@hostelPaymentReceipt",
    "as"    => "hostel-receipt"
]);

Route::get("/hostel-receipt-re/{id}",[
	"uses" => "OnlineAdmissionController@hostelRePaymentReceipt", 
	"as" => "hostel-receipt-re"
]);


Route::get("/a-r-f/{merit_list_id}", [
    "uses"  => "HostelAllotmentController@ARF",
    "as"    => "a-r-f"
]);


Route::get("/no-hostel/{merit_list_id}", [
    "uses"  => "HostelAllotmentController@noHostel",
    "as"    => "merit.no-hostel"
]);

Route::get("/later-hostel/{merit_list_id}", [
    "uses"  => "HostelAllotmentController@laterHostel",
    "as"    => "merit.later-hostel"
]);

Route::get("/index", [
    "uses"  => "JossaController@index",
    "as"    => "jossa.index"
]);


Route::get("/assign-branch-jossa/{id}", [
    "uses"  => "JossaController@assignBranch",
    "as"    => "jossa.assign-branch"
]);

Route::post("/assign-branch-save-jossa/{id}", [
    "uses"  => "JossaController@changeBranch",
    "as"    => "jossa.assign-branch-save"
]);

Route::post("/reason",["uses" => "ApplicationController@reasonOf", "as" => "reason"]);

Route::get("/download-invitation-card/{id}", [
	"uses"  => "Student\ApplicationController@downloadInvitationAdmin",
	"as"    => "download-invitation-card"
]);