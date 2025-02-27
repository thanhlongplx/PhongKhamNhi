<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'employee_id',       // Thêm employee_id vào $fillable
        'medical_record_id',
        'appointment_time',
        'status',
        'notes',            // Ghi chú
    ];

    // Liên kết với bảng bệnh nhân
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    // Liên kết với bảng nhân viên (bác sĩ)
    public function employee() // Đổi tên từ doctor thành employee
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    // Liên kết với bảng hồ sơ bệnh án
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id', 'id');
    }
}