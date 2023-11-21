    <x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
    <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400" id="menu-button" aria-expanded="true" aria-haspopup="true">
                {{-- show menu apa sekarang --}}
                Background Jobs
                <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                </button>
            </div>
            <div x-show="open" @click.away="open = false" class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <a href="{{ route('admin.background-jobs-monitoring.processes.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Jobs
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
            scrollX: true,
            stateSave: true,
            ajax: {
                url: '{{ route('admin.background-jobs-monitoring.jobs.index') }}',
                type: 'GET',
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'type',
                    name: 'type',
                },
                {
                    data: 'process.name',
                    name: 'process.name',
                },
                {
                    data: 'data_amount_to_EIM',
                    name: 'data_amount_to_EIM',
                    render: function(data, type, row) {
                        return data.toLocaleString();
                    }
                },
                {
                    data: 'data_amount_to_S4GL',
                    name: 'data_amount_to_S4GL',
                    render: function(data, type, row) {
                        return data.toLocaleString();
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'duration_to_EIM',
                    name: 'duration_to_EIM',
                    render: function(data, type, row) {
                        let hours = Math.floor(data / 3600);
                        let minutes = Math.floor((data % 3600) / 60);
                        let seconds = data % 60;

                        let formattedDuration = '';

                        if(hours > 0) formattedDuration += `${hours}h `;
                        if(minutes > 0 || (hours > 0 && seconds === 0)) formattedDuration += `${minutes}m `;
                        if(seconds > 0 || (minutes === 0 && hours === 0)) formattedDuration += `${seconds}s`;

                        return formattedDuration;
                    }
                },
                {
                    data: 'duration_to_S4GL',
                    name: 'duration_to_S4GL',
                    render: function(data, type, row) {
                        let hours = Math.floor(data / 3600);
                        let minutes = Math.floor((data % 3600) / 60);
                        let seconds = data % 60;

                        let formattedDuration = '';

                        if(hours > 0) formattedDuration += `${hours}h `;
                        if(minutes > 0 || (hours > 0 && seconds === 0)) formattedDuration += `${minutes}m `;
                        if(seconds > 0 || (minutes === 0 && hours === 0)) formattedDuration += `${seconds}s`;

                        return formattedDuration;
                    }
                },
                {
                    data: 'notes',
                    name: 'notes',
                },
                {
                    data: 'execution_date',
                    name: 'execution_date',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '15%'
                },
            ],
            // order by execution_date first
            order: [[7, 'desc']],

        });

        // sweet alert delete
        $('body').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var form = $(this).parents('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        </script>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="flex gap-4 mb-10">
                <a href="{{ route('admin.background-jobs-monitoring.jobs.create') }}"
                    class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue font-poppins">
                    + Add Jobs
                </a>
                <a href="{{ route('background-jobs-monitoring.daily') }}" target="_blank"
                    class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue font-poppins">
                    View Chart
                </a>
                </div>
            <table id="dataTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Module</th>
                    <th>Job</th>
                    <th>Data Amount to EIM</th>
                    <th>Data Amount to S4GL</th>
                    <th>Status</th>
                    <th>Duration To EIM</th>
                    <th>Duration To S4GL</th>
                    <th>Notes</th>
                    <th>Monitoring Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    </x-app-layout>
