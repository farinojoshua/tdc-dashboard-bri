<?php

namespace App\Http\Controllers\Front\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\UserManagement\Incident;
use App\Models\UserManagement\MonthlyTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function getRequestByType(Request $request)
    {
        $totalRequests = 0;
        $year = $request->input('year', date('Y'));
        $mode = $request->input('mode', 'month');

        if ($mode === 'month') {
            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $incidentCounts = [];

            foreach ($months as $month) {
                $incidentCountsForMonth = Incident::join('usman_req_type', 'usman_incident.type_id', '=', 'usman_req_type.id')
                    ->whereYear('reported_date', '=', $year)
                    ->whereMonth('reported_date', '=', date('m', strtotime($month)))
                    ->groupBy('usman_req_type.name')
                    ->select('usman_req_type.name', DB::raw('count(*) as total'))
                    ->pluck('total', 'usman_req_type.name')
                    ->all();

                $incidentCounts[$month] = $incidentCountsForMonth;
            }

            $totalRequests = Incident::whereYear('reported_date', '=', $year)
                ->count();

            return response()->json([
                'months' => $months,
                'incidentCounts' => $incidentCounts,
                'totalRequests' => $totalRequests
            ]);

        } else {
            $month = $request->input('month', date('m'));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $incidentCounts = [];

            $totalRequests = Incident::whereYear('reported_date', '=', $year)
                ->whereMonth('reported_date', '=', $month)
                ->count();

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $query = Incident::join('usman_req_type', 'usman_incident.type_id', '=', 'usman_req_type.id')
                    ->whereYear('reported_date', '=', $year)
                    ->whereMonth('reported_date', '=', $month)
                    ->whereDay('reported_date', '=', $day)
                    ->groupBy('usman_req_type.name')
                    ->select('usman_req_type.name', DB::raw('count(*) as total'));

                $incidentCountsForDay = $query->get()->keyBy('name')->map(function ($row) {
                    return $row->total;
                })->all();

                $incidentCounts[$day] = $incidentCountsForDay;
            }

            return response()->json([
                'days' => range(1, $daysInMonth),
                'incidentCounts' => $incidentCounts,
                'totalRequests' => $totalRequests
            ]);
        }




    }


    public function showRequestByTypeChart()
    {
        return view('front.user-management.user-management-request');
    }

    public function getSLADataForYear(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $data = DB::table('usman_incident')
            ->select(DB::raw('DATE(reported_date) as date'), DB::raw('sla_category'), DB::raw('COUNT(*) as count'))
            ->whereMonth('reported_date', $month)
            ->whereYear('reported_date', $year)
            ->groupBy('date', 'sla_category')
            ->get();

        $totalIncidents = DB::table('usman_incident')
            ->whereMonth('reported_date', $month)
            ->whereYear('reported_date', $year)
            ->count();

        $doneIncidents = DB::table('usman_incident')
            ->whereMonth('reported_date', $month)
            ->whereYear('reported_date', $year)
            ->where('exec_status', 'Done')
            ->count();

        $pendingIncidents = DB::table('usman_incident')
            ->whereMonth('reported_date', $month)
            ->whereYear('reported_date', $year)
            ->where('exec_status', 'Pending')
            ->count();

        $formattedData = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-' . str_pad($i, 2, "0", STR_PAD_LEFT);
            $formattedData[$date] = ['Meet SLA' => 0, 'Over SLA' => 0];
        }

        foreach ($data as $row) {
            $formattedData[$row->date][$row->sla_category] = $row->count;
        }

        return response()->json([
            'slaData' => $formattedData,
            'totals' => [
                'totalIncidents' => $totalIncidents,
                'doneIncidents' => $doneIncidents,
                'pendingIncidents' => $pendingIncidents
            ]
        ]);
    }



    public function showSLACategoryChart()
    {
        return view('front.user-management.user-management-sla');
    }

    public function getTopBranchRequests(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $query = DB::table('usman_incident')
                ->join('usman_branch', 'usman_incident.branch_id', '=', 'usman_branch.id')
                ->select('usman_branch.name', DB::raw('count(usman_incident.id) as total_requests'))
                ->groupBy('usman_branch.name')
                ->orderByDesc('total_requests')
                ->limit(5);

        if ($month) {
            $query->whereMonth('usman_incident.reported_date', $month);
        }

        if ($year) {
            $query->whereYear('usman_incident.reported_date', $year);
        }

        $branches = $query->get();

        return response()->json($branches);
    }

    public function showTopBranchRequestsChart()
    {
        return view('front.user-management.user-management-top-branch');
    }

    // app/Http/Controllers/ChartDataController.php

    public function getMonthlyDataTargetActual(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $monthlyTargets = MonthlyTarget::where('year', $year)
                                    ->orderBy('month', 'asc')
                                    ->get();

        $targetData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyTargets->firstWhere('month', $i);
            $targetData[] = $monthData ? $monthData->monthly_target_value : 0;
        }

        $months = range(1, 12);
        $actualPercentageData = [];
        foreach ($months as $month) {
            $totalIncidents = Incident::whereYear('reported_date', $year)
                                    ->whereMonth('reported_date', $month)
                                    ->count();
            $completedIncidents = Incident::whereYear('execution_date', $year)
                                        ->whereMonth('execution_date', $month)
                                        ->where('exec_status', 'Done')
                                        ->count();

            if ($totalIncidents != 0) {
                $percentageCompleted = ($completedIncidents / $totalIncidents) * 100;
            } else {
                $percentageCompleted = 0;
            }

            $actualPercentageData[$month] = $percentageCompleted;
        }

        return response()->json([
            'targets' => $targetData,
            'actuals' => array_values($actualPercentageData)
        ]);
    }

    public function showMonthlyDataTargetActualChart()
    {
        return view('front.user-management.user-management-monthly-target');
    }
}
