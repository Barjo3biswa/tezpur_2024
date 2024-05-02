<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraExamDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];

    public function application()
    {
        return $this->belongsTo(Application::class, "application_id", "id");
    }
    public function student()
    {
        return $this->belongsTo(Application::class, "student_id", "id");
    }
}
