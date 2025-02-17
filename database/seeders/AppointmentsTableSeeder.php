<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('appointments')->truncate();

        $appointments = [
            [
                'patient_id' => 1,
                'employee_id' => 1,
                'medical_record_id' => 1,
                'appointment_time' => '2023-01-10 10:00:00',
                'status' => 'completed',
                'notes' => 'Thăm khám định kỳ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'patient_id' => 2,
                'employee_id' => 2,
                'medical_record_id' => 2,
                'appointment_time' => '2023-02-15 14:00:00',
                'status' => 'pending',
                'notes' => 'Thăm khám định kỳ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('appointments')->insert($appointments);
        Schema::enableForeignKeyConstraints();
    }
}