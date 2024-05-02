<?php

namespace App\Models;

use App\Course;
use App\ShortlistedList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', "type"];

    public function courses()
    {
        return $this->hasMany(Course::class, "program_id", "id");
    }

    public function shortlistedCandidate()
    {
        return $this->hasMany(ShortlistedList::class, "program_id", "id");
    }

    
}

