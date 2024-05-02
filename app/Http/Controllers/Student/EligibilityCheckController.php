<?php

namespace App\Http\Controllers\Student;

use App\Models\EligibilityQuestion;
use App\Http\Controllers\Controller;
use App\Models\Application;
use Gate;
use Illuminate\Http\Request;
use Validator;

class EligibilityCheckController extends Controller
{
    public function create(Application $application)
    {
        if(!Gate::check('access-via-application', $application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Eligibility test access denied application id ".$application->id);
            abort(403, "ACCESS DENIED");
            return redirect()
                ->route("student.application.index")
                ->with("error", "Unauthorized access.");
        }
        if($application->is_eligibility_critaria_submitted){
            return redirect()
                ->route("student.application.index")
                ->with("error", "Application eligibility test is completed. Not allowed for another one.");
        }
        $course_id = $application->applied_courses()->pluck("course_id")->toArray();
        $questions = null;
        foreach($application->applied_courses as $applied_course){
            $questions = EligibilityQuestion::where("course_id", $applied_course->course_id)
                ->get();
            if($questions->count()){
                break;
            }
        }
        return view("student.application.eligibility-checking", compact("questions","application"));
    }
    public function store(Application $application, Request $request)
    {
        if(!Gate::check('access-via-application', $application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Eligibility test access denied application id ".$application->id);
            // abort(403, "ACCESS DENIED");
            return response()->json([
                "message" => '
                <div class="alert alert-danger">
                    <strong>ACCESS DENIED.</strong>
                </div>
                ', 
                "status" => false
            ]);
            return "ACCESS DENIED";
        }
        
        if($application->is_eligibility_critaria_submitted){
            return response()
                ->json([
                    "message" => '
                        <div class="alert alert-danger">
                            <strong>Your eligibility test is completed.</strong>
                        </div>
                    ', 
                    "status" => false,
                    "data"  => $application
                ]);
        }
        $rules = [
            "form.*.*.question_id" => "required",
            "form.*.*.question" => "required",
            "form.*.*.operator_condition" => "required",
            "form.*.*.eligibility_requirement" => "required",
            "form.*.*.answer" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                "message" => '
                <div class="alert alert-danger">
                    <strong>Please fill all the required field and submit.</strong>
                </div>
                ', 
                "status" => false,
                "data"  => $validator->errors()->all()
            ]);
        }
        \DB::beginTransaction();
        try {
            $application->eligibility_answers()->delete();
            $is_eligibility_critaria_fullfilled = true;
            foreach($request->form as $form_array){
                foreach($form_array as $form_data){
                    // $data = [
                    //     "question_id" => $form_data["question_id"],
                    //     "question" => $form_data["question"],
                    //     "operator_condition" => $form_data["operator_condition"],
                    //     "eligibility_requirement" => $form_data["eligibility_requirement"],
                    //     "total" => isset($form_data["total"]) ? $form_data["total"] : 0.0,
                    //     "answer" => $form_data["answer"]
                    // ];
                    $data = collect($form_data)->only(["question_id","question","operator_condition","answer", "eligibility_requirement"])->merge([
                        "total"               => isset($form_data["total"]) ? $form_data["total"] : "0.0",
                        "is_eligibility_pass" => dynamic_value_check($form_data["operator_condition"], $form_data["answer"], $form_data["eligibility_requirement"])
                    ]);
                    if(!dynamic_value_check($form_data["operator_condition"], $form_data["answer"], $form_data["eligibility_requirement"])){
                        $is_eligibility_critaria_fullfilled = false;
                    }
                    $application->eligibility_answers()->create($data->toArray());
                }
            }
            $application->update([
                "is_eligibility_critaria_fullfilled" => $is_eligibility_critaria_fullfilled,
                // submitted flag is turned on
                "is_eligibility_critaria_submitted" => true
            ]);
            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            logger($th);
            return response()
            ->json([
                "message" => '
                    <div class="alert alert-danger">
                        <strong>Whoops! something went wrong.</strong>
                    </div>
                ', 
                "status" => false
            ]);
        }
        $message = "Congratualtions! your eligibility test is completed.";
        if(!$is_eligibility_critaria_fullfilled){
            $message = "Congratualtions! your eligibility test is completed.";
        }
        session()->flash("success", $message);
        return response()->json([
            "message" => '
                <div class="alert alert-success">
                    <strong>'.$message.'</strong>
                </div>
            ',
            "url" => route("student.application.index"),
            "status" => true
        ]);
    }
    public function show(Application $application)
    {
        if(!auth("student")->check() && !auth("admin")->check()  && !auth("department_user")->check()){
            abort(403, "ACCESS DENIED");
        }
        if(auth("student")->check()){
            if(!Gate::check('access-via-application', $application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Eligibility show access denied application id ".$application->id);
                abort(403, "ACCESS DENIED");
            }
        }
        $application->load("eligibility_answers");
        
        $eligibility_criteria = $application->eligibility_answers;
        return view("common.application.eligibility-show", compact("application", "eligibility_criteria"));
    }
}
