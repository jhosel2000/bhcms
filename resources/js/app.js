import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// FullCalendar imports
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

// Make FullCalendar globally available
window.FullCalendar = {
    Calendar,
    dayGridPlugin,
    timeGridPlugin,
    listPlugin
};

// Import appointment booking enhancements
import './appointment-availability-fixed';
import './patient-appointment-booking';
import './provider-appointment-booking';
import './appointment-edit-enhancement';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();
