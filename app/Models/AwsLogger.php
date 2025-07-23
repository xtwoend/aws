<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AwsLogger extends Model
{
    use HasFactory;

    protected $fillable = [
        'terminal_time',
        'device_id', 
        'device_location',
        'wind_speed',
        'wind_direction',
        'temperature',
        'humidity',
        'pressure',
        'par_sensor',
        'rainfall',
        'solar_radiation',
        'lat',
        'lng'
    ];

    protected $casts = [
        'terminal_time' => 'datetime',
        'wind_speed' => 'decimal:2',
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'pressure' => 'decimal:2',
        'par_sensor' => 'decimal:2',
        'rainfall' => 'decimal:2',
        'solar_radiation' => 'decimal:2',
        'lat' => 'decimal:6',
        'lng' => 'decimal:6'
    ];

    /**
     * Relationship with Device
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'code');
    }

    /**
     * Get the latest data from all devices
     */
    public static function getLatestData($limit = 10)
    {
        return self::orderBy('terminal_time', 'desc')->limit($limit)->get();
    }

    /**
     * Get data by device ID
     */
    public static function getByDevice($deviceId, $limit = 50)
    {
        return self::where('device_id', $deviceId)
                   ->orderBy('terminal_time', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get summary statistics
     */
    public static function getSummaryStats()
    {
        return [
            'total_devices' => self::distinct('device_id')->count(),
            'total_records' => self::count(),
            'latest_record' => self::orderBy('terminal_time', 'desc')->first(),
            'avg_temperature' => self::avg('temperature'),
            'avg_humidity' => self::avg('humidity'),
            'total_rainfall' => self::sum('rainfall'),
        ];
    }

    /**
     * Get wind direction as text
     */
    public function getWindDirectionTextAttribute()
    {
        $directions = [
            0 => 'N', 45 => 'NE', 90 => 'E', 135 => 'SE',
            180 => 'S', 225 => 'SW', 270 => 'W', 315 => 'NW'
        ];
        
        $closest = null;
        $minDiff = 360;
        
        foreach ($directions as $degree => $text) {
            $diff = abs($this->wind_direction - $degree);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $text;
            }
        }
        
        return $closest;
    }

    /**
     * Get temperature status color
     */
    public function getTemperatureStatusAttribute()
    {
        if ($this->temperature < 20) return 'info';
        if ($this->temperature < 30) return 'success';
        if ($this->temperature < 35) return 'warning';
        return 'danger';
    }

    /**
     * Get humidity status color
     */
    public function getHumidityStatusAttribute()
    {
        if ($this->humidity < 30) return 'danger';
        if ($this->humidity < 60) return 'warning';
        if ($this->humidity < 80) return 'success';
        return 'info';
    }

    /**
     * Formatted accessors for display
     */
    public function getFormattedTemperatureAttribute()
    {
        return $this->temperature ? number_format($this->temperature, 1) . '°C' : 'N/A';
    }

    public function getFormattedHumidityAttribute()
    {
        return $this->humidity ? number_format($this->humidity, 1) . '%' : 'N/A';
    }

    public function getFormattedPressureAttribute()
    {
        return $this->pressure ? number_format($this->pressure, 1) . ' hPa' : 'N/A';
    }

    public function getFormattedPm25Attribute()
    {
        return $this->par_sensor ? number_format($this->par_sensor, 1) . ' μg/m³' : 'N/A';
    }

    public function getFormattedWindSpeedAttribute()
    {
        return $this->wind_speed ? number_format($this->wind_speed, 1) . ' m/s' : 'N/A';
    }

    public function getFormattedWindDirectionAttribute()
    {
        if (!$this->wind_direction) return 'N/A';
        return number_format($this->wind_direction, 0) . '° ' . $this->wind_direction_text;
    }

    public function getFormattedRainfallAttribute()
    {
        return $this->rainfall ? number_format($this->rainfall, 1) . ' mm' : '0.0 mm';
    }

    public function getFormattedSolarRadiationAttribute()
    {
        if (is_null($this->solar_radiation)) {
            return 'N/A';
        }
        $value = number_format($this->solar_radiation, 1);
        $status = 'success';
        if ($this->solar_radiation >= 300) $status = 'warning';
        if ($this->solar_radiation >= 600) $status = 'danger';
        return "<span class=\"badge bg-{$status}-lt\">{$value} W/m²</span>";
    }
}
