<?php

namespace App\Http\Controllers\Front;

use App\Models\Deployment;
use Illuminate\Http\Request;
use App\Models\DeploymentModule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DeploymentServerType;

class DeploymentController extends Controller
{

    // for deployment calendar view
    public function calendar()
    {
        return view('front.deployments-calendar');
    }

    public function index()
    {
        $modules = DeploymentModule::all();
        return view('front.deployments', compact('modules'));
    }

    public function getChartData(Request $request)
    {
        $module_id = $request->input('module_id');
        $year = $request->input('year', date('Y'));  // Jika tidak ada parameter tahun, gunakan tahun saat ini

        $data = DB::table('deployments')
            ->select(DB::raw('MONTH(deployments.deploy_date) as month'), 'deployment_server_types.name as server_type', DB::raw('COUNT(*) as count'))
            ->join('deployment_server_types', 'deployments.server_type_id', '=', 'deployment_server_types.id')
            ->join('deployment_modules', 'deployments.module_id', '=', 'deployment_modules.id')
            ->where('deployments.module_id', $module_id)
            ->whereYear('deployments.deploy_date', $year) // Filter berdasarkan tahun
            ->groupBy(DB::raw('MONTH(deployments.deploy_date)'), 'deployment_server_types.name')
            ->get();

        return response()->json($data);
    }


}
