@extends('layouts.front')

@section('content')
    <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-4 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div id="calendar"></div>
            <div id="calendarLegend" class="mt-10"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="eventInfoModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="w-1/3 p-8 bg-white rounded-lg shadow-lg">
        <h5 class="mb-4 text-2xl" id="modalTitle">Event Info</h5>
        <div class="modal-body" id="modalBody">

        </div>
        <div class="mt-4 modal-footer">
            <button type="button" class="p-2 text-white bg-green-500 rounded" id="modalCloseButton">Close</button>
        </div>
    </div>
</div>

@endsection
