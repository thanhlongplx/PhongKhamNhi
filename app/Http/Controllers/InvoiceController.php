<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    // Lấy danh sách hóa đơn
    public function index()
{
    $invoices = Invoice::all();
    return view('invoices.index', compact('invoices')); // Đảm bảo bạn trả về view
}

    // Tạo hóa đơn mới
    public function store(Request $request)
    {
        // Kiểm tra quyền tạo hóa đơn
        if (!in_array(auth()->user()->role, ['bác sĩ', 'nhân viên hành chính'])) {
            return response()->json(['message' => 'Bạn không có quyền tạo hóa đơn.'], 403);
        }

        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'required|integer',
            'patient_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $invoice = Invoice::create($request->all());
        return response()->json($invoice, 201);
    }

    // Lấy thông tin một hóa đơn
    public function show($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return response()->json(['message' => 'Không tìm thấy hóa đơn.'], 404);
        }
        return response()->json($invoice);
    }

    // Cập nhật hóa đơn
    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return response()->json(['message' => 'Không tìm thấy hóa đơn.'], 404);
        }

        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'sometimes|integer',
            'patient_id' => 'sometimes|integer',
            'employee_id' => 'sometimes|integer',
            'total_amount' => 'sometimes|numeric|min:0',
            'date' => 'sometimes|date',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $invoice->update($request->all());
        return response()->json($invoice);
    }

    // Xóa hóa đơn
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return response()->json(['message' => 'Không tìm thấy hóa đơn.'], 404);
        }

        $invoice->delete();
        return response()->json(['message' => 'Hóa đơn đã được xóa.']);
    }
}