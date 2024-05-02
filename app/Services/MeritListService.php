<?php

namespace App\Services;

use App\Models\AdmissionCategory;
use App\Models\CourseSeat;
use App\Models\MeritList;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MeritListService
{
    private $merit_list_data;
    private $course_id;
    private $opening_time;
    private $closing_time;
    private $interval_days;
    public function __construct(Collection $merit_list_data, $course_id, Carbon $opening_time, Float $interval_days)
    {
        $this->course_id       = $course_id;
        $this->merit_list_data = $merit_list_data;
        $this->opening_time    = $opening_time->format("Y-m-d H:i:s");
        $this->interval_days   = $interval_days;
        $this->closing_time    = $opening_time->addHour($interval_days * 24)->format("Y-m-d H:i:s");
    }
    public function getList(): Collection
    {
        return $this->merit_list_data;
    }
    public function getCourseId(): Int
    {
        return $this->course_id;
    }
    public function getOpeningTime(): String
    {
        return $this->opening_time;
    }
    public function processData(): void
    {
        /**
         * @var \App\Models\CourseSeat $pwd_candidate_limit_in_selected_course
         */
<<<<<<< HEAD
        //dd($this->course_id);
=======
        // dd($this->course_id);
>>>>>>> d4ba93c0f2bc6a6c65624a9215b2ebd137395a78
        $pwd_candidate_limit_in_selected_course = CourseSeat::query()
            ->courseFilter($this->course_id)
            ->pwdFilter()
            ->first();
        //dd($pwd_candidate_limit_in_selected_course);
        $ur_candidate_limit_in_selected_course = CourseSeat::query()
            ->courseFilter($this->course_id)
            ->seatAvailable()
            ->unreseveFilter()
            ->first();
        $all_course_seat = CourseSeat::query()
            ->courseFilter($this->course_id)
            ->get();
        $seats  = [];
        foreach($all_course_seat as $row){
            $seats[$row->admission_category_id] = $row->total_seats - $row->total_seats_applied;
        }
        
        // may be need to sort by rank
        // if pwd candidate found in provided list and pwd seat is available
        // then only change status of the candidates available booking for pwd candidates
        //dd("ok");
        if( $pwd_candidate_limit_in_selected_course){
            $available_seat = $pwd_candidate_limit_in_selected_course->availableSeat();
        }
        else 
            $available_seat=0;
        
         //dd($available_seat);
        $ur_candidate_available_seat = $ur_candidate_limit_in_selected_course ? $ur_candidate_limit_in_selected_course->availableSeat() : 0;
        $pwd_candidates = $this->merit_list_data->filter(function ($merit_list) use (&$seats){
            // return true false,  1/0
            if($merit_list->is_pwd){
                // this logic is implemented if pwd available but belongings category seat is not available
                if($seats[AdmissionCategory::$PWD_CATEOGORY_ID] > 0){
                    $seats[AdmissionCategory::$PWD_CATEOGORY_ID] = $seats[AdmissionCategory::$PWD_CATEOGORY_ID] - 1;
                    return true;
                }
            }
            return false;
            // return $merit_list->is_pwd;
        });
        $ur_candidates = $this->merit_list_data->sortBy("tuee_rank");
        if ($pwd_candidate_limit_in_selected_course && $available_seat && $pwd_candidates->isNotEmpty()) {
            foreach ($pwd_candidates->take($available_seat) as $merit_list) {
                // date from and date to is needed.
                $merit_list->partiallyApproveToTakeAdmission($this->opening_time, $this->closing_time);
            }
        } /* else */if($ur_candidate_available_seat && $ur_candidates->isNotEmpty()) {
            // change status of the remaining category candidates acording to remaining seat counts
            foreach ($ur_candidates->take($ur_candidate_available_seat) as $merit_list) {
                // date from and date to is needed.
                $merit_list->partiallyApproveToTakeAdmission($this->opening_time, $this->closing_time);
                // update category other then UR category 
                $merit_list->update([
                    "admission_category_id" => AdmissionCategory::$UR_CATEOGORY_ID
                ]);
            }
        } else {
            $this->processAllExcepPWDCadidates();
            // change status of the remaining category candidates acording to remaining seat counts
        }
    }
    public function processAllExcepPWDCadidates(): void
    {
        $course_seat_details_for_selected_course = CourseSeat::query()
            ->courseFilter($this->course_id)
            ->get();
        foreach ($course_seat_details_for_selected_course as $course_seat) {
            if ($course_seat->availableSeat()) {
                $merit_list = $this->merit_list_data->where("course_id", $this->course_id)
                    ->where("admission_category_id", $course_seat->admission_category_id)
                    ->take($course_seat->availableSeat());
                $merit_list->each(function (MeritList $merit_list) {
                    $merit_list->partiallyApproveToTakeAdmission($this->opening_time, $this->closing_time);
                });
            }
        }
    }
}
