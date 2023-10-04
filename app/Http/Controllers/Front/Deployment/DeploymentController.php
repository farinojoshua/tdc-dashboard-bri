<?php

namespace App\Http\Controllers\Front\Deployment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Deployment\DeploymentModule;

class DeploymentController extends Controller
{
    public function index()
    {
        $modules = DeploymentModule::all();
        return view('front.deployment.deployments-chart', compact('modules'));
    }

    // for deployment calendar view
    public function calendar()
    {
        // return calendar view
        return view('front.deployment.deployments-calendar');
    }

    // for deployment chart view
    public function getChartData(Request $request)
    {
        // take module_id and year from request
        $module_id = $request->input('module_id');
        $year = $request->input('year', date('Y'));  // if year is not set, use current year

        // get data from database
        $data = DB::table('deployments') // select from deployments table
            ->select(DB::raw('MONTH(deployments.deploy_date) as month'), 'deployment_server_types.name as server_type', DB::raw('COUNT(*) as count')) // select month, server type, and count
            ->join('deployment_server_types', 'deployments.server_type_id', '=', 'deployment_server_types.id') // join server types table
            ->join('deployment_modules', 'deployments.module_id', '=', 'deployment_modules.id') // join modules table
            ->where('deployments.module_id', $module_id) // filter by module id
            ->whereYear('deployments.deploy_date', $year) // filter by year
            ->groupBy(DB::raw('MONTH(deployments.deploy_date)'), 'deployment_server_types.name') // group by month and server type
            ->get(); // get data

        // return json response
        return response()->json($data);
    }


}
