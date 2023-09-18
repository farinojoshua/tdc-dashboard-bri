<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deployment;
use Illuminate\Http\Request;
use App\Models\DeploymentModule;
use App\Http\Controllers\Controller;
use App\Models\DeploymentServerType;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $chartData = $this->getChartData($month, $year);

        if ($request->ajax()) {
            return response()->json($chartData);
        }

        return view('admin.dashboard', compact('chartData'));
    }

    private function getChartData($month = null, $year = null)
    {
        $query = Deployment::query();

        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        $modules = DeploymentModule::all();
        $chartData = [];

        foreach ($modules as $module) {
            $deployments = $query->where('module_id', $module->id)
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
