<?php

namespace App\Http\Controllers\Admin\Deployment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Deployment\DeploymentModule;
use App\Models\Deployment\DeploymentServerType;

class DeploymentServerTypeController extends Controller
{
    /**
     * List of all server types. if request is ajax, return datatables.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = DeploymentServerType::with('module');

            return DataTables::of($query)
                ->addColumn('action', function ($serverType) {
                    return '
                        <div class="flex gap-2">
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none btn-edit ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                            href="' . route('admin.deployments.server-types.edit', $serverType->id) . '">
                            <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                        </a>
                        <form class="block w-full" action="' . route('admin.deployments.server-types.destroy', $serverType->id) . '" method="POST">
                            <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none btn-delete ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                                <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="trash" class="mx-auto svg-inline--fa fa-trash fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41 0H173.59a48 48 0 0 0-40.59 23.3L99 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h304a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177 48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-216-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12z"></path></svg>
                            </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>
                        </div>';
                })
                ->editColumn('module.name', function ($serverType) {
                    return $serverType->module->name;
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.deployment.deployment-server-types.index');
    }

    /**
     * Show the form to create a new server type.
     */
    public function create()
    {
        $modules = DeploymentModule::where('is_active', true)->get();

        return view('admin.deployment.deployment-server-types.create', compact('modules'));
    }

    /**
     * Store a new server type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:10',
            'module_id' => 'required|exists:deployment_modules,id',
            'is_active' => 'required|boolean'
        ]);

        // check if server type already exists
        if (DeploymentServerType::where('name', $request->name)->where('module_id', $request->module_id)->first()) {
            return redirect()->back()->with('error', 'Server Type already exists in the same module.');
        }

        DeploymentServerType::create($request->only('name', 'module_id'));

        return redirect()->route('admin.deployments.server-types.index')->with('success', 'Server Type created successfully.');
    }

    /**
     * Show the form to edit a server type.
     */
    public function edit($id)
    {
        $serverType = DeploymentServerType::findOrFail($id);
        $modules = DeploymentModule::where('is_active', true)->get();

        // Add the current module if it's non-active
        if ($serverType->module->is_active == 0 && !in_array($serverType->module_id, $modules->pluck('id')->toArray())) {
            $modules->push($serverType->module);
        }

        return view('admin.deployment.deployment-server-types.edit', compact('serverType', 'modules'));
    }

    /**
     * Update an existing server type.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:10',
            'module_id' => 'required|exists:deployment_modules,id',
            'is_active' => 'required|boolean'
        ]);

        $serverType = DeploymentServerType::findOrFail($id);

        // check if server type already exists
        if (DeploymentServerType::where('name', $request->name)->where('module_id', $request->module_id)->where('id', '!=', $id)->first()) {
            return redirect()->back()->with('error', 'Server Type already exists in the same module.');
        }

        $serverType->update($request->only('name', 'module_id', 'is_active'));
        return redirect()->route('admin.deployments.server-types.index')->with('success', 'Server Type updated successfully.');
    }

    /**
     * Delete an existing server type.
     */
    public function destroy($id)
    {
        $serverType = DeploymentServerType::findOrFail($id);
        $serverType->delete();

        return redirect()->route('admin.deployments.server-types.index')->with('success', 'Server Type deleted successfully.');
    }
}
