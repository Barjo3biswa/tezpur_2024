<?php
namespace App\Services;

use App\Models\Application;
use PDF;

class ApplicationService
{
    public static function getPDF(Application $application)
    {
        set_time_limit(300);
        $download = false;
        // return view("common.application.download",compact("application", "download"));
        $dontLoadAttachment = true;
        $pdf                = PDF::loadView("common.application.download", compact("application", "download", "dontLoadAttachment"));
        $file_name          = $application->application_no ? $application->application_no : $application->id . '.pdf';
        return [$pdf->output($file_name), str_replace("/", "-", $file_name).".pdf"];
        return [file_get_contents($pdf->save($file_name)), $file_name];
    }
}
