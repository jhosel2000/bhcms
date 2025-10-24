{{-- Appointment List Partial for AJAX Loading --}}
<div class="space-y-4">
    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" />
    @empty
        <x-appointment-empty-state :status="$status" />
    @endforelse
</div>
