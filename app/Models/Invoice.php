<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', // Thay đổi thành patient_id
        'employee_id', // Thay đổi thành employee_id
        'invoice_code',
        'invoice_date',
        'total_amount',
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