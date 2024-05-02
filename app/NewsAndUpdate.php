<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsAndUpdate extends Model
{
    protected $guarded=['id'];
    use SoftDeletes;
}
