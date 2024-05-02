<?php

namespace App\Models;

use App\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionReceipt extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ["id"];

    
    public function getCreatedAtAttribute($date)
	{
	    return date('d-m-Y',strtotime($date));
    }
    public function payment()
    {
        return $this->belongsTo(OnlinePaymentSuccess::class, "online_payment_id", "id")->withTrashed();
    }
    public function application()
    {
        return $this->belongsTo('App\Models\Application', 'application_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

	public function collections()
    {
        return $this->hasMany('App\Models\AdmissionCollection', 'receipt_id', 'id');
    }
    public function previous()
    {
        return $this->belongsTo(AdmissionReceipt::class, "previous_receipt_id", "id")->latest();
    }
    public function course()
    {
        return $this->belongsTo(Course::class, "course_id", "id")->withTrashed();
    }
    public function admission_category()
    {
        return $this->belongsTo(AdmissionCategory::class, "category_id", "id")->withDefault([
            "name"  => "NA"
        ]);
    }
    public function generateRollNumber()
    {
        $course = Course::withTrashed()->find($this->course_id);
        // counting all previous admitted student to the same course/programm
        // first time its return zero
        if(!in_array($this->course_id,[79])){
        $count = AdmissionReceipt::where("course_id", $this->course_id)
            ->whereYear("created_at", date("Y"))
            ->where("id", "<", $this->id)
            ->withTrashed()
            ->count();
        }else{

            // $countI = AdmissionReceipt::where("course_id", 78)
            // ->whereYear("created_at", date("Y"))
            // ->where("id", "<", $this->id)
            // ->withTrashed()
            // ->count();


            $countII = AdmissionReceipt::where("course_id", 79)
            ->whereYear("created_at", date("Y"))
            ->where("id", "<", $this->id)
            ->withTrashed()
            ->count();
            $count= /* $countI */50 + $countII;

        }
        $roll_number = $course->series_1;
        $roll_number .= str_pad($course->series_2 + $count, 3, "000", STR_PAD_LEFT);
        return $roll_number;
    }
}
