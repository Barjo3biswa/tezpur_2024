<?php
namespace App\Traits;

use App\Course;
use App\Models\MeritList;
use App\Models\WithdrawalRequest;
use DB;

/**
 * trait for handling withdrawal requests
 */
trait WithdrawalRequestHandlerTrait
{
    
    public function index()
    {
        $perPage             = 25;
        $course_ids = array_keys(programmes_array());
        $all_courses         = Course::withTrashed()->whereIn("id", $course_ids)->get();
        $withdrawal_requests = WithdrawalRequest::whereHas("meritList", function ($query) use ($course_ids){
            return $query->when(request("course_id"), function ($q){
                return $q->where("course_id", request("course_id"));
            })
            ->whereIn("course_id", $course_ids);;
        })
        ->filter()
        ->latest()->paginate($perPage);
        $status = WithdrawalRequest::$STATUS;
        // implement this function in main controler of department user or admin
        return $this->getIndexView([
            "withdrawal_requests" => $withdrawal_requests,
            "all_courses"         => $all_courses,
            "status"              => $status,
        ]);
        // return view("department-user.withdrawal-request.index", compact());
    }
    public function approve(WithdrawalRequest $w_request)
    {
        // dd($w_request);
        DB::beginTransaction();
        try {
            $user = request()->user();
            $w_request->update([
                "status"  => "approved",
                "by_id"   => $user->id,
                "by_type" => get_class($user),
            ]);
            $merit_list = $w_request->meritList;
            $merit_list->update([
                "status" => MeritList::$WITHDRAWAL_STATUS,
            ]);
            $merit_list->course_seat()->decrement("total_seats_applied");
        } catch (\Throwable $th) {
            report($th);
            return redirect()
                ->back()
                ->with("error", "Whoops! something went wrong. try again later.");
        }
        DB::commit();
        return redirect()
            ->back()
            ->with("success", "Successfully withdrawal.");
    }
    public function reject(WithdrawalRequest $w_request)
    {
        $this->validate(request(), [
            "remark" => "required|max:10000",
        ]);
        $user = request()->user();
        $w_request->update([
            "remark"  => request("remark"),
            "status"  => "request_rejected",
            "by_id"   => $user->id,
            "by_type" => get_class($user),
        ]);
        return redirect()
            ->back()
            ->with("success", "Successfully rejected. Course seat will be remain same.");
    }
}
