<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Hiển thị danh sách tất cả lịch hẹn
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'employee', 'medicalRecord']); // Tải trước các mối quan hệ

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('employee', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Lấy danh sách lịch hẹn
        $appointments = $query->orderBy('appointment_time', 'desc')->paginate(10); // Chuyển từ get() sang paginate()

        return view('appointments.index', compact('appointments'));
    }

    // Hiển thị form tạo lịch hẹn
    public function create()
    {
        $patients = Patient::all(); // Lấy danh sách bệnh nhân
        $employees = Employee::all(); // Lấy danh sách nhân viên
        $medicalRecords = MedicalRecord::all(); // Lấy danh sách hồ sơ bệnh án

        return view('appointments.create', compact('patients', 'employees', 'medicalRecords'));
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
        $patients = Patient::all();
        $employees = Employee::all();
        $medicalRecords = MedicalRecord::all();

        return view('appointments.edit', compact('appointment', 'patients', 'employees', 'medicalRecords'));
    }

    // Cập nhật lịch hẹn
    public function update(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'appointment_time' => 'required|date',
        ]);

        // Nếu có ID, cập nhật
        if ($request->has('id')) {
            $appointment = Appointment::findOrFail($request->id);

            // Chỉ cập nhật trường thời gian
            $appointment->update([
                'appointment_time' => $request->appointment_time,
            ]);

            $message = 'Cập nhật lịch hẹn thành công.';
        } else {
            // Nếu không có ID, có thể tạo mới (tuỳ chọn)
            Appointment::create($request->all());
            $message = 'Tạo lịch hẹn thành công.';
        }

        return redirect()->route('appointments.index')->with('success', $message);
    }

    // Xóa lịch hẹn
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Xóa lịch hẹn thành công.');
    }
    
}