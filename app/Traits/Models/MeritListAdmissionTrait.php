<?php

namespace App\Traits\Models;

use App\Models\MeritList;
use App\Models\MeritMaster;

trait MeritListAdmissionTrait
{
    public function isApprove()
    {
        return $this->status == 1;
    }
    public function isShortlisted()
    {
        return $this->status == 0;
    }
    public function isSystemGenerated()
    {
        return $this->status == 7;
    }
    public function isReported()
    {
        return $this->reported_at ? true : false;
    }
    public function isAutomaticSystem()
    {
        return $this->processing_technique == MeritMaster::$PROCESSING_AUTO_STATUS;
    }
    public function isBookingDone()
    {
        return $this->status == 2;
    }
    public function isValidTime()
    {
        return (strtotime($this->valid_from) <= time() && strtotime($this->valid_till) >= time());
    }
    public function isDeclined()
    {
        return false;
    }
    public function isValidTillExpired()
    {
        return $this->valid_till ? 
        now() > $this->valid_till : 
        false;
    }
    public function isWithdrawal()
    {
        return $this->status == 6;
    }
    public function isAvailableForPayment()
    {
        return in_array($this->course_id, btechCourseIds()) || $this->is_payment_applicable;
    }
    public function isFutureTime()
    {
        return $this->valid_from ? 
        now() < $this->valid_from : 
        false;
    }
}
