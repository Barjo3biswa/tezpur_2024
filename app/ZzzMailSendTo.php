<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZzzMailSendTo extends Model
{
    protected $guarded=['id'];
    use SoftDeletes;
}
