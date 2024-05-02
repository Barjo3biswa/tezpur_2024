<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('finance')->user();

    //dd($users);

    return view('finance.home');
})->name('home');
Route::middleware(['auth:finance'])->prefix('reports')->group(function () {
    Route::get("application-payments", [
        "uses" => "Admin\ReportsController@applicationFeeReport",
        "as"   => "reports.application-payments",
    ]);
    Route::get("admission-payments", [
        "uses" => "Admin\ReportsController@admissionFeeReport",
        "as"   => "reports.admission-payments",
    ]);
    Route::get("daily-collections", [
        "uses" => "Finance\ReportsController@dailyPaymentsCollection",
        "as"   => "reports.daily-collections",
    ]);
});
