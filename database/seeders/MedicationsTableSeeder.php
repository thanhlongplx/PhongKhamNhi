<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MedicationsTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('medications')->truncate();

        $medications = [
            [
                'medicine_name' => 'Paracetamol',
                'description' => 'Giảm đau, hạ sốt.',
                'dosage_form' => 'Viên nén',
                'strength' => '500mg',
                'side_effect' => 'Buồn nôn.',
                'contraindications' => 'Bệnh gan nặng.',
                'price' => 15000.00,
                'stock_quantity' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medicine_name' => 'Ibuprofen',
                'description' => 'Giảm đau, chống viêm.',
                'dosage_form' => 'Viên nén',
                'strength' => '400mg',
                'side_effect' => 'Đau dạ dày.',
                'contraindications' => 'Loét dạ dày.',
                'price' => 20000.00,
                'stock_quantity' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('medications')->insert($medications);
        Schema::enableForeignKeyConstraints();
    }
}