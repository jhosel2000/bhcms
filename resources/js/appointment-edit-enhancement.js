/**
 * Appointment Edit Enhancement
 * Integrates real-time availability checking with appointment edit forms
 */
document.addEventListener('DOMContentLoaded', function() {
    const availabilityService = new AppointmentAvailabilityService();

    // Initialize for different provider types
    initializeDoctorEditForm();
    initializeMidwifeEditForm();

    /**
     * Initialize Doctor Appointment Edit Form
     */
    function initializeDoctorEditForm() {
        const form = document.getElementById('doctor-appointment-edit-form');
        const dateInput = document.getElementById('appointment_date');
        const timeInput = document.getElementById('appointment_time');

        if (form && dateInput) {
            // Add event listener for date selection
            dateInput.addEventListener('change', function() {
                updateDoctorAvailability(dateInput.value);
            });

            // Add form submission validation
            form.addEventListener('submit', function(event) {
                return validateDoctorEditFormSubmission(event, dateInput.value, timeInput.value);
            });
        }
    }

    /**
     * Initialize Midwife Appointment Edit Form
     */
    function initializeMidwifeEditForm() {
        const form = document.getElementById('midwife-appointment-edit-form');
        const dateInput = document.getElementById('appointment_date');
        const timeInput = document.getElementById('appointment_time');

        if (form && dateInput) {
            // Add event listener for date selection
            dateInput.addEventListener('change', function() {
                updateMidwifeAvailability(dateInput.value);
            });

            // Add form submission validation
            form.addEventListener('submit', function(event) {
                return validateMidwifeEditFormSubmission(event, dateInput.value, timeInput.value);
            });
        }
    }

    /**
     * Update Doctor Availability Widget
     */
    function updateDoctorAvailability(date) {
        const widget = document.querySelector('.appointment-availability-widget[data-provider-type="doctor"]');
        if (widget && date) {
            widget.setAttribute('data-selected-date', date);

            // Trigger availability check
            document.dispatchEvent(new CustomEvent('dateSelected', {
                detail: {
                    providerId: widget.getAttribute('data-provider-id'),
                    providerType: 'doctor',
                    date: date
                }
            }));
        }
    }

    /**
     * Update Midwife Availability Widget
     */
    function updateMidwifeAvailability(date) {
        const widget = document.querySelector('.appointment-availability-widget[data-provider-type="midwife"]');
        if (widget && date) {
            widget.setAttribute('data-selected-date', date);

            // Trigger availability check
            document.dispatchEvent(new CustomEvent('dateSelected', {
                detail: {
                    providerId: widget.getAttribute('data-provider-id'),
                    providerType: 'midwife',
                    date: date
                }
            }));
        }
    }

    /**
     * Validate Doctor Edit Form Submission
     */
    async function validateDoctorEditFormSubmission(event, date, time) {
        if (!date) {
            event.preventDefault();
            alert('Please select an appointment date.');
            return false;
        }

        if (!time) {
            event.preventDefault();
            alert('Please select an appointment time.');
            return false;
        }

        // Check if the selected time is available
        try {
            const response = await availabilityService.checkSlotAvailability(
                '{{ auth()->user()->doctor->id }}',
                'doctor',
                date,
                time
            );

            if (!response.success || !response.available) {
                event.preventDefault();
                alert('The selected time slot is no longer available. Please select a different time.');
                return false;
            }
        } catch (error) {
            console.error('Error checking doctor availability:', error);
            // Allow submission if we can't check availability (fail-safe)
        }

        return true;
    }

    /**
     * Validate Midwife Edit Form Submission
     */
    async function validateMidwifeEditFormSubmission(event, date, time) {
        if (!date) {
            event.preventDefault();
            alert('Please select an appointment date.');
            return false;
        }

        if (!time) {
            event.preventDefault();
            alert('Please select an appointment time.');
            return false;
        }

        // Check if the selected time is available
        try {
            const response = await availabilityService.checkSlotAvailability(
                '{{ auth()->user()->midwife->id }}',
                'midwife',
                date,
                time
            );

            if (!response.success || !response.available) {
                event.preventDefault();
                alert('The selected time slot is no longer available. Please select a different time.');
                return false;
            }
        } catch (error) {
            console.error('Error checking midwife availability:', error);
            // Allow submission if we can't check availability (fail-safe)
        }

        return true;
    }

    /**
     * Handle time slot selection from availability widget
     */
    document.addEventListener('timeslotSelected', function(event) {
        const { time } = event.detail;
        const timeInput = document.getElementById('appointment_time');
        if (timeInput) {
            timeInput.value = time;
        }
    });

    /**
     * Add enhanced styling for edit forms
     */
    function addEditFormStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .edit-availability-section {
                margin-top: 1.5rem;
                padding: 1.5rem;
                background-color: #f8fafc;
                border-radius: 0.5rem;
                border: 2px solid #e2e8f0;
            }

            .edit-availability-section h3 {
                color: #1e293b;
                margin-bottom: 1rem;
                font-weight: 600;
            }

            .doctor-edit-availability {
                background-color: #eff6ff;
                border-color: #3b82f6;
            }

            .midwife-edit-availability {
                background-color: #fdf2f8;
                border-color: #ec4899;
            }

            .time-slot-button {
                transition: all 0.2s ease-in-out;
                margin: 0.25rem;
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

            .form-section {
                background-color: white;
                border-radius: 0.5rem;
                padding: 1.5rem;
                margin-bottom: 1rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }

            @media (max-width: 768px) {
                .form-grid {
                    grid-template-columns: 1fr;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Add edit form styles
    addEditFormStyles();
});
