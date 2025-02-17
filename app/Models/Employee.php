<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_code',
        'name', // Thêm trường name nếu cần
        'position',
        'department',
        'phone_number',
        'date_of_hire',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'employee_id', 'id'); // Liên kết với bảng prescriptions
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'employee_id', 'id'); // Liên kết với bảng appointments
    }
}