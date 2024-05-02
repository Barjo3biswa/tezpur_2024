<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
class CommonRouteProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Route::macro('applicationRoutes', function ($attributes) {
            Route::resource("application", "ApplicationController", ['except' => ["update"]]);
            Route::group(["prefix" => "application", "as" => "application."], function(){
                Route::put("/step_one_form/{application_id}", ["uses" =>"ApplicationController@stepOneUpdate", "as" => "step_one_form"]);
                Route::put("/step_two_form/{application_id}", ["uses" =>"ApplicationController@stepTwoUpdate", "as" => "step_two_form"]);
                Route::put("/step_three_form/{application_id}", ["uses" =>"ApplicationController@stepThreeUpdate", "as" => "step_three_form"]);
                Route::put("/step_final_form/{application_id}", ["uses" =>"ApplicationController@stepFinalUpdate", "as" => "step_final_form"]);
                Route::get("/payment-receipt/{application_id}", ["uses" => "ApplicationController@paymentReceipt", "as" => "payment-reciept"]);
                Route::get("/re-payment-receipt/{application_id}", ["uses" => "ApplicationController@rePaymentReceipt", "as" => "re-payment-reciept"]);
                Route::put("/accept/{application_id}", ["uses" =>"ApplicationController@acceptApplication", "as" => "accept"]);
                Route::put("/reject/{application_id}", ["uses" =>"ApplicationController@rejectApplication", "as" => "recept"]);
                Route::put("/hold/{application_id}", ["uses" =>"ApplicationController@holdApplication", "as" => "hold"]);
                Route::group(['prefix' => 'upload'], function () {
                    Route::get("qualified_student", ["uses" => "ApplicationController@qualifiedStudentImport", "as" => "upload.student.qualified"]);
                    Route::post("qualified_student", ["uses" => "ApplicationController@qualifiedStudentImportPost", "as" => "upload.student.qualified.post"]);
                });
                Route::group(['prefix' => 'sms'], function () {
                    Route::post("send", ["uses" => "ApplicationController@sendSMS", "as" => "sms.send"]);
                });
            });
        });
    }
}
