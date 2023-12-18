<x-app-layout>
  <x-slot name="title">Admin</x-slot>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      <a href="#!" onclick="window.history.go(-1); return false;">
        ← Back
      </a>
    </h2>
  </x-slot>

  <div class="py-12 font-poppins">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="p-6 bg-white rounded-lg">
        <h1 class="mb-10 text-2xl font-medium">Add Server Type</h1>
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

        <form class="w-full" action="{{ route('admin.deployments.server-types.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                <div class="w-full">
                    <label class="block mt-4 mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="server_type_name">
                        Server Type Name*
                    </label>
                    <input value="{{ old('name') }}" name="name"
                    class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                    id="server_type_name" type="text" placeholder="Nama Server Type" required>
                    <div class="mt-2 text-sm text-gray-500">
                    Nama server type dan modul terkait. Wajib diisi.
                    </div>

                </div>
            </div>
            <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                <div class="w-full">
                <label class="block mt-4 mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="module_id">
                    Module*
                </label>
                <select name="module_id" id="module_id" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                  <option value="">-- Pilih Module --</option>
                  @foreach($modules as $module)
                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                  @endforeach
                </select>
                </div>
            </div>
            <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                <div class="w-full">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="is_active">
                        Status*
                    </label>
                    <select name="is_active" id="is_active" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white" required>
                        <option value="1">Active</option>
                        <option value="0">Non-Active</option>
                    </select>
                </div>
            </div>

          <div class="flex flex-wrap mb-6 -mx-3">
            <div class="w-full px-3 text-right">
              <button type="submit"
                      class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">
                Add Server Type
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
