<x-app-layout>
  <x-slot name="title">Admin - Deployments</x-slot>
  <x-slot name="header">
    <a href="{{ route('admin.deployments.index') }}"
           class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700 mr-4">
          Deployments
    </a>
    <a href="{{ route('admin.deployment-modules.index') }}"
           class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700 mr-4">
          Modules
    </a>
    <a href="{{ route('admin.deployment-server-types.index') }}"
           class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
          Server Types
    </a>
  </x-slot>

  <x-slot name="script">
    <script>
      // AJAX DataTable
      var datatable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
            url: '{{ route('admin.deployments.index') }}',
            type: 'GET',
        },
        language: {
          url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
        },
        columns: [
          { data: 'id', name: 'id' },
          { data: 'title', name: 'title' },
          { data: 'module', name: 'module' },
          { data: 'server_type', name: 'server_type' },
          { data: 'deploy_date', name: 'deploy_date' },
          { data: 'document_status', name: 'document_status' },
          { data: 'document_description', name: 'document_description' },
          { data: 'cm_status', name: 'cm_status' },
          { data: 'cm_description', name: 'cm_description' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%' }
        ],
      });
    </script>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="mb-10">
        <a href="{{ route('admin.deployments.create') }}"
           class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
          + Buat Deployment
        </a>
      </div>
      <div class="overflow-hidden shadow sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
          <table id="dataTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Module</th>
                    <th>Server Type</th>
                    <th>Deploy Date</th>
                    <th>Document Status</th>
                    <th>Document Description</th>
                    <th>CM Status</th>
                    <th>CM Description</th>
                    <th>Aksi</th>
                </tr>
                </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
