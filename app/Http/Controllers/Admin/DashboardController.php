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
        $chartData = $this->getChartData();
        return view('admin.dashboard', compact('chartData'));
    }

    private function getChartData()
    {
        $modules = DeploymentModule::all();
        $chartData = [];

        foreach ($modules as $module) {
            $deployments = Deployment::where('module_id', $module->id)
                                    ->with('serverType')
                                    ->get()
                                    ->groupBy('server_type_id');

            $data = [];
            foreach ($deployments as $serverTypeId => $items) {
                $serverType = DeploymentServerType::find($serverTypeId);
                $data[$serverType->name] = count($items);
            }

            $chartData[$module->name] = $data;
        }

        return $chartData;
    }
}
