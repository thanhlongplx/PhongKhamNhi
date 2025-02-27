<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Hiển thị danh sách tất cả lịch hẹn
    public function index()
    {
        $appointments = Appointment::with(['patient', 'employee', 'medicalRecord'])
            ->orderBy('appointment_time', 'desc')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    // Hiển thị form tạo lịch hẹn
    public function create()
    {
        // Truyền dữ liệu cần thiết cho view (ví dụ: danh sách bệnh nhân, nhân viên)
        return view('appointments.create');
    }

    // Lưu lịch hẹn
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'employee_id' => 'required|exists:employees,id',
            'medical_record_id' => 'required|exists:medical_records,id',
            'appointment_time' => 'required|date',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Appointment::create($request->all());

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    // Hiển thị lịch hẹn cụ thể
    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    // Hiển thị form chỉnh sửa lịch hẹn
    public function edit(Appointment $appointment)
    {
        return view('appointments.edit', compact('appointment'));
    }

    // Cập nhật lịch hẹn
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'employee_id' => 'required|exists:employees,id',
            'medical_record_id' => 'required|exists:medical_records,id',
            'appointment_time' => 'required|date',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($request->all());

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    // Xóa lịch hẹn
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }
}