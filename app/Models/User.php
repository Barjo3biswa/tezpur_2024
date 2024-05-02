<?php

namespace App\Models;

use App\Country;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\StudentResetPassword;
use App\Scopes\ActiveSessionScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_name","middle_name","last_name", 
        'email', 'password', "mobile_no",'otp_verified', 
        "otp_verified_at", "otp" , "country_id", 
        "email_verified_at", "token", "isd_code", 
        "roll_number", "session_id","other_country_name",
        'is_mba','program_name','exam_through',
        'qualifying_exam','marks','cuet_verified'
    ];
    public static $otp_retry_limit = 3;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','otp'
    ];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // if(\Auth::guest()){
        //     $active_session = getActiveSession();
        //     $other_active_sessions = config("vknrl.optional_active_session_ids");
        //     $ids = [];
        //     if($other_active_sessions){
        //         $ids = explode(",", $other_active_sessions);
        //     }
        //     $ids[] = $active_session->id;
        //     // static::addGlobalScope(new ActiveSessionScope);
        //     static::addGlobalScope('ancient', function (Builder $builder) use ($ids){
        //         $builder->whereIn('session_id', $ids);
        //     });
        // }
    }
    public function sendPasswordResetNotification($token) {
        $this->notify(new StudentResetPassword($token));
    }
    public function application() {
        return $this->hasMany("App\Models\Application", "student_id", "id");
    }
    public function getNameAttribute()
    {
        return strtoupper($this->first_name.($this->middle_name ? " ".$this->middle_name." " : ($this->last_name ? " " : "")). $this->last_name);
    }
    public function country()
    {
        return $this->belongsTo(Country::class, "country_id", "id");
    }
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtoupper($value);
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setMiddleNameAttribute($value)
    {
        $this->attributes['middle_name'] = strtoupper($value);
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtoupper($value);
    }
    public function admissionReceipts()
    {
        return $this->hasMany(AdmissionReceipt::class, "student_id", "id");
    }

    public function admitedCourse(){
        return $this->hasOne(MeritList::class, "student_id", "id")->where('status',2);
    }
}
