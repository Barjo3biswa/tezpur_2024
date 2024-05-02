<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\AdmitCard;
use App\Models\Application;
use Crypt;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use Log;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
    

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route("admin.login"))->with("success", "Successfully Logged Out.");
    }    
    public function username() {
        return "username";
    }
    
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ]);
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $passphrase = (Session::has("admin_login_crypt") ? Session::get("admin_login_crypt") : null);
        $password = cryptoJsAesDecrypt($passphrase, $request->password);
        $request->merge(["password" => $password]);
        // dump($password);
        // dump($request->session());
        // dd($request->all());
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function qrView($id)
    {
        // dd("ok");
        try {
            $id = Crypt::decrypt($id);
            $application = Application::with("attachments","applied_courses",'cuet_exam_details')->whereId($id)->first();

        } catch (\Exception $e) {
            Log::error($e);
        }
        // dd($application->get());
        return view('student.scanned-qr',compact("application"));
        // return view("common/application/show", compact("application"));
    }

    public function qrviewAdmitCard($id)
   {
    // try {
    //     $decrypted_id = Crypt::decrypt($id);
    // } catch (Exception $e) {
    //     Log::error($e);
    //     return redirect()->route("admin.admit-card.index")
    //             ->with("error", "Whoops! Something went wrong please try again later.");
    // }
    // try {      
        // $admit_card = AdmitCard::with(["application.caste","application.attachments", "exam_center","applied_course_details"])->findOrFail($id);
    // } catch (Exception $e) {
    //     // dd($e);
    //     Log::error($e);
    //     return redirect()
    //         ->route("admin.admit-card.index")
    //         ->with("error", "Whoops! Something went wrong please try again later.");
    // }
    // dd($admit_card);
    // return view("common.application.admit_card.public-admit", compact("admit_card"));
    // return view("admin.admit_card_new.show", compact("admit_card"));

    // try {
            $deid = $id;
            $application = Application::with("attachments","applied_courses",'cuet_exam_details')->whereId($deid)->first();

        // } catch (\Exception $e) {
        //     Log::error($e);
        // }
        // dd($application->get());
        return view('student.scanned-qr',compact("application"));
        // return view("common/application/show", compact("application"));
   }

}
