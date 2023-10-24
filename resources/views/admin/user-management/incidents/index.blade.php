<x-app-layout>
  <x-slot name="title">Admin</x-slot>
  <x-slot name="header">
    <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
        <div>
            <button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400" id="menu-button" aria-expanded="true" aria-haspopup="true">
            {{-- show menu apa sekarang --}}
            User Management
            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            </button>
        </div>

        <div x-show="open" @click.away="open = false" class="absolute right-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                <a href="{{ route('admin.user-management.monthly-target.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    Monthly Target
                </a>
            </div>
        </div>
    </div>
  </x-slot>

  <x-slot name="script">
    <script>
        // function format date from database to dd/mm/yyyy
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [day, month, year].join('/');
        }
      // AJAX DataTable
      var datatable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
            url: '{{ route('admin.user-management.incidents.index') }}',
            type: 'GET',
        },
        language: {
          url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
        },

        columns: [
            {
                data: 'id',
                name: 'id',
            },
            {
                data: 'reported_date',
                name: 'reported_date',
                render: function (data, type, row) {
                    return formatDate(data);
                }
            },
            {
                data: 'type_name',
                name: 'type_name'
            },
            {
                data: 'branch_name',
                name: 'branch_name'
            },
            {
                data: 'req_status',
                name: 'req_status'
            },
            {
                data: 'exec_status',
                name: 'exec_status'
            },
            {
                data: 'execution_date',
                name: 'execution_date',
                render: function (data, type, row) {
                    return data ? formatDate(data) : '-';
                }
            },
            {
                data: 'sla_category',
                name: 'sla_category',
                render: function (data, type, row) {
                    return data ? data : '-';
                }
            },

        ],
      });

    </script>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="mb-10">
              <a href="{{ route('admin.user-management.incidents.create') }}"
                 class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue font-poppins">
                + Add  Data
              </a>
            </div>
          <table id="dataTable">
            <thead>
              <tr>
                <th style="max-width: 1%">ID</th>
                <th>Reported Date</th>
                <th>Type</th>
                <th>Unit Kerja</th>
                <th>Request Status</th>
                <th>Execution Status</th>
                <th>Execution Date</th>
                <th>SLA Category</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
