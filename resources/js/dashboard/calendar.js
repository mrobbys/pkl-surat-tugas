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
  instanceDesktop: null,
  instanceMobile: null,

  init: function () {
    this.initDesktopCalendar();
    this.initMobileCalendar();
  },

  // Inisialisasi kalender untuk tampilan desktop
  initDesktopCalendar: function () {
    const calendarEl = document.getElementById('calendar-desktop');
    if (!calendarEl) return;

    this.instanceDesktop = new Calendar(calendarEl, {
      plugins: [dayGridPlugin, listPlugin],
      locale: 'id',
      noEventsText: 'Tidak ada jadwal perjalanan dinas',
      initialView: 'dayGridMonth',
      headerToolbar: headerConfig.desktop,
      height: 'auto',
      eventClick: this.handleEventClick,
      eventDidMount: this.handleEventMount,
      events: '/dashboard/calendar',
      eventSourceFailure: function (error) {
        console.error('Failed to load calendar events:', error);
      }
    });

    this.instanceDesktop.render();
  },

  // Inisialisasi kalender untuk tampilan mobile
  initMobileCalendar: function () {
    const calendarEl = document.getElementById('calendar-mobile');
    if (!calendarEl) return;

    const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

    this.instanceMobile = new Calendar(calendarEl, {
      plugins: [dayGridPlugin, listPlugin],
      locale: 'id',
      noEventsText: 'Tidak ada jadwal perjalanan dinas',
      initialView: isMobile ? 'listMonth' : 'dayGridMonth',
      headerToolbar: isMobile ? headerConfig.mobile : headerConfig.desktop,
      handleWindowResize: true,
      windowResize: this.handleResize.bind(this),
      height: 'auto',
      eventClick: this.handleEventClick,
      events: '/dashboard/calendar',
      eventSourceFailure: function (error) {
        console.error('Failed to load calendar events:', error);
      }
    });

    this.instanceMobile.render();
  },

  handleResize: function () {
    if (!this.instanceMobile) return;

    const isMobile = window.innerWidth < MOBILE_BREAKPOINT;
    const newView = isMobile ? 'listMonth' : 'dayGridMonth';
    const newHeader = isMobile ? headerConfig.mobile : headerConfig.desktop;

    if (this.instanceMobile.view.type !== newView) {
      this.instanceMobile.changeView(newView);
      this.instanceMobile.setOption('headerToolbar', newHeader);
    }
  },

  handleEventMount: function (info) {
    const safeTitle = info.event.title.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    
    tippy(info.el, {
      content: `<div style="text-align:center">Klik untuk lihat detail<br>Surat Tugas:${safeTitle}</div>`,
      placement: 'top',
      allowHTML: true,
      arrow: true,
      theme: 'light',
    });
  },

  handleEventClick: function (info) {
    info.jsEvent.preventDefault();

    if (info.event.url && info.event.url.startsWith(window.location.origin)) {
      window.location.href = info.event.url;
    }
  },

  destroy: function () {
    if (this.instanceDesktop) {
      this.instanceDesktop.destroy();
      this.instanceDesktop = null;
    }
    if (this.instanceMobile) {
      this.instanceMobile.destroy();
      this.instanceMobile = null;
    }
  }
};