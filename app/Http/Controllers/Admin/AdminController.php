<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonApplicationController;
use App\Models\Session;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Log;

class AdminController extends CommonApplicationController
{
    public function applicantList(Request $request) {
        $applicants = User::query();
        if($request->get("mobile_no")){
            $applicants = $applicants->where("mobile_no", "LIKE", "%".$request->get("mobile_no")."%");
        }
        if($request->get("email")){
            $applicants = $applicants->where("email", "LIKE", "%".$request->get("email")."%");
        }
        /*if($request->get("name")){
            $applicants = $applicants->where("name", "LIKE", "%".$request->get("name")."%");
        } */
        if($request->get("registration_no")){
            $applicants = $applicants->where("id", "=", $request->get("registration_no"));
        }
        if($request->get("country")){
            $applicants = $applicants->where("country_id", "=", $request->get("country"));
        }
        $applicants->when(request("session"), function($query){
            return $query->where("session_id", "=", request("session"));
        });
        $applicants = applicant_global_filter($applicants);
        $applicants = $applicants->with("session")->paginate(100);
        $sessions = Session::all();
        return view("admin.applicants.index", compact("applicants", "sessions"));
    }
    
    public function changePass(Request $request)
    {
        try {
            $id =Crypt::decrypt($request->get("user_id"));
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                "message"   => "Failed",
                "status"    => false
            ]);
        }
        try {
            $user = User::findOrFail($id);
        } catch (\Throwable $th) {
            
        }
        try {
            $user->password = bcrypt($user->mobile_no);
            $user->save();    
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                "message"   => "Failed",
                "status"    => false
            ]);
        }
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Password reset for registration no {$user->id}");
        return response()->json([
            "message"   => "Password successfully changed to mobile no.",
            "status"    => true
        ]);
    }
}
