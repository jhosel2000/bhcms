document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('appointment-calendar');

    if (calendarEl) {
        var calendar = new window.FullCalendar.Calendar(calendarEl, {
            plugins: [window.FullCalendar.dayGridPlugin, window.FullCalendar.timeGridPlugin, window.FullCalendar.listPlugin],
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '/api/doctor/appointments/calendar', // API endpoint to fetch appointments in calendar format
            editable: true,
            droppable: false,
            eventDrop: function(info) {
                // Handle drag-and-drop rescheduling
                var appointmentId = info.event.id;
                var newDate = info.event.start.toISOString();

                fetch('/api/doctor/appointments/' + appointmentId + '/reschedule', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        appointment_date: newDate
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to reschedule appointment');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Appointment rescheduled successfully');
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                    info.revert();
                });
            },
            eventClick: function(info) {
                // Show appointment details in modal
                const appointmentData = {
                    id: info.event.id,
                    patient: info.event.title,
                    status: info.event.extendedProps.status,
                    date: info.event.start.toLocaleDateString(),
                    time: info.event.extendedProps.time,
                    reason: info.event.extendedProps.reason,
                    notes: info.event.extendedProps.notes
                };
                showAppointmentDetails(appointmentData);
            }
        });

        calendar.render();
    }
});
