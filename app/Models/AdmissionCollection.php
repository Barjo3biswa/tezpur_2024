<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionCollection extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function receipt()
    {
        return $this->belongsTo('App\Models\AdmissionReceipt', 'receipt_id');
    }
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
    public function application()
    {
        return $this->belongsTo('App\Models\Application', 'application_id');
    }
    public function feeHead()
    {
        return $this->belongsTo('App\Models\FeeHead', 'fee_head_id');
    }
    public function fee()
    {
        return $this->belongsTo('App\Models\Fee', 'fee_id');
    }
}
