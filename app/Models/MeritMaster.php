<?php

namespace App\Models;

use App\CourseSeatTypeMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeritMaster extends Model
{
    use SoftDeletes;
    protected $guarded                      = ['id'];
    public static $PROCESSING_AUTO_STATUS   = "automatic";
    public static $PROCESSING_MANUAL_STATUS = "manual";
    
    public static function processging_status(): array
    {
        return [
            static::$PROCESSING_AUTO_STATUS,
            static::$PROCESSING_MANUAL_STATUS,
        ];
    }

    public function courseSeatType(){
        return $this->hasOne(CourseSeatTypeMaster::class, "course_seat_type_id", "id");
    }
}
