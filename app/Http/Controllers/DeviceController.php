<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\AwsLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * Display device management dashboard
     */
    public function index(Request $request)
    {
        $query = Device::with(['latestLog']);
        
        // Filter by status if specified
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by type if specified
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }
        
        // Search by name, code, or location
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $devices = $query->orderBy('code')->paginate(15);
        
        // Get summary statistics
        $stats = [
            'total_devices' => Device::count(),
            'active_devices' => Device::active()->count(),
            'online_devices' => Device::online()->count(),
            'device_types' => Device::getTypes()->count(),
            'total_logs_today' => AwsLogger::whereDate('terminal_time', today())->count(),
        ];
        
        $deviceTypes = Device::getTypes();
        
        return view('devices.index', compact('devices', 'stats', 'deviceTypes'));
    }

    /**
     * Show the form for creating a new device
     */
    public function create()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'You do not have permission to create devices.');
        }

        return view('devices.create');
    }

    /**
     * Store a newly created device
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'You do not have permission to create devices.');
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:devices',
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:devices',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Device::create($request->all());

        return redirect()->route('devices.index')
            ->with('success', 'Device created successfully');
    }

    /**
     * Display the specified device
     */
    public function show(Device $device)
    {
        $device->load(['latestLog']);
        
        // Get device statistics
        $stats = $device->stats;
        
        // Get recent logs (last 50 entries)
        $recentLogs = $device->awsLogs()
            ->orderBy('terminal_time', 'desc')
            ->limit(50)
            ->get();
        
        // Get chart data (last 24 hours)
        $chartData = $device->awsLogs()
            ->where('terminal_time', '>=', now()->subDay())
            ->orderBy('terminal_time')
            ->get();
        
        return view('devices.show', compact('device', 'stats', 'recentLogs', 'chartData'));
    }

    /**
     * Show the form for editing the device
     */
    public function edit(Device $device)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'You do not have permission to edit devices.');
        }

        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified device
     */
    public function update(Request $request, Device $device)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'You do not have permission to update devices.');
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:devices,code,' . $device->id,
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:devices,serial_number,' . $device->id,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $device->update($request->all());

        return redirect()->route('devices.index')
            ->with('success', 'Device updated successfully');
    }

    /**
     * Remove the specified device
     */
    public function destroy(Device $device)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('devices.index')
                ->with('error', 'You do not have permission to delete devices.');
        }

        // Check if device has AWS logs
        if ($device->awsLogs()->exists()) {
            return redirect()->route('devices.index')
                ->with('error', 'Cannot delete device that has AWS logger data. Please remove logs first.');
        }
        
        $device->delete();

        return redirect()->route('devices.index')
            ->with('success', 'Device deleted successfully');
    }

    /**
     * Toggle device status
     */
    public function toggleStatus(Device $device)
    {
        $device->update(['status' => !$device->status]);
        
        $status = $device->status ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Device {$status} successfully",
            'status' => $device->status,
            'status_text' => $device->status_text,
            'status_color' => $device->status_color
        ]);
    }

    /**
     * Get device data for AJAX
     */
    public function getData(Request $request)
    {
        $query = Device::with(['latestLog']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('online_only') && $request->online_only) {
            $query->online();
        }
        
        $devices = $query->orderBy('code')->get();
        
        return response()->json([
            'data' => $devices,
            'count' => $devices->count()
        ]);
    }

    /**
     * Sync devices with AWS Logger data
     */
    public function syncWithAwsLogger()
    {
        // Get all unique device IDs from AWS Logger
        $awsDeviceIds = AwsLogger::distinct('device_id')->pluck('device_id');
        
        $synced = 0;
        
        foreach ($awsDeviceIds as $deviceId) {
            // Check if device already exists
            $device = Device::where('code', $deviceId)->first();
            
            if (!$device) {
                // Get sample data from AWS Logger for this device
                $sampleLog = AwsLogger::where('device_id', $deviceId)->first();
                
                Device::create([
                    'code' => $deviceId,
                    'name' => "AWS Station {$deviceId}",
                    'location' => $sampleLog->device_location ?? 'Unknown',
                    'type' => 'Weather Station',
                    'status' => 1,
                    'serial_number' => null
                ]);
                
                $synced++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$synced} devices synced successfully",
            'synced_count' => $synced
        ]);
    }

    /**
     * Export devices to CSV
     */
    public function export(Request $request)
    {
        $query = Device::with(['latestLog']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        $devices = $query->get();
        
        $filename = 'devices_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($devices) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Code', 'Name', 'Location', 'Type', 'Status', 'Serial Number',
                'Online Status', 'Last Data', 'Total Logs', 'Avg Temperature',
                'Avg Humidity', 'Max Wind Speed', 'Total Rainfall'
            ]);
            
            // Add data rows
            foreach ($devices as $device) {
                $stats = $device->stats;
                
                fputcsv($file, [
                    $device->code,
                    $device->name,
                    $device->location,
                    $device->type,
                    $device->status_text,
                    $device->serial_number,
                    $device->online_status_text,
                    $device->latestLog ? $device->latestLog->terminal_time : 'Never',
                    $stats['total_logs'] ?? 0,
                    $stats['avg_temperature'] ? number_format($stats['avg_temperature'], 2) : 'N/A',
                    $stats['avg_humidity'] ? number_format($stats['avg_humidity'], 2) : 'N/A',
                    $stats['max_wind_speed'] ? number_format($stats['max_wind_speed'], 2) : 'N/A',
                    $stats['total_rainfall'] ? number_format($stats['total_rainfall'], 2) : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
