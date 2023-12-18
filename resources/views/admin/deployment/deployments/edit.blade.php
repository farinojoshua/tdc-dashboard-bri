<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      <a href="#!" onclick="window.history.go(-1); return false;">
        ← Back
      </a>
    </h2>
  </x-slot>

  <div class="py-12 font-poppins">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="p-6 bg-white rounded-xl">
        <h1 class="mb-10 text-2xl font-medium">Edit Data Deployment</h1>
        <form action="{{ route('admin.deployments.deployment.update', $deployment->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="grid grid-cols-2 gap-16">
              <div>
                  <!-- Title -->
                  <div class="mb-4">
                      <label for="title" class="block mb-2 text-sm font-bold text-gray-600">Title:</label>
                      <input type="text" id="title" name="title" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                              value="{{ old('title', $deployment->title) }}" required>
                  </div>

                  <!-- Module ID -->
                  <div class="mb-4">
                    <label for="module" class="block mb-2 text-sm font-bold text-gray-600">Module</label>
                    <select name="module_id" id="module_id" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ $deployment->module_id == $module->id ? 'selected' : '' }}>
                                {{ $module->name }}{{ $module->is_active == 0 ? ' (Currently Non-Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                  </div>

                <!-- Server Type ID -->
                <div class="mb-4">
                    <label for="server_type_id" class="block mb-2 text-sm font-bold text-gray-600">Server Type:</label>
                    <select name="server_type_id" id="server_type_id" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        @foreach($serverTypes as $serverType)
                            <option value="{{ $serverType->id }}" {{ $deployment->server_type_id == $serverType->id ? 'selected' : '' }}>
                                {{ $serverType->name }}{{ $serverType->is_active == 0 ? ' (Currently Non-Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>


                  <!-- Deploy Date -->
                  <div class="mb-4">
                      <label for="deploy_date" class="block mb-2 text-sm font-bold text-gray-600">Deploy Date:</label>
                      <input type="date" id="deploy_date" name="deploy_date" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                              value="{{ old('deploy_date', $deployment->deploy_date) }}" required>
                  </div>
              </div>

              <div>
                  <!-- Document Status -->
                  <div class="mb-4">
                      <label for="document_status" class="block mb-2 text-sm font-bold text-gray-600">Document Status:</label>
                      <select id="document_status" name="document_status" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                          <option value="Not Done" {{ (old('document_status', $deployment->document_status) == 'Not Done' ? 'selected' : '') }}>
                              Not Done
                          </option>
                          <option value="In Progress" {{ (old('document_status', $deployment->document_status) == 'In Progress' ? 'selected' : '') }}>
                              In Progress
                          </option>
                          <option value="Done" {{ (old('document_status', $deployment->document_status) == 'Done' ? 'selected' : '') }}>
                          Done
                          </option>
                      </select>
                  </div>

                  <!-- Document Description -->
                  <div class="mb-4">
                      <label for="document_description" class="block mb-2 text-sm font-bold text-gray-600">Document Description:</label>
                      <textarea id="document_description" name="document_description" rows="4" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>{{ old('document_description', $deployment->document_description) }}</textarea>
                  </div>

                  <!-- CM Status -->
                  <div class="mb-4">
                      <label for="cm_status" class="block mb-2 text-sm font-bold text-gray-600">CM Status:</label>
                      <select id="cm_status" name="cm_status" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                          <option value="Draft" {{ (old('cm_status', $deployment->cm_status) == 'Draft' ? 'selected' : '') }}>
                          Draft
                          </option>
                          <option value="Reviewer" {{ (old('cm_status', $deployment->cm_status) == 'Reviewer' ? 'selected' : '') }}>
                          Reviewer
                          </option>
                          <option value="Checker" {{ (old('cm_status', $deployment->cm_status) == 'Checker' ? 'selected' : '') }}>
                          Checker
                          </option>
                          <option value="Signer" {{ (old('cm_status', $deployment->cm_status) == 'Signer' ? 'selected' : '') }}>
                          Signer
                          </option>
                          <option value="Done Deploy" {{ (old('cm_status', $deployment->cm_status) == 'Done Deploy' ? 'selected' : '') }}>
                          Done Deploy
                          </option>
                      </select>
                  </div>

                  <!-- CM Description -->
                  <div class="mb-4">
                      <label for="cm_description" class="block mb-2 text-sm font-bold text-gray-600">CM Description:</label>
                      <textarea id="cm_description" name="cm_description" rows="4" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>{{ old('cm_description', $deployment->cm_description) }}</textarea>
                  </div>
              </div>
          </div>



            <div class="text-right">
                <button type="submit" class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">Update Deployment</button>
            </div>
        </form>
    </div>
    </div>
  </div>

    <x-slot name="script">
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var previouslySelectedServerTypeId = "{{ $deployment->server_type_id }}";

            var moduleSelect = document.getElementById('module_id');
            var serverTypeSelect = document.getElementById('server_type_id');

            if (moduleSelect.value) {
                fetchServerTypes(moduleSelect.value, previouslySelectedServerTypeId);
            }

            moduleSelect.addEventListener('change', function() {
                fetchServerTypes(this.value);
            });

            function fetchServerTypes(selectedModule, selectedServerType = null) {
                var url = `/api/modules/${selectedModule}/server-types`;
                if (selectedServerType) {
                    url += `/${selectedServerType}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        serverTypeSelect.innerHTML = '';
                        data.forEach(function(serverType) {
                            var optionText = serverType.name;
                            if (serverType.is_active === 0) {
                                optionText += ' (Currently Non-Active)';
                            }

                            var option = new Option(optionText, serverType.id);
                            option.selected = selectedServerType && serverType.id.toString() === selectedServerType;
                            serverTypeSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });


        </script>
    </x-slot>
</x-app-layout>

