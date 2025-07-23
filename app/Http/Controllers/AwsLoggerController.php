<?php

namespace App\Http\Controllers;

use App\Models\AwsLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AwsLoggerController extends Controller
{
    /**
     * Display AWS Logger dashboard
     */
    public function index(Request $request)
    {
        // Get summary statistics
        $stats = AwsLogger::getSummaryStats();
        
        // Build query for latest records with filters
        $latestQuery = AwsLogger::with('device')->orderBy('terminal_time', 'desc');
        
        // Apply device filter if specified
        if ($request->device_id) {
            $latestQuery->where('device_id', $request->device_id);
        }
        
        // Apply status filter
        if ($request->status == 'active') {
            $latestQuery->whereHas('device', function($q) {
                $q->where('status', 1);
            });
        } elseif ($request->status == 'online') {
            $latestQuery->whereHas('device', function($q) {
                $q->where('status', 1);
            })->where('terminal_time', '>=', now()->subHour());
        }
        
        // Apply time range filter
        if ($request->time_range == 'today') {
            $latestQuery->whereDate('terminal_time', today());
        } elseif ($request->time_range == 'week') {
            $latestQuery->where('terminal_time', '>=', now()->subWeek());
        } elseif ($request->time_range == 'month') {
            $latestQuery->where('terminal_time', '>=', now()->subMonth());
        }
        
        $latestRecords = $latestQuery->limit(8)->get();
        
        // Get devices from Device table (not AWS Logger) for filter
        $devices = \App\Models\Device::orderBy('code')->get();
        
        // Get data for charts (last 24 hours) with device info
        $chartQuery = AwsLogger::with('device')
            ->where('terminal_time', '>=', now()->subDay())
            ->orderBy('terminal_time');
            
        // Apply device filter to chart data if specified
        if ($request->device_id) {
            $chartQuery->where('device_id', $request->device_id);
        }
        
        $chartData = $chartQuery->get();
        
        return view('aws-logger.index', compact('stats', 'latestRecords', 'devices', 'chartData'));
    }

    /**
     * Show detailed view for specific device
     */
    public function show(Request $request, $deviceId)
    {
        // Get device from Device table first
        $deviceRecord = \App\Models\Device::where('code', $deviceId)->first();
        
        // Get AWS Logger data for this device
        $awsDevice = AwsLogger::where('device_id', $deviceId)->first();
        
        if (!$awsDevice) {
            return redirect()->route('aws-logger.index')->with('error', 'Device not found in AWS Logger data');
        }
        
        // Get device records with pagination for charts only
        $records = AwsLogger::where('device_id', $deviceId)
            ->orderBy('terminal_time', 'desc')
            ->take(100) // Limit for charts
            ->get();
        
        // Get device statistics
        $deviceStats = [
            'total_records' => AwsLogger::where('device_id', $deviceId)->count(),
            'records_today' => AwsLogger::where('device_id', $deviceId)->whereDate('terminal_time', today())->count(),
            'records_week' => AwsLogger::where('device_id', $deviceId)->where('terminal_time', '>=', now()->subWeek())->count(),
            'records_month' => AwsLogger::where('device_id', $deviceId)->where('terminal_time', '>=', now()->subMonth())->count(),
            'first_record' => AwsLogger::where('device_id', $deviceId)->orderBy('terminal_time')->first(),
            'last_record' => AwsLogger::where('device_id', $deviceId)->orderBy('terminal_time', 'desc')->first(),
            'avg_temperature' => AwsLogger::where('device_id', $deviceId)->avg('temperature'),
            'min_temperature' => AwsLogger::where('device_id', $deviceId)->min('temperature'),
            'max_temperature' => AwsLogger::where('device_id', $deviceId)->max('temperature'),
            'avg_humidity' => AwsLogger::where('device_id', $deviceId)->avg('humidity'),
            'avg_wind_speed' => AwsLogger::where('device_id', $deviceId)->avg('wind_speed'),
            'min_wind_speed' => AwsLogger::where('device_id', $deviceId)->min('wind_speed'),
            'max_wind_speed' => AwsLogger::where('device_id', $deviceId)->max('wind_speed'),
            'total_rainfall' => AwsLogger::where('device_id', $deviceId)->sum('rainfall'),
            'min_rainfall' => AwsLogger::where('device_id', $deviceId)->min('rainfall'),
            'max_rainfall' => AwsLogger::where('device_id', $deviceId)->max('rainfall'),
            'avg_solar_radiation' => AwsLogger::where('device_id', $deviceId)->avg('solar_radiation'),
            'min_solar_radiation' => AwsLogger::where('device_id', $deviceId)->min('solar_radiation'),
            'max_solar_radiation' => AwsLogger::where('device_id', $deviceId)->max('solar_radiation'),
        ];
        
        return view('aws-logger.show', compact('deviceRecord', 'awsDevice', 'records', 'deviceStats', 'deviceId'));
    }

    /**
     * Get DataTables data for server-side processing
     */
    public function getDataTablesData(Request $request, $deviceId)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $order = $request->get('order')[0] ?? ['column' => 0, 'dir' => 'desc'];
        $columns = ['terminal_time', 'temperature', 'humidity', 'wind_speed', 'wind_direction', 'pressure', 'rainfall', 'solar_radiation'];
        
        $query = AwsLogger::where('device_id', $deviceId);
        
        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('temperature', 'like', "%{$search}%")
                  ->orWhere('humidity', 'like', "%{$search}%")
                  ->orWhere('wind_speed', 'like', "%{$search}%")
                  ->orWhere('pressure', 'like', "%{$search}%")
                  ->orWhere('rainfall', 'like', "%{$search}%")
                  ->orWhere('solar_radiation', 'like', "%{$search}%");
            });
        }
        
        // Get total count
        $totalRecords = AwsLogger::where('device_id', $deviceId)->count();
        $filteredRecords = $query->count();
        
        // Apply ordering
        $orderColumn = $columns[$order['column']] ?? 'terminal_time';
        $orderDirection = $order['dir'] ?? 'desc';
        $query->orderBy($orderColumn, $orderDirection);
        
        // Apply pagination
        if ($length != -1) {
            $query->offset($start)->limit($length);
        }
        
        $data = $query->get()->map(function($record) {
            return [
                'terminal_time' => $record->terminal_time->format('M d, Y H:i') . '<br><small class="text-muted">' . $record->terminal_time->diffForHumans() . '</small>',
                'temperature' => '<span class="badge bg-warning-lt text-warning">' . $record->formatted_temperature . '</span>',
                'humidity' => '<span class="badge bg-info-lt text-info">' . $record->formatted_humidity . '</span>',
                'wind_speed' => $record->formatted_wind_speed,
                'wind_direction' => $record->wind_direction . '° <small class="text-muted">(' . $record->wind_direction_text . ')</small>',
                'pressure' => $record->formatted_pressure,
                'rainfall' => $record->rainfall > 0 ? '<span class="text-blue">' . $record->formatted_rainfall . '</span>' : '<span class="text-muted">0mm</span>',
                'solar_radiation' => $record->formatted_solar_radiation
            ];
        });
        
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get DataTables data for server-side processing (private method)
     */
    private function getDataTablesDataPrivate(Request $request, $deviceId)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0];
        $columns = ['terminal_time', 'temperature', 'humidity', 'wind_speed', 'wind_direction', 'pressure', 'rainfall', 'solar_radiation'];
        
        $query = AwsLogger::where('device_id', $deviceId);
        
        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('temperature', 'like', "%{$search}%")
                  ->orWhere('humidity', 'like', "%{$search}%")
                  ->orWhere('wind_speed', 'like', "%{$search}%")
                  ->orWhere('pressure', 'like', "%{$search}%")
                  ->orWhere('rainfall', 'like', "%{$search}%")
                  ->orWhere('solar_radiation', 'like', "%{$search}%");
            });
        }
        
        // Get total count
        $totalRecords = AwsLogger::where('device_id', $deviceId)->count();
        $filteredRecords = $query->count();
        
        // Apply ordering
        $orderColumn = $columns[$order['column']] ?? 'terminal_time';
        $orderDirection = $order['dir'] ?? 'desc';
        $query->orderBy($orderColumn, $orderDirection);
        
        // Apply pagination
        if ($length != -1) {
            $query->offset($start)->limit($length);
        }
        
        $data = $query->get()->map(function($record) {
            return [
                $record->terminal_time->format('Y-m-d H:i:s'),
                '<span class="badge bg-warning-lt text-warning">' . $record->formatted_temperature . '</span>',
                '<span class="badge bg-info-lt text-info">' . $record->formatted_humidity . '</span>',
                $record->formatted_wind_speed,
                $record->wind_direction . '°',
                $record->formatted_pressure,
                $record->formatted_rainfall,
                $record->formatted_solar_radiation
            ];
        });
        
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get data for AJAX requests
     */
    public function getData(Request $request)
    {
        $query = AwsLogger::query();
        
        // Filter by device if specified
        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }
        
        // Filter by date range if specified
        if ($request->date_from) {
            $query->where('terminal_time', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->where('terminal_time', '<=', $request->date_to);
        }
        
        $data = $query->orderBy('terminal_time', 'desc')
                     ->limit($request->limit ?? 50)
                     ->get();
        
        return response()->json($data);
    }

    /**
     * Get real-time data for dashboard updates
     */
    public function getRealTimeData()
    {
        $stats = AwsLogger::getSummaryStats();
        $latestRecords = AwsLogger::getLatestData(5);
        
        return response()->json([
            'stats' => $stats,
            'latest_records' => $latestRecords,
            'timestamp' => now()
        ]);
    }

    /**
     * Export data to CSV
     */
    public function export(Request $request)
    {
        $query = AwsLogger::query();
        
        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }
        
        if ($request->date_from) {
            $query->where('terminal_time', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->where('terminal_time', '<=', $request->date_to);
        }
        
        $data = $query->orderBy('terminal_time', 'desc')->get();
        
        $filename = 'aws_logger_data_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Terminal Time', 'Device ID', 'Location', 
                'Temperature (°C)', 'Humidity (%)', 'Pressure (hPa)',
                'Wind Speed (m/s)', 'Wind Direction', 'Rainfall (mm)',
                'Solar Radiation', 'PAR Sensor', 'Latitude', 'Longitude'
            ]);
            
            // Add data rows
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->terminal_time,
                    $row->device_id,
                    $row->device_location,
                    $row->temperature,
                    $row->humidity,
                    $row->pressure,
                    $row->wind_speed,
                    $row->wind_direction,
                    $row->rainfall,
                    $row->solar_radiation,
                    $row->par_sensor,
                    $row->lat,
                    $row->lng
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
