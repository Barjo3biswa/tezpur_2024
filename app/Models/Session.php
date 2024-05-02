<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use SoftDeletes;
    protected $guaraded = ["id"];
    /**
     * Scope a query to only include active
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function admissionDateTime() {
        return $this->hasOne("\App\Models\AdmissionDateTime", "session_id", "id");
    }
    public function applications()
    {
        return $this->hasMany(Application::class, "session_id", "id");
    }
}
