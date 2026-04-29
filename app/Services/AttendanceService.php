<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    // ✅ Main sync method — Mock or Real Device
    public function syncAttendance(): array
    {
        $isMock = env('ZKTECO_MOCK', true);

        $logs = $isMock
            ? $this->getMockData()
            : $this->getDeviceData();

        $saved = 0;

        foreach ($logs as $log) {
            $record = Attendance::updateOrCreate(
                [
                    'device_user_id' => $log['id'],
                    'punch_time'     => $log['timestamp'],
                ],
                [
                    'employee_name' => $log['name'] ?? null,
                    'punch_type'    => $log['type'],
                    'device_ip'     => env('ZKTECO_DEVICE_IP', 'mock'),
                ]
            );

            if ($record->wasRecentlyCreated) {
                $saved++;
            }
        }

        return [
            'saved' => $saved,
            'total' => count($logs),
        ];
    }

    // ✅ Real UFace800 Device
    private function getDeviceData(): array
    {
        try {
            $zk = new \Jmrashed\Zkteco\Lib\ZKTeco(
                env('ZKTECO_DEVICE_IP', '192.168.1.201'),
                env('ZKTECO_DEVICE_PORT', 4370)
            );

            if (!$zk->connect()) {
                throw new \Exception('Cannot connect to UFace800 device!');
            }

            $zk->enableDevice();
            $logs = $zk->getAttendance();
            $zk->disableDevice();
            $zk->disconnect();

            return $logs;

        } catch (\Exception $e) {
            \Log::error('UFace800 Error: ' . $e->getMessage());
            return [];
        }
    }

    // ✅ Mock/Fake Data for Testing
    private function getMockData(): array
    {
        $now = Carbon::now();

        return [
            [
                'id'        => '1',
                'name'      => 'Ahmed Khan',
                'timestamp' => $now->copy()->setTime(8, 0)->toDateTimeString(),
                'type'      => 0,
            ],
            [
                'id'        => '1',
                'name'      => 'Ahmed Khan',
                'timestamp' => $now->copy()->setTime(17, 0)->toDateTimeString(),
                'type'      => 1,
            ],
            [
                'id'        => '2',
                'name'      => 'Sara Ali',
                'timestamp' => $now->copy()->setTime(9, 15)->toDateTimeString(),
                'type'      => 0,
            ],
            [
                'id'        => '2',
                'name'      => 'Sara Ali',
                'timestamp' => $now->copy()->setTime(18, 0)->toDateTimeString(),
                'type'      => 1,
            ],
            [
                'id'        => '3',
                'name'      => 'Bilal Ahmed',
                'timestamp' => $now->copy()->setTime(8, 45)->toDateTimeString(),
                'type'      => 0,
            ],
            [
                'id'        => '4',
                'name'      => 'Fatima Malik',
                'timestamp' => $now->copy()->setTime(9, 0)->toDateTimeString(),
                'type'      => 0,
            ],
        ];
    }
}