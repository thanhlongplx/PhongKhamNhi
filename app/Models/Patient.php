<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // Các trường có thể gán giá trị thông qua mass assignment
    protected $fillable = [
        'name',             // Tên bệnh nhân
        'date_of_birth',    // Ngày sinh
        'sex',              // Giới tính (M, F, O)
        'height',           // Chiều cao
        'weight',           // Cân nặng
        'parent_name',      // Tên phụ huynh
        'address',          // Địa chỉ
        'status',
        'medical_history',  // Lịch sử bệnh
    ];
   
    // Quan hệ một-nhiều với Prescription
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id', 'id');
    }

    // Quan hệ một-nhiều với Appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    // Quan hệ một-nhiều với Invoice
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id', 'id');
    }
}