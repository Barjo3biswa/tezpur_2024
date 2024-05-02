<?php
namespace App\Traits;

use App\Models\Application;
use PDF;
use Log;
/**
 * Trait for handling Download (Applications)
 * date: 06-07-2019
 */
trait ApplicationDownloader
{
    public function downloadApplicationAsPDF(Application $application) {
        // dd("ok");
        set_time_limit(300);
        $download = true;
        // return view("common.application.download",compact("application", "download"));
        $pdf = PDF::loadView("common.application.download", compact("application", "download"));
        // dd("ok");
        return $pdf->download($application->id.'.pdf');
    }

}