<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deployment;
use Illuminate\Http\Request;
use App\Models\DeploymentModule;
use App\Http\Controllers\Controller;
use App\Models\DeploymentServerType;
use Yajra\DataTables\Facades\DataTables;

class DeploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
        $query = Deployment::with(['module', 'serverType']);  // Gunakan query dari model Deployment

        return DataTables::of($query)
            ->addColumn('module', function ($deployment) {
                return $deployment->module->name; // Gantikan 'name' dengan kolom sesungguhnya di model Module
            })
            ->addColumn('server_type', function ($deployment) {
                return $deployment->serverType->name; // Gantikan 'name' dengan kolom sesungguhnya di model ServerType
            })
            ->addColumn('action', function ($deployment) {
                return '
                    <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                        href="' . route('admin.deployments.edit', $deployment->id) . '">
                        Sunting
                    </a>
                    <form class="block w-full" onsubmit="return confirm(\'Apakah anda yakin?\');" action="' . route('admin.deployments.destroy', $deployment->id) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                            Hapus
                        </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
            })
            ->rawColumns(['action'])
            ->make();
    }

    return view('admin.deployments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = DeploymentModule::all();
        $serverTypes = DeploymentServerType::all();
        return view('admin.deployments.create', compact('modules', 'serverTypes'));
    }

    public function getServerTypesByModule($module_id, Request $request)
    {
        $serverTypes = DeploymentServerType::where('module_id', $module_id)->get();
        return response()->json($serverTypes);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'module_id' => 'required|exists:deployment_modules,id',
            'server_type_id' => 'required|exists:deployment_server_types,id',
            // add validation for other fields
        ]);

        Deployment::create($request->all());

        return redirect()->route('admin.deployments.index');
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

    public function edit(Deployment $deployment)
    {
        $modules = DeploymentModule::all();
        $serverTypes = DeploymentServerType::all();
        return view('admin.deployments.edit', compact('deployment', 'modules', 'serverTypes'));
    }

    public function update(Request $request, Deployment $deployment)
    {
        $request->validate([
            'title' => 'required',
            'module_id' => 'required',
            'server_type_id' => 'required',
            'deploy_date' => 'required',
            'document_status' => 'required',
            'document_description' => 'required',
            'cm_status' => 'required',
            'cm_description' => 'required',
        ]);

        $deployment->update($request->all());

        return redirect()->route('admin.deployments.index')->with('success', 'Deployment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deployment = Deployment::find($id);

        if ($deployment) {
            $deployment->delete();
            return redirect()->route('admin.deployments.index')->with('success', 'Deployment successfully deleted.');
        } else {
            return redirect()->route('admin.deployments.index')->with('error', 'Deployment not found.');
        }
    }
}
