/**
 * Patient Appointment Booking Enhancement
 * Integrates real-time availability checking with the booking form
 */
document.addEventListener('DOMContentLoaded', function() {
    const availabilityService = new AppointmentAvailabilityService();

    // Get form elements
    const appointmentForm = document.getElementById('appointment-form');
    const appointmentDate = document.getElementById('appointment_date');
    const appointmentTime = document.getElementById('appointment_time');
    const doctorSelect = document.getElementById('doctor_id');
    const midwifeSelect = document.getElementById('midwife_id');
    const bhwSelect = document.getElementById('bhw_id');

    // Initialize form validation
    if (appointmentForm) {
        initializeFormValidation();
    }

    /**
     * Initialize form validation and availability checking
     */
    function initializeFormValidation() {
        // Add event listeners for provider selection
        [doctorSelect, midwifeSelect, bhwSelect].forEach(select => {
            if (select) {
                select.addEventListener('change', handleProviderSelection);
            }
        });

        // Add event listener for date selection
        if (appointmentDate) {
            appointmentDate.addEventListener('change', handleDateSelection);
        }

        // Add form submission validation
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', validateFormSubmission);
        }
    }

    /**
     * Handle provider selection change
     */
    function handleProviderSelection(event) {
        const selectedProvider = getSelectedProvider();

        if (selectedProvider && appointmentDate.value) {
            updateAvailabilityWidget(selectedProvider.id, selectedProvider.type, appointmentDate.value);
        }
    }

    /**
     * Handle date selection change
     */
    function handleDateSelection(event) {
        const selectedProvider = getSelectedProvider();

        if (selectedProvider && appointmentDate.value) {
            updateAvailabilityWidget(selectedProvider.id, selectedProvider.type, appointmentDate.value);
        }
    }

    /**
     * Get the currently selected provider
     */
    function getSelectedProvider() {
        if (doctorSelect && doctorSelect.value) {
            return { id: doctorSelect.value, type: 'doctor' };
        }

        if (midwifeSelect && midwifeSelect.value) {
            return { id: midwifeSelect.value, type: 'midwife' };
        }

        if (bhwSelect && bhwSelect.value) {
            return { id: bhwSelect.value, type: 'bhw' };
        }

        return null;
    }

    /**
     * Update the availability widget with new data
     */
    function updateAvailabilityWidget(providerId, providerType, date) {
        const widget = document.querySelector('.appointment-availability-widget');
        if (widget) {
            // Update widget data attributes
            widget.setAttribute('data-provider-id', providerId);
            widget.setAttribute('data-provider-type', providerType);
            widget.setAttribute('data-selected-date', date);

            // Trigger availability check by dispatching custom event
            document.dispatchEvent(new CustomEvent('dateSelected', {
                detail: {
                    providerId: providerId,
                    providerType: providerType,
                    date: date
                }
            }));
        }
    }

    /**
     * Validate form before submission
     */
    function validateFormSubmission(event) {
        const selectedProvider = getSelectedProvider();

        if (!selectedProvider) {
            event.preventDefault();
            alert('Please select a healthcare provider.');
            return false;
        }

        if (!appointmentDate.value) {
            event.preventDefault();
            alert('Please select an appointment date.');
            return false;
        }

        if (!appointmentTime.value) {
            event.preventDefault();
            alert('Please select an appointment time.');
            return false;
        }

        // Check if the selected time is actually available
        const isTimeAvailable = checkTimeAvailability(selectedProvider, appointmentDate.value, appointmentTime.value);

        if (!isTimeAvailable) {
            event.preventDefault();
            alert('The selected time slot is no longer available. Please select a different time.');
            return false;
        }

        return true;
    }

    /**
     * Check if a specific time slot is available
     */
    async function checkTimeAvailability(provider, date, time) {
        try {
            const response = await availabilityService.checkSlotAvailability(
                provider.id,
                provider.type,
                date,
                time
            );

            return response.success && response.available;
        } catch (error) {
            console.error('Error checking time availability:', error);
            // Allow submission if we can't check availability (fail-safe)
            return true;
        }
    }

    /**
     * Handle time slot selection from availability widget
     */
    document.addEventListener('timeslotSelected', function(event) {
        const { time } = event.detail;
        if (appointmentTime) {
            appointmentTime.value = time;
        }
    });

    /**
     * Add visual feedback for form validation
     */
    function addFormValidationStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .form-group {
                margin-bottom: 1rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
            }

            .provider-selection {
                border: 2px solid #e5e7eb;
                border-radius: 0.375rem;
                padding: 1rem;
                background-color: #f9fafb;
            }

            .provider-selection select {
                margin-bottom: 0.5rem;
            }

            .availability-section {
                margin-top: 1.5rem;
                padding: 1rem;
                background-color: #f0f9ff;
                border-radius: 0.375rem;
                border: 1px solid #0ea5e9;
            }

            .availability-section h3 {
                color: #0c4a6e;
                margin-bottom: 1rem;
            }

            .time-slot-button {
                transition: all 0.2s ease-in-out;
            }

            .time-slot-button:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .time-slot-button.available {
                background-color: #10b981;
                color: white;
            }

            .time-slot-button.available:hover {
                background-color: #059669;
            }

            .time-slot-button.unavailable {
                background-color: #ef4444;
                color: white;
                opacity: 0.6;
                cursor: not-allowed;
            }

            .loading-spinner {
                display: inline-block;
                width: 1rem;
                height: 1rem;
                border: 2px solid #f3f3f3;
                border-top: 2px solid #3498db;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }

    // Add validation styles
    addFormValidationStyles();
});
