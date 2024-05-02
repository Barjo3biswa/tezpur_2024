<?php
namespace App\Traits\Reports;

use App\Models\OnlinePaymentSuccess;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * this trait will be responsible for payment related report.
 */
trait PaymentReportsTrait
{
    public function applicationFeeReport(Request $request)
    {
        $per_page = $request->get("per_page", 100);
        $payments = OnlinePaymentSuccess::query()
            ->with("application:id,first_name,middle_name,last_name,student_id,application_no", "courses")
            ->paymentTypeApplication()
            ->filter()
            ->latest("updated_at");
        if (request("export") == "excel") {
            return $this->exportApplicationFees($payments);
        }
        $payments = $payments->paginate($per_page);
        return view("admin.reports.payments.application-fee-daily", compact("payments"));
    }
    public function admissionFeeReport(Request $request)
    {
        $per_page = $request->get("per_page", 100);
        $payments = OnlinePaymentSuccess::query()
            ->with("application:id,first_name,middle_name,last_name,student_id,application_no")
            ->with("merit_list.admissionReceipt", "merit_list.course")
            ->paymentTypeAdmission()
            ->filter()
            ->latest("updated_at");
        if (request("export") == "excel") {
            return $this->exportAdmissionFees($payments);
        }
        $payments = $payments->paginate($per_page);

        return view("admin.reports.payments.admission-fee-daily", compact("payments"));
    }
    public function exportAdmissionFees($payments)
    {
        Excel::create('Admission fees report ', function ($excel) use ($payments) {
            $excel->sheet('Admission fees report ', function ($sheet) use ($payments) {
                $sheet->setTitle('Admission fees report');

                $sheet->cells('A1:G1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setAutoSize(true);
                // $sheet->fromArray($arr, null, 'A1', false, true);
                $arr = [
                    "Registration No" => "",
                    "Application No." => "",
                    "Programm Name"   => "",
                    "Applicant Name"  => "",
                    "Receipt No"      => "",
                    "Roll No"         => "",
                    "Payment Date"    => "",
                    "Amount"          => "",
                ];

                $sheet->appendRow($arr);
                $additional_no = 0;
                $arr           = [];

                $payments->chunk(500, function ($new_payments) use ($sheet, &$additional_no, &$arr) {
                    foreach ($new_payments as $key => $payment) {
                        $arr[] = [
                            "Registration No" => $payment->application->student_id,
                            "Application No." => $payment->application->application_no,
                            "Applicant Name"  => $payment->application->full_name,
                            "Programm Name"   => $payment->merit_list->course->name ?? "NA",
                            "Receipt No"      => $payment->merit_list->admissionReceipt->receipt_no ?? "NA",
                            "Roll No"         => $payment->merit_list->admissionReceipt->roll_number ?? "NA",
                            "Payment Date"    => $payment->updated_at->format("d-m-Y h:i a") ?? "NA",
                            "Amount"          => $payment->amount_decimal,
                        ];
                        // $sheet->appendRow($arr);
                    }
                    $additional_no = $additional_no + 500;
                });
                $sheet->fromArray($arr, null, 'A1', false, true);

            });
        })->download('xlsx');
    }
    public function exportApplicationFees($payments)
    {
        Excel::create('Application fee report ', function ($excel) use ($payments) {
            $excel->sheet('Application fee report ', function ($sheet) use ($payments) {
                $sheet->setTitle('Application fee report');

                $sheet->cells('A1:E1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setAutoSize(true);
                // $sheet->fromArray($arr, null, 'A1', false, true);
                $arr = [
                    "Registration No" => "",
                    "Application No." => "",
                    "Applicant Name"  => "",
                    "Programmes"      => "",
                    "Payment Date"    => "",
                    "Amount"          => "",
                ];

                $sheet->appendRow($arr);
                $additional_no = 0;
                $arr           = [];

                $payments->chunk(500, function ($new_payments) use ($sheet, &$additional_no, &$arr) {
                    foreach ($new_payments as $key => $payment) {
                        $arr[] = [
                            "Registration No" => $payment->application->student_id,
                            "Application No." => $payment->application->application_no,
                            "Applicant Name"  => $payment->application->full_name,
                            "Programmes"      => $payment->courses->implode("name", ",\n") ?? "NA",
                            "Payment Date"    => $payment->updated_at->format("d-m-Y h:i a") ?? "NA",
                            "Amount"          => $payment->amount_decimal,
                        ];
                        // $sheet->appendRow($arr);
                    }
                    $additional_no = $additional_no + 500;
                });
                $sheet->fromArray($arr, null, 'A1', false, true);

            });
        })->download('xlsx');
    }
    public function dailyPaymentsCollection(Request $request)
    {
        $collection_date_wise = OnlinePaymentSuccess::select(DB::raw("date(updated_at) as date, sum(amount) as sum_amount"))->orderBy("date", "DESC")->groupBy(DB::raw("date(updated_at)"));
        $collection_date_wise->filter();
        if ($request->get('export') == "excel") {
            return $this->dailyPaymentsCollectionExport($collection_date_wise);
        }
        $collection_date_wise = $collection_date_wise->get();
        $total_receipts       = OnlinePaymentSuccess::count();
        return view("finance.reports.daily-payments-collection", compact("collection_date_wise", "total_receipts"));
    }
    public function dailyPaymentsCollectionExport(Builder $collections)
    {
        Excel::create('Daily Payment Collections', function ($excel) use ($collections) {
            $excel->sheet('collections', function ($sheet) use ($collections) {
                $sheet->setAutoSize(true);
                // $sheet->fromArray($arr, null, 'A1', false, true);
                $arr = [
                    "Sl. No."    => "Sl. No.",
                    "Date"       => "Date",
                    "Collection" => "Collection",
                ];

                $sheet->appendRow($arr);
                $additional_no = 0;
                $arr           = [];

                $collections->chunk(500, function ($collecction) use ($sheet, &$additional_no, &$arr) {
                    foreach ($collecction as $key => $payment) {
                        $arr[] = [
                            "Sl. No."    => ($key + $additional_no + 1),
                            "Date"       => $payment->date,
                            "Collection" => $payment->sum_amount,
                        ];
                    }
                    $additional_no = $additional_no + 500;
                });
                $sheet->fromArray($arr, null, 'A1', false, true);
            });
        })->download('xls');
    }
}
