<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RePaymentSuccess extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public function tried_process() {
        return $this->hasMany(RePaymentProcessing::class, "order_id", "order_id");
    }
    public function application()
    {
        return $this->belongsTo(Application::class)->withTrashed();
    }
}
