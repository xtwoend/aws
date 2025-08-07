<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AwsLogger;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AwsLoggerApiController extends Controller
{
    /**
     * Receive weather data from AWS Logger devices
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function receiveData(Request $request): JsonResponse
    {
        try {
            // Log incoming request for debugging
            Log::info('AWS Logger API data received', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => $request->all()
            ]);

            $data = $request->input('DataArray');
            $aws = $data[0];

            $aws['terminal_time'] = $aws['_terminalTime'];
            unset($aws['_terminalTime']);
            unset($aws['_groupTag']);

            // Validate the incoming data
            $validator = Validator::make($aws, [
                'terminal_time' => 'required|date',
                'device_id' => 'required|string|max:255',
                'device_location' => 'nullable|string|max:255',
                'wind_speed' => 'required|numeric|min:0|max:999.99',
                'wind_direction' => 'required|integer|min:0|max:360',
                'temperature' => 'required|numeric|min:-50|max:100',
                'humidity' => 'required|numeric|min:0|max:100',
                'pressure' => 'required|numeric|min:800|max:1200',
                'par_sensor' => 'nullable|numeric|min:0|max:9999.99',
                'rainfall' => 'required|numeric|min:0|max:999.99',
                'solar_radiation' => 'required|numeric|min:0|max:9999.99',
                'lat' => 'nullable|numeric|between:-90,90',
                'lng' => 'nullable|numeric|between:-180,180',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            // Convert terminal_time to proper format if needed
            if (isset($validatedData['terminal_time'])) {
                $validatedData['terminal_time'] = Carbon::parse($validatedData['terminal_time']);
            }

            // Set default values for optional fields
            $validatedData['par_sensor'] = $validatedData['par_sensor'] ?? 0;
            $validatedData['lat'] = $validatedData['lat'] ?? 0;
            $validatedData['lng'] = $validatedData['lng'] ?? 0;

            // Create or update the AWS Logger record
            $awsLogger = AwsLogger::create($validatedData);

            // Update device last seen if device exists
            $this->updateDeviceLastSeen($validatedData['device_id']);

            // Log successful data reception
            Log::info('AWS Logger data saved successfully', [
                'id' => $awsLogger->id,
                'device_id' => $awsLogger->device_id,
                'terminal_time' => $awsLogger->terminal_time
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data received and stored successfully',
                'data' => [
                    'id' => $awsLogger->id,
                    'device_id' => $awsLogger->device_id,
                    'terminal_time' => $awsLogger->terminal_time->toISOString(),
                    'created_at' => $awsLogger->created_at->toISOString()
                ]
            ], 201);

        } catch (\Exception $e) {
            // Log the error
            Log::error('AWS Logger API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error occurred while processing data',
                'error_code' => 'INTERNAL_ERROR'
            ], 500);
        }
    }

    /**
     * Receive bulk weather data from AWS Logger devices
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function receiveBulkData(Request $request): JsonResponse
    {
        try {
            // Validate that the data is an array
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1|max:100', // Limit to 100 records at once
                'data.*.terminal_time' => 'required|date',
                'data.*.device_id' => 'required|string|max:255',
                'data.*.device_location' => 'nullable|string|max:255',
                'data.*.wind_speed' => 'required|numeric|min:0|max:999.99',
                'data.*.wind_direction' => 'required|integer|min:0|max:360',
                'data.*.temperature' => 'required|numeric|min:-50|max:100',
                'data.*.humidity' => 'required|numeric|min:0|max:100',
                'data.*.pressure' => 'required|numeric|min:800|max:1200',
                'data.*.par_sensor' => 'nullable|numeric|min:0|max:9999.99',
                'data.*.rainfall' => 'required|numeric|min:0|max:999.99',
                'data.*.solar_radiation' => 'required|numeric|min:0|max:9999.99',
                'data.*.lat' => 'nullable|numeric|between:-90,90',
                'data.*.lng' => 'nullable|numeric|between:-180,180',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $dataArray = $request->input('data');
            $successCount = 0;
            $errors = [];
            $deviceIds = [];

            foreach ($dataArray as $index => $data) {
                try {
                    // Convert terminal_time to proper format
                    $data['terminal_time'] = Carbon::parse($data['terminal_time']);
                    
                    // Set default values for optional fields
                    $data['par_sensor'] = $data['par_sensor'] ?? 0;
                    $data['lat'] = $data['lat'] ?? 0;
                    $data['lng'] = $data['lng'] ?? 0;

                    // Create the AWS Logger record
                    $awsLogger = AwsLogger::create($data);
                    $successCount++;
                    
                    // Collect unique device IDs
                    if (!in_array($data['device_id'], $deviceIds)) {
                        $deviceIds[] = $data['device_id'];
                    }

                } catch (\Exception $e) {
                    $errors[] = [
                        'index' => $index,
                        'error' => $e->getMessage(),
                        'data' => $data
                    ];
                }
            }

            // Update device last seen for all devices
            foreach ($deviceIds as $deviceId) {
                $this->updateDeviceLastSeen($deviceId);
            }

            // Log bulk data reception
            Log::info('AWS Logger bulk data processed', [
                'total_received' => count($dataArray),
                'success_count' => $successCount,
                'error_count' => count($errors),
                'device_ids' => $deviceIds
            ]);

            $response = [
                'status' => 'success',
                'message' => "Bulk data processed: {$successCount} successful, " . count($errors) . " failed",
                'summary' => [
                    'total_received' => count($dataArray),
                    'success_count' => $successCount,
                    'error_count' => count($errors),
                    'devices_updated' => count($deviceIds)
                ]
            ];

            if (!empty($errors)) {
                $response['errors'] = $errors;
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('AWS Logger bulk API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error occurred while processing bulk data',
                'error_code' => 'BULK_INTERNAL_ERROR'
            ], 500);
        }
    }

    /**
     * Get device status and latest data
     * 
     * @param string $deviceId
     * @return JsonResponse
     */
    public function getDeviceStatus(string $deviceId): JsonResponse
    {
        try {
            // Get latest record for the device
            $latestRecord = AwsLogger::where('device_id', $deviceId)
                ->orderBy('terminal_time', 'desc')
                ->first();

            if (!$latestRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No data found for device',
                    'device_id' => $deviceId
                ], 404);
            }

            // Get device information if exists
            $device = Device::where('code', $deviceId)->first();

            // Calculate online status
            $minutesSinceLastData = $latestRecord->terminal_time->diffInMinutes(now());
            $isOnline = $minutesSinceLastData < 60;

            return response()->json([
                'status' => 'success',
                'device_id' => $deviceId,
                'device_info' => $device ? [
                    'name' => $device->name,
                    'location' => $device->location,
                    'type' => $device->type,
                    'status' => $device->status
                ] : null,
                'online_status' => [
                    'is_online' => $isOnline,
                    'last_seen' => $latestRecord->terminal_time->toISOString(),
                    'minutes_since_last_data' => $minutesSinceLastData
                ],
                'latest_data' => [
                    'terminal_time' => $latestRecord->terminal_time->toISOString(),
                    'temperature' => $latestRecord->temperature,
                    'humidity' => $latestRecord->humidity,
                    'wind_speed' => $latestRecord->wind_speed,
                    'wind_direction' => $latestRecord->wind_direction,
                    'pressure' => $latestRecord->pressure,
                    'rainfall' => $latestRecord->rainfall,
                    'solar_radiation' => $latestRecord->solar_radiation,
                    'location' => [
                        'lat' => $latestRecord->lat,
                        'lng' => $latestRecord->lng
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('AWS Logger device status API error', [
                'device_id' => $deviceId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving device status',
                'error_code' => 'DEVICE_STATUS_ERROR'
            ], 500);
        }
    }

    /**
     * Update device last seen timestamp
     * 
     * @param string $deviceId
     * @return void
     */
    private function updateDeviceLastSeen(string $deviceId): void
    {
        try {
            Device::where('code', $deviceId)->update([
                'last_seen' => now()
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to update device last seen', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
