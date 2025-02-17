<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrescriptionsTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('prescriptions')->truncate();

        $prescriptions = [
            [
                'medical_record_id' => 1, // ID của hồ sơ bệnh án đã tồn tại
                'employee_id' => 1, // ID của nhân viên đã tồn tại
                'patient_id' => 1, // ID của bệnh nhân đã tồn tại
                'notes' => 'Take after meals',
                'date' => '2023-01-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medical_record_id' => 2,
                'employee_id' => 2,
                'patient_id' => 2,
                'notes' => 'Take with plenty of water',
                'date' => '2023-02-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('prescriptions')->insert($prescriptions);
        Schema::enableForeignKeyConstraints();
    }
}