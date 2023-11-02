<?php

namespace App\Http\Controllers\Front\Deployment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Deployment\Deployment;
use App\Models\Deployment\DeploymentModule;

class DeploymentController extends Controller
{
    public function index()
    {
        $modules = DeploymentModule::all();
        return view('front.deployment.deployments-chart', compact('modules'));
    }

    public function calendar()
    {
        return view('front.deployment.deployments-calendar');
    }

    /**
     * Get events for calendar
     */
    public function getEvents()
    {
        $deployments = Deployment::all();
        $events = [];

        foreach ($deployments as $deployment) {
            $events[] = [
                'id' => $deployment->id,
                'title' => $deployment->title,
                'start' => $deployment->deploy_date,
                'module' => $deployment->module->name,
                'server_type' => $deployment->serverType->name,
                'status_doc' => $deployment->document_status,
                'document_description' => $deployment->document_description,
                'status_cm' => $deployment->cm_status,
                'cm_description' => $deployment->cm_description,
            ];
        }

        return response()->json($events);
    }

    /**
     * Get chart data
     */
    public function getChartData(Request $request)
    {
        $module_id = $request->input('module_id');
        $year = $request->input('year', date('Y'));

        $data = DB::table('deployments')
            ->select(DB::raw('MONTH(deployments.deploy_date) as month'), 'deployment_server_types.name as server_type', DB::raw('COUNT(*) as count'))
            ->join('deployment_server_types', 'deployments.server_type_id', '=', 'deployment_server_types.id')
            ->join('deployment_modules', 'deployments.module_id', '=', 'deployment_modules.id')
            ->where('deployments.module_id', $module_id)
            ->whereYear('deployments.deploy_date', $year)
            ->groupBy(DB::raw('MONTH(deployments.deploy_date)'), 'deployment_server_types.name')
            ->get();

        return response()->json($data);
    }

}
