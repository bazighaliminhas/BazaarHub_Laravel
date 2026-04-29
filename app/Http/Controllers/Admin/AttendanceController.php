<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected AttendanceService $service;

    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    // ✅ View all attendance logs
    public function index(Request $request)
    {
        $query = Attendance::query();

        // ── Filters ──
        if ($request->filled('emp_id')) {
            $query->where('device_user_id', $request->emp_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('punch_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('punch_time', '<=', $request->date_to);
        }

        $logs = $query->orderBy('punch_time', 'desc')->paginate(20)->withQueryString();

        // ── Stats ──
        $todayCount   = Attendance::whereDate('punch_time', today())->count();
        $presentCount = Attendance::whereDate('punch_time', today())
                            ->where('punch_type', 0)
                            ->count();
        $lateCount    = Attendance::whereDate('punch_time', today())
                            ->where('punch_type', 0)
                            ->whereRaw('HOUR(punch_time) > 9')
                            ->count();
        $totalRecords = Attendance::count();
        $monthCount   = Attendance::whereMonth('punch_time', now()->month)
                            ->whereYear('punch_time', now()->year)
                            ->count();

        // Absent = total unique employees - present today
        $totalEmployees = Attendance::distinct('device_user_id')->count('device_user_id');
        $absentCount    = max(0, $totalEmployees - $presentCount);

        return view('admin.attendance.index', compact(
            'logs',
            'todayCount',
            'presentCount',
            'absentCount',
            'lateCount',
            'totalRecords',
            'monthCount'
        ));
    }

    // ✅ Sync from UFace800 or Mock
    public function sync()
    {
        $result = $this->service->syncAttendance();

        return redirect()->route('admin.attendance.index')
                         ->with('success', "✅ Sync complete! {$result['saved']} new records saved out of {$result['total']} total.");
    }

    // ✅ Daily Report
    public function report()
    {
        $logs = Attendance::selectRaw('
                    device_user_id,
                    employee_name,
                    DATE(punch_time) as date,
                    MIN(punch_time) as check_in,
                    MAX(punch_time) as check_out
                ')
                ->groupBy('device_user_id', 'employee_name', 'date')
                ->orderBy('date', 'desc')
                ->paginate(20);

        // ── Report Stats ──
        $totalDays      = Attendance::selectRaw('DATE(punch_time) as date')->distinct()->count();
        $totalEmployees = Attendance::distinct('device_user_id')->count('device_user_id');
        $thisMonthDays  = Attendance::selectRaw('DATE(punch_time) as date')
                             ->whereMonth('punch_time', now()->month)
                             ->distinct()
                             ->count();

        return view('admin.attendance.report', compact(
            'logs',
            'totalDays',
            'totalEmployees',
            'thisMonthDays'
        ));
    }

    // ✅ Delete a record
    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();

        return back()->with('success', '🗑️ Attendance record deleted!');
    }
}