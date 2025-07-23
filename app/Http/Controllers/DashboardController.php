<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AwsLogger;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with comprehensive statistics
     */
    public function index()
    {
        // Get basic counts
        $totalDevices = Device::count();
        $totalAwsRecords = AwsLogger::count();
        $totalUsers = User::count();
        
        // Get active devices (devices with data in the last 60 minutes)
        $activeDevices = Device::whereHas('awsLogs', function ($query) {
            $query->where('terminal_time', '>=', Carbon::now()->subMinutes(60));
        })->count();
        
        // Get latest data entry
        $latestData = AwsLogger::with('device')
            ->orderBy('terminal_time', 'desc')
            ->first();
        
        // Get devices that are online (last data within 60 minutes)
        $onlineDevicesCount = AwsLogger::select('device_id')
            ->where('terminal_time', '>=', Carbon::now()->subMinutes(60))
            ->distinct()
            ->count();
        
        // Get today's records count
        $todayRecords = AwsLogger::whereDate('terminal_time', Carbon::today())->count();
        
        // Get this week's records count
        $thisWeekRecords = AwsLogger::where('terminal_time', '>=', Carbon::now()->startOfWeek())->count();
        
        // Get average temperature for today (if any records exist)
        $avgTempToday = AwsLogger::whereDate('terminal_time', Carbon::today())
            ->avg('temperature');
        
        // Get latest 5 devices with their last activity
        $recentDeviceActivity = AwsLogger::select('device_id', 
            DB::raw('MAX(terminal_time) as last_activity'),
            DB::raw('AVG(temperature) as avg_temp'),
            DB::raw('COUNT(*) as record_count'))
            ->groupBy('device_id')
            ->orderBy('last_activity', 'desc')
            ->limit(5)
            ->get();
        
        // Get hourly data for the last 24 hours for chart
        $hourlyData = AwsLogger::select(
            DB::raw('HOUR(terminal_time) as hour'),
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(temperature) as avg_temp')
        )
        ->where('terminal_time', '>=', Carbon::now()->subHours(24))
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();
        
        return view('dashboard', compact(
            'totalDevices',
            'totalAwsRecords',
            'totalUsers',
            'activeDevices',
            'onlineDevicesCount',
            'latestData',
            'todayRecords',
            'thisWeekRecords',
            'avgTempToday',
            'recentDeviceActivity',
            'hourlyData'
        ));
    }
    
    /**
     * Get dashboard data for AJAX requests
     */
    public function getData()
    {
        $stats = [
            'total_devices' => Device::count(),
            'total_records' => AwsLogger::count(),
            'total_users' => User::count(),
            'active_devices' => Device::whereHas('awsLogs', function ($query) {
                $query->where('terminal_time', '>=', Carbon::now()->subMinutes(60));
            })->count(),
            'online_devices' => AwsLogger::select('device_id')
                ->where('terminal_time', '>=', Carbon::now()->subMinutes(60))
                ->distinct()
                ->count(),
            'today_records' => AwsLogger::whereDate('terminal_time', Carbon::today())->count(),
            'latest_data' => AwsLogger::with('device')
                ->orderBy('terminal_time', 'desc')
                ->first()
        ];
        
        return response()->json($stats);
    }
}
