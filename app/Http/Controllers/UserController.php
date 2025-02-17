<?php

namespace App\Http\Controllers;

use App\Models\User; // Đảm bảo import model đúng
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Lấy danh sách tất cả người dùng
    public function index()
    {
        // Lấy tất cả người dùng từ cơ sở dữ liệu
        $users = User::all();

        // Trả về view với dữ liệu người dùng
        return view('users.index', compact('users')); // Sử dụng compact để truyền dữ liệu
    }

    // Thêm các phương thức khác nếu cần
}