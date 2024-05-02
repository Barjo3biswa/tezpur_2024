<?php

namespace App;

use App\Notifications\DepartmentUserResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DepartmentUser extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',"mobile"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new DepartmentUserResetPassword($token));
    }
    public function getUsernameAttribute()
    {
        return $this->mobile;
    }
    public function departments()
    {
        return $this->hasManyThrough(Department::class, DepartmentAssignedUser::class, null, "id", null,"department_id");
    }
    public function filterData()
    {
        $builder = $this->query();
        $builder->when(request("mobile"), function($query){
            return $query->where("mobile", "=", request("mobile"));
        });
        $builder->when(request("email"), function($query){
            return $query->where("email", "=", request("email"));
        });
        $builder->when(request("name"), function($query){
            return $query->where("name", "LIKE", "%".request("name")."%");
        });
        $builder->when($this->checkAvlDepartments(request("department")), function($query){
            return $query->whereHas("departments", function($query){
                return $query->whereIn("departments.id", request("department"));
            });
        });
        return $builder;
    }
    public function checkAvlDepartments($array)
    {
        $status  = false;
        if(!$array){
            return false;
        }
        foreach ($array as $key => $value) {
            if($value){
                $status = true;
                break;
            }
        }
        return $status;
    }
}
