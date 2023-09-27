<?php

namespace App\Http\Controllers\Front\BackgroundJobsMonitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BackgroundJobsMonitoring\BackgroundJob;

class BackgroundJobController extends Controller
{
    public function index()
    {
        return view('front.background-jobs-monitoring.background-jobs');
    }

    private function getFormattedData($data, $month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year); // Mendapatkan jumlah hari dalam bulan
        $formattedData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%s-%s-%s', $year, str_pad($month, 2, '0', STR_PAD_LEFT), str_pad($day, 2, '0', STR_PAD_LEFT));
            $formattedData[$date] = []; // Membuat entri untuk setiap hari dalam bulan, bahkan jika tidak ada data
        }

        foreach ($data as $datum) {
            $executionDate = $datum->execution_date;
            $processName = $datum->process->name;
            $status = $datum->status;

            // Menambahkan data ke entri tanggal yang relevan
            $formattedData[$executionDate][$processName] = $status;
        }

        return $formattedData;
    }

    public function getBackgroundJobs(Request $request)
    {
        $month = $request->query('month', date('m')); // Jika tidak ada month, gunakan bulan saat ini
        $year = $request->query('year', date('Y')); // Jika tidak ada year, gunakan tahun saat ini

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
            'type1' => [
                'processes' => $type1Data,
            ],
            'type2' => [
                'processes' => $type2Data,
            ]
        ]);
    }



}
