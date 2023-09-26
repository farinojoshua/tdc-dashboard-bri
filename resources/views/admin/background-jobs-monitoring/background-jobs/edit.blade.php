<x-app-layout>
  <x-slot name="title">Admin</x-slot>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 font-poppins">
      <a href="#!" onclick="window.history.go(-1); return false;">
        ← Back
      </a>
    </h2>
  </x-slot>

  <div class="py-12 font-poppins">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="p-6 bg-white rounded-lg">
        <h1 class="mb-10 text-2xl font-medium">Update Background Job</h1>
        @if ($errors->any())
          <div class="mb-5" role="alert">
            <div class="px-4 py-2 font-bold text-white bg-red-500 rounded-t">
              Ada kesalahan!
            </div>
            <div class="px-4 py-3 text-red-700 bg-red-100 border border-t-0 border-red-400 rounded-b">
              <p>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              </p>
            </div>
          </div>
        @endif
        <form class="w-full" action="{{ route('admin.background-jobs-monitoring.jobs.update', $job->id) }}" method="post">
        @csrf
        @method('PUT')
                <div class="grid grid-cols-2 gap-16">
                <!-- Kolom Pertama -->
                <div>
                    <!-- Type Dropdown -->
                    <div class="mb-4">
                        <label for="type" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Type*</label>
                        <select name="type"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                            id="type" required>
                        <option value="" disabled>Select Type</option>
                        <option value="Product" {{ $job->type == 'Product' ? 'selected' : '' }}>Product</option>
                        <option value="Non-Product" {{ $job->type == 'Non-Product' ? 'selected' : '' }}>Non-Product</option>
                        </select>
                        <div class="mt-2 text-sm text-gray-500">
                            Select the type of background job. Mandatory.
                        </div>
                    </div>

                    <!-- Process Dropdown -->
                    <div class="mb-4">
                        <label for="process_id" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Process Name*</label>
                       <select name="process_id"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                            id="process_id" required>
                        <option value="" disabled selected>Select Process</option>
                        <!-- Options will be populated by JavaScript -->
                        </select>
                        <div class="mt-2 text-sm text-gray-500">
                            Select the associated process. Mandatory.
                        </div>
                    </div>

                    <!-- Data Amount to IEM -->
                    <div class="mb-4">
                        <label for="data_amount_to_IEM" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Data Amount to IEM*</label>
                        <input value="{{ old('data_amount_to_IEM', $job->data_amount_to_IEM) }}" name="data_amount_to_IEM"
                          class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                          id="data_amount_to_IEM" type="number" placeholder="Data Amount to IEM" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The amount of data to IEM. Mandatory. Numeric.
                        </div>
                    </div>

                     <!-- Data Amount to S4GL -->
                    <div class="mb-4">
                        <label for="data_amount_to_S4GL" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Data Amount to S4GL*</label>
                        <input value="{{ old('data_amount_to_S4GL', $job->data_amount_to_S4GL) }}" name="data_amount_to_S4GL"
                          class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                          id="data_amount_to_S4GL" type="number" placeholder="Data Amount to S4GL" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The amount of data to S4GL. Mandatory. Numeric.
                        </div>
                    </div>


                </div>

                <!-- Kolom Kedua -->
                <div>
                    <!-- Duration -->
                    <div class="mb-4">
                        <label for="duration" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Duration*</label>
                        <input value="{{ old('duration', $job->duration) }}" name="duration"
                          class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                          id="duration" type="number" placeholder="Duration" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The duration of the background job in seconds. Mandatory. Integer.
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="mb-4">
                        <label for="status" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Status*</label>
                        <select name="status"
                              class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                              id="status" required>
                          <option value="" disabled>Select Status</option>
                          <option value="Normal Run" {{ $job->status == 'Normal Run' ? 'selected' : '' }}>Normal Run</option>
                          <option value="Rerun Background Job" {{ $job->status == 'Rerun Background Job' ? 'selected' : '' }}>Rerun Background Job</option>
                          <option value="Manual Run Background Job" {{ $job->status == 'Manual Run Background Job' ? 'selected' : '' }}>Manual Run Background Job</option>
                          <option value="Pending" {{ $job->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                      </select>
                        <div class="mt-2 text-sm text-gray-500">
                            The status of the background job. Mandatory.
                        </div>
                    </div>

                    <!-- Execution Date -->
                    <div class="mb-4">
                        <label for="execution_date" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Execution Date*</label>
                        <input value="{{ old('execution_date', $job->execution_date) }}" name="execution_date"
                          class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                          id="execution_date" type="date" placeholder="Execution Date" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The date when the background job will be executed. Mandatory.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Submit -->
            <div class="text-right">
                <button type="submit" class="px-4 py-2 font-bold text-white rounded-full bg-primary">Add Background Job</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        function loadProcesses(type, selectedId = null) {
            $.ajax({
                url: '/api/get-processes-by-type',
                type: 'GET',
                data: {type: type},
                success: function(data) {
                    $('#process_id').empty();
                    $('#process_id').append('<option value="" disabled>Select Process</option>');
                    $.each(data, function(key, value) {
                        var isSelected = selectedId == value.id ? 'selected' : '';
                        $('#process_id').append('<option value="'+ value.id +'" ' + isSelected + '>'+ value.name +'</option>');
                    });
                }
            });
        }

        // Load processes when the page loads
        var initialType = $('#type').val();
        var selectedProcessId = {{ $job->process_id }}; // Assuming $job is the variable containing the job data.
        if(initialType) loadProcesses(initialType, selectedProcessId);

        $('#type').on('change', function() {
            var type = $(this).val();
            if(type) loadProcesses(type);
            else $('#process_id').empty();
        });
    });
    </script>

</x-app-layout>
