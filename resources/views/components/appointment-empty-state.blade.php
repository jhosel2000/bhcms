@props(['status' => 'all'])

@php
    $messages = [
        'all' => 'No appointments found',
        'pending' => 'No pending appointments',
        'approved' => 'No approved appointments',
        'completed' => 'No completed appointments',
        'declined' => 'No declined appointments',
    ];

    $message = $messages[$status] ?? 'No appointments found';
@endphp

<div class="text-center py-12">
    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $message }}</h3>
    <p class="mt-2 text-sm text-gray-500">
        @if($status === 'pending')
            All appointment requests have been reviewed.
        @elseif($status === 'approved')
            No appointments are currently approved and waiting.
        @elseif($status === 'completed')
            No appointments have been completed yet.
        @else
            Try adjusting your filters or check back later.
        @endif
    </p>
</div>
