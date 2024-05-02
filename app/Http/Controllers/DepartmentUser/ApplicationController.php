<?php

namespace App\Http\Controllers\DepartmentUser;

use App\Http\Controllers\CommonApplicationController;
use App\Traits\ApplicationARHControll;
use App\Traits\ApplicationDownloader;
use App\Traits\ApplicationExport;
use Illuminate\Http\Request;
use Log;
use Crypt;
use PDF;
use App\Models\AdmitCard;
use App\Models\Application;
use Exception;

class ApplicationController extends CommonApplicationController
{
    use ApplicationARHControll, ApplicationDownloader, ApplicationExport;
    public function downloadPdfAdmin(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.admit-card.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $admit_card = AdmitCard::with(["active_application.caste","active_application.attachments", "exam_center"])->findOrFail($decrypted_id);
            if(!$admit_card->publish){
               return  redirect()->back()->with("error", "Admit card is not published yet.");
            }
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()
                ->route("admin.admit-card.index")
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        // return view("common/application/admit_card/admit_card_download", compact("admit_card"));
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
        // return view("common.application.admit_card.admit_card_download", compact("admit_card"));
        $pdf = PDF::loadView("common/application/admit_card/admit_card_download", compact("admit_card"));
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Admit-card-".$admit_card->application->id.'.pdf');
    }
    public function uploadExtraDocumentShow($encryptedValue)
    {
        try {
            $decrypted = Crypt::decrypt($encryptedValue);
            $application = Application::with("extra_documents")->find($decrypted);
        } catch (\Throwable $th) {
            Log::error($th);
            abort(404, "Application not found.");
        }

        return view("student.application.upload-extra-doc-view", compact("application"));
    }
}
