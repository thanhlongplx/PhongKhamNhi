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

    //Tạo chi tiết dựa trên đơn thuốc vừa tạo
    public function create($prescriptionId)
    {
        $prescription = Prescription::findOrFail($prescriptionId); // Tìm đơn thuốc theo ID
        $medications = Medication::all(); // Lấy danh sách thuốc

        return view('prescription_details.create', compact('prescription', 'medications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'required|integer|min:1',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'usage_instructions' => 'nullable|string', // Nếu có trường này
        ]);

        PrescriptionDetail::create($request->all()); // Lưu chi tiết đơn thuốc

        return redirect()->route('prescription_details.index', [$request->prescription_id])->with('success', 'Chi tiết đơn thuốc đã được thêm thành công!');
    }




    // Lấy danh sách chi tiết đơn thuốc
    public function index()
    {
        // Khởi tạo truy vấn chi tiết đơn thuốc
        $query = PrescriptionDetail::with(['patient', 'prescription', 'medication'])
            ->orderBy('created_at', 'desc'); // Sắp xếp theo thời gian từ mới nhất đến cũ nhất

        // Kiểm tra vai trò người dùng
        if (auth()->user()->role === 'nurse') {
            // Nếu là điều dưỡng, chỉ lấy chi tiết đơn thuốc được tạo trong ngày hôm nay
            $prescriptionDetails = $query->whereDate('created_at', today())->get();
        } else {
            // Nếu không phải điều dưỡng, lấy tất cả chi tiết đơn thuốc
            $prescriptionDetails = $query->get();
        }

        return view('prescription_details.index', compact('prescriptionDetails')); // Truyền dữ liệu vào view
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
    public function destroy($id)
    {
        $prescriptionDetail = PrescriptionDetail::findOrFail($id); // Tìm chi tiết đơn thuốc theo ID

        $prescriptionDetail->delete(); // Xóa chi tiết đơn thuốc

        return redirect()->route('prescription_details.index')->with('success', 'Chi tiết đơn thuốc đã được xóa thành công!');
    }
}