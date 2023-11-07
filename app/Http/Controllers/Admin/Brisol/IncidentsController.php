<?php

namespace App\Http\Controllers\Admin\Brisol;

use Illuminate\Http\Request;
use App\Models\Brisol\Incident;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Brisol\IncidentsImport;
use Yajra\DataTables\Facades\DataTables;

class IncidentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Incident::all();

            return DataTables::of($query)
                ->make();
        }
        return view('admin.brisol.incidents.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brisol.incidents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ], [
            'file.required' => 'File is required',
            'file.mimes' => 'File must be an Excel document',
        ]);

        $file = $request->file('file');

        Excel::import(new IncidentsImport, $file);

        return redirect()->route('admin.brisol.incidents.index')->with('success', 'Incidents imported successfully');
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
