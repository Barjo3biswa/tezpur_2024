<?php

namespace App;

use App\Models\AdmitCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TueeResult extends Model
{
    protected $guarded=['id'];
    use SoftDeletes;

    public function admit_card()
    {
        return $this->hasOne(AdmitCard::class,'roll_no','roll_no');
    }
}
