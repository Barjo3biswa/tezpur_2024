<?php

namespace App\Http\Controllers\DepartmentUser;

use App\Course;
use App\Http\Controllers\Controller;
use App\Models\MeritList;
use App\Models\WithdrawalRequest;
use App\Traits\WithdrawalRequestHandlerTrait;
use DB;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    use WithdrawalRequestHandlerTrait;

    private function getIndexView($data)
    {
        return view("department-user.withdrawal-request.index", $data);
    }
}
