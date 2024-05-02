<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Course;
use App\Models\FeeHead;
use App\Models\AdmissionCategory;
use App\Models\Caste;
use App\Models\FeeStructure;
use DB, Session, Log, Str, Validator, Uuid;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $course                 = $request->get("course");
        $fee_head               = $request->get("fee_head");
        $admission_category_id  = $request->get("admission_category_id");
        $financial_year         = $request->get("financial_year");
        $type                   = $request->get("type");
        $hostel_required        = $request->get("hostel_required");

        $fees = new Fee();
        if($course){
            $fees = $fees->where('course_id',$course);
        }
        
        if($type){
            $fees = $fees->where('type',$type);
        }
        
        if($admission_category_id){
            $fees = $fees->where('caste_id',$admission_category_id);
        }
        
        if($financial_year){
            $fees = $fees->where('financial_year',$financial_year);
        }
        if($hostel_required === 0 || $hostel_required === 1){
            $fees = $fees->where('hostel_required',$hostel_required);
        }
        $fees = $fees->with(["feeStructures.feeHead", "caste"])->paginate();
        $courses = Course::withTrashed()->get();
        // $admission_categories = AdmissionCategory::where('status',1)->get();
        $admission_categories = Caste::get();
        $fee_heads = FeeHead::get();
        $financial_years = Fee::select('financial_year')->distinct()->get();
        return view('admin.fee.index',compact('courses','fee_heads','financial_years','fees','admission_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::withTrashed()->get();
        $fee_heads = FeeHead::get();
        $fees = Fee::get();
        // $admission_categories = AdmissionCategory::where('status',1)->get();
        $admission_categories = Caste::all();
        debug($admission_categories);
        return view('admin.fee.create',compact('courses','admission_categories','fee_heads','fees'));
    }

    public function otherCategory(){
        // $admission_categories = AdmissionCategory::select('id','name')->where([['status',1],['id','!=',1]])->get();
        $admission_categories = Caste::select('id','name')->where([['id','!=',1]])->get();
        if(!$admission_categories->isEmpty())
            return response()->json(['success'=>true,'data'=>$admission_categories]);
        else
        return response()->json(['success'=>false]);    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $admission_category_id  = $request->admission_category_id;
        $if_exists = Fee::where('course_id',$request->course_id)
                    ->where('financial_year',$request->financial_year)
                    ->where('type', $request->type)
                    ->where('hostel_required', ($request->hostel_required === "yes"))
                    ->where('programm_type', $request->programm_type)
                    ->where('year', $request->year);
        if($admission_category_id){
            if($admission_category_id == 1)
                $if_exists = $if_exists->where('caste_id',$admission_category_id);
            else
                $if_exists =  $if_exists->whereIn('caste_id',$request->other_admission_category_id);
        }
        $if_exists = $if_exists->count();
        if($if_exists){
            Session::flash('error','Fee Structure Already Exists.');
            return back();
        }
        $validator = Validator::make($request->all(), Fee::getRules());
        if($validator->fails()){
            Log::error($validator->errors());
            Session::flash("error", "Whoops! looks like you have missed something. Please verify and try again later.");
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        DB::begintransaction();
        try {
            if($request->admission_category_id == 1){
                $fee_data = [
                    'uuid'          => (string) Uuid::generate(),
                    'course_id'     => $request->course_id,
                    'caste_id'      => $request->admission_category_id,
                    'type'          => $request->type,
                    'financial_year' => $request->financial_year,
                    'year'          => $request->year,
                    'hostel_required'=> ($request->hostel_required === "yes"),
                    'programm_type'=> $request->programm_type,
                ];
                $fee = Fee::create($fee_data);
                foreach ($request->is_required as $key => $required_status) {
                    if ($required_status) {
                        $data = [
                            'fee_id' => $fee->id,
                            'fee_head_id'   => $request->fee_heads[$key],
                            'amount'        => (isset($request->amounts[$key]) ? $request->amounts[$key] : 0),
                        ];
                        $fee_structure = FeeStructure::create($data);
                    }
                }
            }
            else {
                foreach($request->other_admission_category_id as $category_id){
                    $fee_data = [
                        'uuid'          => (string) Uuid::generate(),
                        'course_id'     => $request->course_id,
                        'caste_id'      => $category_id,
                        'type'          => $request->type,
                        'financial_year' => $request->financial_year,
                        'year'          => $request->year,
                        'hostel_required'=> ($request->hostel_required === "yes"),
                        'programm_type'=> $request->programm_type,
                    ];
                    $fee = Fee::create($fee_data);
                    foreach ($request->is_required as $key => $required_status) {
                        if ($required_status) {
                            $data = [
                                'fee_id' => $fee->id,
                                'fee_head_id'   => $request->fee_heads[$key],
                                'amount'        => (isset($request->amounts[$key]) ? $request->amounts[$key] : 0),
                            ];
                            $fee_structure = FeeStructure::create($data);
                        }
                    }
                }
                
            }
            
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something Went Wrong');
            return back();
        }
        DB::commit();
        saveLogs(auth()->guard('admin')->id(), auth()->guard('admin')->user()->username, 'Admin', "Fee created for {$request->course_id},{$request->stream_id},{$request->semester_id},{$request->gender},{$request->practical},{$request->financial_year}");
        Session::flash('success','Fee structure successfully created.');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Fee $fee)
    {
        $courses = Course::get();
        $fee_heads = FeeHead::get();
        $fees = Fee::get();
        // $admission_categories = AdmissionCategory::where('status',1)->get();
        $admission_categories = Caste::get();
        return view('admin.fee.edit',compact('fee','courses','fee_heads','fees','admission_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        $validator = Validator::make($request->all(), Fee::$rules);
        if($validator->fails()){
            Log::error($validator->errors());
            Session::flash("error", "Whoops! looks like you have missed something. Please verify and try again later.");
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        DB::begintransaction();
        try {
            $fee_data = [
                'course_id'     => $request->course_id,
                'type'          => $request->type,
                'financial_year' => $request->financial_year,
                'year'          => $request->year,
                'hostel_required'=> ($request->hostel_required === "yes"),
            ];
            $fee->update($fee_data);
            $fee->feeStructures()->delete();
            $fee->save();
            foreach ($request->is_required as $key => $required_status) {
                if ($required_status) {
                    $data = [
                        'fee_id' => $fee->id,
                        'fee_head_id'   => $request->fee_heads[$key],
                        'amount'        => (isset($request->amounts[$key]) ? $request->amounts[$key] : 0),
                        'is_free'       => array_key_exists($key, $request->is_free ?? []) ? $request->is_free[$key]: 0,
                    ];
                    $fee_structure = FeeStructure::create($data);
                }

            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something Went Wrong');
            return back();
        }
        DB::commit();
        saveLogs(auth()->guard('admin')->id(), auth()->guard('admin')->user()->username, 'Admin', "Fee updated for {$request->course_id},{$request->stream_id},{$request->semester_id},{$request->gender},{$request->practical},{$request->financial_year}");
        Session::flash('success','Fee structure successfully updated.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        $fee->feeStructures()->delete();
        $fee->delete();
        Session::flash('success','Deleted Successfully');
        return back();
    }
}
