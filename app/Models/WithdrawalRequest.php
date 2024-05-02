<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawalRequest extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded    = ["id"];
    public static $STATUS = [
        "request_sent",
        "approved",
        "request_rejected",
    ];
    /**
     * Scope a query to only include filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query)
    {
        return $query->when(request("status"), function ($query) {
            return $query->where("status", request("status"));
        });
    }
    public function student()
    {
        return $this->belongsTo(User::class);
    }
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    public function meritList()
    {
        return $this->belongsTo(MeritList::class);
    }
    public function getStatusPlainTextAttribute()
    {
        return str_replace("_", " ", $this->status);
    }
}
