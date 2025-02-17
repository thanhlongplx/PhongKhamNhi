<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord; // Đảm bảo import model đúng
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    // Lấy danh sách tất cả hồ sơ bệnh án
    public function index()
    {
        $medicalRecords = MedicalRecord::with('patient')->get(); // Lấy hồ sơ kèm thông tin bệnh nhân

        return view('medical_records.index', compact('medicalRecords')); // Sử dụng compact để truyền dữ liệu
    }

    // Thêm các phương thức khác nếu cần
}