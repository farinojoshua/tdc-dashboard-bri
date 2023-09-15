<x-app-layout>
  <x-slot name="title">Admin - Deployments</x-slot>
  <x-slot name="header">
    <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
        <div>
            <button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-primary focus:outline-none focus:ring focus:ring-slate-400" id="menu-button" aria-expanded="true" aria-haspopup="true">
            {{-- show menu apa sekarang --}}
            Deployments
            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            </button>
        </div>

        <div x-show="open" @click.away="open = false" class="absolute right-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                <a href="{{ route('admin.deployments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    Deployments
                </a>
                <a href="{{ route('admin.deployment-modules.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    Modules
                </a>
                <a href="{{ route('admin.deployment-server-types.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    Server Types
                </a>
            </div>
        </div>
    </div>

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
      <div class="overflow-hidden shadow sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
        <div class="mb-10">
            <a href="{{ route('admin.deployments.create') }}"
            class="px-4 py-2 font-bold text-white rounded shadow-lg font-poppins bg-primary">
            + Add Deployment
            </a>
        </div>
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
