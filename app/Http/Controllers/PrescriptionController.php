<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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
    public function index(Request $request)
    {
        // Khởi tạo truy vấn đơn thuốc
        $query = Prescription::with(['patient', 'doctor', 'medicalRecord', 'details.medication']);

        // Xử lý tìm kiếm
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                    ->orWhereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('doctor', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        // Lấy thông tin người dùng đã đăng nhập
        $user = auth()->user();

        // Kiểm tra vai trò người dùng và lấy đơn thuốc tương ứng
        if ($user->role === 'nurse') {
            // Lấy đơn thuốc được tạo trong ngày hôm nay
            $prescriptions = $query->whereDate('created_at', today())
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Nếu không phải bác sĩ hoặc điều dưỡng, lấy tất cả đơn thuốc
            $prescriptions = $query->orderBy('created_at', 'desc')->get();
        }

        // Sắp xếp đơn thuốc: Đơn thuốc của bác sĩ hiện tại lên đầu
        $sortedPrescriptions = $prescriptions->sortByDesc(function ($prescription) use ($user) {
            // Kiểm tra xem người dùng có phải là admin hay không
            if ($user->role === 'admin') {
                return 1; // Đặt admin lên đầu
            }

            // Kiểm tra nếu employee không tồn tại
            if (!$user->employee) {
                return 0; // Đặt không có nhân viên ở cuối
            }

            // Nếu không phải admin, so sánh employee_id
            return $prescription->employee_id === $user->employee->id ? 1 : 0;
        })->values();

        // Tạo một Paginator mới từ Collection đã sắp xếp
        $paginatedPrescriptions = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedPrescriptions->forPage($request->input('page', 1), 10),
            $sortedPrescriptions->count(),
            10,
            $request->input('page', 1),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Truyền biến $paginatedPrescriptions cho view
        return view('prescriptions.index', compact('paginatedPrescriptions', 'prescriptions'));
    }

    // Hiển thị form thêm đơn thuốc
    public function create()
    {
        $patients = Patient::whereIn('status', ['Đợi khám', 'Tái khám'])->get();
        $doctors = Employee::where('position', 'Doctor')->get(); // Lấy danh sách bác sĩ
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
            'medication_id' => 'required|array',
            'medication_id.*' => 'required|exists:medications,id',
            'dosage' => 'required|array',
            'dosage.*' => 'required|string',
            'frequency' => 'required|array',
            'frequency.*' => 'required|string',
            'usage_instructions' => 'nullable|array',
            'usage_instructions.*' => 'nullable|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'follow_up_date' => 'nullable|date|after:today',
        ]);

        // Tạo hồ sơ y tế mới cho bệnh nhân
        $medicalRecord = MedicalRecord::create([
            'visit_date' => now(),
            'symptoms' => $request->notes,
            'diagnosis' => null,
            'treatment' => null,
            'patient_id' => $request->patient_id, // Liên kết với bệnh nhân
        ]);

        // Tạo đơn thuốc
        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'notes' => $request->notes,
            'medical_record_id' => $medicalRecord->id, // Gán ID hồ sơ bệnh án
        ]);

        // Lưu các chi tiết đơn thuốc
        foreach ($request->medication_id as $index => $medicationId) {
            // Tạo chi tiết đơn thuốc
            PrescriptionDetail::create([
                'prescription_id' => $prescription->id,
                'medication_id' => $medicationId,
                'dosage' => $request->dosage[$index],
                'frequency' => $request->frequency[$index],
                'quantity' => $request->quantity[$index],
                'total_price' => $request->total_price[$index] ?? null,
                'usage_instructions' => $request->usage_instructions[$index] ?? null,
            ]);

            // Cập nhật số lượng thuốc trong bảng medications
            $medication = Medication::find($medicationId);
            if ($medication) {
                if ($medication->stock_quantity >= $request->quantity[$index]) {
                    $medication->stock_quantity -= $request->quantity[$index];
                    $medication->save();
                } else {
                    return redirect()->back()->withErrors(['quantity' => 'Số lượng thuốc không đủ trong kho.']);
                }
            }
        }

        // Xử lý ngày hẹn tái khám nếu có
        if ($request->has('follow_up_date') && $request->follow_up_date) {
            $employeeId = auth()->user()->employee ? auth()->user()->employee->id : null;

            if ($employeeId) {
                Appointment::create([
                    'patient_id' => $request->patient_id,
                    'medical_record_id' => $medicalRecord->id,
                    'appointment_time' => $request->follow_up_date,
                    'status' => 'Đợi khám',
                    'notes' => $request->notes,
                    'employee_id' => $employeeId,
                ]);
            } else {
                return redirect()->back()->withErrors(['employee' => 'Người dùng không có bác sĩ liên kết.']);
            }
        }

        // Chuyển hướng đến trang chỉnh sửa hồ sơ bệnh án mới
        return redirect()->route('medical_records.edit', ['id' => $medicalRecord->id])
            ->with('success', 'Đơn thuốc và hồ sơ bệnh án đã được tạo thành công!');
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
        $prescription = Prescription::with('details')->findOrFail($id);

        // Cập nhật thông tin đơn thuốc chính
        $prescription->patient_id = $request->input('patient_id');
        $prescription->employee_id = $request->input('employee_id');
        $prescription->date = $request->input('date');
        $prescription->notes = $request->input('notes');
        $prescription->save(); // Lưu thông tin đơn thuốc

        // Xóa tất cả chi tiết cũ trước khi lưu mới
        $prescription->details()->delete();

        // Thêm chi tiết mới
        $medicationIds = $request->input('medication_id') ?? []; // Đảm bảo biến luôn là mảng
        $dosages = $request->input('dosage') ?? [];
        $frequencies = $request->input('frequency') ?? [];
        $quantities = $request->input('quantity') ?? [];
        $totalPrices = $request->input('total_price') ?? [];
        $usageInstructions = $request->input('usage_instructions') ?? [];

        // Lưu thời gian cập nhật


        for ($i = 0; $i < count($medicationIds); $i++) {
            if (!empty($medicationIds[$i])) { // Kiểm tra không có giá trị rỗng
                $prescription->details()->create([
                    'medication_id' => $medicationIds[$i],
                    'dosage' => $dosages[$i],
                    'frequency' => $frequencies[$i],
                    'quantity' => $quantities[$i],
                    'total_price' => $totalPrices[$i],
                    'usage_instructions' => $usageInstructions[$i],
                    'updated_at' => now(), // Cập nhật thời gian
                ]);
            }
        }

        return redirect()->route('prescriptions')->with('success', 'Cập nhật đơn thuốc thành công!');
    }

    // Xóa đơn thuốc
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete(); // Xóa đơn thuốc

        return redirect()->route('prescriptions')->with('success', 'Đơn thuốc đã được xóa thành công.');
    }
    public function show($id)
    {
        // Lấy đơn thuốc theo ID và eager load các quan hệ
        $prescription = Prescription::with(['patient', 'doctor', 'medicalRecord', 'details.medication'])->findOrFail($id);

        return view('prescriptions.show', compact('prescription'));
    }
}