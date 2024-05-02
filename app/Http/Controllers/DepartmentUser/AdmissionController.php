<?php

namespace App\Http\Controllers\DepartmentUser;

use App\Http\Controllers\CommonAdmissionController;
use App\Http\Controllers\CommonSlideController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionReceipt;
use App\Models\AdmitCard;
use App\Models\Application;
use App\Models\CourseSeat;
use App\Models\MeritList;
use App\Models\User;
use App\Traits\SeatSlideControll;
use Crypt;
use DB;
use Exception;
use Log;

class AdmissionController extends CommonAdmissionController
{
    // public function bookSeat($id)
    // {
    //     dd("OK");
    // }
    use SeatSlideControll;

    public function collectionReports()
    {
        $collection_date_wise = AdmissionReceipt::select(DB::raw("date(created_at) as date, sum(total) as sum_amount"))->orderBy("date", "DESC")->groupBy(DB::raw("date(created_at)"))->get();
        // $collection_date_wise->dd();
        $total_receipts = AdmissionReceipt::count();
        return view("admin.reports.fee-collections", compact("collection_date_wise", "total_receipts"));
    }

    public function changePass(Request $request)
    {
        try {
            $id =Crypt::decrypt($request->get("user_id"));
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                "message"   => "Failed",
                "status"    => false
            ]);
        }
        try {
            $user = User::findOrFail($id);
        } catch (\Throwable $th) {
            
        }
        try {
            $user->password = bcrypt($user->mobile_no);
            $user->save();    
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                "message"   => "Failed",
                "status"    => false
            ]);
        }
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Password reset for registration no {$user->id}");
        return response()->json([
            "message"   => "Password successfully changed to mobile no.",
            "status"    => true
        ]);
    }

    public function cashFrom($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::emergency($e);
            return redirect()->/* route('student.application.index') */back()->with("error", "Whoops! Something went wrong.");
        }
        $merit_list = MeritList::whereId($decrypted_id)->with(["application.online_admission_payment_tried"])->first();
        $application = $merit_list->application;
        $this->setFeeStructure($merit_list);
        $fee_structure = $this->fee_structure;
        [$amount, $currency_value, $last_receipt] = $this->getAdmissionAmount($application);
        return view("student.admission.cash-payment",compact('merit_list','application','fee_structure','last_receipt'));
        // dd($decrypted_id);
    }
    private function setFeeStructure(MeritList $merit_list)
    {
        $this->fee_structure = $merit_list->fee_structure();
        return $this;
    }
    private function getAdmissionAmount()
    {
        $sum_amount = 0.00;
        if($this->fee_structure){
            $sum_amount = $this->fee_structure->feeStructures->sum("amount");
        }
        if(!$this->fee_structure){
            throw new Exception("Fee Structure not generate. Please contact Tezpur University Authority / Helpline No..", 1);
        }
        $last_receipt = AdmissionReceipt::where("student_id", auth("student")->id())->latest()->first();
        $sum_amount -= $last_receipt->total ?? 0.00;
        if($sum_amount < 0){
            $sum_amount = 0;
        }
        // else you can throw exception here. fee_structure not set yet.
        // payment related condition if payment allowed or not.
        return [$sum_amount, "INR", $last_receipt];
    }


    
}
