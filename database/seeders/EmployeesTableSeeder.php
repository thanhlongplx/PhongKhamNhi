<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('employees')->truncate();

        $employees = [
            [
                'user_id' => 1, // ID của user đã tồn tại
                'staff_code' => 'EMP001',
                'name' => 'Nguyễn Văn A',
                'position' => 'Bác sĩ',
                'department' => 'Khoa Nội',
                'phone_number' => '0123456789',
                'date_of_hire' => '2023-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'staff_code' => 'EMP002',
                'name' => 'Trần Thị B',
                'position' => 'Y tá',
                'department' => 'Khoa Ngoại',
                'phone_number' => '0987654321',
                'date_of_hire' => '2023-01-02',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('employees')->insert($employees);
        Schema::enableForeignKeyConstraints();
    }
}