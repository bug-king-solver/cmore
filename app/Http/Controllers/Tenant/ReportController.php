<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Show the application report dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return tenant_view('tenant.dynamic-dashboard.report-dashboard');
    }
}
