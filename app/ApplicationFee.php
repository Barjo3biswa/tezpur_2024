<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationFee extends Model
{
    protected $guarded = ['id'];
    use SoftDeletes;
}
