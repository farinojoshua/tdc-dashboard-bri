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

    public function getBackgroundJobs()
    {
        $type1Data = $this->getFormattedData(BackgroundJob::with('process')->where('type', 'Product')->get());
        $type2Data = $this->getFormattedData(BackgroundJob::with('process')->where('type', 'Non-Product')->get());

        return response()->json([
            'type1' => $type1Data,
            'type2' => $type2Data
        ]);
    }

    private function getFormattedData($data)
    {
        return $data->groupBy('execution_date')
                    ->map(function ($dateGroup) {
                        return $dateGroup->groupBy('process.name')
                                        ->map(function ($processGroup) {
                                            return $processGroup->pluck('status');
                                        });
                    });
    }

}
