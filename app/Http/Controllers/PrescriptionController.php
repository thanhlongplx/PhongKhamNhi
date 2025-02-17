<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    // Hiển thị danh sách đơn thuốc
    public function index()
    {
        $prescriptions = Prescription::with(['patient', 'doctor', 'medicalRecord'])->paginate(10); // Lấy danh sách đơn thuốc
        return view('prescriptions.index', compact('prescriptions')); // Truyền dữ liệu vào view
    }

    // Hiển thị form thêm đơn thuốc
    public function create()
    {
        $patients = Patient::all();
        $doctors = Employee::where('position', 'doctor')->get();

        return view('prescriptions.create', compact('patients', 'doctors'));
    }

    // Lưu đơn thuốc mới
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Tạo hồ sơ bệnh án mới
        $medicalRecord = MedicalRecord::create([
            'visit_date' => now(), // Ngày khám hiện tại
            'symptoms' => null, 
            'diagnosis' => null, 
            'treatment' => null, 
        ]);

        // Tạo đơn thuốc với medical_record_id vừa tạo
        Prescription::create(array_merge($request->all(), [
            'medical_record_id' => $medicalRecord->id,
        ]));

        return redirect()->route('prescriptions.index')->with('success', 'Thêm đơn thuốc thành công.');
    }

    // Hiển thị form chỉnh sửa đơn thuốc
    public function edit($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'medicalRecord'])->findOrFail($id); // Lấy đơn thuốc theo ID
        $patients = Patient::all(); // Lấy danh sách bệnh nhân
        $doctors = Employee::where('position', 'doctor')->get(); // Lấy danh sách bác sĩ

        return view('prescriptions.edit', compact('prescription', 'patients', 'doctors'));
    }

    // Cập nhật đơn thuốc
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $prescription = Prescription::findOrFail($id);
        $prescription->update($request->all()); // Cập nhật thông tin đơn thuốc

        return redirect()->route('prescriptions.index')->with('success', 'Cập nhật đơn thuốc thành công.');
    }

    // Xóa đơn thuốc
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete(); // Xóa đơn thuốc

        return redirect()->route('prescriptions.index')->with('success', 'Đơn thuốc đã được xóa thành công.');
    }
}