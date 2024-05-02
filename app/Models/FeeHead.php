<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FeeHead extends Model
{
    //
    use SoftDeletes;
	
    protected $guarded = [
        'id'
    ];
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
