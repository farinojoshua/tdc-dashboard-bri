<?php

namespace App\Http\Controllers\Front\Brisol;

use Illuminate\Http\Request;
use App\Models\Brisol\Incident;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Brisol\MonthlyTarget;

class BrisolController extends Controller
{
    public function getServiceCIChart(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $mode = $request->input('mode', 'month');

        if ($mode === 'month') {
            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $incidentCounts = [];

            foreach ($months as $month) {
                $incidentCountsForMonth = DB::table('brisol_incident')
                    ->whereYear('reported_date', '=', $year)
                    ->whereMonth('reported_date', '=', date('m', strtotime($month)))
                    ->groupBy('service_ci')
                    ->select('service_ci', DB::raw('count(*) as total'))
                    ->get()
                    ->filter(function($value, $key) {
                        return !empty($value->service_ci);
                    })
                    ->pluck('total', 'service_ci')
                    ->all();

                $incidentCounts[$month] = $incidentCountsForMonth;
            }

            $totalRequests = DB::table('brisol_incident')
                ->whereYear('reported_date', '=', $year)
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

            $totalRequests = DB::table('brisol_incident')
                ->whereYear('reported_date', '=', $year)
                ->whereMonth('reported_date', '=', $month)
                ->count();

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $query = DB::table('brisol_incident')
                    ->whereYear('reported_date', '=', $year)
                    ->whereMonth('reported_date', '=', $month)
                    ->whereDay('reported_date', '=', $day)
                    ->groupBy('service_ci')
                    ->select('service_ci', DB::raw('count(*) as total'));

                $incidentCountsForDay = $query->get()->filter(function ($value, $key) {
                    return !empty($value->service_ci);
                })->keyBy('service_ci')->map(function ($row) {
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


    public function showServiceCIChart()
    {
        return view('front.brisol.brisol-service-ci');
    }

    public function getSLMStatusChart(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                    'August', 'September', 'October', 'November', 'December'];

        $data = DB::table('brisol_incident')
            ->select(DB::raw('MONTH(reported_date) as month, slm_status, COUNT(*) as count'))
            ->whereYear('reported_date', '=', $year)
            ->groupBy(DB::raw('MONTH(reported_date)'), 'slm_status')
            ->get();

        $data = $data->reject(function ($item) {
            return empty($item->slm_status);
        });

        $monthlyData = [];

        foreach ($allMonths as $month) {
            $monthlyData[$month] = [];
        }

        foreach ($data as $item) {
            $monthlyStatus = &$monthlyData[$allMonths[$item->month - 1]];

            if (!array_key_exists($item->slm_status, $monthlyStatus)) {
                $monthlyStatus[$item->slm_status] = $item->count;
            } else {
                $monthlyStatus[$item->slm_status] += $item->count;
            }
        }

        return response()->json([
            'months' => $allMonths,
            'data' => $monthlyData,
        ]);
    }



    public function showSLMStatusChart()
    {
        return view('front.brisol.brisol-slm-status');
    }


    public function getReportedSource(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $data = DB::table('brisol_incident')
            ->select(DB::raw('MONTH(reported_date) as month, reported_source, COUNT(*) as count'))
            ->whereYear('reported_date', '=', $year)
            ->groupBy(DB::raw('MONTH(reported_date)'), 'reported_source')
            ->get();

        $monthlyData = [];

        foreach ($allMonths as $month) {
            $monthlyData[$month] = [];
        }

        foreach ($data as $item) {
            $monthlyData[$allMonths[$item->month - 1]][$item->reported_source] = $item->count;
        }

        return response()->json([
            'months' => $allMonths,
            'data' => $monthlyData,
        ]);
    }

    public function showReportedSourceChart()
    {
        return view('front.brisol.brisol-reported-source');
    }

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
            $completedIncidents = Incident::whereYear('reported_date', $year)
                                        ->whereMonth('reported_date', $month)
                                        ->where('status', ['closed', 'resolved'])
                                        ->count();

            $totalIncidents = Incident::whereYear('reported_date', $year)
                                        ->whereMonth('reported_date', $month)
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
        return view('front.brisol.brisol-monthly-target');
    }

    public function getServiceCITopIssueChart(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $serviceCisWithTopIssues = DB::table('brisol_incident')
                                    ->select('service_ci', 'ctg_tier2')
                                    ->whereYear('reported_date', '=', $year)
                                    ->groupBy('service_ci', 'ctg_tier2')
                                    ->orderByRaw('COUNT(*) DESC')
                                    ->get()
                                    ->groupBy('service_ci')
                                    ->map(function ($items) {
                                        return count($items) >= 5 ? $items->take(5) : null;
                                    })
                                    ->filter();

        $serviceCiData = [];

        foreach ($serviceCisWithTopIssues as $serviceCi => $issues) {
            $issuesData = [];

            foreach ($issues as $issue) {
                $issueCount = DB::table('brisol_incident')
                                ->whereYear('reported_date', '=', $year)
                                ->where('service_ci', '=', $serviceCi)
                                ->where('ctg_tier2', '=', $issue->ctg_tier2)
                                ->count();

                $issuesData[] = [
                    'issue' => $issue->ctg_tier2,
                    'count' => $issueCount
                ];
            }

            if (!empty($issuesData)) {
                $serviceCiData[] = [
                    'service_ci' => $serviceCi,
                    'issues' => $issuesData
                ];
            }
        }

        return response()->json($serviceCiData);
    }


    public function showServiceCITopIssueChart()
    {
        return view('front.brisol.brisol-service-ci-top-issue');
    }


}
