<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'location',
        'type',
        'status',
        'serial_number'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Relationship with AWS Logger data
     */
    public function awsLogs()
    {
        return $this->hasMany(AwsLogger::class, 'device_id', 'code');
    }

    /**
     * Get latest AWS log entry
     */
    public function latestLog()
    {
        return $this->hasOne(AwsLogger::class, 'device_id', 'code')
                    ->orderBy('terminal_time', 'desc');
    }

    /**
     * Get device status
     */
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    /**
     * Get device status badge color
     */
    public function getStatusColorAttribute()
    {
        return $this->status ? 'success' : 'danger';
    }

    /**
     * Check if device is online (has data in last hour)
     */
    public function getIsOnlineAttribute()
    {
        if (!$this->latestLog) {
            return false;
        }
        
        return $this->latestLog->terminal_time->diffInMinutes() <= 60;
    }

    /**
     * Get online status color
     */
    public function getOnlineStatusColorAttribute()
    {
        return $this->is_online ? 'success' : 'warning';
    }

    /**
     * Get online status text
     */
    public function getOnlineStatusTextAttribute()
    {
        if (!$this->latestLog) {
            return 'No Data';
        }
        
        return $this->is_online ? 'Online' : 'Offline';
    }

    /**
     * Get last seen text
     */
    public function getLastSeenAttribute()
    {
        if (!$this->latestLog) {
            return 'Never';
        }
        
        return $this->latestLog->terminal_time->diffForHumans();
    }

    /**
     * Get last seen timestamp
     */
    public function getLastSeenTimestampAttribute()
    {
        if (!$this->latestLog) {
            return null;
        }
        
        return $this->latestLog->terminal_time;
    }

    /**
     * Get device statistics
     */
    public function getStatsAttribute()
    {
        return [
            'total_logs' => $this->awsLogs()->count(),
            'logs_today' => $this->awsLogs()->whereDate('terminal_time', today())->count(),
            'logs_this_week' => $this->awsLogs()->where('terminal_time', '>=', now()->subWeek())->count(),
            'logs_this_month' => $this->awsLogs()->where('terminal_time', '>=', now()->subMonth())->count(),
            'avg_temperature' => $this->awsLogs()->avg('temperature'),
            'avg_humidity' => $this->awsLogs()->avg('humidity'),
            'max_wind_speed' => $this->awsLogs()->max('wind_speed'),
            'total_rainfall' => $this->awsLogs()->sum('rainfall'),
            'latest_log' => $this->latestLog,
            'first_log' => $this->awsLogs()->orderBy('terminal_time')->first(),
            'last_seen' => $this->last_seen,
            'last_seen_timestamp' => $this->last_seen_timestamp,
            'is_online' => $this->is_online,
        ];
    }

    /**
     * Scope for active devices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for online devices (has data in last hour)
     */
    public function scopeOnline($query)
    {
        return $query->whereHas('latestLog', function($q) {
            $q->where('terminal_time', '>=', now()->subHour());
        });
    }

    /**
     * Get device types for filtering
     */
    public static function getTypes()
    {
        return self::distinct('type')->whereNotNull('type')->pluck('type');
    }
}
