<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            [
                'code' => 'AWS001',
                'name' => 'Jakarta Central Weather Station',
                'location' => 'Jakarta Pusat',
                'type' => 'Weather Station',
                'status' => 1,
                'serial_number' => 'WS-JKT-001-2024'
            ],
            [
                'code' => 'AWS002',
                'name' => 'Bandung Weather Station',
                'location' => 'Bandung',
                'type' => 'Weather Station',
                'status' => 1,
                'serial_number' => 'WS-BDG-002-2024'
            ],
            [
                'code' => 'AWS003',
                'name' => 'Surabaya Weather Station',
                'location' => 'Surabaya',
                'type' => 'Weather Station',
                'status' => 1,
                'serial_number' => 'WS-SBY-003-2024'
            ],
            [
                'code' => 'AWS004',
                'name' => 'Medan Weather Station',
                'location' => 'Medan',
                'type' => 'Weather Station',
                'status' => 1,
                'serial_number' => 'WS-MDN-004-2024'
            ],
            [
                'code' => 'AWS005',
                'name' => 'Makassar Weather Station',
                'location' => 'Makassar',
                'type' => 'Weather Station',
                'status' => 0, // Inactive for testing
                'serial_number' => 'WS-MKS-005-2024'
            ],
        ];

        foreach ($devices as $device) {
            Device::firstOrCreate(
                ['code' => $device['code']], 
                $device
            );
        }

        // Add some additional test devices
        $additionalDevices = [
            [
                'code' => 'AWS006',
                'name' => 'Yogyakarta Weather Station',
                'location' => 'Yogyakarta',
                'type' => 'Weather Station',
                'status' => 1,
                'serial_number' => 'WS-YGY-006-2024'
            ],
            [
                'code' => 'ENV001',
                'name' => 'Bali Environmental Monitor',
                'location' => 'Denpasar, Bali',
                'type' => 'Environmental Monitor',
                'status' => 1,
                'serial_number' => 'ENV-BAL-001-2024'
            ],
            [
                'code' => 'ENV002',
                'name' => 'Lombok Environmental Monitor',
                'location' => 'Mataram, Lombok',
                'type' => 'Environmental Monitor',
                'status' => 0,
                'serial_number' => null // Test null serial number
            ],
        ];

        foreach ($additionalDevices as $device) {
            Device::firstOrCreate(
                ['code' => $device['code']], 
                $device
            );
        }
    }
}
