<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord; // Đảm bảo import model đúng
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    // Lấy danh sách tất cả hồ sơ bệnh án
    public function index(Request $request)
    {
        // Khởi tạo truy vấn lấy hồ sơ bệnh án
        $query = MedicalRecord::with(['prescriptions.patient']);

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('symptoms', 'like', "%{$search}%")
                    ->orWhere('diagnosis', 'like', "%{$search}%")
                    ->orWhereHas('prescriptions.patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Lấy hồ sơ kèm thông tin bệnh nhân và sắp xếp theo thời gian từ mới nhất đến cũ nhất
        $medicalRecords = $query->orderBy('created_at', 'desc')->paginate(10); // Phân trang với 10 bản ghi mỗi trang

        return view('medical_records.index', compact('medicalRecords')); // Truyền dữ liệu vào view
    }

    // Hiển thị form chỉnh sửa hồ sơ bệnh án
    public function edit($id)
    {
        // Lấy hồ sơ bệnh án theo ID
        $medicalRecord = MedicalRecord::findOrFail($id);
    
        // Lấy thông tin đơn thuốc liên quan
        $prescription = Prescription::where('medical_record_id', $medicalRecord->id)->first();
        
        // Nếu cần, bạn có thể lấy thêm thông tin bệnh nhân từ đơn thuốc
        $patient = Patient::find($prescription->patient_id);
    
        return view('medical_records.edit', compact('medicalRecord', 'prescription', 'patient'));
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
        'visit_date' => $request->visit_date,
        'symptoms' => $request->symptoms,
        'diagnosis' => $request->diagnosis,
        'treatment' => $request->treatment,
    ]);

    // Tìm đơn thuốc liên quan để cập nhật trạng thái bệnh nhân
    $prescription = Prescription::where('medical_record_id', $medicalRecord->id)->first();

    if ($prescription) {
        // Lấy ID bệnh nhân từ đơn thuốc
        $patient = Patient::find($prescription->patient_id);

        // Cập nhật trạng thái của bệnh nhân
        if ($patient) {
            $patient->status = 'Đã khám, chưa thanh toán';
            $patient->save();
        }
    }

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