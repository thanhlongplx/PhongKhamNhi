<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionDetail; // Chỉnh sửa tên Model
use Illuminate\Http\Request;

class PrescriptionDetailController extends Controller
{
    // Lấy danh sách chi tiết đơn thuốc
    public function index()
    {
        // Lấy tất cả chi tiết đơn thuốc kèm theo thông tin đơn thuốc và thuốc
        $prescriptionDetails = PrescriptionDetail::with(['patient', 'prescription', 'medication',])->get(); // Lấy tất cả dữ liệu

        return view('prescription_details.index', compact('prescriptionDetails')); // Sử dụng compact để truyền dữ liệu
    }
    public function edit($id)
    {
        $prescription = PrescriptionDetail::findOrFail($id); // Tìm chi tiết đơn thuốc theo ID
        $prescriptions = Prescription::all(); // Lấy danh sách đơn thuốc
        $medications = Medication::all(); // Lấy danh sách thuốc
        $doctors = Employee::where('position', 'Bác sĩ')->get();
        ;
        $patients = Patient::all(); // Lấy danh sách bệnh nhân

        return view('prescription_details.edit', compact('prescription', 'prescriptions', 'medications', 'patients', 'doctors'));
    }

    // Cập nhật chi tiết đơn thuốc
    public function update(Request $request, $id)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'required|integer|min:1',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        $prescription = PrescriptionDetail::findOrFail($id);
        $prescription->update($request->all()); // Cập nhật thông tin chi tiết đơn thuốc

        return redirect()->route('prescription_details.index')->with('success', 'Chi tiết đơn thuốc đã được cập nhật thành công!');
    }
}