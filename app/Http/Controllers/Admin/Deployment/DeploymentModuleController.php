<?php

namespace App\Http\Controllers\Admin\Deployment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Deployment\DeploymentModule;

class DeploymentModuleController extends Controller
{
    /**
     * List of all modules. if request is ajax, return datatables.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = DeploymentModule::query();

            return DataTables::of($query)
                ->addColumn('action', function ($module) {
                    return '
                        <div class="flex gap-2">
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none btn-edit ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                            href="' . route('admin.deployments.modules.edit', $module->id) . '">
                            <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                        </a>
                        <form class="block w-full" action="' . route('admin.deployments.modules.destroy', $module->id) . '" method="POST">
                            <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none btn-delete ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="trash" class="mx-auto svg-inline--fa fa-trash fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41 0H173.59a48 48 0 0 0-40.59 23.3L99 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h304a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177 48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-216-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12z"></path></svg>
                            </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.deployment.deployment-modules.index');
    }

    /**
     * Show the form to create a new module.
     */
    public function create()
    {
        return view('admin.deployment.deployment-modules.create');
    }

    /**
     * Store a new module.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:15',
            'is_active' => 'required|boolean'
        ]);

        // check if module already exists
        if (DeploymentModule::where('name', $request->name)->first()) {
            return redirect()->back()->with('error', 'Module already exists.');
        }

        DeploymentModule::create($request->only('name', 'is_active'));

        return redirect()->route('admin.deployments.modules.index')->with('success', 'Module created successfully.');
    }

    /**
     * Show the form to edit a module.
     */
    public function edit($id)
    {
        $deploymentModule = DeploymentModule::findOrfail($id);

        return view('admin.deployment.deployment-modules.edit', compact('deploymentModule'));
    }

    /**
     * Update an existing module.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:15',
            'is_active' => 'required|boolean'
        ]);

        $module = DeploymentModule::findOrfail($id);

        // check if module already exists
        if ($module->name != $request->name) {
            if (DeploymentModule::where('name', $request->name)->first()) {
                return redirect()->back()->with('error', 'Module already exists.');
            }
        }

        $module->update($request->only('name', 'is_active'));

        return redirect()->route('admin.deployments.modules.index')->with('success', 'Module updated successfully.');
    }


    /**
     * Delete a module.
     */
    public function destroy($id)
    {
        $module = DeploymentModule::findOrfail($id);
        $module->delete();

        return redirect()->route('admin.deployments.modules.index')->with('success', 'Module deleted successfully.');
    }
}
