<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserManagement\Incident;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\UserManagement\IncidentsImport;

class IncidentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if request is ajax, return datatable
        if (request()->ajax()) {
            $query = Incident::with(['reqType', 'branch']);

            return DataTables::of($query)
                ->addColumn('branch_name', function ($incident) {
                    return $incident->branch->name;
                })
                ->addColumn('type_name', function ($incident) {
                    return $incident->reqType->name;
                })
                ->rawColumns(['branch_name', 'type_name'])
                ->make();
        }

        return view('admin.user-management.incidents.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-management.incidents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ], [
            'file.required' => 'File is required',
            'file.mimes' => 'File must be an Excel document',
            'file.max' => 'File must not exceed 2MB'
        ]);

        $file = $request->file('file');
        Incident::truncate();
        Excel::import(new IncidentsImport, $file);

        return redirect()->route('admin.user-management.incidents.index')
            ->with('success', 'Incidents imported successfully');
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
