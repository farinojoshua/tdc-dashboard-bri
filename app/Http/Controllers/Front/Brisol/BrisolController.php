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

                $incidentCountsForDay = $query->get()->keyBy('service_ci')->map(function ($row) {
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

        $monthlyData = [];

        foreach ($allMonths as $month) {
            $monthlyData[$month] = [];
        }

        foreach ($data as $item) {
            $monthlyData[$allMonths[$item->month - 1]][$item->slm_status] = $item->count;
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

}
