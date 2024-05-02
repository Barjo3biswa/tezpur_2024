<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id'
    ];
    protected $with = ['course'];
    public static $rules = [
        "course_id"     => "required|exists:courses,id",
        "fee_heads.*"   => "required|exists:fee_heads,id",
        "amount.*"      => "required|numeric|min:0",
        'type'          => "required|in:admission,examination",
        'other_admission_category_id.*'          => "exists:castes,id",
    ];
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id')->withTrashed();
    }
    public function feeStructures()
    {
        return $this->hasMany('App\Models\FeeStructure', 'fee_id', 'id');
    }
    public function admissionCategory()
    {
        return $this->belongsTo('App\Models\AdmissionCategory', 'admission_category_id');
    }
    public function caste()
    {
        return $this->belongsTo(Caste::class, 'caste_id');
    }
    public static function getRules()
    {
        $additional_rules = [
            // "ask_hostel"    => "required|boolean",
            "programm_type"    => "required|in:".implode(",", MeritList::$programm_types),
        ];
        return array_merge(static::$rules, $additional_rules);
    }
}
