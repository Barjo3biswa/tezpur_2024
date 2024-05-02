<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraDocument extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function application()
    {
        return $this->belongsTo(Application::class, "application_id", "id");
    }

    public function student()
    {
        return $this->belongsTo(User::class, "student_id", "id");
    }
}
