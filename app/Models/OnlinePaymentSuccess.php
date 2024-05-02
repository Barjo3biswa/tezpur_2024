<?php

namespace App\Models;

use App\AppliedCourse;
use App\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlinePaymentSuccess extends Model
{
    use SoftDeletes;

    protected $guarded =["id"];
    public static $ADMISSION_PAYMENT_TYPE = "admission";
    public static $APPLICAION_PAYMENT_TYPE = "application";

    public function tried_process() {
        return $this->hasMany("App\Models\OnlinePaymentProcessing", "order_id", "order_id");
    }
    public function application()
    {
        return $this->belongsTo(Application::class)->withTrashed();
    }
    /**
     * Scope a query to only include TypeApplication
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaymentTypeApplication($query)
    {
        return $query->where('payment_type', self::$APPLICAION_PAYMENT_TYPE);
    }
    /**
     * Scope a query to only include TypeApplication
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaymentTypeAdmission($query)
    {
        return $query->where('payment_type', self::$ADMISSION_PAYMENT_TYPE);
    }
    /**
     * Scope a query to only include filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query)
    {
        $query->when(request("date_from"), function($query){
            return $query->whereDate("updated_at", ">=", dateFormat(request("date_from"), "Y-m-d"));
        });
        $query->when(request("date_to"), function($query){
            return $query->whereDate("updated_at", "<=", dateFormat(request("date_to"), "Y-m-d"));
        });
        return $query;
    }
    /**
     * Get the getAmount
     *
     * @param  string  $value
     * @return string
     */
    public function getAmountDecimalAttribute()
    {
        return number_format($this->amount, 2, ".", "");
    }
    public function merit_list()
    {
        return $this->belongsTo(MeritList::class)->withTrashed();
    }
    public function courses()
    {
        return $this->hasManyThrough(
            Course::class, 
            AppliedCourse::class,
            "application_id", 
            "id", 
            "application_id", 
            "course_id"
        )
        ->orderBy("applied_courses.preference")->withTrashed();
    }
}
