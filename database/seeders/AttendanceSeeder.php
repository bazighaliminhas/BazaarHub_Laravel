<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['id' => '1', 'name' => 'Ahmed Khan'],
            ['id' => '2', 'name' => 'Sara Ali'],
            ['id' => '3', 'name' => 'Bilal Ahmed'],
            ['id' => '4', 'name' => 'Fatima Malik'],
            ['id' => '5', 'name' => 'Usman Tariq'],
        ];

        // ✅ Generate 30 days of attendance
        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->subDays($day);

            // Skip Sundays
            if ($date->isSunday()) continue;

            foreach ($employees as $emp) {
                $checkIn  = $date->copy()->setTime(rand(8, 9), rand(0, 59));
                $checkOut = $date->copy()->setTime(rand(17, 18), rand(0, 59));

                // Check IN
                Attendance::updateOrCreate(
                    [
                        'device_user_id' => $emp['id'],
                        'punch_time'     => $checkIn,
                    ],
                    [
                        'employee_name' => $emp['name'],
                        'punch_type'    => 0,
                        'device_ip'     => 'seeder',
                    ]
                );

                // Check OUT
                Attendance::updateOrCreate(
                    [
                        'device_user_id' => $emp['id'],
                        'punch_time'     => $checkOut,
                    ],
                    [
                        'employee_name' => $emp['name'],
                        'punch_type'    => 1,
                        'device_ip'     => 'seeder',
                    ]
                );
            }
        }

        $this->command->info('✅ Attendance seeded for 30 days (5 employees)!');
    }
}