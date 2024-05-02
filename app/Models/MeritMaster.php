<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeritMaster extends Model
{
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
}
