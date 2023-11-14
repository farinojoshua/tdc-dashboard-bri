@extends('layouts.front')

@section('title')
    <title>Deployment Calendar</title>
@endsection

@section('style')
    @vite(['resources/js/calendar.js'])
@endsection

@section('content')
    <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-4 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div class="flex justify-between p-6 mb-4 bg-white rounded shadow calendar-filter">
                <form id="calendarFilterForm" class="flex flex-wrap items-center">
                <label for="month" class="mr-2 font-bold">Month:</label>
                <select id="month" name="month" class="w-48 p-2 mr-4 border rounded">
                    <option value="0">Januari</option>
                    <option value="1">Februari</option>
                    <option value="2">Maret</option>
                    <option value="3">April</option>
                    <option value="4">Mei</option>
                    <option value="5">Juni</option>
                    <option value="6">Juli</option>
                    <option value="7">Agustus</option>
                    <option value="8">September</option>
                    <option value="9">Oktober</option>
                    <option value="10">November</option>
                    <option value="11">Desember</option>
                </select>

                <label for="year" class="mr-2 font-bold">Year:</label>
                <select id="year" name="year" class="w-48 p-2 mr-4 border rounded">
                    @for($i = 2023; $i <= 2030; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>

                <button type="submit" class="px-6 py-2 text-white rounded bg-darker-blue focus:outline-none focus:ring-2 focus:ring-blue-200">
                    Filter
                </button>
                </form>

               <div class="relative">
                    <button class="inline-flex px-4 py-2 text-white rounded bg-darker-blue focus:outline-none focus:ring-2 focus:ring-gray-200 dropdown-btn">
                        Calendar
                        <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="absolute right-0 z-10 hidden w-48 py-2 mt-2 bg-white border border-gray-300 rounded shadow dropdown-menu">
                        <a href="{{ route('deployments.calendar') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Calendar</a>
                        <a href="{{ route('deployments.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Chart</a>
                    </div>
                </div>
            </div>
            <div id="calendar"></div>
            <div id="calendarLegend" class="mt-10"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="eventInfoModal" class="fixed inset-0 z-50 items-center justify-center hidden">
    <div class="relative w-1/3 p-8 bg-white rounded-lg shadow-lg"> <!-- Added "relative" class -->
        <h5 class="mb-4 text-2xl" id="modalTitle">Event Info</h5>
        <button type="button" class="absolute p-2 text-4xl text-gray-800 rounded top-2 right-4" id="modalCloseButton">X</button> <!-- Moved the button and added "absolute top-2 right-2" classes -->
        <div class="modal-body" id="modalBody">
        </div>
    </div>
</div>

@endsection

@section('script')
@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle dropdown menu toggle
        const dropdownBtn = document.querySelector('.dropdown-btn');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        dropdownBtn.addEventListener('click', function () {
            dropdownMenu.classList.toggle('hidden');
        });

        // Mendapatkan bulan dan tahun saat ini
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth(); // Bulan saat ini (0-11)
        const currentYear = currentDate.getFullYear(); // Tahun saat ini

        // Mengatur bulan dan tahun saat ini sebagai pilihan yang dipilih
        const monthSelect = document.getElementById('month');
        const yearSelect = document.getElementById('year');

        monthSelect.value = currentMonth.toString();
        yearSelect.value = currentYear.toString();
    });
</script>
@endsection
