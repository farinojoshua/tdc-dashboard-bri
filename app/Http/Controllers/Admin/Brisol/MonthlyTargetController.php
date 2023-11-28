<?php

namespace App\Http\Controllers\Admin\Brisol;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brisol\MonthlyTarget;
use Yajra\DataTables\Facades\DataTables;

class MonthlyTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    // check if request is ajax
    if (request()->ajax()) {
        $query = MonthlyTarget::query();

        return DataTables::of($query)
            ->editColumn('month', function ($monthlyTarget) {
                return date('F', mktime(0, 0, 0, $monthlyTarget->month, 10));
            })
            ->addColumn('action', function ($monthlyTarget) {
                return '
                    <div class="flex gap-2">
                    <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none btn-edit ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                        href="' . route('admin.brisol.monthly-target.edit', $monthlyTarget->id) . '">
                            <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                    </a>
                    <form class="block w-full" action="' . route('admin.brisol.monthly-target.destroy', $monthlyTarget->id) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none btn-delete ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                <svg aria-hidden="true" width="24px" height="
                                24px" focusable="false" data-prefix="fas" data-icon="trash" class="mx-auto svg-inline--fa fa-trash fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="
                                currentColor" d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12
                                12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41
                                0H173.59a48 48 0 0 0-40.59 23.3L99 80H16A16 16 0 0 0 0 96v16a16 16 0 0
                                0 16 16h16v336a48 48 0 0 0 48 48h304a48 48 0 0 0 48-48V128h16a16 16 0
                                0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177
                                48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-216-48h24a12
                                12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12
                                12v216a12 12 0 0 0 12 12z"></path></svg>
                        </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make();

    }

    return view('admin.brisol.monthly-target.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brisol.monthly-target.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer',
            'year' => 'required|integer',
            'monthly_target_value' => 'required|numeric',
        ]);

        // if target at the same month and year already exist then return error message
        $monthlyTarget = MonthlyTarget::where('month', $request->month)->where('year', $request->year)->first();
        if ($monthlyTarget) {
            return redirect()->back()->with('error', 'Monthly target already exist.');
        }

        MonthlyTarget::create($request->only('month', 'year', 'monthly_target_value'));

        return redirect()->route('admin.brisol.monthly-target.index')->with('success', 'Monthly target created successfully.');
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
        $monthlyTarget =  MonthlyTarget::findOrfail($id);

        return view('admin.brisol.monthly-target.edit', compact('monthlyTarget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request...
        $request->validate([
            'month' => 'required|integer',
            'year' => 'required|integer',
            'monthly_target_value' => 'required|numeric',
        ]);

        $monthlyTarget = MonthlyTarget::findOrfail($id);

        // if target at the same month and year already exist then return error message
        $monthlyTargetExist = MonthlyTarget::where('month', $request->month)->where('year', $request->year)->first();
        if ($monthlyTargetExist && $monthlyTargetExist->id != $monthlyTarget->id) {
            return redirect()->back()->with('error', 'Monthly target already exist.');
        }

        $monthlyTarget->update($request->only('month', 'year', 'monthly_target_value'));

        return redirect()->route('admin.brisol.monthly-target.index')->with('success', 'Monthly target updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $monthlyTarget = MonthlyTarget::findOrfail($id);

        $monthlyTarget->delete();

        return redirect()->route('admin.brisol.monthly-target.index')->with('success', 'Monthly target deleted successfully.');
    }
}
