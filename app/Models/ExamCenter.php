<?php

namespace App\Models;

use App\AppliedCourse;
use App\Country;
use App\SubExamCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamCenter extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    public static $rules = [
        "center_code"   => "max:255",
        "center_name"   => "required|max:255",
        "address"       => "required|max:255",
        "city"          => "required|max:100",
        "state"         => "nullable|max:100",
        "country_id"    => "required|exists:countries,id",
        "pin"           => "nullable|min:4|max:6",
    ];
    public static $messages = [
        "country_id.required"    => "Country field is required.",
        "country_id.exists"    => "Country field must be valid country.",
    ];
    public function country()
    {
        return $this->belongsTo(Country::class, "country_id", "id");
    }

    public function application()
    {
        return $this->hasMany(Application::class, "exam_center_id")->whereNotNull('application_no');
    }

    public function applied_courses()
    {
        return $this->hasManyThrough(
            AppliedCourse::class,
            Application::class,
            'exam_center_id',
            'application_id'
        )->where('exam_through','TUEE')->orderby('course_id')/* ->orderby('application.first_name') */;
    }

    public function applied_courses2024()
    {
        return $this->hasManyThrough(
            AppliedCourse::class,
            Application::class,
            'exam_center_id',
            'application_id'
        )->where('exam_through','TUEE')->orderby('course_id')/* ->orderby('application.first_name') */;
    }
    
    public function subExamCenter(){
        return $this->hasMany(SubExamCenter::class, "city_id","id");
    }

    public function AdmitCards(){
        return $this->hasMany(AdmitCard::class, "exam_center_id");
    }
}
