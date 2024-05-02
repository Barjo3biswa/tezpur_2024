<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseType extends Model
{
    use SoftDeletes;
    protected $fillable =['name','code'];
    
    public function courses(){
        return $this->hasMany(Course::class);
    }
}
