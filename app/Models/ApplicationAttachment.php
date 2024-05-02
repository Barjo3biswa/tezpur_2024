<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationAttachment extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];

    public function application()
    {
        return $this->hasOne(Application::class,'id','application_id');
    }
}
