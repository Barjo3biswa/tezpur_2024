<?php

namespace App\Models;

use App\ApplicationAcademic;
use App\AppliedCourse;
use App\Course;
use App\OtherQualification;
use App\TueeResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmitCard extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    public static $rules = [
        "publish"       => "required|in:0,1",
        "exam_center"   => "required|exists:exam_centers,id",
        "date"          => "required|date",
        "time"          => "required",
        "applications"  => "required|array|min:1",
    ];
    public function application() {
        return $this->belongsTo("\App\Models\Application", "application_id", "id")/* ->withTrashed() */;
    }
    public function exam_center() {
        return $this->belongsTo("\App\Models\ExamCenter", "exam_center_id", "id")->withTrashed();
    }

    public function sub_exam_center() {
        return $this->belongsTo("\App\Models\SubExamCenter", "sub_exam_center_id", "id")->withTrashed();
    }

    public function active_application(){
        return $this->belongsTo("\App\Models\Application", "application_id", "id");
    }

    public function applied_course_details()
    {
        // app\AppliedCourse.php
        return $this->belongsTo(AppliedCourse::class,'applied_course_id','id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id')->withTrashed();
    }

    public function Session_dtl()
    {
        return $this->belongsTo(Session::class,'session','id');
    }

    public function academic()
    {
        return $this->hasOne(ApplicationAcademic::class,'application_id','application_id');
    }

    public function other_quali()
    {
        return $this->hasMany(OtherQualification::class,'application_id','application_id');
    }

    public function tuee_result()
    {
        return $this->belongsTo(TueeResult::class,'roll_no','roll_no')/* ->where('publish',1) */;
    }
}
