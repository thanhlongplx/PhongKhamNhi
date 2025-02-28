<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Lấy danh sách tất cả người dùng
    public function index(Request $request)
{
    // Khởi tạo truy vấn lấy tất cả người dùng
    $query = User::query();

    // Kiểm tra nếu có từ khóa tìm kiếm
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('role', 'like', "%{$search}%");
        });
    }

    // Lấy danh sách người dùng với phân trang
    $users = $query->paginate(10); // Phân trang với 10 bản ghi mỗi trang

    return view('users.index', compact('users'));
}

    // Hiển thị form để thêm người dùng mới
    public function create()
    {
        return view('users.create');
    }

    // Lưu người dùng mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|max:50',
            'password' => 'required|string|min:8', // Kiểm tra mật khẩu
        ]);

        // Tạo người dùng mới với mật khẩu đã mã hóa
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password), // Hash mật khẩu
        ]);

        return redirect()->route('users')->with('success', 'Người dùng đã được thêm thành công.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|max:50',
            'password' => 'nullable|string|min:8', // Có thể không cần phải cập nhật mật khẩu
        ]);

        $data = $request->all();

        // Nếu mật khẩu được nhập, mã hóa và cập nhật
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']); // Nếu không cập nhật mật khẩu, bỏ qua
        }

        $user->update($data);
        return redirect()->route('users')->with('success', 'Người dùng đã được cập nhật thành công.');
    }
    // Hiển thị form để chỉnh sửa người dùng
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    // Xóa người dùng
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users')->with('success', 'Người dùng đã được xóa thành công.');
    }
}