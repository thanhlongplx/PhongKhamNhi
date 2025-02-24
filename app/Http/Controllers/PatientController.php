<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    // Lấy danh sách tất cả bệnh nhân
    public function index(Request $request)
    {
        // Khởi tạo truy vấn
        $query = Patient::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('id_cccd', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('parent_name', 'like', "%{$search}%"); // Thêm tìm kiếm theo tên phụ huynh
            });
        }

        // Phân trang kết quả tìm kiếm
        $patients = $query->paginate(10);

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
        $prescriptions = $patient->prescriptions()->with('medicalRecord')->get();
        return view('patients.show', compact('patient', 'prescriptions'));
    }

    // Xóa bệnh nhân
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('patients')->with('success', 'Xóa bệnh nhân thành công.');
    }
}