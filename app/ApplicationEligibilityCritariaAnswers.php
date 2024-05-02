<?php

namespace App;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationEligibilityCritariaAnswers extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    
    protected $guarded = ["id"];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
