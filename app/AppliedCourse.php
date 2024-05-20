<?php

namespace App;

use App\Models\AdmitCard;
use App\Models\Application;
use App\Models\CuetSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppliedCourse extends Model
{
    use SoftDeletes;
    // protected $fillable = ['application_id','student_id','is_btech','course_id','preference','course_type_id','course_type','status','rejection_reason','hold_reason','last_date'];
    protected $guarded = ['id'];
    public function application()
    {
        return $this->belongsTo(Application::class, "id", "application_id");
    }

    public function course()
    {
        return $this->belongsTo(Course::class, "course_id", "id")->withTrashed();
    }

    public function admitcard()
    {
        return $this->belongsTo(AdmitCard::class,"id","applied_course_id");
    }

    public function admitcardPublished()
    {
        return $this->belongsTo(AdmitCard::class,"id","applied_course_id")->where('publish',1);
    }

    public function cuet_course_code(){
        return $this->hasMany(CuetSubject::class,'course_id','course_id')->where('status',1);
    }
   
}
