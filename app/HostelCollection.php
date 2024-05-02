<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelCollection extends Model
{
    use SoftDeletes;
    protected $guarded=['id'];
    public function feeHead()
    {
        return $this->belongsTo('App\Models\FeeHead', 'fee_head_id');
    }
}
