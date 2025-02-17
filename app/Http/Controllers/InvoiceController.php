<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    // Lấy danh sách hóa đơn
    public function index()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    // Tạo hóa đơn mới
    public function store(Request $request)
    {
        // Kiểm tra quyền tạo hóa đơn
        if (!in_array(auth()->user()->role, ['bác sĩ', 'nhân viên hành chính'])) {
            return response()->json(['message' => 'Bạn không có quyền tạo hóa đơn.'], 403);
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