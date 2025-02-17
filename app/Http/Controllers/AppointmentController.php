<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    // Lấy danh sách lịch hẹn
    public function index()
    {
        $appointments = Appointment::all();
        return response()->json($appointments);
    }

    // Tạo lịch hẹn mới
    public function store(Request $request)
    {
        // Kiểm tra xem người dùng có phải là bác sĩ không
        if (auth()->user()->role !== 'bác sĩ') {
            return response()->json(['message' => 'Bạn không có quyền tạo lịch hẹn.'], 403);
        }

        $appointment = Appointment::create($request->all());
        return response()->json($appointment, 201);
    }

    // Lấy thông tin một lịch hẹn
    public function show($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy lịch hẹn.'], 404);
        }
        return response()->json($appointment);
    }

    // Cập nhật lịch hẹn
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy lịch hẹn.'], 404);
        }

        $appointment->update($request->all());
        return response()->json($appointment);
    }

    // Xóa lịch hẹn
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy lịch hẹn.'], 404);
        }

        $appointment->delete();
        return response()->json(['message' => 'Lịch hẹn đã được xóa.']);
    }
}