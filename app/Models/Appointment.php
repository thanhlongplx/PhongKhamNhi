<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', // Thay đổi thành patient_id
        'employee_id', // Thay đổi thành employee_id
        'medical_record_id', // Thêm medical_record_id
        'appointment_time',
        'appointment_code',
        'status',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id'); // Liên kết với bảng bệnh nhân
    }

    public function doctor()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id'); // Liên kết với bảng nhân viên
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id', 'id'); // Liên kết với bảng hồ sơ bệnh án
    }
}