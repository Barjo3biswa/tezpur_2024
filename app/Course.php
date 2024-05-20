<?php

namespace App;

use App\Models\CourseCombination;
use App\Models\Program;
use App\Models\CourseSeat;
use App\Models\AdmissionReceipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','eligibility','department_id','code','course_type_id', 'course_duration', "program_id"];

    public function branches(){
        return $this->hasMany(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, "department_id", "id");
    }

    public function course_type()
    {
        return $this->belongsTo(CourseType::class, "course_type_id", "id")->withTrashed();
    }

    public function program()
    {
        return $this->belongsTo(Program::class, "program_id", "id")->withTrashed();
    }
    public function combination()
    {
        return $this->belongsTo(CourseCombination::class, "combination_id", "id");
    }
    public function child()
    {
        return $this->hasMany(Course::class, "sub_combination_id", "code");
    }
    public function courseSeats()
    {
        if(auth("student")->check()){
            return $this->hasMany("App\Models\CourseSeat")->where("admission_category_id", "<=", 7);
        }
        return $this->hasMany("App\Models\CourseSeat");
    }
    public function admission_receipt(){
        return $this->hasMany(AdmissionReceipt::class,"course_id","id")->where("status", 0);

    }
    public function course_seats()
    {
        return $data= $this->hasMany(CourseSeat::class,"course_id","id")->select('total_seats');

    }
    public function total_seats()
    {
        return CourseSeat::selectRaw('sum(total_seats) as sum_seat, sum(total_seats_applied) as sum_applied_seat')->where("course_id", $this->id)->first();
    }

    public function ExamGroup()
    {
        return $this->belongsTo(GroupMaster::class,"exam_group","group_name");
    }

    public function course_seats_new()
    {
        return $data= $this->hasMany(CourseSeat::class,"course_id","id")->orderBy('admission_category_id');

    }

}
