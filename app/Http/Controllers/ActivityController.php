<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityController extends Controller
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
      try {
        $data = $this->activityLogService->getAllLogs();
        return response()->json(['data' => $data]);
      } catch (\Exception $e) {
        return response()->json([
          'status' => 'error',
          'message' => 'Gagal mengambil data activity logs.'
        ], 500);
      }
    }
    return view('pages.activity-logs.index');
  }
}
