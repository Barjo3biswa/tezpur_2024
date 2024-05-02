<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RePaymentProcessing extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function succed_payments() {
        return $this->hasMany(RePaymentSuccess::class, "order_id", "order_id");
    }
}
