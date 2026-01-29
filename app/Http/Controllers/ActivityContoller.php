<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityContoller extends Controller
{
  // service activity log
  protected $activityLogService;

  // inject service via constructor
  public function __construct(ActivityLogService $activityLogService)
  {
    $this->activityLogService = $activityLogService;
  }

  public function index(Request $request)
  {
    if ($request->ajax()) {
      $data = $this->activityLogService->getAllLogs();
      
      return response()->json(['data' => $data]);
    }
    return view('pages.activity-logs.index');
  }
}
