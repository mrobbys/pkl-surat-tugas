import './bootstrap';
import 'remixicon/fonts/remixicon.css'

import Choices from "choices.js";
window.Choices = Choices;
import "choices.js/public/assets/styles/choices.css";

import Chart from 'chart.js/auto'
window.Chart = Chart;

import flatpickr from "flatpickr";
import "flatpickr/dist/themes/material_blue.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
flatpickr.localize(Indonesian);
window.flatpickr = flatpickr;

import swal from 'sweetalert2';
window.Swal = swal;

import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
window.tippy = tippy;

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';
window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.listPlugin = listPlugin;

// Import semua file di folder managers secara otomatis
import.meta.glob('./managers/*.js', { eager: true });

import Alpine from 'alpinejs';
window.Alpine = Alpine;
import focus from '@alpinejs/focus'
Alpine.plugin(focus)
Alpine.start();

