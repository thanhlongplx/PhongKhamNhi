<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    // Các trường có thể được gán giá trị
    protected $fillable = [
        'visit_date',  // Ngày khám
        'symptoms',    // Triệu chứng
        'diagnosis',   // Chẩn đoán của bác sĩ
        'treatment',    // Phương pháp điều trị
    ];

    // Liên kết tới model Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id'); // Sửa lại để liên kết đúng với patient_id
    }

    // Liên kết tới model Prescription
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'medical_record_id', 'id'); // Liên kết tới bảng prescriptions
    }

    // Liên kết tới model Appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'medical_record_id', 'id'); // Liên kết tới bảng appointments
    }
}