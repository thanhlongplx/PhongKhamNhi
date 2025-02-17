<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', // Sử dụng prescription_id
        'medication_id', // Sử dụng medication_id
        'quantity',
        'dosage',
        'frequency',
        'total_price',
        'usage_instructions',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'id'); // Cập nhật để liên kết đúng
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class, 'medication_id', 'id'); // Cập nhật để sử dụng medication_id
    }
}