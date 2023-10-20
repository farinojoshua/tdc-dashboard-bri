<?php

namespace App\Http\Controllers\Front\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\UserManagement\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function getRequestByType(Request $request)
    {
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

            return response()->json([
                'months' => $months,
                'incidentCounts' => $incidentCounts
            ]);

        } else {
            $month = $request->input('month', date('m'));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $incidentCounts = [];

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
                'incidentCounts' => $incidentCounts
            ]);
        }




    }


    public function showRequestByTypeChart()
    {
        return view('front.user-management.user-management-request');
    }

    public function getSLADataByMonth(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $meetSLA = Incident::where('sla_category', 'Meet SLA')
            ->whereYear('reported_date', '=', $year)
            ->whereMonth('reported_date', '=', $month)
            ->count();

        $overSLA = Incident::where('sla_category', 'Over SLA')
            ->whereYear('reported_date', '=', $year)
            ->whereMonth('reported_date', '=', $month)
            ->count();

        return response()->json([
            'meetSLA' => $meetSLA,
            'overSLA' => $overSLA
        ]);
    }

    public function showSLACategoryChart()
    {
        return view('front.user-management.user-management-sla');
    }
}
