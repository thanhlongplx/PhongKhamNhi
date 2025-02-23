<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\PrescriptionDetail;
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
        $patients = Patient::where('status', 'Đợi khám')->get(); // Lấy bệnh nhân đang chờ khám
        $doctors = Employee::where('position', 'Bác sĩ')->get(); // Lấy danh sách bác sĩ
        $medications = Medication::all(); // Lấy danh sách thuốc

        return view('prescriptions.create', compact('patients', 'doctors', 'medications'));
    }

    // Lưu đơn thuốc mới
    public function store(Request $request)
    {
        // Xác thực dữ liệu vào
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'medication_id.*' => 'required|exists:medications,id',
            'dosage.*' => 'required|string',
            'frequency.*' => 'required|string',
            'usage_instructions.*' => 'nullable|string',
            'quantity.*' => 'required|integer|min:1',
        ]);

        // Tạo hồ sơ y tế nếu chưa tồn tại
        $medicalRecord = MedicalRecord::firstOrCreate([
            'id' => $request->patient_id,
            'visit_date' => now(), // Hoặc bạn có thể dùng $request->date
            'symptoms' => $request->notes, // Sử dụng ghi chú làm triệu chứng (nếu cần)
            'diagnosis' => null, // Có thể để trống hoặc gán giá trị khác
            'treatment' => null, // Có thể để trống hoặc gán giá trị khác
        ]);

        // Tạo đơn thuốc
        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'notes' => $request->notes,
            'medical_record_id' => $medicalRecord->id, // Lưu trường medical_record_id tự động
        ]);

        // Lưu các chi tiết đơn thuốc
        foreach ($request->medication_id as $index => $medicationId) {
            PrescriptionDetail::create([
                'prescription_id' => $prescription->id,
                'medication_id' => $medicationId,
                'dosage' => $request->dosage[$index],
                'frequency' => $request->frequency[$index],
                'quantity' => $request->quantity[$index],
                'total_price' => $request->total_price[$index], // Lưu giá tổng
                'usage_instructions' => $request->usage_instructions[$index] ?? null,
            ]);
        }

        return redirect()->route('medical_records.index')->with('success', 'Đơn thuốc đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa đơn thuốc
    public function edit($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'medicalRecord'])->findOrFail($id); // Lấy đơn thuốc theo ID
        $patients = Patient::all(); // Lấy danh sách bệnh nhân
        $doctors = Employee::where('position', 'doctor')->get(); // Lấy danh sách bác sĩ
        $medications = Medication::all(); // Lấy danh sách thuốc


        return view('prescriptions.edit', compact('prescription', 'patients', 'doctors', 'medications'));
    }

    // Cập nhật đơn thuốc
    public function update(Request $request, $id)
    {
        // Tìm đơn thuốc theo ID
        $prescription = Prescription::findOrFail($id);

        // Cập nhật thông tin đơn thuốc chính
        $prescription->patient_id = $request->input('patient_id');
        $prescription->employee_id = $request->input('employee_id');
        $prescription->date = $request->input('date');
        $prescription->notes = $request->input('notes');
        $prescription->save(); // Lưu thông tin đơn thuốc

        // Cập nhật chi tiết đơn thuốc
        $medicationIds = $request->input('medication_id');
        $dosages = $request->input('dosage');
        $frequencies = $request->input('frequency');
        $quantities = $request->input('quantity');
        $totalPrices = $request->input('total_price');
        $usageInstructions = $request->input('usage_instructions');

        // Xóa tất cả chi tiết cũ trước khi lưu mới
        $prescription->details()->delete();

        // Thêm chi tiết mới
        for ($i = 0; $i < count($medicationIds); $i++) {
            $prescription->details()->create([
                'medication_id' => $medicationIds[$i],
                'dosage' => $dosages[$i],
                'frequency' => $frequencies[$i],
                'quantity' => $quantities[$i],
                'total_price' => $totalPrices[$i],
                'usage_instructions' => $usageInstructions[$i],
            ]);
        }

        // Chuyển hướng người dùng hoặc trả về thông báo thành công
        return redirect()->route('prescriptions')->with('success', 'Cập nhật đơn thuốc thành công!');
    }

    // Xóa đơn thuốc
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete(); // Xóa đơn thuốc

        return redirect()->route('prescriptions')->with('success', 'Đơn thuốc đã được xóa thành công.');
    }
}