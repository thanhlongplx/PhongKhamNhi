<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    // Hiển thị form đăng ký


    // Xử lý đăng ký


    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        // Xác thực dữ liệu
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công
            return redirect()->intended('staffs'); // Redirect đến trang bạn muốn
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    // Xử lý đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login'); // Chuyển hướng về trang đăng nhập
    }
}