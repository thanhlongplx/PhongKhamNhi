<?php

namespace App\Http\Controllers;

use App\Models\Medication; // Đảm bảo import model đúng
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    // Lấy danh sách tất cả các thuốc
    public function index(Request $request)
    {
        // Khởi tạo truy vấn lấy thuốc
        $query = Medication::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('medicine_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('dosage_form', 'like', "%{$search}%")
                    ->orWhere('strength', 'like', "%{$search}%")
                    ->orWhere('side_effect', 'like', "%{$search}%")
                    ->orWhere('contraindications', 'like', "%{$search}%");
            });
        }

        // Lấy danh sách thuốc và phân trang
        $medications = $query->orderBy('created_at', 'desc')->paginate(10); // Đảm bảo gọi paginate trên truy vấn

        return view('medications.index', compact('medications')); // Truyền dữ liệu vào view
    }

    // Thêm thuốc mới
    public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dosage_form' => 'nullable|string',
            'strength' => 'nullable|string',
            'side_effect' => 'nullable|string',
            'contraindications' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        Medication::create($request->all());
        return redirect()->route('medications')->with('success', 'Thêm thuốc thành công.');
    }

    // Hiển thị form sửa thuốc
    public function edit($id)
    {
        $medication = Medication::findOrFail($id);
        return response()->json($medication); // Trả về thông tin thuốc dưới dạng JSON
    }

    // Cập nhật thông tin thuốc
    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dosage_form' => 'nullable|string',
            'strength' => 'nullable|string',
            'side_effect' => 'nullable|string',
            'contraindications' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $medication = Medication::findOrFail($id);
        $medication->update($request->all());

        return redirect()->route('medications')->with('success', 'Cập nhật thuốc thành công.');
    }

    public function destroy($id)
    {
        $patient = Medication::findOrFail($id);
        $patient->delete();
        return redirect()->route('medications')->with('success', 'Xóa thuốc thành công.');
    }
}