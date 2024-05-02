<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    protected $guarded=['id'];
    use SoftDeletes;

    public function district()
    {
        return $this->hasMany(District::class, "state_id", "id");
    }
}
