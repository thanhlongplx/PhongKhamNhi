<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'employee_id',
        'medical_record_id',
        'date',
        'notes',
    ];

    // Mối quan hệ với model Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
   

    // Mối quan hệ với model Employee (Bác sĩ)
    public function doctor()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Mối quan hệ với model MedicalRecord
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id');
    }
    public function details()
{
    return $this->hasMany(PrescriptionDetail::class);
}
}