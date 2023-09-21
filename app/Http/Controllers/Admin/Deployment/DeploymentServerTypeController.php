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
     * Display a listing of the resource.
     */
    public function index()
    {
        // check if request is ajax
        if (request()->ajax()) {
            // query all server types
            $query = DeploymentServerType::with('module');

            // return datatables
            return DataTables::of($query)
                ->addColumn('action', function ($serverType) {
                    return '
                        <div class="flex gap-2">
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                            href="' . route('admin.deployment-server-types.edit', $serverType->id) . '">
                            <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                        </a>
                        <form class="block w-full" action="' . route('admin.deployment-server-types.destroy', $serverType->id) . '" method="POST">
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

        // return index view
        return view('admin.deployment.deployment-server-types.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // select all modules
        $modules = DeploymentModule::all();

        // return create view
        return view('admin.deployment.deployment-server-types.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required|max:10',
            'module_id' => 'required|exists:deployment_modules,id'
        ]);

        // check if server type already exists
        if (DeploymentServerType::where('name', $request->name)->where('module_id', $request->module_id)->first()) {
            return redirect()->back()->with('error', 'Server Type already exists in the same module.');
        }

        // create new server type
        DeploymentServerType::create($request->all());

        // redirect to index page
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
    public function edit($id)
    {
        // find server type by id
        $serverType = DeploymentServerType::findOrFail($id);
        // select all modules
        $modules = DeploymentModule::all();

        // return edit view with server type and modules data
        return view('admin.deployment.deployment-server-types.edit', compact('serverType', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // validate request
        $request->validate([
            'name' => 'required|max:10',
            'module_id' => 'required|exists:deployment_modules,id'
        ]);

        // find server type by id
        $serverType = DeploymentServerType::findOrFail($id);

        // check if server type already exists
        if (DeploymentServerType::where('name', $request->name)->where('module_id', $request->module_id)->where('id', '!=', $id)->first()) {
            return redirect()->back()->with('error', 'Server Type already exists in the same module.');
        }

        // update server type
        $serverType->update($request->all());

        // redirect to index page
        return redirect()->route('admin.deployment-server-types.index')->with('success', 'Server Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // find server type by id
        $serverType = DeploymentServerType::findOrFail($id);

        // delete server type
        $serverType->delete();

        // redirect to index page
        return redirect()->route('admin.deployment-server-types.index')->with('success', 'Server Type deleted successfully.');
    }
}
