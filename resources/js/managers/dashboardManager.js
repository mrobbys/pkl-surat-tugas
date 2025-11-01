function dashboardManager() {
  return {
    init() {
      let calendarEl = document.getElementById('calendar');
      let calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        locale: 'id',
        initialView: 'dayGridMonth',
        height: 650,
        eventDidMount: function (info) {
          tippy(info.el, {
            content: info.event.title,
            placement: 'top',
            arrow: true,
            theme: 'light',
          });
        },
        events: '/dashboard/calendar'
      });
      calendar.render();
    }
  };
}

window.dashboardManager = dashboardManager;