<?php

namespace App\Http\Controllers\Auth;

use App\Country;
use App\Http\Controllers\Controller;
use App\MDesExam;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Notifications\sendOTPViaEmail;
use Crypt;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'student/otp-verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest', "admissionController"]);
    }

    public function showRegistrationForm(Request $request)
    {   
        $req=Crypt::decrypt($request->is_mba);
        // dd($req);
        $is_mba=$req;
        $active_session = getActiveSession();
        if(!$active_session->id){
            abort(400, "Session closed.");
        }
        // abort(404, "Registration closed");
        $countries = Country::orderBy('id', 'asc')->where('id','!=',1)->get();
        $mdes_exam= MDesExam::whereIn('program_name',[$is_mba])->get();
        return view('student.auth.register2', compact('countries','is_mba','mdes_exam'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // dd($data);
        // if(/* $data['by_mba']=='PG'|| */$data['by_mba']=='MDES'){       
        //     if($data['exam_through'] ==null || $data['marks_ob']==null ){
        //         return redirect()->back()->with('error','Please Select Exam Through and Marks.');
        //     }               
        // }
        $session = getActiveSession();
        return Validator::make($data, [
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|max:100',
            'last_name'   => 'nullable|max:100',
            'isd_code'    => 'required|max:10|in:' . implode(",", isd_codes()),
            'email'       => ['required', 'email','max:255', Rule::unique('users', "email")],
            'mobile_no'   => ['required','digits:10', Rule::unique('users', "mobile_no")],
            'nationality' => 'required|max:10|exists:countries,id',
            'password'    => 'required|confirmed|min:8'/* |regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@]).*$/' */,
            'captcha'     => 'required|captcha',
            'by_mba'=> /* $data['by_mba'] == 'MDES' ? 'required' :  */'required'
        ],
        [
            "regex" => "The :attribute must be at least 8 characters long, contain at least one number, one special character and have a mixture of uppercase and lowercase letters."
        ]);

       

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd($data['declear_tu']??0);
        // dd($data);
        if(!in_array($data['by_mba'], ["PHD","PG","LATERAL","BTECH","MDES","UG","MBBT","MBA","FOREIGN","BDES","PHDPROF","CHINESE"])){
            // return view('welcome2');
            throw new \Exception("Something went wrong. Try again later.");
        }
        if($data['nationality']==5){
            $data['isd_code']=$data['other_isd_code'];
        }
        // if($data['by_mba']=="UG"){
        //     $qualifying_exam='CUET';
        // }elseif($data['by_mba']=="BTECH"){
        //     $qualifying_exam='SPOT';
        // }elseif($data['by_mba']=="PHD"){
        //     $qualifying_exam='SPRING';
        // }else{
            $qualifying_exam=$data['exam_through']??null;
        // }
        $active_session = getActiveSession();
        // abort(404);
        $otp     = mt_rand(123452, 998877);
        $message = "Dear Applicant, {$otp} is the OTP for Registration to apply at " . env("OTP_APP_NAME");
        sendSMS($data['isd_code'].$data['mobile_no'], $message, "1207161901897672542");
        // dd($data['by_mba']);
        // if($data['by_mba']==1){
        //     $program_name = "MBA";
        // }else if($data['by_mba']=="FOREIGN"){
        //     $program_name = "PG,PHD,LATERAL,BDES,MDES";
        // }else{
        //     $program_name = $data['by_mba'];
        // }
        
        return User::create([
            'first_name'  => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name'   => $data['last_name'] ?? null,
            'email'       => $data['email'],
            'mobile_no'   => $data['mobile_no'],
            'country_id'  => $data['nationality'],
            'isd_code'    => $data['isd_code'],
            'password'    => bcrypt($data['password']),
            'otp'         => $otp,
            'session_id'  => $active_session->id,
            'other_country_name' => $data['other_country'] ?? null,
            'is_mba'      => $data['by_mba']=='MBA'?1:0,
            'program_name'=> $data['by_mba']==1?'MBA':$data['by_mba'],
            'exam_through'=> /* $data['exam_through']?? */'TUEE',
            'qualifying_exam' => $qualifying_exam,
            'marks'           => $data['marks_ob']??null,
            'cuet_verified'   => $data['declear_tu']??0,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $password              = base64_decode($request->password);
        $password_confirmation = base64_decode($request->password_confirmation);
        $passphrase            = (Session::has("admin_login_crypt") ? Session::get("admin_login_crypt") : null);
        $password              = cryptoJsAesDecrypt($passphrase, $password);
        $password_confirmation = cryptoJsAesDecrypt($passphrase, $password_confirmation);
        $request->merge(["password" => $password]);
        $request->merge(["password_confirmation" => $password_confirmation]);
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        $this->guard()->login($user);
        $user  = auth()->user();
        $token = str_random(60);
        User::where('email',$request->email)->update([
            "token" => $token
        ]);
        $user->notify(new sendOTPViaEmail($user, $token));
        $user->notify(new EmailVerification($user, $token));

        return $this->registered($request, $user)
        ?: redirect($this->redirectPath())->with("status", "OTP is sent to your mobile no. Please verify to proceed.");
    }
}
