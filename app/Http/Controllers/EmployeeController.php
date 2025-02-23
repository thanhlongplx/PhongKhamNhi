<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    // Lấy danh sách tất cả nhân viên
    public function index()
    {
        // Lấy tất cả các nhân viên kèm thông tin người dùng
        $employees = Employee::with('user')->get();
        $users = User::all(); // Hoặc điều kiện phù hợp với ứng dụng của bạn

        return view('employees.index', compact('employees', 'users')); // Sử dụng compact để truyền dữ liệu
    }
    public function create()
    {
        // Lấy tất cả user_id đã có trong bảng employees
        $existingUserIds = Employee::pluck('user_id')->toArray();

        // Lấy tất cả người dùng chưa có trong bảng employees
        $users = User::whereNotIn('id', $existingUserIds)->get();

        return view('employees.create', compact('users'));
        return view('employees.index', compact('users'));
    }
    public function destroy($id)
    {
        try {
            Employee::findOrFail($id)->delete();
            return redirect()->route('employees.index')->with('success', 'Nhân viên đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    // Hàm update cũ, không tác động được bảng users và trường position của bảng đó
// public function update(Request $request, $id)
// {
//     $request->validate([
//         'position' => 'required',
//         'department' => 'nullable|string',
//         'phone_number' => 'nullable|string',
//     ]);

    //     try {
//         $employee = Employee::findOrFail($id);
//         $employee->update($request->all());
//         return redirect()->route('employees.index')->with('success', 'Thông tin nhân viên đã được cập nhật thành công.');
//     } catch (\Exception $e) {
//         return redirect()->route('employees.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//     }
// }

public function update(Request $request, $id)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'position' => 'required|string|max:255', // Sử dụng 'position' để xác thực
        'department' => 'nullable|string|max:255',
        'phone_number' => 'nullable|string|max:15',
    ]);

    // Tìm nhân viên
    $employee = Employee::findOrFail($id);

    // Cập nhật thông tin nhân viên
    $employee->update($request->only(['position', 'department', 'phone_number']));

    // Cập nhật thông tin người dùng
    $user = User::find($employee->user_id);
    if ($user) {
        $user->role = $request->position; // Cập nhật trường role
        $user->save(); // Lưu thay đổi
    }

    return redirect()->route('employees.index')->with('success', 'Cập nhật thông tin nhân viên thành công.');
}
    // Thêm nhân viên mới
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'staff_code' => 'required|unique:employees,staff_code', // Ví dụ kiểm tra unique cho staff_code
            // Các quy tắc khác nếu cần
        ]);

        try {
            // Thêm nhân viên
            Employee::create($request->all());
            return redirect()->route('employees.index')->with('success', 'Nhân viên đã được thêm thành công.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Kiểm tra mã lỗi để xác định lỗi duplicate
            if ($e->getCode() == 1062) {
                return redirect()->route('employees.index')->with('error', 'Trường ' . $this->getDuplicateField($e->getMessage()) . ' không được trùng lặp.');
            }
            return redirect()->route('employees.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Hàm để lấy trường bị lỗi từ thông báo
    private function getDuplicateField($errorMessage)
    {
        preg_match('/for key \'(.*?)\'/', $errorMessage, $matches);
        return $matches[1] ?? 'unknown field';
    }
}