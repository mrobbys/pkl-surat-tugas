// config fullcalendar
const MOBILE_BREAKPOINT = 768;

const headerConfig = {
  desktop: {
    left: 'title',
    center: '',
    right: 'today prev,next'
  },
  mobile: {
    left: 'title',
    center: '',
    right: 'prev,today,next'
  }
};

export const calendar = {
  instance: null,

  init: function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

    this.instance = new Calendar(calendarEl, {
      plugins: [dayGridPlugin, listPlugin],
      locale: 'id',
      noEventsText: 'Tidak ada jadwal perjalanan dinas',
      initialView: isMobile ? 'listMonth' : 'dayGridMonth',
      headerToolbar: isMobile ? headerConfig.mobile : headerConfig.desktop,
      handleWindowResize: true,
      windowResize: this.handleResize.bind(this),
      height: 'auto',
      eventClick: this.handleEventClick,
      eventDidMount: this.handleEventMount,
      events: '/dashboard/calendar',
      // Error handling
      eventSourceFailure: function (error) {
        console.error('Failed to load calendar events:', error);
      }
    });

    this.instance.render();
  },

  handleResize: function () {
    if (!this.instance) return;

    const isMobile = window.innerWidth < MOBILE_BREAKPOINT;
    const newView = isMobile ? 'listMonth' : 'dayGridMonth';
    const newHeader = isMobile ? headerConfig.mobile : headerConfig.desktop;

    // Hanya update jika view berbeda (optimasi)
    if (this.instance.view.type !== newView) {
      this.instance.changeView(newView);
      this.instance.setOption('headerToolbar', newHeader);
    }
  },

  handleEventMount: function(info) {
    tippy(info.el, {
      content: `<div style="text-align:center">Klik untuk lihat detail surat<br>${info.event.title}</div>`,
      placement: 'top',
      allowHTML: true,
      arrow: true,
      theme: 'light',
    });
  },

  handleEventClick: function(info) {
    info.jsEvent.preventDefault();

    // redirect ke detail surat
    if (info.event.url) {
      window.location.href = info.event.url;
    }
  },
};