import { indexMethods } from "../activity-logs/index.js"
import { sanitizeString } from '../utils/sanitizeString.js'
import { formatTanggalWaktuIndonesia } from '../utils/dateFormatterIndonesia.js'

function activityManager(config) {
  return {
    config: config,
    dataTable: null,

    ...indexMethods,

    // sanitize string
    sanitizeString(str) {
      return sanitizeString(str);
    },

    // format date to Indonesian format
    formatTanggalWaktuIndonesia(dateString) {
      return formatTanggalWaktuIndonesia(dateString);
    },

    // cleanup
    destroy() {
      this.destroyDataTable();
    },

    // destory datatable
    destroyDataTable() {
      if (this.dataTable) {
        this.dataTable.destroy();
        this.dataTable = null;
      }
    }

  }
}

window.activityManager = activityManager;