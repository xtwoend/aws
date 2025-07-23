<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AwsLoggerDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For now, allow all requests. In production, you might want to add API key validation
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'terminal_time' => [
                'required',
                'date',
                'before_or_equal:now'
            ],
            'device_id' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9_-]+$/' // Only alphanumeric, underscore, and dash
            ],
            'device_location' => [
                'nullable',
                'string',
                'max:255'
            ],
            'wind_speed' => [
                'required',
                'numeric',
                'min:0',
                'max:999.99'
            ],
            'wind_direction' => [
                'required',
                'integer',
                'min:0',
                'max:360'
            ],
            'temperature' => [
                'required',
                'numeric',
                'min:-50',
                'max:100'
            ],
            'humidity' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
            'pressure' => [
                'required',
                'numeric',
                'min:800',
                'max:1200'
            ],
            'par_sensor' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999.99'
            ],
            'rainfall' => [
                'required',
                'numeric',
                'min:0',
                'max:999.99'
            ],
            'solar_radiation' => [
                'required',
                'numeric',
                'min:0',
                'max:9999.99'
            ],
            'lat' => [
                'nullable',
                'numeric',
                'between:-90,90'
            ],
            'lng' => [
                'nullable',
                'numeric',
                'between:-180,180'
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'terminal_time.required' => 'Terminal time is required',
            'terminal_time.date' => 'Terminal time must be a valid date',
            'terminal_time.before_or_equal' => 'Terminal time cannot be in the future',
            
            'device_id.required' => 'Device ID is required',
            'device_id.regex' => 'Device ID can only contain letters, numbers, underscores, and dashes',
            'device_id.max' => 'Device ID cannot exceed 255 characters',
            
            'wind_speed.required' => 'Wind speed is required',
            'wind_speed.numeric' => 'Wind speed must be a number',
            'wind_speed.min' => 'Wind speed cannot be negative',
            'wind_speed.max' => 'Wind speed cannot exceed 999.99',
            
            'wind_direction.required' => 'Wind direction is required',
            'wind_direction.integer' => 'Wind direction must be an integer',
            'wind_direction.min' => 'Wind direction must be at least 0 degrees',
            'wind_direction.max' => 'Wind direction cannot exceed 360 degrees',
            
            'temperature.required' => 'Temperature is required',
            'temperature.numeric' => 'Temperature must be a number',
            'temperature.min' => 'Temperature cannot be below -50°C',
            'temperature.max' => 'Temperature cannot exceed 100°C',
            
            'humidity.required' => 'Humidity is required',
            'humidity.numeric' => 'Humidity must be a number',
            'humidity.min' => 'Humidity cannot be negative',
            'humidity.max' => 'Humidity cannot exceed 100%',
            
            'pressure.required' => 'Pressure is required',
            'pressure.numeric' => 'Pressure must be a number',
            'pressure.min' => 'Pressure must be at least 800 hPa',
            'pressure.max' => 'Pressure cannot exceed 1200 hPa',
            
            'par_sensor.numeric' => 'PAR sensor value must be a number',
            'par_sensor.min' => 'PAR sensor value cannot be negative',
            'par_sensor.max' => 'PAR sensor value cannot exceed 9999.99',
            
            'rainfall.required' => 'Rainfall is required',
            'rainfall.numeric' => 'Rainfall must be a number',
            'rainfall.min' => 'Rainfall cannot be negative',
            'rainfall.max' => 'Rainfall cannot exceed 999.99 mm',
            
            'solar_radiation.required' => 'Solar radiation is required',
            'solar_radiation.numeric' => 'Solar radiation must be a number',
            'solar_radiation.min' => 'Solar radiation cannot be negative',
            'solar_radiation.max' => 'Solar radiation cannot exceed 9999.99 W/m²',
            
            'lat.numeric' => 'Latitude must be a number',
            'lat.between' => 'Latitude must be between -90 and 90 degrees',
            
            'lng.numeric' => 'Longitude must be a number',
            'lng.between' => 'Longitude must be between -180 and 180 degrees',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'terminal_time' => 'terminal time',
            'device_id' => 'device ID',
            'device_location' => 'device location',
            'wind_speed' => 'wind speed',
            'wind_direction' => 'wind direction',
            'par_sensor' => 'PAR sensor',
            'solar_radiation' => 'solar radiation',
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422)
        );
    }
}
