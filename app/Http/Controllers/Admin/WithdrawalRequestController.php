<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\WithdrawalRequestHandlerTrait;

class WithdrawalRequestController extends Controller
{
    use WithdrawalRequestHandlerTrait;
    
    private function getIndexView($data)
    {
        return view("admin.withdrawal-request.index", $data);
    }
}
