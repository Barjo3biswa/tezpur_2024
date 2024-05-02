<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Traits\Reports\PaymentReportsTrait;

class ReportsController extends Controller
{
    use PaymentReportsTrait;
}
