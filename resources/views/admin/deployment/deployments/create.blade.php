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
        <div class="p-6 bg-white rounded-xl">
            <h1 class="mb-10 text-2xl font-medium">Add Data Deployment</h1>
            <form action="{{ route('admin.deployments.deployment.store') }}" method="POST">
              @csrf

              <div class="grid grid-cols-2 gap-16">
                  <div>
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="block mb-2 text-sm font-bold text-gray-600">Title:</label>
                        <input type="text" id="title" name="title" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" value="{{ old('title') }}" required>
                    </div>

                    <!-- Module ID Dropdown -->
                    <div class="mb-4">
                        <label for="module_id" class="block mb-2 text-sm font-bold text-gray-600">Module:</label>
                        <select id="module_id" name="module_id" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="" disabled selected>-- Pilih Module --</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Server Type ID Dropdown -->
                    <div class="mb-4">
                        <label for="server_type_id" class="block mb-2 text-sm font-bold text-gray-600">Server Type:</label>
                        <select id="server_type_id" name="server_type_id" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="" disabled selected>-- Pilih Server Type (Pilih Module Terlebih Dahulu)--</option>
                            <!-- Options will be populated based on the selected module -->
                        </select>
                    </div>


                    <!-- Deploy Date -->
                    <div class="mb-4">
                        <label for="deploy_date" class="block mb-2 text-sm font-bold text-gray-600">Deploy Date:</label>
                        <input type="date" id="deploy_date" name="deploy_date" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" value="{{ old('deploy_date') }}" required>
                    </div>
                  </div>

                  <div>
                    <!-- Document Status -->
                    <div class="mb-4">
                        <label for="document_status" class="block mb-2 text-sm font-bold text-gray-600">Document Status:</label>
                        <select id="document_status" name="document_status" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <option value="" selected disabled>-- Pilih Status Dokumen --</option>
                            <option value="Not Done">Not Done</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>

                    <!-- Document Description -->
                    <div class="mb-4">
                        <label for="document_description" class="block mb-2 text-sm font-bold text-gray-600">Document Description:</label>
                        <textarea id="document_description" name="document_description" rows="4" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
                    </div>

                    <!-- CM Status -->
                    <div class="mb-4">
                        <label for="cm_status" class="block mb-2 text-sm font-bold text-gray-600">CM Status:</label>
                        <select id="cm_status" name="cm_status" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option value="" selected disabled>-- Pilih Status CM --</option>
                        <option value="Draft">Draft</option>
                        <option value="Reviewer">Reviewer</option>
                        <option value="Checker">Checker</option>
                        <option value="Signer">Signer</option>
                        <option value="Done Deploy">Done Deploy</option>
                        </select>
                    </div>

                    <!-- CM Description -->
                    <div class="mb-4">
                        <label for="cm_description" class="block mb-2 text-sm font-bold text-gray-600">CM Description:</label>
                        <textarea id="cm_description" name="cm_description" rows="4" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
                    </div>
                  </div>
              </div>

            <div class="text-right">
                <button type="submit" class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">Add Deployment</button>
            </div>

            </form>
        </div>
    </div>
  </div>

    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('module_id').addEventListener('change', function() {
                    var selectedModule = this.value;

                    fetch(`/api/modules/${selectedModule}/server-types`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        var serverTypeSelect = document.getElementById('server_type_id');
                        serverTypeSelect.innerHTML = '';

                        data.forEach(function(serverType) {
                            var option = new Option(serverType.name, serverType.id);
                            serverTypeSelect.appendChild(option);
                        });
                    });
                });
            });
        </script>
    </x-slot>



</x-app-layout>
