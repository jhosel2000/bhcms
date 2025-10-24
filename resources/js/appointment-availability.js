/**
 * Appointment Availability Service
 * Handles real-time availability checking for healthcare providers
 */
class AppointmentAvailabilityService {
    constructor() {
        this.baseUrl = '/api/availability';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Check if a specific time slot is available for a provider
     */
    async checkSlotAvailability(providerId, providerType, date, time, durationMinutes = 30) {
        try {
            const response = await fetch(`${this.baseUrl}/check-slot`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error checking slot availability:', error);
            return {
                success: false,
                available: false,
                message: 'Unable to check availability. Please try again.'
            };
        }
    }

    /**
     * Get available time slots for a doctor on a specific date
     */
    async getDoctorSlots(doctorId, date) {
        try {
            const response = await fetch(`${this.baseUrl}/doctor/${doctorId}/slots?date=${date}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error getting doctor slots:', error);
            return {
                success: false,
                data: { available_slots: [] }
            };
        }
    }

    /**
     * Get available time slots for a midwife on a specific date
     */
    async getMidwifeSlots(midwifeId, date) {
        try {
            const response = await fetch(`${this.baseUrl}/midwife/${midwifeId}/slots?date=${date}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error getting midwife slots:', error);
            return {
                success: false,
                data: { available_slots: [] }
            };
        }
    }

    /**
     * Get available time slots for a BHW on a specific date
     */
    async getBHWSlots(bhwId, date) {
        try {
            const response = await fetch(`${this.baseUrl}/bhw/${bhwId}/slots?date=${date}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error getting BHW slots:', error);
            return {
                success: false,
                data: { available_slots: [] }
            };
        }
    }

    /**
     * Get provider's schedule for a specific date
     */
    async getProviderSchedule(providerId, providerType, date) {
        try {
            const response = await fetch(`${this.baseUrl}/provider-schedule?provider_id=${providerId}&provider_type=${providerType}&date=${date}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error getting provider schedule:', error);
            return {
                success: false,
                data: null
            };
        }
    }

    /**
     * Update time slot UI based on availability
     */
    updateSlotUI(slotElement, isAvailable, conflicts = []) {
        if (isAvailable) {
            slotElement.classList.remove('slot-unavailable', 'slot-conflict');
            slotElement.classList.add('slot-available');
            slotElement.style.opacity = '1';
            slotElement.style.pointerEvents = 'auto';
        } else {
            slotElement.classList.remove('slot-available');
            slotElement.classList.add('slot-unavailable');
            slotElement.style.opacity = '0.5';
            slotElement.style.pointerEvents = 'none';

            if (conflicts.length > 0) {
                slotElement.classList.add('slot-conflict');
                slotElement.title = `Conflicts: ${conflicts.map(c => c.patient_name).join(', ')}`;
            }
        }
    }

    /**
     * Show loading state for time slots
     */
    showSlotLoading(slotElement) {
        slotElement.classList.add('slot-loading');
        slotElement.style.opacity = '0.7';
        slotElement.style.pointerEvents = 'none';
    }

    /**
     * Hide loading state for time slots
     */
    hideSlotLoading(slotElement) {
        slotElement.classList.remove('slot-loading');
    }
}

// Export for use in other modules
window.AppointmentAvailabilityService = AppointmentAvailabilityService;
