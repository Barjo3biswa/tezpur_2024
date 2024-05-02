<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FeeHead;
use DB, Session, Log, Str,Uuid;

class FeeHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fee_heads = FeeHead::paginate();
        return view('admin.fee-head.index',compact('fee_heads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.fee-head.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'uuid' => (string) Uuid::generate(),
            'name' => $request->name,
            'applicable_on' => $request->applicable_on
        ];
        $fee_head = FeeHead::create($data);
        saveLogs(auth()->guard('admin')->id(), auth()->guard('admin')->user()->username, 'Admin', "Fee head created with id {$fee_head->id}");
        Session::flash('success','Created Successfully');
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
    public function edit(Request $request, FeeHead $fee_head)
    {
        return view('admin.fee-head.edit',compact('fee_head'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeHead $fee_head)
    {
        $data = [
            'name' => $request->name,
            'applicable_on' => $request->applicable_on,
        ];
        $fee_head->update($data);
        $fee_head->save();
        saveLogs(auth()->guard('admin')->id(), auth()->guard('admin')->user()->username, 'Admin', "Fee head updated with id {$fee_head->id}");
        Session::flash('success','Updated Successfully');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $fee_head = FeeHead::where('uuid',$id)->first();
        $fee_head->delete();
        saveLogs(auth()->guard('admin')->id(), auth()->guard('admin')->user()->username, 'Admin', "Fee head deleted");
        Session::flash('success','Deleted Successfully');
        return back();
    }
}
