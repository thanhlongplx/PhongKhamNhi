<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InvoicesTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('invoices')->truncate();

        $invoices = [
            [
                'medical_record_id' => 1,
                'employee_id' => 1,
                'patient_id' => 1,
                'total_amount' => 50000.00,
                'date' => '2023-01-10',
                'description' => 'Hóa đơn khám bệnh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medical_record_id' => 2,
                'employee_id' => 2,
                'patient_id' => 2,
                'total_amount' => 70000.00,
                'date' => '2023-02-15',
                'description' => 'Hóa đơn khám bệnh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('invoices')->insert($invoices);
        Schema::enableForeignKeyConstraints();
    }
}