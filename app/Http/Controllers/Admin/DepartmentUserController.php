<?php

namespace App\Http\Controllers\Admin;

use App\DepartmentalPermission;
use App\DepartmentAssignedPermission;
use App\DepartmentAssignedUser;
use App\DepartmentUser;
use App\Http\Controllers\Controller;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Log;
use Throwable;
use Validator;

class DepartmentUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department_users = new DepartmentUser;
        $department_users = $department_users->filterData();
        $department_users = $department_users->with(["departments"])->withTrashed()->paginate(100);
        return view("admin.department_user.index", compact("department_users"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = DepartmentalPermission::get();
        return view("admin.department_user.create",compact('permissions'));
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
        $validator = Validator::make($request->all(), [
            'name'         => 'required|max:255',
            'email'        => 'required|email|max:255|unique:department_users',
            'password'     => 'required|min:6|confirmed',
            'mobile'       => 'required|unique:department_users|digits:10',
            'department'   => 'required|array|min:1',
            'department.*' => 'required|exists:departments,id',
        ]);
        if($validator->fails()){
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->all());
        }
        DB::beginTransaction();
        try {
            $department_users = DepartmentUser::create([
                'name'     => $request['name'],
                'email'    => $request['email'],
                'password' => bcrypt($request['password']),
                'mobile'   => $request['mobile'],
            ]);
            $insert_records = collect($request["department"])->map(function ($item) use ($department_users) {
                return [
                    "department_id"      => $item,
                    "department_user_id" => $department_users->id,
                ];
            });
            DepartmentAssignedUser::insert($insert_records->toArray());

            $insert_recordsII = collect($request["permission"])->map(function ($item) use ($department_users) {
                return [
                    "department_id" => $department_users->id,
                    "permission_id" => $item,
                ];
            });
            
            DepartmentAssignedPermission::insert($insert_recordsII->toArray());
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            Log::error($th);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong. try again later.");
        }
        DB::commit();
        return redirect()
                ->back()
                ->with("success", "Successfully registered new user.");
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $decrypted = Crypt::decrypt($id);
            $user      = DepartmentUser::find($decrypted);
            $user->delete();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong. try again later.");
        }
        return redirect()
            ->back()
            ->with("success", "User deactivated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        try {
            $decrypted        = Crypt::decrypt($id);
            $user             = DepartmentUser::withTrashed()->find($decrypted);
            $user->deleted_at = null;
            $user->save();
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong. try again later.");
        }
        return redirect()
            ->back()
            ->with("success", "User activated successfully.");
    }
}
