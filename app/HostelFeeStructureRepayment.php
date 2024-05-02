<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HostelFeeStructureRepayment extends Model
{
    protected $guarded=['id'];
    public function feeHead()
    {
        return $this->belongsTo('App\Models\FeeHead', 'fee_head_id');
    }
}
