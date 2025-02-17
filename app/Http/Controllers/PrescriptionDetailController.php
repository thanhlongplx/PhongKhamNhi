<?php

namespace App\Http\Controllers;

use App\Models\PrescriptionDetail; // Chỉnh sửa tên Model
use Illuminate\Http\Request;

class PrescriptionDetailController extends Controller
{
    // Lấy danh sách chi tiết đơn thuốc
    public function index()
    {
        // Lấy tất cả chi tiết đơn thuốc kèm theo thông tin đơn thuốc và thuốc
        $prescriptionDetails = PrescriptionDetail::with(['prescription', 'medication'])->get(); // Lấy tất cả dữ liệu

        return view('prescription_details.index', compact('prescriptionDetails')); // Sử dụng compact để truyền dữ liệu
    }
}