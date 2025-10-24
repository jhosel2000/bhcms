/**
 * Appointment Management JavaScript Module
 * Handles all appointment-related interactions for doctors
 */

// ============================================
// NOTIFICATION SYSTEM
// ============================================

/**
 * Show a toast notification
 * @param {string} message - The message to display
 * @param {string} type - Type of notification: 'success', 'error', 'info'
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    } text-white`;

    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto-dismiss after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ============================================
// APPOINTMENT ACTIONS
// ============================================

/**
 * Approve an appointment
 * @param {number} appointmentId - The ID of the appointment to approve
 */
function approveAppointment(appointmentId) {
    if (!confirm('Are you sure you want to approve this appointment?')) {
        return;
    }

    fetch(`/doctor/appointments/${appointmentId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Appointment approved successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to approve appointment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while approving the appointment', 'error');
    });
}

/**
 * Decline an appointment with optional reason
 * @param {number} appointmentId - The ID of the appointment to decline
 */
function declineAppointment(appointmentId) {
    const reason = prompt('Please provide a reason for declining this appointment (optional):');

    if (reason === null) {
        return; // User cancelled
    }

    fetch(`/doctor/appointments/${appointmentId}/decline`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Appointment declined', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to decline appointment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while declining the appointment', 'error');
    });
}

/**
 * Mark an appointment as complete
 * @param {number} appointmentId - The ID of the appointment to complete
 */
function completeAppointment(appointmentId) {
    if (!confirm('Mark this appointment as completed?')) {
        return;
    }

    fetch(`/doctor/appointments/${appointmentId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Appointment marked as completed!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to complete appointment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while completing the appointment', 'error');
    });
}

// ============================================
// VIEW MANAGEMENT
// ============================================

/**
 * Toggle between list and calendar views
 * @param {string} view - The view to display: 'list' or 'calendar'
 */
function toggleView(view) {
    const listView = document.getElementById('listView');
    const calendarView = document.getElementById('calendarView');
    const listBtn = document.getElementById('listViewBtn');
    const calendarBtn = document.getElementById('calendarViewBtn');

    if (view === 'list') {
        listView.classList.remove('hidden');
        calendarView.classList.add('hidden');
        listBtn.classList.remove('bg-gray-200', 'text-gray-700');
        listBtn.classList.add('bg-blue-600', 'text-white');
        calendarBtn.classList.remove('bg-blue-600', 'text-white');
        calendarBtn.classList.add('bg-gray-200', 'text-gray-700');
    } else {
        listView.classList.add('hidden');
        calendarView.classList.remove('hidden');
        calendarBtn.classList.remove('bg-gray-200', 'text-gray-700');
        calendarBtn.classList.add('bg-blue-600', 'text-white');
        listBtn.classList.remove('bg-blue-600', 'text-white');
        listBtn.classList.add('bg-gray-200', 'text-gray-700');

        // Render calendar when switching to calendar view
        renderCalendar();
    }
}

// ============================================
// FILTER MANAGEMENT
// ============================================

/**
 * Reset all filters and reload the page
 */
function resetFilters() {
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    window.location.href = `${window.location.pathname}?status=${currentStatus}`;
}

// ============================================
// EXPORT FUNCTIONALITY
// ============================================

/**
 * Export appointments based on current filters
 */
function exportAppointments() {
    const status = new URLSearchParams(window.location.search).get('status') || 'all';
    const search = document.querySelector('input[name="search"]')?.value || '';
    const dateFrom = document.querySelector('input[name="date_from"]')?.value || '';
    const dateTo = document.querySelector('input[name="date_to"]')?.value || '';
    const urgency = document.querySelector('select[name="urgency"]')?.value || '';

    let url = `/doctor/appointments/export?status=${status}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (dateFrom) url += `&date_from=${dateFrom}`;
    if (dateTo) url += `&date_to=${dateTo}`;
    if (urgency) url += `&urgency=${urgency}`;

    // For now, show notification - implement actual export in controller
    showNotification('Export functionality will download appointments as CSV/PDF', 'info');
    // Uncomment when backend is ready:
    // window.location.href = url;
}

// ============================================
// AUTO-REFRESH (Optional)
// ============================================

/**
 * Auto-refresh appointments every 30 seconds (optional)
 * Uncomment to enable
 */
// setInterval(() => {
//     location.reload();
// }, 30000);

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Appointment Management Module Loaded');
});
