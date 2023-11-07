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
            <h1 class="mb-10 text-2xl font-medium">Add Monthly Target</h1>
            <form action="{{ route('admin.brisol.monthly-target.store') }}" method="POST">
              @csrf

                <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                    <div class="w-full">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="module_name">
                            Month
                        </label>
                        <?php
                        $currentMonth = date('n');
                        ?>
                        <select name="month" id="month" class="w-full px-4 py-2 mb-4 bg-gray-200 border border-gray-200 rounded shadow-sm focus:outline-none focus:bg-white focus:border-gray-500">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $currentMonth ? 'selected' : '' }}>{{ strftime('%B', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>

                    </div>
                    <div class="w-full">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="module_name">
                            Year
                        </label>
                        <?php
                        $currentYear = date('Y');
                        $selectedYear = isset($monthlyTarget) ? $monthlyTarget->year : $currentYear;
                        ?>
                        <select name="year" id="year" class="w-full px-4 py-2 mb-4 bg-gray-200 border border-gray-200 rounded shadow-sm focus:outline-none focus:bg-white focus:border-gray-500">
                            @for ($i = 2021; $i <= 2030; $i++)
                                <option value="{{ $i }}" {{ $i == $selectedYear ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="module_name">
                            Target
                        </label>
                        <input class="w-full px-4 py-2 mb-4 bg-gray-200 border border-gray-200 rounded shadow-sm focus:outline-none focus:bg-white focus:border-gray-500"
                               id="monthly_target_value"
                               name="monthly_target_value"
                               type="number"
                               placeholder="Target"
                               step="0.01"
                               required>
                    </div>
                </div>

                <div class="flex flex-wrap mb-6 -mx-3">
                    <div class="w-full px-3 text-right">
                    <button type="submit"
                            class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">
                        Add Monthly Target
                    </button>
                    </div>
                </div>


            </form>
        </div>
    </div>
  </div>

</x-app-layout>
