<?php

namespace App\Models;

use App\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EligibilityQuestion extends Model
{
    use SoftDeletes;
    public static $OPERATORS = [
        "equal",
        "greater_than_or_equal_to",
        "less_than_or_equal_to",
        "less",
        "greater",
        "any",
    ];
    public static $QUESTION_TYPES = [
        "text",
        "options",
        "radio",
        "optional",
    ];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ["id"];

    public function course()
    {
        return $this->belongsTo(Course::class)->withTrashed();
    }

    /**
     * Get the question_options
     *
     * @param  string  $value
     * @return string
     */
    public function getQuestionDetailsAttribute($value)
    {
        // return (Array)json_decode($value);
        $array_value = (Array)json_decode($value);
        foreach($array_value as $key => $val){
            if(is_object($val)){
                $array_value[$key] = (Array)$val;
            }else{
                $array_value[$key] = $val;
            }
        }
        return $array_value;
    }
}
