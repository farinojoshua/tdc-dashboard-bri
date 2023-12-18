<?php

namespace App\Http\Controllers\Admin\Deployment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Deployment\Deployment;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Deployment\DeploymentModule;
use App\Models\Deployment\DeploymentServerType;

class DeploymentController extends Controller
{
    /**
     * List of all deployments. if request is ajax, return datatables.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Deployment::with(['module', 'serverType']);

            return DataTables::of($query)
                ->addColumn('module', function ($deployment) {
                    return $deployment->module->name;
                })
                ->addColumn('server_type', function ($deployment) {
                    return $deployment->serverType->name;
                })
                ->addColumn('updated_at', function ($deployment) {
                    return $deployment->updated_at->format('d F Y H:i:s'); // Format the date as needed
                })
                ->addColumn('action', function ($deployment) {
                    return '
                        <div class="flex gap-2">
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                            href="' . route('admin.deployments.deployment.edit', $deployment->id) . '">
                            <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                        </a>
                        <form class="block w-full" action="' . route('admin.deployments.deployment.destroy', $deployment->id) . '" method="POST">
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

        return view('admin.deployment.deployments.index');
    }

    /**
     * Show the form to create a new deployment.
     */
    public function create()
    {
        $modules = DeploymentModule::where('is_active', 1)->get();
        $serverTypes = DeploymentServerType::where('is_active', 1)->get();

        return view('admin.deployment.deployments.create', compact('modules', 'serverTypes'));
    }

    /**
     * Store a new deployment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'module_id' => 'required|exists:deployment_modules,id',
            'server_type_id' => 'required|exists:deployment_server_types,id',
            'deploy_date' => 'required|date',
            'document_status' => 'required|in:Done,Not Done,In Progress',
            'document_description' => 'required|string',
            'cm_status' => 'required|in:Draft,Reviewer,Checker,Signer,Done Deploy',
            'cm_description' => 'required|string',
        ]);

        if (Deployment::where('title', $request->title)->exists()) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Deployment already exists. Please choose another title.');
        }

        Deployment::create($request->all());

        return redirect()->route('admin.deployments.deployment.index')
                        ->with('success', 'Success Create Deployment');
    }

    /**
     * Show the form to edit a deployment.
     */

    public function edit(Deployment $deployment)
    {
        $modules = DeploymentModule::where('is_active', 1)->get();
        $serverTypes = DeploymentServerType::where('is_active', 1)->get();

        if ($deployment->module->is_active == 0) {
            $modules->push($deployment->module);
        }

        if ($deployment->serverType->is_active == 0) {
            $serverTypes->push($deployment->serverType);
        }

        return view('admin.deployment.deployments.edit', compact('deployment', 'modules', 'serverTypes'));
    }

    public function update(Request $request, Deployment $deployment)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'module_id' => 'required|exists:deployment_modules,id',
            'server_type_id' => 'required|exists:deployment_server_types,id',
            'deploy_date' => 'required|date',
            'document_status' => 'required|in:Done,Not Done,In Progress',
            'document_description' => 'required|string',
            'cm_status' => 'required|in:Draft,Reviewer,Checker,Signer,Done Deploy',
            'cm_description' => 'required|string',
        ]);

        // check if deployment already exists
        if ($deployment->title != $request->title) {
            if (Deployment::where('title', $request->title)->first()) {
                return redirect()->back()->with('error', 'Deployment already exists.');
            }
        }

        $deployment->update($request->all());

        return redirect()->route('admin.deployments.deployment.index')->with('success', 'Deployment updated successfully.');
    }

    /**
     * Delete a deployment.
     */
    public function destroy($id)
    {
        $deployment = Deployment::findOrFail($id);
        $deployment->delete();

        return redirect()->route('admin.deployments.deployment.index')->with('success', 'Deployment deleted successfully.');
    }

    /**
     * Get server types by module id.
     */
    public function getServerTypesByModule($module_id, $selectedServerTypeId = null)
    {
        $serverTypes = DeploymentServerType::where('module_id', $module_id)
                                            ->where('is_active', 1)
                                            ->get();

        // Tambahkan server type yang saat ini dipilih jika tidak aktif
        if ($selectedServerTypeId) {
            $selectedServerType = DeploymentServerType::where('id', $selectedServerTypeId)
                                                    ->where('is_active', 0)
                                                    ->first();

            if ($selectedServerType) {
                $serverTypes->push($selectedServerType);
            }
        }

        return response()->json($serverTypes);
    }

}
