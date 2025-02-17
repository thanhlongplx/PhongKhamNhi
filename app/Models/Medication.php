<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_name',
        'description',
        'dosage_form',
        'strength',
        'side_effect',
        'contraindications',
        'price',
        'stock_quantity',
    ];

    // Quan hệ với chi tiết đơn thuốc
    public function prescriptionDetails()
    {
        return $this->hasMany(PrescriptionDetail::class, 'medication_id', 'id'); // Cập nhật để sử dụng medication_id
    }
}