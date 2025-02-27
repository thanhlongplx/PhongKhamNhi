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
    public function index()
    {
        // Lấy hồ sơ kèm thông tin bệnh nhân và sắp xếp theo thời gian từ mới nhất đến cũ nhất
        $medicalRecords = MedicalRecord::with(['prescriptions', 'patient'])
                                        ->orderBy('created_at', 'desc') // Sắp xếp theo thời gian
                                        ->paginate(10); // Phân trang với 10 bản ghi mỗi trang
    
        $prescriptions = Prescription::all(); // Lấy tất cả đơn thuốc
    
        return view('medical_records.index', compact('medicalRecords', 'prescriptions')); // Truyền dữ liệu vào view
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
            'visit_date' => $request->visit_date, // Ngày khám
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);
    
        // Tìm đơn thuốc liên quan
        $prescription = Prescription::where('medical_record_id', $medicalRecord->id)->first();
    
        if ($prescription) {
            // Tìm bệnh nhân từ đơn thuốc
            $patient = Patient::find($prescription->patient_id);
            if ($patient) {
                // Kiểm tra lịch hẹn khám của bệnh nhân
                $appointment = Appointment::where('patient_id', $patient->id)
                    ->where('appointment_time', '>', now()) // Ngày hẹn khám sau ngày hôm nay
                    ->first();
    
                if ($appointment) {
                    // Nếu có lịch hẹn khám trong tương lai
                    $patient->status = 'Đã khám, hẹn tái khám'; // Cập nhật trạng thái
                } else {
                    $patient->status = 'Đã khám, chưa thanh toán'; // Nếu không có lịch hẹn trong tương lai
                }
    
                $patient->save(); // Lưu thay đổi
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