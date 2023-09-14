<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DeploymentModule;
use App\Http\Controllers\Controller;
use App\Models\DeploymentServerType;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DeploymentServerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
        $query = DeploymentServerType::with('module');

        return DataTables::of($query)
            ->addColumn('action', function ($serverType) {
                return '
                    <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                        href="' . route('admin.deployment-server-types.edit', $serverType->id) . '">
                        Sunting
                    </a>
                    <form class="block w-full" onsubmit="return confirm(\'Apakah anda yakin?\');" action="' . route('admin.deployment-server-types.destroy', $serverType->id) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Hapus
                        </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
            })
            ->editColumn('module.name', function ($serverType) {
                return $serverType->module->name;
            })
            ->rawColumns(['action'])
            ->make();
    }

    return view('admin.deployment-server-types.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = DeploymentModule::all();
        return view('admin.deployment-server-types.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required',
        'module_id' => 'required|exists:deployment_modules,id'
        ]);

        if (DeploymentServerType::where('name', $request->name)->where('module_id', $request->module_id)->first()) {
            return redirect()->back()->with('error', 'Server Type already exists in the same module.');
        }

        DeploymentServerType::create($request->all());

        return redirect()->route('admin.deployment-server-types.index')->with('success', 'Server Type created successfully.');
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
        $serverType = DeploymentServerType::findOrFail($id);
        $modules = DeploymentModule::all();
        return view('admin.deployment-server-types.edit', compact('serverType', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'module_id' => 'required|exists:deployment_modules,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

         // if update name is not the same as the current name or the name is not unique
        if (DeploymentServerType::where('name', $request->name)->where('module_id', $request->module_id)->where('id', '!=', $id)->first()) {
            return redirect()->back()->with('error', 'Server Type already exists in the same module.');
        }

        $serverType = DeploymentServerType::findOrFail($id);
        $serverType->update($request->all());

        return redirect()->route('admin.deployment-server-types.index')->with('success', 'Server Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $serverType = DeploymentServerType::findOrFail($id);
        $serverType->delete();

        return redirect()->route('admin.deployment-server-types.index')->with('success', 'Server Type deleted successfully.');
    }
}
