<?php

namespace App\Models;

use App\Events\MeritListPartiallyApproveEvent;
use App\HostelFeeStructure;
use App\HostelReceipt;
use App\Traits\Models\MeritListAdmissionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MeritList extends Model
{
    use MeritListAdmissionTrait;
    use SoftDeletes;
    protected $guarded    = ['id', 'token'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    
    public static $new_status = [
        'admitted' => 'Admited',
        // 'Transfered' => 'Transfered',
        'time_extended' => 'Hold & Extended',
        'reported' => 'Reported',
        'declined' => 'Declined',
        'cancel' => 'Rejected',
        'denied' => 'Denied',
        // 'denied'   => 'attendence dea timeotv reject koroile attendence_flag=2',
        // 'attendence_flag=1' => 'Repored',

    ];

    public static $status = [
        0 => "Selected",
        // 1 => "Approved",
        2 => "Admited",
        3 => "Seat Transferred",
        // 4 => "Cancelled",
        5 => "Temporarily On Hold",
        6 => "Withdrawal",
        // 7 => "System Generated",
        8 => "Reported for Admission",
        // 9 => "Declined by Candidate",
        // 10 => "Reporting time expired",
        11 => "Admission Time Expired",
        14 => "Seat Slided",
        // 15=>'for old merit list when slided'
        // 16=>'when sliding denied'
        17=> "Present & Not Admitted",
        18=> "Present & Admitted",
        // 19 => "Present & Declined",
    ];
    public static $programm_types       = ["full_time", "part_time", "not_required"];
    public static $programm_types_excel = ["FT", "PT", "NR"];
    public static $programm_types_store = [
        "FT" => "full_time",
        "PT" => "part_time",
        "NR" => "not_required",
    ];
    public static $WITHDRAWAL_STATUS = 6;
    public function student()
    {
        return $this->belongsTo('App\Models\User', 'student_id', 'id');
    }
    public function application()
    {
        return $this->belongsTo('App\Models\Application', 'application_no', 'application_no');
    }
    public function meritMaster()
    {
        return $this->belongsTo('App\Models\MeritMaster', 'merit_master_id');
    }
    public function admissionCategory()
    {
        return $this->belongsTo('App\Models\AdmissionCategory', 'admission_category_id');
    }
    public function shortlistedCategory()
    {
        return $this->belongsTo('App\Models\AdmissionCategory', 'shortlisted_ctegory_id');
    }
    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id')->withTrashed();
    }
    public function fee_structure()
    {
        return Fee::with(["feeStructures"])->where("course_id", $this->course_id)
            ->where("caste_id", $this->application->caste_id)
            ->where("hostel_required", $this->hostel_required)
            ->where("programm_type", $this->programm_type)
            ->orderBy("id", "DESC")
            ->first();
    }

    public function fee_structure_hostel()
    {
        return HostelFeeStructure::where("course_id", $this->course_id)
            ->where("caste_id", $this->application->caste_id)
            // ->where("hostel_required", $this->hostel_required)
            // ->where("programm_type", $this->programm_type)
            ->orderBy("id")
            ->get();
    }


    public function stringStatus()
    {
        return self::$status[$this->status];
    }

    public function admissionReceipt()
    {
        return $this->hasOne(AdmissionReceipt::class, "merit_list_id", "id");
        // return $this->hasOne(AdmissionReceipt::class, "student_id", "student_id")/* ->where('deleted_at',null)->where('status',0) */;

        // return AdmissionReceipt::where("course_id", $this->course_id) 
        //     ->where("student_id", $this->student_id)
        //     ->first();
    }

    public function hostelReceipt()
    {
        return $this->hasOne(HostelReceipt::class, "merit_list_id", "id")->where('type','hostel');
    }

    public function hostelReceiptRepayment()
    {
        return $this->hasOne(HostelReceipt::class, "merit_list_id", "id")->where('type','hostel_repayment');
    }

    public function online_admission_payment_tried($payment_type = "admission")
    {
        return $this->hasMany(OnlinePaymentProcessing::class, "merit_list_id", "id")
            ->where("payment_type", $payment_type)
            // ->whereYear('created_at','2023')
            ->whereDate('created_at', '>=', '2023-05-01')
            ->orderBy("id", "ASC");
    }
    public function online_admission_payment_tried_hostel($payment_type = "hostel")
    {
        return $this->hasMany(OnlinePaymentProcessing::class, "merit_list_id", "id")
            ->where("payment_type", $payment_type)
            // ->whereYear('created_at','2023')
            ->whereDate('created_at', '>=', '2023-05-01')
            ->orderBy("id", "ASC");
    }

    public function online_admission_payment_tried_hostel_repayment($payment_type = "hostel_repayment")
    {
        return $this->hasMany(OnlinePaymentProcessing::class, "merit_list_id", "id")
            ->where("payment_type", $payment_type)
            // ->whereYear('created_at','2023')
            ->whereDate('created_at', '>=', '2023-05-01')
            ->orderBy("id", "ASC");
    }

    public function online_admission_payments_succeed($payment_type = "admission")
    {
        return $this->hasMany(OnlinePaymentSuccess::class, "merit_list_id", "id")
            ->where("payment_type", $payment_type)
            // ->whereYear('created_at','2023')
            ->whereDate('created_at', '>=', '2023-05-01')
            ->orderBy("id", "ASC");
    }

    public function online_admission_payments_succeed_hostel($payment_type = "hostel")
    {
        return $this->hasMany(OnlinePaymentSuccess::class, "merit_list_id", "id")
            ->where("payment_type", "hostel")
            // ->whereYear('created_at','2023')
            ->whereDate('created_at', '>=', '2023-05-01')
            ->orderBy("id", "ASC");
    }


    public function isDateExpired()
    {
        // return opposit of available
        return !(strtotime($this->valid_from) <= time() && strtotime($this->valid_till) >= time());
    }

    public function course_seat()
    {
        return CourseSeat::where("course_id", $this->course_id)
            ->where("admission_category_id", $this->admission_category_id)
            ->first();
    }

    public function shortlisted_course_seat()
    {
        return CourseSeat::where("course_id", $this->course_id)
            ->where("admission_category_id", $this->shortlisted_ctegory_id)
            ->first();
    }

    public static function getMeritListRules()
    {
        return [
            "*.roll_no"        => "required|exists:users,id|bail",
            "*.application_no" => "required|exists:applications,application_no|bail",
            "*.category"       => "required|exists:admission_categories,name|bail",
            "*.tuee_rank"      => "required|integer|min:1|bail",
            "*.hostelrequired" => "required|integer|in:1,0|bail",
            "*.askhostel"      => "required|integer|in:1,0|bail",
            "*.is_pwd"         => "required|integer|in:1,0|bail",
            "*.cdtype"         => "required|bail|in:" . implode(",", static::$programm_types_excel),
            '*.student_rank'   => 'required|string',
        ];
    }
    public function approved_undertaking()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "undertaking")
            ->where("status", "=", MeritListUndertaking::$accepted);
    }
    public function active_undertaking()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "undertaking")
            ->whereNotIn("status", [MeritListUndertaking::$rejected, MeritListUndertaking::$accepted]);
    }
    public function rejected_undertaking()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "undertaking")
            ->where("status", MeritListUndertaking::$rejected);
    }
    public function undertakings()
    {
        return $this->hasMany(MeritListUndertaking::class, "merit_list_id", "id")
        // ->where("doc_name", "undertaking")
            ->latest();
    }
    // mark-sheet details
    public function approved_marksheet()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "12_+_2_marksheet")
            ->where("status", "=", MeritListUndertaking::$other_accepted);
    }
    public function active_marksheet()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "12_+_2_marksheet")
            ->whereNotIn("status", [MeritListUndertaking::$other_rejected, MeritListUndertaking::$other_accepted]);
    }
    public function rejected_marksheet()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "12_+_2_marksheet")
            ->where("status", MeritListUndertaking::$other_rejected);
    }
    // marksheet end

    // mark-sheet details
    public function approved_prc()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "prc")
            ->where("status", "=", MeritListUndertaking::$other_accepted);
    }
    public function active_prc()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "prc")
            ->whereNotIn("status", [MeritListUndertaking::$other_rejected, MeritListUndertaking::$other_accepted]);
    }
    public function rejected_prc()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "prc")
            ->where("status", MeritListUndertaking::$other_rejected);
    }
    // prc end

    // mark-sheet details
    public function approved_category()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "category")
            ->where("status", "=", MeritListUndertaking::$other_accepted);
    }
    public function active_category()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "category")
            ->whereNotIn("status", [MeritListUndertaking::$other_rejected, MeritListUndertaking::$other_accepted]);
    }
    public function rejected_category()
    {
        return $this->hasOne(MeritListUndertaking::class, "merit_list_id", "id")
            ->where("doc_name", "category")
            ->where("status", MeritListUndertaking::$other_rejected);
    }
    // category end
    public function courseChanges()
    {
        return $this->hasMany(MeritListCourseChange::class, "merit_list_id", "id");
    }

    /**
     * partiallyApproveTakeAdmission is used to make merit list candidate partially approved for admission.
     * then admin will final approve
     */
    public function partiallyApproveToTakeAdmission($from_date, $to_date)
    {        
        $this->update([
            "status"     => 7,
            "valid_from" => $from_date,
            "valid_till"   => $to_date,
        ]);
        event(new MeritListPartiallyApproveEvent($this));
    }
    public function getStatusText()
    {
        return static::$status[$this->status] ?? "NA";
    }
    public function meritList()
    {
        return $this->hasMany(MeritList::class, "course_id", "course_id"); //->whereColumn("admission_category_id", "admission_category_id");
    }
    public function withdrawalRequest()
    {
        return $this->hasOne(WithdrawalRequest::class);
    }
    public function convertToSeatWithdrawal()
    {
        $this->update([
            "status"    => static::$WITHDRAWAL_STATUS
        ]);
    }

    public function selectionCategory()
    {
        return $this->belongsTo('App\Models\AdmissionCategory','selection_category');
    }

    public function btechPrevious(){
        return $this->hasOne(MeritList::class,'student_id','student_id')->whereIn('merit_master_id',[45,105]);
    }
}
