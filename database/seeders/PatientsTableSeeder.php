<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PatientsTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('patients')->truncate();

        $patients = [
            [
                'name' => 'Nguyễn Văn A',
                'date_of_birth' => '2010-05-15',
                'sex' => 'M',
                'height' => 140.5,
                'weight' => 30.0,
                'parent_name' => 'Nguyễn Văn B',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'medical_history' => 'Không có tiền sử bệnh lý.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Thị B',
                'date_of_birth' => '2012-08-20',
                'sex' => 'F',
                'height' => 135.0,
                'weight' => 28.0,
                'parent_name' => 'Trần Văn C',
                'address' => '456 Đường DEF, Quận 2, TP.HCM',
                'medical_history' => 'Bị dị ứng với thuốc kháng sinh.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('patients')->insert($patients);
        Schema::enableForeignKeyConstraints();
    }
}