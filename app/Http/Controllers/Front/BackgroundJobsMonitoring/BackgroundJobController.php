<?php

namespace App\Http\Controllers\Front\BackgroundJobsMonitoring;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BackgroundJobsMonitoring\Process;
use App\Models\BackgroundJobsMonitoring\BackgroundJob;

class BackgroundJobController extends Controller
{
    public function daily()
    {
        return view('front.background-jobs-monitoring.background-jobs-daily');
    }

    // Get data for daily background jobs monitoring
    private function getFormattedData($data, $month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $formattedData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%s-%s-%s', $year, str_pad($month, 2, '0', STR_PAD_LEFT), str_pad($day, 2, '0', STR_PAD_LEFT));
            $formattedData[$date] = [];
        }

        foreach ($data as $datum) {
            $formattedData[$datum->execution_date][$datum->process->name] = [
                'status' => $datum->status,
                'notes' => $datum->notes,
            ];
        }

        return $formattedData;
    }


    public function getBackgroundJobs(Request $request)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $type1Data = $this->getFormattedData(
            BackgroundJob::with('process')
                ->where('type', 'Product')
                ->whereYear('execution_date', $year)
                ->whereMonth('execution_date', $month)
                ->get(),
            $month, $year
        );

        $type2Data = $this->getFormattedData(
            BackgroundJob::with('process')
                ->where('type', 'Non-Product')
                ->whereYear('execution_date', $year)
                ->whereMonth('execution_date', $month)
                ->get(),
            $month, $year
        );

        return response()->json([
            'type1' => ['processes' => $type1Data],
            'type2' => ['processes' => $type2Data]
        ]);
    }

    public function showDataAmountCharts(Request $request)
    {
        $mode = $request->input('mode', 'month');
        $chosenMonth = $request->input('month', date('m'));
        $chosenYear = $request->input('year', date('Y'));

        $processes = Process::where(function ($query){
            $query->where('name', 'like', '%INBOUND%')
                ->orWhere('name', 'like', '%OUTBOUND%');
        })->get();

        $allChartData = [];

        foreach ($processes as $process) {
            if ($mode == 'month') {
                $results = $process->backgroundJobs()
                    ->whereYear('execution_date', $chosenYear)
                    ->select(DB::raw('MONTH(execution_date) as month_num'),
                        DB::raw('SUM(data_amount_to_S4GL) as total_s4gl'),
                        DB::raw('SUM(data_amount_to_EIM) as total_eim'))
                    ->groupBy(DB::raw('MONTH(execution_date)'))
                    ->orderBy(DB::raw('MONTH(execution_date)'), 'asc')
                    ->get();

                $s4glAmounts = array_fill(0, 12, 0);
                $eimAmounts = array_fill(0, 12, 0);
                $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            } else {
                $results = $process->backgroundJobs()
                    ->select(DB::raw('DAY(execution_date) as day_num'),
                        DB::raw('SUM(data_amount_to_S4GL) as total_s4gl'),
                        DB::raw('SUM(data_amount_to_EIM) as total_eim'))
                    ->whereMonth('execution_date', $chosenMonth)
                    ->whereYear('execution_date', $chosenYear)
                    ->groupBy(DB::raw('DAY(execution_date)'))
                    ->orderBy(DB::raw('DAY(execution_date)'), 'asc')
                    ->get();

                $lastDay = date('t', mktime(0, 0, 0, $chosenMonth, 1, date('Y')));
                $s4glAmounts = array_fill(0, $lastDay, 0);
                $eimAmounts = array_fill(0, $lastDay, 0);
                $labels = range(1, $lastDay);
            }

            foreach ($results as $result) {
                $index = ($mode == 'month' ? $result->month_num : $result->day_num) - 1;
                $s4glAmounts[$index] = $result->total_s4gl;
                $eimAmounts[$index] = $result->total_eim;
            }

            $allChartData[$process->name] = ['labels' => $labels, 's4glAmounts' => $s4glAmounts, 'eimAmounts' => $eimAmounts];
        }

        return view('front.background-jobs-monitoring.background-jobs-data-amount', [
            'allChartData' => $allChartData,
            'mode' => $mode,
            'chosenMonth' => $chosenMonth,
            'chosenYear' => $chosenYear
        ]);
    }

    public function showDurationCharts(Request $request)
    {
        $mode = $request->input('mode', 'month');
        $chosenMonth = $request->input('month', date('m'));
        $chosenYear = $request->input('year', date('Y'));

        $processes = Process::where(function ($query) {
            $query->where('name', 'like', '%INBOUND%')
                ->orWhere('name', 'like', '%OUTBOUND%');
        })->get();

        $allChartData = [];

        foreach ($processes as $process) {
            if ($mode == 'month') {
                $results = $process->backgroundJobs()
                    ->whereYear('execution_date', $chosenYear)
                    ->select(DB::raw('MONTH(execution_date) as month_num'),
                        DB::raw('SUM(duration_to_EIM) as total_duration_eim'),
                        DB::raw('SUM(duration_to_S4GL) as total_duration_s4gl'))
                    ->groupBy(DB::raw('MONTH(execution_date)'))
                    ->orderBy(DB::raw('MONTH(execution_date)'), 'asc')
                    ->get();

                $durationsEIM = array_fill(0, 12, 0);
                $durationsS4GL = array_fill(0, 12, 0);
                $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            } else {
                $results = $process->backgroundJobs()
                    ->select(DB::raw('DAY(execution_date) as day_num'),
                        DB::raw('SUM(duration_to_EIM) as total_duration_eim'),
                        DB::raw('SUM(duration_to_S4GL) as total_duration_s4gl'))
                    ->whereMonth('execution_date', $chosenMonth)
                    ->whereYear('execution_date', $chosenYear)
                    ->groupBy(DB::raw('DAY(execution_date)'))
                    ->orderBy(DB::raw('DAY(execution_date)'), 'asc')
                    ->get();

                $lastDay = date('t', mktime(0, 0, 0, $chosenMonth, 1, date('Y')));
                $durationsEIM = array_fill(0, $lastDay, 0);
                $durationsS4GL = array_fill(0, $lastDay, 0);
                $labels = range(1, $lastDay);
            }

            foreach ($results as $result) {
                $index = ($mode == 'month' ? $result->month_num : $result->day_num) - 1;
                $durationsEIM[$index] = $result->total_duration_eim;
                $durationsS4GL[$index] = $result->total_duration_s4gl;
            }

            $allChartData[$process->name] = ['labels' => $labels, 'durationsEIM' => $durationsEIM, 'durationsS4GL' => $durationsS4GL];
        }

        return view('front.background-jobs-monitoring.background-jobs-duration', [
            'allChartData' => $allChartData,
            'mode' => $mode,
            'chosenMonth' => $chosenMonth,
            'chosenYear' => $chosenYear
        ]);
    }
}
