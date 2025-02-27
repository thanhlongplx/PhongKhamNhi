<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'medical_record_id',
        'patient_id', // Thay đổi thành patient_id
        'employee_id', // Thay đổi thành employee_id
        'total_amount',
        'date',
        'description',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id'); // Liên kết với bảng patients
    }

    public function doctor()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id'); // Liên kết với bảng employees
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id', 'id'); // Thêm liên kết nếu cần
    }
}