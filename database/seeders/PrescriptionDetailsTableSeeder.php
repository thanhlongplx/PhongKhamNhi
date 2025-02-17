<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrescriptionDetailsTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('prescription_details')->truncate();

        $prescriptionDetails = [
            [
                'prescription_id' => 1, // ID của đơn thuốc đã tồn tại
                'medication_id' => 1, // ID của thuốc đã tồn tại
                'quantity' => 2,
                'dosage' => '1 viên/lần',
                'frequency' => '3 lần/ngày',
                'total_price' => 30000.00,
                'usage_instructions' => 'Uống sau khi ăn.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'prescription_id' => 1,
                'medication_id' => 2,
                'quantity' => 1,
                'dosage' => '1 viên/lần',
                'frequency' => '2 lần/ngày',
                'total_price' => 20000.00,
                'usage_instructions' => 'Uống trước bữa ăn.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('prescription_details')->insert($prescriptionDetails);
        Schema::enableForeignKeyConstraints();
    }
}