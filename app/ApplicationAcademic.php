<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationAcademic extends Model
{
    protected $guarded = [];
    
    /**
     * Get the proposed_area_of_research
     *
     * @param  string  $value
     * @return string
     */
    public function getProposedAreaOfResearchAttribute($value)
    {
        return (Array)json_decode($value, true);
    }
    /**
     * Get the is_full_time converted to boolean
     *
     * @param  string  $value
     * @return boolean
     */
    public function getIsFullTimeAttribute($value)
    {
        // return true or false
        return $value == 1;
    }
}
