<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'id'); // Cập nhật để liên kết với user_id
    }
    
    // Định nghĩa mối quan hệ với Prescription
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'employee_id'); // 'employee_id' là khóa ngoại
    }
}