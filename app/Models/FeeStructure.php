<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FeeStructure extends Model
{
    //
    use SoftDeletes;
	
    protected $guarded = [
        'id'
    ];
    public function fee()
    {
        return $this->belongsTo('App\Models\Fee', 'fee_id');
    }

    public function feeHead()
    {
        return $this->belongsTo('App\Models\FeeHead', 'fee_head_id');
    }
}
