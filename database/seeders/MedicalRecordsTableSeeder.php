<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MedicalRecordsTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('medical_records')->truncate();

        $medicalRecords = [
            [
                'visit_date' => '2023-01-10',
                'symptoms' => 'Đau đầu',
                'diagnosis' => 'Cảm cúm',
                'treatment' => 'Nghỉ ngơi và uống thuốc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'visit_date' => '2023-02-15',
                'symptoms' => 'Sốt cao',
                'diagnosis' => 'Viêm phổi',
                'treatment' => 'Kháng sinh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('medical_records')->insert($medicalRecords);
        Schema::enableForeignKeyConstraints();
    }
}