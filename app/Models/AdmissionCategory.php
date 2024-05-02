<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionCategory extends Model
{
    //use SoftDeletes;
    public static $PWD_CATEOGORY_ID = 7;
    public static $UR_CATEOGORY_ID  = 1;
    
    public function CourseSeats()
    {
        return $this->belongsTo("App\Models\CourseSeat", "id", "admission_category_id");
    }
}
