<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deployment;
use Illuminate\Http\Request;
use App\Models\DeploymentModule;
use App\Http\Controllers\Controller;
use App\Models\DeploymentServerType;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
