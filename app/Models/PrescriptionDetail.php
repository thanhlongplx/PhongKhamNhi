<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', // Sử dụng prescription_id
        'medication_id',   // Sử dụng medication_id
        'quantity',
        'dosage',
        'frequency',
        'total_price',
        'usage_instructions', // Nếu có trường này trong cơ sở dữ liệu
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'id'); // Liên kết đúng với prescription
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class, 'medication_id', 'id'); // Liên kết đúng với medication
    }

    // Phương thức này có thể không đúng
    public function patient()
    {
        // Thông thường, chi tiết đơn thuốc không trực tiếp liên kết với bệnh nhân
        // Bạn có thể lấy patient thông qua prescription
        return $this->belongsTo(Patient::class, 'prescription_id', 'id'); // Cần xem lại logic
    }
}