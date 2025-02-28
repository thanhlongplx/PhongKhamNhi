<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Prescription;

class PatientController extends Controller
{
    // Lấy danh sách tất cả bệnh nhân
    public function index(Request $request)
    {
        // Khởi tạo truy vấn
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('sex', 'like', "%{$search}%")
                    ->orWhere('id_cccd', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('parent_name', 'like', "%{$search}%") // Tìm kiếm theo tên phụ huynh
                    ->orWhereDate('date_of_birth', '=', $search) // Tìm kiếm theo ngày sinh
                    ->orWhereDate('created_at', '=', $search); // Tìm kiếm theo ngày thêm
            });
        }

        // Sắp xếp
        if ($request->input('search') === 'chưa thanh toán') {
            $query->orderBy('created_at', 'asc'); // Sắp xếp tăng dần
        } else {
            $query->orderBy('created_at', 'desc'); // Sắp xếp giảm dần cho các tìm kiếm khác
        }

        // Phân trang kết quả tìm kiếm
        $patients = $query->paginate(10); // Sắp xếp và phân trang

        return view('patients.index', compact('patients'));
    }

    // Thêm bệnh nhân mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:M,F,O',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'parent_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:Đợi khám,Tái khám,Đang khám,Đã khám,Hủy khám',
            'phone_number' => 'required|string|max:10',
            'id_cccd' => 'required|string|max:12',
            'medical_history' => 'nullable|string',
        ]);

        Patient::create($request->all());
        return redirect()->route('patients')->with('success', 'Thêm bệnh nhân thành công.');
    }

    // Hiển thị form sửa bệnh nhân

    public function edit($id)
    {
        $patient = Patient::find($id); // Hoặc sử dụng findOrFail để tự động xử lý lỗi

        if (!$patient) {
            return redirect()->route('patients')->with('error', 'Bệnh nhân không tồn tại.');
        }

        return view('patients.edit', compact('patient'));
    }

    // Cập nhật thông tin bệnh nhân
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:M,F,O',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'parent_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:Đợi khám,Tái khám,Đang khám,Đã khám,Hủy khám',
            'phone_number' => 'required|string|max:10',
            'id_cccd' => 'required|string|max:12',
            'medical_history' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($request->only([
            'name',
            'date_of_birth',
            'sex',
            'height',
            'weight',
            'parent_name',
            'address',
            'status',
            'phone_number',
            'id_cccd',
            'medical_history'
        ]));

        return redirect()->route('patients')->with('success', 'Cập nhật thông tin bệnh nhân thành công.');
    }
    // Xem view xem hồ sơ bệnh án
    public function show($id)
    {
        $patient = Patient::findOrFail($id);

        // Lấy các đơn thuốc và sắp xếp theo thời gian từ mới nhất đến cũ nhất
        $prescriptions = $patient->prescriptions()->with('medicalRecord')->orderBy('created_at', 'desc')->get();

        return view('patients.show', compact('patient', 'prescriptions'));
    }

    // Xóa bệnh nhân
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('patients')->with('success', "Xóa bệnh nhân {$patient->name} thành công.");
    }
    public function clickChecked($id)
    {
        // Tìm bệnh nhân theo ID
        $patient = Patient::findOrFail($id);

        // Cập nhật trạng thái bệnh nhân
        if ($patient->status === 'Đã khám, hẹn tái khám' || $patient->status === 'Đã khám, không hẹn tái khám') {
            $patient->status = 'Đã khám, hẹn tái khám(đã thanh toán)';
        } else {
            $patient->status = 'Đã khám';
        }

        // Lưu trạng thái bệnh nhân
        $patient->save();

        // Lấy thông tin đơn thuốc liên quan đến bệnh nhân
        $prescription = Prescription::where('patient_id', $id)->first();

        // Kiểm tra xem có đơn thuốc không
        if (!$prescription) {
            return redirect()->route('patients')->with('error', "Không tìm thấy đơn thuốc cho bệnh nhân {$patient->name}.");
        }

        // Lấy hồ sơ y tế từ đơn thuốc
        $medicalRecord = MedicalRecord::find($prescription->medical_record_id);

        // Kiểm tra xem người dùng đã đăng nhập và có thông tin nhân viên không
        $user = auth()->user();
        if (!$user || !$user->employee) {
            return redirect()->route('patients')->with('error', "Người dùng không hợp lệ hoặc không có thông tin nhân viên.");
        }

        // Lấy ID nhân viên
        $employeeId = $user->employee->id;

        // Tính tổng chi phí từ chi tiết đơn thuốc
        $totalAmount = $prescription->details()->sum('total_price');

        // Tạo hóa đơn mới
        $invoice = new Invoice();
        $invoice->medical_record_id = $medicalRecord->id; // ID hồ sơ y tế
        $invoice->patient_id = $patient->id; // ID bệnh nhân
        $invoice->employee_id = $employeeId; // ID nhân viên
        $invoice->total_amount = $totalAmount; // Tổng số tiền
        $invoice->date = now(); // Ngày hiện tại
        $invoice->description = 'Hóa đơn cho bệnh nhân ' . $patient->name; // Mô tả hóa đơn
        $invoice->save();

        // Chuyển hướng về trang danh sách bệnh nhân với thông báo thành công
        return redirect()->route('patients')->with('success', "Đã xác nhận bệnh nhân {$patient->name} hoàn thành thanh toán và tạo hóa đơn.");
    }
    public function setWaiting($id)
{
    // Tìm bệnh nhân theo ID
    $patient = Patient::findOrFail($id);
    
    // Cập nhật trạng thái bệnh nhân
    $patient->status = 'Đợi khám';
    $patient->save(); // Lưu thay đổi

    // Tìm lịch hẹn của bệnh nhân
    $appointment = Appointment::where('patient_id', $patient->id)
        ->where('appointment_time', '>', now()) // Chỉ tìm lịch hẹn trong tương lai
        ->first();

    // Nếu tìm thấy lịch hẹn, cập nhật trạng thái của nó
    if ($appointment) {
        $appointment->status = 'Hoàn thành'; // Cập nhật trạng thái lịch hẹn
        $appointment->save(); // Lưu thay đổi
    }

    return redirect()->route('patients')->with('success', 'Trạng thái bệnh nhân đã được cập nhật thành "Đợi khám" và lịch hẹn đã được đánh dấu là "Hoàn thành".');
}
}