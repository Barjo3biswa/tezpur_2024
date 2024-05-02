<?php

namespace App\Models;

use App\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCombination extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ["id"];

    public function courses()
    {
        return $this->hasMany(Course::class, "combination_id", "id");
    }
}
