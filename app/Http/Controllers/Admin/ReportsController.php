<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCollection;
use App\Models\AdmissionReceipt;
use App\Models\FeeHead;
use App\Traits\Reports\PaymentReportsTrait;
use DB;

class ReportsController extends Controller
{
    use PaymentReportsTrait;
    public function collectionReports()
    {
        $collection_date_wise = AdmissionReceipt::select(DB::raw("date(created_at) as date, sum(total) as sum_amount"))->orderBy("date", "DESC")->groupBy(DB::raw("date(created_at)"))->get();
        // $collection_date_wise->dd();
        $total_receipts = AdmissionReceipt::count();
        return view("admin.reports.fee-collections", compact("collection_date_wise", "total_receipts"));
    }
    public function ajaxBreakup()
    {
        $collections = AdmissionCollection::query();
        $collections->when(request("date"), function($query){
            return $query->whereHas("receipt", function($query){
                return $query->whereDate("created_at", request("date"));
            });
        });
        $collections->select(DB::raw("fee_head_id, sum(amount) as sum_amount"))
        ->groupBy("fee_head_id");
        $collections = $collections->get();
        $fee_heads = FeeHead::select(["id", "name"])->get();
        return view("admin.reports.ajax-breakups", compact("collections", "fee_heads"));
    }
    public function ajaxAplicationWise()
    {
        $receipts = AdmissionReceipt::query();
        $receipts->when(request("date"), function($query){
            return $query->whereDate("created_at", request("date"));
        });
        $collections = $receipts->with(["application" => function($select){
            return $select->select("id","first_name","middle_name","last_name","application_no");
        }])->get();
        return view("admin.reports.ajax-application-wise", compact("collections"));
    }
}
