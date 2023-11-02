<x-app-layout>
  <x-slot name="title">Admin</x-slot>
  <x-slot name="header">
    <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
        <div>
            <button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400" id="menu-button" aria-expanded="true" aria-haspopup="true">
            {{-- show menu apa sekarang --}}
            Brisol
            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            </button>
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
            url: '{{ route('admin.brisol.incidents.index') }}',
            type: 'GET',
        },
        language: {
          url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
        },

        columns: [
            {
                data: 'inc_id',
                name: 'inc_id',
            },
            {
                data: 'reported_date',
                name: 'reported_date',
            },
            {
                data: 'resolved_date',
                name: 'resolved_date',
            },
            {
                data: 'region',
                name: 'region',
            },
            {
                data: 'service_ci',
                name: 'service_ci',
            },
            {
                data: 'ctg_tier1',
                name: 'ctg_tier1',
            },
            {
                data: 'ctg_tier2',
                name: 'ctg_tier2',
            },
            {
                data: 'ctg_tier3',
                name: 'ctg_tier3',
            },
            {
                data: 'resolution_category',
                name: 'resolution_category',
            },
            {
                data: 'priority',
                name: 'priority',
            },
            {
                name: 'status',
                data: 'status',
            },
            {
                name: 'slm_status',
                data: 'slm_status',
            }
        ],
      });

    </script>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="mb-10">
              <a href="{{ route('admin.brisol.incidents.create') }}"
                 class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue font-poppins">
                + Add  Data
              </a>
            </div>
          <table id="dataTable">
            <thead>
              <tr>
                <th>Incident ID</th>
                <th>Reported Date</th>
                <th>Resolved Date</th>
                <th>Region</th>
                <th>Service CI</th>
                <th>CTG Tier 1</th>
                <th>CTG Tier 2</th>
                <th>CTG Tier 3</th>
                <th>Resolution Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>SLM Status</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>