<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeritListUndertaking extends Model
{
    use SoftDeletes;
    public static $pending  = "undertaking_verification_pending";
    public static $accepted = "undertaking_approved";
    public static $rejected = "undertaking_rejected";
    public static $other_pending  = "verification_pending";
    public static $other_accepted = "approved";
    public static $other_rejected = "rejected";
    public static $upload_try_limit = 6;

    protected $cast = [
        "created_at"    => "date"
    ];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ["id"];

    public function statusString()
    {
        return str_replace("_", " ", $this->status);
    }
    public function application()
    {
        return $this->belongsTo(Application::class, "application_id", "id");
    }
}
