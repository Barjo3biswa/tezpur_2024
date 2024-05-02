<?php

namespace App\Models;

use App\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    
    public function departments()
    {
        return $this->hasMany(Department::class, "school_id", "id");
    }
}
