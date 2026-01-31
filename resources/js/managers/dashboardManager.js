import { calendar } from '../dashboard/calendar.js';
import { statusStatistics, employeeAssignmentByRank, intensityStatistics } from '../dashboard/chartDasboard.js';

function dashboardManager() {
  return {
    init() {
      calendar.init();
      statusStatistics.init();
      employeeAssignmentByRank.init();
      intensityStatistics.init();
    }
  };
}

window.dashboardManager = dashboardManager;