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
        <h1 class="mb-10 text-2xl font-medium">Add Background Job</h1>
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
        <form action="{{ route('admin.background-jobs-monitoring.jobs.store') }}" method="post" class="w-full">
            @csrf

            <div class="grid grid-cols-2 gap-16">
                <!-- Kolom Pertama -->
                <div>
                    <!-- Type Dropdown -->
                    <div class="mb-4">
                        <label for="type" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Type*</label>
                        <select id="type" name="type" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Product">Product</option>
                            <option value="Non-Product">Non-Product</option>
                        </select>
                        <div class="mt-2 text-sm text-gray-500">
                            Select the type of background job. Mandatory.
                        </div>
                    </div>

                    <!-- Process Dropdown -->
                    <div class="mb-4">
                        <label for="process_id" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Module Name*</label>
                        <select id="process_id" name="process_id" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="" disabled selected>Select Module</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                        <div class="mt-2 text-sm text-gray-500">
                            Select the associated module. Mandatory.
                        </div>
                    </div>

                    <!-- Data Amount to EIM -->
                    <div class="mb-4">
                        <label for="data_amount_to_EIM" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Data Amount to EIM*</label>
                        <input type="number" id="data_amount_to_EIM" name="data_amount_to_EIM" value="{{ old('data_amount_to_EIM') }}" placeholder="Data Amount to EIM" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The amount of data to EIM. Mandatory. Numeric.
                        </div>
                    </div>

                    <!-- Data Amount to S4GL -->
                    <div class="mb-4">
                        <label for="data_amount_to_S4GL" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Data Amount to S4GL*</label>
                        <input type="number" id="data_amount_to_S4GL" name="data_amount_to_S4GL" value="{{ old('data_amount_to_S4GL') }}" placeholder="Data Amount to S4GL" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The amount of data to S4GL. Mandatory. Numeric.
                        </div>
                    </div>

                </div>

                <!-- Kolom Kedua -->
                <div>
                    <!-- Duration -->
                    <div class="mb-4">
                        <label for="duration_to_EIM" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Duration to EIM</label>
                        <input type="number" id="duration_to_EIM" name="duration_to_EIM" value="{{ old('duration_to_EIM') }}" placeholder="Duration To EIM" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The duration of the background job in seconds. Mandatory. Integer.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="duration_to_S4GL" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Duration to S4GL</label>
                        <input type="number" id="duration_to_S4GL" name="duration_to_S4GL" value="{{ old('duration_to_S4GL') }}" placeholder="Duration To S4GL" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The duration of the background job in seconds. Mandatory. Integer.
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="mb-4">
                        <label for="status" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Status*</label>
                        <select id="status" name="status" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="Normal Run" {{ old('status') == 'Normal Run' ? 'selected' : '' }}>Normal Run</option>
                            <option value="Rerun Background Job" {{ old('status') == 'Rerun Background Job' ? 'selected' : '' }}>Rerun Background Job</option>
                            <option value="Manual Run Background Job" {{ old('status') == 'Manual Run Background Job' ? 'selected' : '' }}>Manual Run Background Job</option>
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        <div class="mt-2 text-sm text-gray-500">
                            The status of the background job. Mandatory.
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Notes</label>
                        <textarea id="notes" name="notes" placeholder="Notes" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500">{{ old('notes') }}</textarea>
                        <div class="mt-2 text-sm text-gray-500">
                            The notes of the background job. Optional.
                        </div>
                    </div>

                    <!-- Execution Date -->
                    <div class="mb-4">
                        <label for="execution_date" class="block mb-2 text-sm font-bold text-gray-600 uppercase">Monitoring Date*</label>
                        <input type="date" id="execution_date" name="execution_date" value="{{ old('execution_date', date('Y-m-d')) }}" placeholder="YYYY-MM-DD" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <div class="mt-2 text-sm text-gray-500">
                            The date when the background job will be executed. Mandatory.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Submit -->
            <div class="text-right">
                <button type="submit" class="px-4 py-2 font-bold text-white rounded-full bg-darker-blue">Add Background Job</button>
            </div>
        </form>

      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function() {
      $('#type').on('change', function() {
          var type = $(this).val();
          if (type) {
              $.ajax({
                  url: '/api/bjm/get-processes-by-type',
                  type: 'GET',
                  data: {type: type},
                  success: function(data) {
                      $('#process_id').empty();
                      $('#process_id').append('<option value="" disabled selected>Select Process</option>');
                      $.each(data, function(key, value) {
                          $('#process_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                      });
                  }
              });
          } else {
              $('#process_id').empty();
          }
      });
  });

  </script>
</x-app-layout>
