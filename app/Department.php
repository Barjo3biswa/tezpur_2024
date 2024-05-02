<?php

namespace App;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','code',"school_id"];

    public function school()
    {
        return $this->belongsTo(School::class, "school_id", "id");
    }

    public function courses()
    {
        return $this->hasMany(Course::class, "department_id", "id");
    }
}
