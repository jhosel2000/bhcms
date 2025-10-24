@props(['providerId' => null, 'providerType' => null, 'selectedDate' => null])

<div class="appointment-availability-widget" data-provider-id="{{ $providerId }}" data-provider-type="{{ $providerType }}">
    @if($providerId && $providerType && $selectedDate)
        <div class="availability-status mb-3">
            <div class="d-flex align-items-center">
                <div class="spinner-border spinner-border-sm me-2" role="status" id="availability-loading">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span id="availability-status">Checking availability...</span>
            </div>
        </div>

        <div class="available-slots" id="available-slots">
            <!-- Available time slots will be populated here -->
        </div>

        <div class="alert alert-info mt-3" id="no-slots-message" style="display: none;">
            <i class="fas fa-info-circle me-2"></i>
            No available time slots found for the selected date.
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Please select a healthcare provider and date to check availability.
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const availabilityService = new AppointmentAvailabilityService();

    // Initialize availability checking for the widget
    const widget = document.querySelector('.appointment-availability-widget');
    if (widget) {
        const providerId = widget.dataset.providerId;
        const providerType = widget.dataset.providerType;
        const selectedDate = widget.dataset.selectedDate || '{{ $selectedDate }}';

        if (providerId && providerType && selectedDate) {
            checkAvailability(providerId, providerType, selectedDate);
        }
    }

    // Function to check availability
    async function checkAvailability(providerId, providerType, date) {
        const loadingElement = document.getElementById('availability-loading');
        const statusElement = document.getElementById('availability-status');
        const slotsContainer = document.getElementById('available-slots');
        const noSlotsMessage = document.getElementById('no-slots-message');

        // Show loading state
        loadingElement.style.display = 'block';
        statusElement.textContent = 'Checking availability...';
        slotsContainer.innerHTML = '';
        noSlotsMessage.style.display = 'none';

        try {
            let response;
            switch (providerType) {
                case 'doctor':
                    response = await availabilityService.getDoctorSlots(providerId, date);
                    break;
                case 'midwife':
                    response = await availabilityService.getMidwifeSlots(providerId, date);
                    break;
                case 'bhw':
                    response = await availabilityService.getBHWSlots(providerId, date);
                    break;
                default:
                    throw new Error('Invalid provider type');
            }

            if (response.success && response.data) {
                const { available_slots, schedule, existing_appointments_count } = response.data;

                // Update status
                statusElement.textContent = `Found ${available_slots.length} available slots (${existing_appointments_count} existing appointments)`;

                if (available_slots.length > 0) {
                    // Create time slot buttons
                    available_slots.forEach(slot => {
                        const slotButton = document.createElement('button');
                        slotButton.type = 'button';
                        slotButton.className = 'btn btn-outline-primary btn-sm me-2 mb-2 slot-available';
                        slotButton.textContent = `${slot.start_time} - ${slot.end_time}`;
                        slotButton.onclick = function() {
                            selectTimeSlot(slot.start_time);
                        };

                        slotsContainer.appendChild(slotButton);
                    });
                } else {
                    noSlotsMessage.style.display = 'block';
                }
            } else {
                statusElement.textContent = 'Unable to check availability';
                noSlotsMessage.style.display = 'block';
            }

        } catch (error) {
            console.error('Error checking availability:', error);
            statusElement.textContent = 'Error checking availability';
            noSlotsMessage.style.display = 'block';
        } finally {
            loadingElement.style.display = 'none';
        }
    }

    // Function to handle time slot selection
    function selectTimeSlot(time) {
        // Remove selected class from all slots
        document.querySelectorAll('.slot-available').forEach(slot => {
            slot.classList.remove('btn-primary');
            slot.classList.add('btn-outline-primary');
        });

        // Add selected class to clicked slot
        event.target.classList.remove('btn-outline-primary');
        event.target.classList.add('btn-primary');

        // Update hidden input if exists
        const timeInput = document.getElementById('appointment_time');
        if (timeInput) {
            timeInput.value = time;
        }

        // Trigger custom event for other scripts
        document.dispatchEvent(new CustomEvent('timeslotSelected', {
            detail: { time: time }
        }));
    }

    // Listen for date changes to update availability
    document.addEventListener('dateSelected', function(event) {
        const { providerId, providerType, date } = event.detail;
        if (providerId && providerType && date) {
            checkAvailability(providerId, providerType, date);
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.appointment-availability-widget {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
}

.slot-available {
    transition: all 0.2s ease-in-out;
}

.slot-available:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.slot-loading {
    position: relative;
    overflow: hidden;
}

.slot-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.availability-status {
    min-height: 2rem;
}

#availability-loading {
    width: 1rem;
    height: 1rem;
}
</style>
@endpush
