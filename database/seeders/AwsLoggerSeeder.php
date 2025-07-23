<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AwsLogger;
use Carbon\Carbon;

class AwsLoggerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            ['id' => 'AWS001', 'location' => 'Jakarta Pusat', 'lat' => -6.2088, 'lng' => 106.8456],
            ['id' => 'AWS002', 'location' => 'Bandung', 'lat' => -6.9175, 'lng' => 107.6191],
            ['id' => 'AWS003', 'location' => 'Surabaya', 'lat' => -7.2575, 'lng' => 112.7521],
            ['id' => 'AWS004', 'location' => 'Medan', 'lat' => 3.5952, 'lng' => 98.6722],
            ['id' => 'AWS005', 'location' => 'Makassar', 'lat' => -5.1477, 'lng' => 119.4327],
        ];

        // Generate data for the last 7 days
        for ($day = 7; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            
            foreach ($devices as $device) {
                // Generate 24 records per day (hourly)
                for ($hour = 0; $hour < 24; $hour++) {
                    $timestamp = $date->copy()->addHours($hour);
                    
                    // Simulate realistic weather data based on location and time
                    $baseTemp = $this->getBaseTemperature($device['location'], $hour);
                    $tempVariation = rand(-5, 5);
                    
                    AwsLogger::create([
                        'terminal_time' => $timestamp,
                        'device_id' => $device['id'],
                        'device_location' => $device['location'],
                        'temperature' => $baseTemp + $tempVariation,
                        'humidity' => rand(60, 85),
                        'pressure' => rand(1005, 1025) + (rand(0, 100) / 100),
                        'wind_speed' => rand(0, 15) + (rand(0, 100) / 100),
                        'wind_direction' => rand(0, 359),
                        'rainfall' => $this->generateRainfall(),
                        'solar_radiation' => $this->getSolarRadiation($hour),
                        'par_sensor' => $this->getSolarRadiation($hour) * 2.1,
                        'lat' => $device['lat'] + (rand(-100, 100) / 10000), // Small variation
                        'lng' => $device['lng'] + (rand(-100, 100) / 10000), // Small variation
                    ]);
                }
            }
        }
    }

    private function getBaseTemperature($location, $hour)
    {
        $baseTemps = [
            'Jakarta Pusat' => 27,
            'Bandung' => 23,
            'Surabaya' => 28,
            'Medan' => 26,
            'Makassar' => 29,
        ];

        $baseTemp = $baseTemps[$location] ?? 25;
        
        // Temperature variation based on hour (cooler at night)
        if ($hour >= 22 || $hour <= 6) {
            $baseTemp -= rand(2, 5);
        } elseif ($hour >= 12 && $hour <= 15) {
            $baseTemp += rand(2, 5);
        }

        return $baseTemp;
    }

    private function generateRainfall()
    {
        // 80% chance of no rain, 20% chance of rain
        if (rand(1, 100) <= 80) {
            return 0;
        }
        
        // Light rain (0.1-2mm), moderate rain (2-10mm), heavy rain (10-50mm)
        $rainType = rand(1, 100);
        if ($rainType <= 60) {
            return rand(1, 20) / 10; // Light rain
        } elseif ($rainType <= 90) {
            return rand(20, 100) / 10; // Moderate rain
        } else {
            return rand(100, 500) / 10; // Heavy rain
        }
    }

    private function getSolarRadiation($hour)
    {
        // Solar radiation based on hour (0 at night, peak at noon)
        if ($hour <= 6 || $hour >= 18) {
            return 0;
        }
        
        // Bell curve for solar radiation
        $peak = 12; // Noon
        $maxRadiation = 1200;
        
        $x = abs($hour - $peak);
        $radiation = $maxRadiation * exp(-($x * $x) / 18);
        
        return round($radiation + rand(-50, 50), 2);
    }
}
