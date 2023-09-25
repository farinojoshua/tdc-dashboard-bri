<?php

namespace App\Http\Controllers\Admin\BackgroundJobsMonitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BackgroundJobsMonitoring\BackgroundJob;

class BackgroundJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()-> ajax()) {
            $backgroundJobs = BackgroundJob::with('process')->get();

            return datatables()->of($backgroundJobs)
                ->addColumn('process_name', function ($backgroundJob) {
                    return $backgroundJob->process->name;
                })
                ->addColumn('status', function ($backgroundJob) {
                    return $backgroundJob->status == 'Success' ? '<span class="badge badge-success">Success</span>' : '<span class="badge badge-danger">Failed</span>';
                })
                ->addColumn('action', function ($backgroundJob) {
                    return '<a href="' . route('admin.background-jobs-monitoring.background-jobs.show', $backgroundJob->id) . '" class="btn btn-sm btn-primary">Show</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // return index view
        return view('admin.background-jobs-monitoring.background-jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
