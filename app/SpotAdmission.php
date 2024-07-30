<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpotAdmission extends Model
{
    protected $guarded = ['id'];
    use SoftDeletes;
    public function course()
    {
        return $this->belongsTo(Course::class, "course_id", "id")->withTrashed();
    }
}
