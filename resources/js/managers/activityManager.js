import { indexMethods } from "../activity-logs"

function activityManager(config) {
  return {
    config: config,

    ...indexMethods,
  }
}

window.activityManager = activityManager;