<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpVerify;
use Request;
use App\Models\Application;
use App\Models\MeritList;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Notifications\sendOTPViaEmail;
use Auth;
use Crypt;
use Illuminate\Validation\Rule;
use Log;
use Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except("verifyEmail", 'refreshCapcha');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("ok");
        $application = Application::query();
        $application = $application->with("caste", "attachments", "student", "admit_card_published", "merit_list");
        if (auth("student")->check()) {
            $applications = $application->where("student_id", auth("student")->id());
            $student_id = auth("student")->id();
            $merit_list = MeritList::where('student_id',$student_id)->where('new_status','called')->get();
        }
        
        $applications = $application->whereNull("deleted_at");
        // dd($applications->get());
        $applications = $application->paginate(100);

        $program=0;
        // dd($application);
        return view('home', compact("applications","program","merit_list"));
    }
    public function verifyOTP(OtpVerify $request)
    {
        $request->validate();
        $user = auth()->user();
        if ($user->otp_verified) {
            return back()->with("error", 'OTP is already verified.');
        }
        if (!$this->verify_otp($request->otp)) {
            return back()->with("error", 'Please enter the correct OTP.');
        }
        try {

            $user = auth()->user();
            $user->otp_verified = 1;
            $user->otp_verified_at = date("Y-m-d H:i:s");
            $user->save();
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return redirect()->route("student.home")->with("success", "OTP verified successfully.");
    }
    private function verify_otp($otp)
    {
        if ($otp == auth()->guard("student")->user()->otp) {
            return true;
        }
        return false;
    }
    public function resendOTP(Request $request) {
        $user = auth("student")->user();
        if($user->otp_verified){
            return redirect()->back()->with("error", "OTP is already verified.");
        }else{

            // $otp     = mt_rand(123452, 998877);
            // $message = "Dear Applicant, {$otp} is the OTP for Registration to apply at " . env("OTP_APP_NAME");
            // sendSMS($data['isd_code'].$data['mobile_no'], $message, "1207161901897672542");


            $otp = mt_rand(123452,998877);
            $user->otp = $otp;
            if($user->otp_retry < config("vknrl.otp-limit")){
                $user->save();
                $message = "Dear Applicant, {$otp} is the OTP for Registration to apply at " . env("OTP_APP_NAME");
                // sendSMS($data['isd_code'].$data['mobile_no'], $message, "1207161901897672542");
                sendSMS($user->isd_code.$user->mobile_no, $message, "1207161901897672542");
                $user->increment('otp_retry');
                $user->notify(new sendOTPViaEmail($user, $otp));
                return redirect()->back()->with("success", "OTP successfully resent. Remaining ".(config("vknrl.otp-limit") - $user->otp_retry)." times.");
            }else{
                return redirect()->back()->with("error", "You have crossed the limit of OTP resend. Try another valid mobile no in registration. Or contact university authority.");
            }            
        }
        
        return redirect()->back()->with("success", "OTP successfully resent.");

    }
    public function verifyEmail()
    {
        
        $rules = [
            "email" => "required|email",
            "token" => "required"
        ];
        $validator = Validator::make(request()->all(), $rules);
        if($validator->fails()){
            abort("Whoops! something went wrong. Please verify your link.");
        }
        $user = User::where("email", request()->get("email"))
        ->where("token", request()->get("token"))->first();
        
        if($user){
            if($user->email_verified_at){
                if(Auth::guard('student')->check()){
                    return redirect()->route("student.application.index")->with("status", "Email successfully verified.");
                }else{
                    return redirect()->route("student.login")->with("status", "Email successfully verified.");
                }
            }
            $user->update(["email_verified_at" => current_date_time()]);
            if(Auth::guard('student')->check()){
                return redirect()->route("student.application.index")->with("status", "Email successfully verified.");
            }else{
                return redirect()->route("student.login")->with("status", "Email successfully verified.");
            }
        }
        // dd("ok22");
        return redirect()->route("student.login")->with("error", "Link Expired try again later.");
    }
    public function resendEmail()
    {
        
        $user = auth()->user();
        $token = str_random(60);
        try {
            if($user->email_verified_at){
                return redirect()->back()->with("error", "Email Already verified.");
            }
            $user->update([
                "token" => $token
            ]);
            $user->notify(new EmailVerification($user, $token));
        } catch (\Throwable $th) {
            //  dd($th);
            Log::error($th);
            return redirect()->back()->with("error", "Whoops! Something went wrong.");
        }
        // dd("ok");
        return redirect()->back()->with("success", "Verification email sent. Please check mailbox.");
    }

    public function changeEmail(Request $request)
    {
        return view('student.change-email');
    }
    public function saveChangeEmail(Request $request)
    {
        $session = getActiveSession();
        $rules = [
            'email'=> ['required', 'email','max:255', Rule::unique('users', "email")->where(function ($query) use ($session) {
                return $query->where('session_id', $session->id);
            })],
        ];
        $validator = Validator::make(request()->all(), $rules);
        if($validator->fails()){
            return redirect()->back()->with("error", "Your Email is already used.");
        }
        $user = auth()->user();
        $user->update([
            'email' => request()->get("email"),
            // "token" => $token,
            "email_verified_at" => null,
        ]);
        $this->resendEmail();
        return redirect()->route("student.home")->with("success", "Succesfully changed email.");
    }
    public function emailVerifyView()
    {
        if(!request()->session()->get('status') && !request()->session()->get('error') && !request()->session()->get('success')){
            request()->session()->flash('status', "Please verify email to proceed.");
        }
        return view("student.email-verify");
    }
    public function downloadFormat()
    {
        return view("student.information");
    }
    public function refreshCapcha()
    {
        return captcha_img();
    }

    
}
