<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSeat extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ["id"];

    public function admissionCategory()
    {
        return $this->belongsTo("App\Models\AdmissionCategory","admission_category_id");
    }

    /**
     * Scope a query to only include courseFilter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCourseFilter($query, $course_id)
    {
        return $query->where('course_id', $course_id);
    }
    /**
     * Scope a query to only include pwdFilter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePwdFilter($query)
    {
        return $query->where('admission_category_id', "=", AdmissionCategory::$PWD_CATEOGORY_ID);
    }
    /**
     * Scope a query to only include generalFilter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnreseveFilter($query)
    {
        return $query->where('admission_category_id', "=", AdmissionCategory::$UR_CATEOGORY_ID);
    }
    /**
     * Scope a query to only include seatAvailable
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSeatAvailable($query)
    {
        return $query->whereRaw("(total_seats - total_seats_applied) > 0");
    }
    public function availableSeat(): Int
    {
        //dd("OK");
        //return 0;
       // dd($this->total_seats);
        return (Int)$this->total_seats - (Int)$this->total_seats_applied ?? 0;
    }
    public function withdrawalSeats()
    {
        return $this->getWitdrawalListQuery()->get();
    }
    public function withdrawalSeatsCount()
    {
       return $this->getWitdrawalListQuery()->count();
    }
    private function getWitdrawalListQuery()
    {
        return MeritList::where("course_id", $this->course_id)
            ->where("admission_category_id", $this->admission_category_id)
            ->where("status", MeritList::$WITHDRAWAL_STATUS);
    }
}
