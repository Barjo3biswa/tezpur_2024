<?php
use Illuminate\Http\Request;
Route::get("/", function(){
    return redirect()->route("student.login");
});
Route::get("otp-verify", function(){
    return view("student.otp-verify");
})->name("otp-verify")->middleware("otp.guest");



Route::post("loadallsport", ["uses" => "Student\ApplicationController@loadSports"])->name("loadallsport");

Route::post("otp-verify", ["uses" => "HomeController@verifyOTP"])->name("otp-verify")->middleware("otp.guest");
Route::get("otp-resend", ["uses" => "HomeController@resendOTP"])->name("otp-resend");
Route::get("declining-otp/{merit_list}", ["uses" => "Student\AdmissionController@sendOTP"])->name("declining-otp");
Route::group(["middleware" => "otp"], function(){
    
    Route::get('/application/loadstep1data/{id}','Student\ApplicationController@loadStep1Data');
    Route::post('/step_one_form/submit','Student\ApplicationController@submitStepOne')->middleware('null_converter');
    Route::post('/step_two_form/submit','Student\ApplicationController@submitStepTwo')->middleware('null_converter');
    Route::post('/step_three_form/submit','Student\ApplicationController@submitStepThree')->middleware('null_converter');
    Route::post('/step_four_form/submit','Student\ApplicationController@submitStepFour');
    //update routes


    
    Route::get('/application/edit/{id}','Student\ApplicationController@editForm')->name('application.edit_form');
    Route::get('/application/change-exam-center/{id}','Student\ApplicationController@changeExamCenter')->name('application.change-exam-center');
    

    Route::get('/application/change-preference/{id}','Student\ApplicationController@changePreference')->name('application.change-preference');
    Route::Post('/application/change-preference-save','Student\ApplicationController@savePreferenceChange')->name('application.change-preference-save');

    Route::post('/application/update-ex-cen/{id}','Student\ApplicationController@updateExamCenter')->name('application.update-ex-cen');
    Route::post('/application/edit/load_old/{id}','Student\ApplicationController@loadOldApplication');


    Route::post('/application/edit/load_cuet_sujects/{id}','Student\ApplicationController@loadCuetSubjects');

    Route::get('/application/edit/load_area_of_research/{id}','Student\ApplicationController@loadAreaOfResearch');

    Route::post('/application/district/load_specific_district/{id}','Student\ApplicationController@loadDistricts');


    Route::post('/step_one_form/update','Student\ApplicationController@updateStepOne')->middleware('null_converter');
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::post('/step_two_form/update','Student\ApplicationController@updateStepTwo');
    //Edit MBA
    Route::get('/application/edit_mba/{id}','Student\ApplicationController@edit_mba_Form')->name('application.edit_mba_form');
    Route::post('/application/update_edit_mba','Student\ApplicationController@update_mba_Form')->name('application.update_mba_form');


    // Application Route
    Route::resource("application", "Student\ApplicationController", ['except' => ["update"]]);
    Route::get('/applicationii/{id}','Student\ApplicationController@showii')->name('application.edit_mba_form');
    Route::get('/edit_mba_form_new/{id}','Student\ApplicationController@editMBAScore')->name('application.edit_mba_form_new');
    
    Route::post('/update_mba_form_new/{id}','Student\ApplicationController@updateMBAScore')->name('application.update_mba_form_new');

    // inserting OR Updating Application
    Route::group(["prefix" => "application"], function(){
        Route::put("/step_one_form/{application_id}", ["uses" =>"Student\ApplicationController@stepOneUpdate", "as" => "application.step_one_form"]);
        Route::put("/step_two_form/{application_id}", ["uses" =>"Student\ApplicationController@stepTwoUpdate", "as" => "application.step_two_form"]);
        Route::put("/step_three_form/{application_id}", ["uses" =>"Student\ApplicationController@stepThreeUpdate", "as" => "application.step_three_form"]);
        Route::put("/step_final_form/{application_id}", ["uses" =>"Student\ApplicationController@stepFinalUpdate", "as" => "application.step_final_form"]);
        Route::get("/process-payment/{application_id}", ["uses" => "Student\ApplicationController@processPayment", "as" => "application.process-payment"]);
        Route::post("/process-payment/{application_id}", ["uses" => "Student\ApplicationController@paymentRecieved", "as" => "application.process-payment-post"]);
        Route::get("/payment-receipt/{application_id}", ["uses" => "Student\ApplicationController@paymentReceipt", "as" => "application.payment-reciept"]);
        Route::get("/final-submit/{application_id}", ["uses" => "Student\ApplicationController@finalSubmit", "as" => "application.final-submit"]);
        Route::group(['prefix' => 'admit-card'], function () {
            Route::get('download/{application_id}', ["uses" => "Student\ApplicationController@downloadAdmitCard", "as" => "admit-card.download"]);
        });        
        Route::get("/destroy/{application_id}", ["uses" =>"Student\ApplicationController@destroy", "as" => "application.destroy"]);
        Route::get("/resubmit/{application_id}", ["uses" =>"Student\ApplicationController@resubmitDocument", "as" => "application.resubmit"]);
        Route::post("/resubmit", ["uses" =>"Student\ApplicationController@resubmitDocumentsPost", "as" => "application.resubmit.post"]);
        Route::get("/re-payment/{application_id}", ["uses" =>"Student\ApplicationController@rePayment", "as" => "application.re-payment"]);
        Route::post("/re-payment/{application_id}", ["uses" =>"Student\ApplicationController@rePaymentRecieved", "as" => "application.re-payment-post"]);
        Route::get("/re-payment-receipt/{application_id}", ["uses" => "Student\ApplicationController@rePaymentReceipt", "as" => "application.re-payment-reciept"]);
        Route::get("/exta-document-upload/{encryptedValue}", [
            "uses" => "Student\ApplicationController@uploadExtraDocumentGet",
            "as" => "application.doc-upload.get"
        ]);
        Route::get("/exta-document-view/{encryptedValue}", [
            "uses" => "Student\ApplicationController@uploadExtraDocumentShow",
            "as" => "application.doc-upload.view"
        ]);
        Route::post("/exta-document-upload/{encryptedValue}", [
            "uses" => "Student\ApplicationController@uploadExtraDocumentPost",
            "as" => "application.doc-upload.post"
        ]);
        Route::get("/eligibilty-check/{application}", [
            "uses" => "Student\EligibilityCheckController@create",
            "as" => "application.eligibility.create"
        ])
        ->middleware(["admissionController", "otp"]);
        Route::post("/eligibilty-check/{application}", [
            "uses" => "Student\EligibilityCheckController@store",
            "as" => "application.eligibility.store"
        ])->middleware(["admissionController", "otp"]);
        Route::post("/file-upload/{application}", [
            "uses" => "Student\ApplicationController@singleFileUpload",
            "as" => "application.single-file-upload"
        ]);
    });
    Route::group(['prefix' => 'admission', "as" => "admission.", "middleware" => "auth:student"], function () {
        Route::get("/book-seat/{application_id}", [
            "uses"  => "Student\AdmissionController@bookSeat",
            "as"    => "book.seat"
        ]);
        Route::get("/proceed-admission/{merit_list_id}", [
            "uses"  => "Student\AdmissionController@admissionProcessPayment",
            "as"    => "proceed"
        ]);
        Route::post("/payment-response/{merit_list_id}", [
            "uses"  => "Student\AdmissionController@admissionPaymentRecieved",
            "as"    => "payment-response"
        ]);
        Route::get("/receipt/{merit_list_id}", [
            "uses"  => "Student\AdmissionController@admissionPaymentReceipt",
            "as"    => "payment-receipt"
        ]);
        Route::get("/release/{merit_list_id}", [
            "uses"  => "Student\AdmissionController@admissionSeatRelease",
            "as"    => "release"
        ]);
        Route::post("/choose-hostel/{meritList}", [
            "uses"  => "Student\AdmissionController@chooseHostel",
            "as"    => "choose-hostel"
        ]);
        Route::post("/upload-undertaking/{meritList}", [
            "uses"  => "Student\AdmissionController@uploadingUnderTaking",
            "as"    => "upload-undertaking"
        ]);
        Route::post("/report-counselling/{merit_list}", [
            "uses"  => "Student\AdmissionController@reportCounselling",
            "as"    => "report-counselling"
        ]);
        Route::post("/decline-seat/{merit_list}", [
            "uses"  => "Student\AdmissionController@declineSeat",
            "as"    => "declined-seat"
        ]);
        Route::post("/withdraw-seat/{merit_list}", [
            "uses"  => "Student\AdmissionController@withdrawSeat",
            "as"    => "withdraw-seat"
        ]);
    });
});
Route::get("/email-verification", [
    'uses'  => "HomeController@emailVerifyView",
    "as"    => "email-verify-view"
])->middleware("otp.guest");


Route::get("/resend-email-verification", [
    'uses'  => "HomeController@resendEmail",
    "as"    => "resend-email-verification"
]);

Route::get("/change-email", [
    'uses'  => "HomeController@changeEmail",
    "as"    => "change-email"
]);


Route::post("/save-change-email", [
    'uses'  => "HomeController@saveChangeEmail",
    "as"    => "save-change-email"
]);

Route::get("/download-format-files", [
    'uses'  => "HomeController@downloadFormat",
    "as"    => "download.formates"
]);
Route::group(['prefix' => 'vacancy'], function () {
	Route::get('/',[
		'as'=>'vacancy.index',
		'uses' => 'Student\VacancySeatController@index'
	]);
});

Route::get("/undertaking-view/{meritList}", [
    "uses" => "Student\AdmissionController@undertakingView",
	"as"   => "merit.undertaking-view",
	"middleware" => [
		"auth:student"
	]
]);

Route::get("/edit-sub-pref/{id}", [
    "uses" => "Student\ApplicationController@editSubPref",
	"as"   => "application.edit-sub-pref",
	"middleware" => [
		"auth:student"
	]
]);
Route::group(['prefix' => 'merit'], function () {
    Route::get("/index",["uses" => "MeritController@index", "as" => "merit.index"]);
    Route::get("/attendence",["uses" => "MeritController@attendence", "as" => "merit.attendence"]);
    Route::post("/ab-pre/{id}",["uses" => "MeritController@presentAbsent", "as" => "merit.ab-pre"]);
	Route::post("/merit-master",["uses" => "MeritController@meritMaster", "as" => "merit.master"]);
    Route::get("/payment",["uses" => "MeritController@payment", "as" => "merit.payment"]);
    Route::post("/checklist",["uses" => "MeritController@saveChecklist", "as" => "merit.checklist"]);
});

Route::get("/download-admit-card/{id}", [
	"uses"  => "Student\ApplicationController@downloadPdfAdmin",
	"as"    => "download-admit-card"
]);

Route::get("/download-invitation-card/{id}", [
	"uses"  => "Student\ApplicationController@downloadInvitationAdmin",
	"as"    => "download-invitation-card"
]);

Route::get("/download-score-card/{id}", [
	"uses"  => "Student\ApplicationController@downloadScoreCard",
	"as"    => "download-score-card"
]);
Route::post("/reason",["uses" => "Student\ApplicationController@reasonOf", "as" => "reason"]);

Route::group(['prefix' => 'online-admission'], function () {
    Route::get("/process/{id}",["uses" => "Student\NewAdmissionController@process", "as" => "online-admission.process"]);

    Route::post("/accept-invite/{id}",["uses" => "Student\NewAdmissionController@acceptInvite", "as" => "online-admission.accept-invite"]);
    Route::get("/decline-invite/{id}",["uses" => "Student\NewAdmissionController@declineInvite", "as" => "online-admission.decline-invite"]);

    Route::Post("/reporting/{id}",["uses" => "Student\NewAdmissionController@reportCounsellingOnline", "as" => "online-admission.reporting"]);

    Route::Post("/decline/{id}",["uses" => "Student\NewAdmissionController@declineSeatOnline", "as" => "online-admission.decline"]);

    Route::Post("/withdraw/{id}",["uses" => "Student\NewAdmissionController@withdrawSeatOnline", "as" => "online-admission.withdraw"]);

    Route::Post("/choose-hostal/{id}",["uses" => "Student\NewAdmissionController@chooseHostel", "as" => "online-admission.choose-hostal"]);

    Route::Post("/decline_otp",["uses" => "Student\NewAdmissionController@getDeclineOTP", "as" => "online-admission.decline_otp"]);

    Route::get("/release-seat/{id}",["uses" => "Student\NewAdmissionController@admissionSeatRelease", "as" => "online-admission.release-seat"]);

    Route::get("/sms-test",["uses" => "Student\NewAdmissionController@SmsTest", "as" => "online-admission.sms-test"]);

    Route::get("/hostel-fee-re/{id}",["uses" => "Student\AdditionalController@index", "as" => "online-admission.hostel-fee-re"]);
    Route::post("/hostel-repayment-response/{id}",["uses" => "Student\AdditionalController@hostelPaymentRecieved", "as" => "hostel-repayment-response"]);
    Route::get("/hostel-receipt-re/{id}",["uses" => "Student\AdditionalController@hostelPaymentReceipt", "as" => "hostel-receipt-re"]);


});

Route::group(['prefix' => 'cuet-details'], function () {
    Route::get("/form/{id}",["uses" => "Student\ApplicationController@CUETForm", "as" => "cuet-details.form"]);
    Route::post("/submit/{id}",["uses" => "Student\ApplicationController@CUETSubmit", "as" => "cuet-details.submit"]);
});

// reportCounsellingOnline  
