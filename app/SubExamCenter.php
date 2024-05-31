<?php

namespace App;

use App\Models\AdmitCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubExamCenter extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    public function students(){
        return $this->hasMany(AdmitCard::class, 'id','sub_exam_center_id');
    }
}
