import { calendar } from '../dashboard/calendar.js';
import { statusStatistics, employeeAssignmentByRank } from '../dashboard/chartDasboard.js';

function dashboardManager() {
  return {
    init() {
      calendar.init();
      statusStatistics.init();
      employeeAssignmentByRank.init();
    }
  };
}

window.dashboardManager = dashboardManager;