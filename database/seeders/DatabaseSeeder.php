<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\StaffTableSeeder;
use Database\Seeders\PatientsTableSeeder;
use Database\Seeders\MedicinesTableSeeder;
use Database\Seeders\PrescriptionsTableSeeder;
use Database\Seeders\PrescriptionDetailsSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
{
    $this->call([
        EmployeesTableSeeder::class,
        PatientsTableSeeder::class,
        MedicalRecordsTableSeeder::class,
        MedicationsTableSeeder::class,
        PrescriptionsTableSeeder::class,
        InvoicesTableSeeder::class,
        AppointmentsTableSeeder::class,
        PrescriptionDetailsTableSeeder::class,
    ]);
}
}

