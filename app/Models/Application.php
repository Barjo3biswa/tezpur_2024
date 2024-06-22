<?php

namespace App\Models;

use App\ApplicationAcademic;
use App\ApplicationEligibilityCritariaAnswers;
use App\AppliedCourse;
use App\Course;
use App\District;
use App\Events\ApplicationEdited;
use App\IncomeRange;
use App\MiscDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\OtherQualification;
use App\ParentsQualification;
use App\Priority;
use App\State;
use Illuminate\Database\Eloquent\Builder;
use Log;

class Application extends Model
{
    use SoftDeletes;
    protected $guarded                       = ["id"];
    public static $applicant_lower_age_limit = 17;
    public static $applicant_upper_age_limit = 35;
    public static $dob_compare_to            = "2019-12-31";
    // marks related
    public static $hs_marks_percent         = 40; //percent
    public static $anm_marks_percent        = 40; //percent
    public static $marks_percent_relaxation = 5; //percent incase of ST/SC

    public static $statuses_for_admin = ["payment_done", "rejected", "on_hold", "accepted", "qualified", "document_resubmitted"];
    public function caste()
    {
        return $this->belongsTo("App\Models\Caste", "caste_id", "id");
    }
    public function session()
    {
        return $this->belongsTo("App\Models\Session", "session_id", "id")->withTrashed();
    }
    public function student()
    {
        return $this->belongsTo("App\Models\User", "student_id", "id")->withoutGlobalScopes();
    }
    public function attachments()
    {
        return $this->hasMany("App\Models\ApplicationAttachment", "application_id", "id");
    }
    public function attachment_others()
    {
        return $this->attachments->whereNotIn("doc_name", ["passport_photo", "signature"]);
        return $this->hasMany("App\Models\ApplicationAttachment", "application_id", "id");
    }
    //Extra Document
    public function attachment_extra_document()
    {
        //return $this->attachments->whereNotIn("doc_name", ["passport_photo", "signature"]);
        return $this->hasMany("App\Models\ExtraDocument", "application_id", "id");

    }
    //Extra Document
    public function passport_photo()
    {
        return $this->attachments->where("doc_name", "passport_photo")->first();
        return $this->hasOne("App\Models\ApplicationAttachment", "application_id", "id")->where("doc_name", "passport_photo");
    }
    public function signature()
    {
        return $this->attachments->where("doc_name", "signature")->first();
        return $this->hasOne("App\Models\ApplicationAttachment", "application_id", "id")->where("doc_name", "signature");
    }
    public function remarks()
    {
        return $this->hasMany("App\Models\ApplicationRemark", "application_id", "id");
    }
    public function online_payment_tried($payment_type = "application")
    {
        return $this->hasMany("App\Models\OnlinePaymentProcessing", "application_id", "id")
            ->where("payment_type", $payment_type)
            ->orderBy("id", "ASC");
    }

    public function online_re_payment_tried($payment_type = "application_repayment")
    {
        return $this->hasMany("App\Models\OnlinePaymentProcessing", "application_id", "id")
            ->where("payment_type", $payment_type)
            ->orderBy("id", "ASC");
    }


    public function online_payments_succeed($payment_type = "application")
    {
        return $this->hasMany("App\Models\OnlinePaymentSuccess", "application_id", "id")
            ->where("payment_type", $payment_type)
            ->orderBy("id", "ASC");
    }

    public function online_admission_payment_tried($payment_type = "admission")
    {
        return $this->hasMany("App\Models\OnlinePaymentProcessing", "application_id", "id")
            ->where("payment_type", $payment_type)
            ->whereYear('created_at','2023')
            ->orderBy("id", "ASC");
    }
    
    public function online_admission_payments_succeed($payment_type = "admission")
    {
        return $this->hasMany("App\Models\OnlinePaymentSuccess", "application_id", "id")
            ->where("payment_type", $payment_type)
            ->whereYear('created_at','2023')
            ->orderBy("id", "ASC");
    }
    public function paymentReceipt($payment_type = "application")
    {
        return $this->hasOne("App\Models\OnlinePaymentSuccess", "application_id", "id")
            ->where("payment_type", $payment_type)
            ->where("biller_status", "captured")
            ->where("status", 1)
            ->orderBy("id", "ASC");
    }
    public function allPaymentReceipt()
    {
        return $this->hasOne("App\Models\OnlinePaymentSuccess", "application_id", "id")
            ->where("biller_status", "captured")
            ->where("status", 1)
            ->orderBy("id", "ASC");
    }
    public function admissionPaymentReceipt($payment_type = "admission")
    {
        return $this->hasOne("App\Models\OnlinePaymentSuccess", "application_id", "id")
            ->where("payment_type", $payment_type)
            ->where("biller_status", "captured")
            ->where("status", 1)
            ->orderBy("id", "ASC");
    }
    public function re_payment_tried()
    {
        return $this->hasMany(RePaymentProcessing::class, "application_id", "id")->orderBy("id", "ASC");
    }
    public function re_payments_succeed()
    {
        return $this->hasMany(RePaymentSuccess::class, "application_id", "id")->orderBy("id", "ASC");
    }
    public function rePaymentReceipt()
    {
        return $this->hasOne(RePaymentSuccess::class, "application_id", "id")->where("biller_status", "captured")->where("status", 1)->orderBy("id", "ASC");
    }
    public function auditTrail()
    {
        return $this->hasMany("App\Models\ApplicationEdited", "application_id", "id");
    }
    public function admit_card()
    {
        return $this->hasOne("App\Models\AdmitCard", "application_id", "id");
    }
    public function admit_card_published()
    {
        return $this->hasOne("App\Models\AdmitCard", "application_id", "id")->where("publish", 1);
    }
    public function admit_card_draft()
    {
        return $this->hasOne("App\Models\AdmitCard", "application_id", "id")->where("publish", 0);
    }

    public function ExamCenter(){
        return $this->hasOne(ExamCenter::class,'id',"exam_center_id");
    }

    public function ExamCenter1(){
        return $this->hasOne(ExamCenter::class,'id',"exam_center_id1");
    }

    public function ExamCenter2(){
        return $this->hasOne(ExamCenter::class,'id',"exam_center_id2");
    }

    public function cor_district(){
        return $this->hasOne(District::class,"id","correspondence_district");
    }
    public function per_district(){
        return $this->hasOne(District::class,"id","permanent_district");
    }
    public function cor_state(){
        return $this->hasOne(State::class,"id","correspondence_state");
    }
    public function per_state(){
        return $this->hasOne(State::class,"id","permanent_state");
    }
    public function extra_documents()
    {
        return $this->hasMany(ExtraDocument::class, "application_id", "id");
    }
    public function admission_receipt()
    {
        return $this->hasOne(AdmissionReceipt::class, "application_id", "id")->orderBy("id", "DESC");
    }
    public function misc_documents()
    {
        return $this->hasMany(MiscDocument::class, "application_id", "id")->orderBy("document_sl");
    }

    public function fatherQualification()
    {
        return $this->hasOne(ParentsQualification::class,"id", "father_qualification");
    }
    public function motherQualification()
    {
        return $this->hasOne(ParentsQualification::class,"id", "mother_qualification");
    }

    public static $rules = [
        "personal_information"   => [
            "fullname"                => "required|max:255",
            "gender"                  => "required|in:Female,Testing_gender",
            "father_name"             => "required|max:255",
            "father_occupation"       => "required|max:255",

            "mother_name"             => "nullable|max:255",
            "mother_occupation"       => "nullable|max:255",
            "maritial_status"         => "required|in:Married,Unmarried",
            "religion"                => "required|max:255",
            "nationality"             => "required|max:255|in:Indian,India,INDIAN",
            "dob"                     => "required|date_format:d-m-Y",
            "disablity"               => "required",
            "caste"                   => "required|exists:castes,id",
            "anm_or_lhv"              => "required|numeric|in:1,0",
            "anm_or_lhv_registration" => "nullable|max:100",
            // "bpl"   => "required"
        ],
        "address_information"    => [
            // address
            "correspondence_vill_town" => "required|max:255",
            "correspondence_po"        => "required|max:255",
            "correspondence_ps"        => "required|max:255",
            "correspondence_pin"       => "required|digits:6",
            "correspondence_state"     => "required|max:255",
            "correspondence_district"  => "required|max:255",
            "correspondence_contact"   => "required|digits:10",
            "same_address"             => "numeric",
            "permanent_vill_town"      => "required|max:255",
            "permanent_po"             => "required|max:255",
            "permanent_ps"             => "required|max:255",
            "permanent_pin"            => "required|digits:6",
            "permanent_state"          => "required|max:255",
            "permanent_district"       => "required|max:255",
            "permanent_contact"        => "required|digits:10",
        ],
        "academic_information"   => [
            // academic
            "academic_10_stream"      => "required|max:255",
            "academic_10_year"        => "required|numeric",
            "academic_10_board"       => "required|max:255",
            "academic_10_school"      => "required|max:255",
            "academic_10_subject"     => "required|max:255",
            "academic_10_mark"        => "required|numeric|max:1000|min:0",
            "academic_10_percentage"  => "required|numeric|max:100|min:30",

            "academic_12_stream"      => "required|max:255",
            "academic_12_year"        => "required|numeric",
            "academic_12_board"       => "required|max:255",
            "academic_12_school"      => "required|max:255",
            "academic_12_subject"     => "required|max:255",
            "academic_12_mark"        => "required|numeric|max:1000|min:0",
            "academic_12_percentage"  => "required|numeric|max:100|min:30",

            "academic_voc_stream"     => "nullable|max:255",
            "academic_voc_year"       => "nullable|numeric",
            "academic_voc_board"      => "nullable|max:255",
            "academic_voc_school"     => "nullable|max:255",
            "academic_voc_subject"    => "nullable|max:255",
            "academic_voc_mark"       => "nullable|numeric|max:1000|min:0",
            "academic_voc_percentage" => "nullable|numeric|max:100|min:30",

            "academic_anm_stream"     => "nullable|max:255",
            "academic_anm_year"       => "nullable|numeric",
            "academic_anm_board"      => "nullable|max:255",
            "academic_anm_school"     => "nullable|max:255",
            "academic_anm_subject"    => "nullable|max:255",
            "academic_anm_mark"       => "nullable|numeric|max:1000|min:0",
            "academic_anm_percentage" => "nullable|numeric|max:100|min:30",

            "other_qualification"     => "required|max:255",
            "english_mark_obtained"   => "required|numeric|max:100|min:40",
        ],
        "attachment_information" => [
            // files
            'passport_photo'                                  => "image|required|mimes:jpeg,jpg,png|max:100|dimensions:max_width=200,max_height=250",
            'signature'                                       => "image|required|mimes:jpeg,jpg,png|max:100|dimensions:max_width=200,max_height=150",
            "prc_certificate"                                 => "nullable|verify_corrupted|file|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "caste_certificate"                               => "nullable|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "age_proof_certificate"                           => "verify_corrupted|required|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "12_marksheet"                                    => "verify_corrupted|required|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "disablity_certificate"                           => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "document_mentioning_name_of_the_school_class_10" => "verify_corrupted|required|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "anm_registration"                                => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "anm_marksheet"                                   => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "bpl_document"                                    => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
        ],

    ];
    protected $dispatchesEvents = [
        'saving' => ApplicationEdited::class,
    ];
    // "application/pdf|image/jpeg|image/png"
    public function applied_courses()
    {
        return $this->hasMany(AppliedCourse::class, "application_id", "id")->orderBy('preference','asc');
    }

    public function other_qualifications(){
        return $this->hasMany(OtherQualification::class);
    }

    public function cuet_exam_details(){
        return $this->hasMany(CuetExamDetail::class,"application_id", "id")->orderby('id');
    }
    public function application_academic(){
        return $this->hasOne(ApplicationAcademic::class);
    }
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        // static::created(function ($model) {
            //     self::generateApplicationNo($model);
            // });
            // static::addGlobalScope(new ActiveSessionScope);
        if(\Auth::guard("student")->check()){
            $active_session = getActiveSession();
            $other_active_sessions = config("vknrl.optional_active_session_ids");
            $ids = [];
            if($other_active_sessions){
                $ids = explode(",", $other_active_sessions);
            }
            $ids[] = $active_session->id;
            $ids[] = 12;
            // dd($ids);
            static::addGlobalScope('active', function (Builder $builder) use ($ids){
                $builder->whereIn('session_id', $ids);
            });
        }
    }
    /**
     * Scope a query to only include completed
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull("application_no");
    }
    public function getFullNameAttribute(){
        return $this->first_name.($this->middle_name ? " ".$this->middle_name." " : " ").$this->last_name;
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, "priority_id", "id");
    }
    public function father_income_range()
    {
        return $this->belongsTo(IncomeRange::class, "father_income", "id");
    }
    public function mother_income_range()
    {
        return $this->belongsTo(IncomeRange::class, "mother_income", "id");
    }
    public function family_income_range()
    {
        return $this->belongsTo(IncomeRange::class, "family_income", "id");
    }
    
    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtoupper($value);
    }

    /**
     * Set the user's middle name.
     *
     * @param  string  $value
     * @return void
     */
    public function setMiddleNameAttribute($value)
    {
        $this->attributes['middle_name'] = strtoupper($value);
    }

    /**
     * Set the user's last name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtoupper($value);
    }
    public function admission_fee_structure($course_id,$category_id, $type = "admission", $year = null)
    {
        if($year){
            $year = date("Y");
        }
        return $this->hasManyThrough(Fee::class, MeritList::class, "application_id", "fee_id")
            ->wereYear($year)
            ->where("type", $type)
            ->where("course_id", $course_id)
            ->where("admission_category_id", $category_id);
    }
    public function merit_list()
    {
        return $this->hasMany(MeritList::class, "application_no", "application_no");
    }
    public function extraExamDetails()
    {
        return $this->hasMany(ExtraExamDetail::class, "application_id", "id");
    }
    public function work_experiences()
    {
        return $this->hasMany(WorkExperience::class, "application_id", "id");
    }
    public function eligibility_answers()
    {
        return $this->hasMany(ApplicationEligibilityCritariaAnswers::class);
    }
    public function courses()
    {
        return $this->hasManyThrough(
            Course::class, 
            AppliedCourse::class, 
            "application_id",
            "id",
            "id",
            "course_id"
        );
    }
    public function isNorthEastCandidate() : bool
    {
        $ne_states = ['Arunachal Pradesh', 'Assam', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Tripura'];
        return in_array($this->permanent_state, $ne_states);
    }

    public function JossaStatus(){
        return $this->hasOne(User::class,'id','student_id')->where('qualifying_exam','JOSSA');
    }

    public function failedPayment(){
        return $this->hasMany(OnlinePaymentProcessing::class,'application_id','id');
    }

    public function applicationMaster(){
        return $this->hasOne(Application::class,'student_id','student_id')
                    ->where('is_master', 1);
    }

    public function isNetJrfQualified(){
        return $this->hasMany(OtherQualification::class,'application_id','id')
                    ->where(function ($query) {
                        $query->where('exam_name', 'like', '%NET%')
                            ->orWhere('exam_name', 'like', '%JRF%')
                            ->orWhere('exam_name', 'like', '%net%')
                            ->orWhere('exam_name', 'like', '%jrf%');
                    });
    }

    public function isNetJrfQualifiedSecond(){
        return $this->hasOne(ApplicationAcademic::class,'application_id','id')
        ->whereIn('qualified_national_level_test',["UGCNET/JRF","UGC-CSIR","NET/JRF","UGC-CSIR-NET/JRF","DBT- JRF","ICAR-NET","ICMR-JRF","NETLS","GATE","SLET"]);
    }
    public function isNetJrfthird(){
        return $this->hasOne(OtherQualification::class,'application_id','id')
                    ->where(function ($query) {
                        $query->where('exam_name', 'like', '%NET%')
                            ->orWhere('exam_name', 'like', '%JRF%')
                            ->orWhere('exam_name', 'like', '%net%')
                            ->orWhere('exam_name', 'like', '%jrf%');
                    });
    }
    // public function getMasterIdAttribute(){
    //     // dd($this->id);
    //     return $this->id;
    // }

}
