<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    // Lấy danh sách tất cả bệnh nhân
    public function index()
    {
        $patients = Patient::paginate(10);
        return view('patients.index', compact('patients'));
        $doctors = Employee::paginate(10);
        return view('patients.index', compact('doctors'));
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
            'medical_history' => 'nullable|string',
        ]);

        Patient::create($request->all());
        return redirect()->route('patients')->with('success', 'Thêm bệnh nhân thành công.');
    }

    // Hiển thị form sửa bệnh nhân
    public function edit($id)
    {
        $patients = Patient::findOrFail($id);
        return view('patients.index', compact('patients'));
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
            'medical_history'
        ]));

        return redirect()->route('patients.index')->with('success', 'Cập nhật thông tin bệnh nhân thành công.');
    }

    // Xóa bệnh nhân
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('patients')->with('success', 'Xóa bệnh nhân thành công.');
    }
}