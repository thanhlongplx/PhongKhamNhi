<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Hủy khóa ngoại để tránh lỗi khi xóa dữ liệu
        Schema::disableForeignKeyConstraints();

        // Xóa dữ liệu cũ
        DB::table('users')->truncate();

        // Chèn dữ liệu mới
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'), // Mã hóa mật khẩu
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Doctor User',
                'email' => 'doctor@example.com',
                'password' => Hash::make('password123'), // Mã hóa mật khẩu
                'role' => 'doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nurse User',
                'email' => 'nurse@example.com',
                'password' => Hash::make('password123'), // Mã hóa mật khẩu
                'role' => 'nurse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clinic Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password123'), // Mã hóa mật khẩu
                'role' => 'clinic_manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // Bật lại khóa ngoại
        Schema::enableForeignKeyConstraints();
    }
}