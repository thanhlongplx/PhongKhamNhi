<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord; // Đảm bảo import model đúng
use App\Models\Prescription;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    // Lấy danh sách tất cả hồ sơ bệnh án
    public function index()
    {
        $medicalRecords = MedicalRecord::with(['prescriptions', 'patient'])->get(); // Lấy hồ sơ kèm thông tin bệnh nhân
        $prescriptions = Prescription::all();

        return view('medical_records.index', compact('medicalRecords', 'prescriptions')); // Sử dụng compact để truyền dữ liệu
    }

    // Hiển thị form chỉnh sửa hồ sơ bệnh án
    public function edit($id)
    {
        $medicalRecord = MedicalRecord::findOrFail($id); // Tìm hồ sơ bệnh án theo ID

        return view('medical_records.edit', compact('medicalRecord'));
    }

    // Cập nhật hồ sơ bệnh án
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu yêu cầu
        $request->validate([
            'visit_date' => 'required|date',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
        ]);

        // Tìm hồ sơ bệnh án theo ID
        $medicalRecord = MedicalRecord::findOrFail($id);

        // Cập nhật thông tin hồ sơ bệnh án
        $medicalRecord->update([
            'visit_date' => $request->visit_date, // Sẽ là ngày hôm nay
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        // Chuyển hướng về trang danh sách hoặc trang chi tiết với thông báo thành công
        return redirect()->route('medical_records.index')->with('success', 'Hồ sơ bệnh án đã được cập nhật thành công!');
    }
    // Xóa hồ sơ bệnh án
    public function destroy($id)
    {
        // Tìm hồ sơ bệnh án theo ID
        $medicalRecord = MedicalRecord::findOrFail($id);

        // Xóa hồ sơ bệnh án
        $medicalRecord->delete();

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('medical_records.index')->with('success', 'Hồ sơ bệnh án đã được xóa thành công!');
    }
}