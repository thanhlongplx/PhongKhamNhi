@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
    <div class="container">
        <h1>Chỉnh Sửa Người Dùng</h1>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Tên</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="role">Vai Trò</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="clinic_manager" {{ old('role', $user->role) == 'clinic_manager' ? 'selected' : '' }}>Clinic
                        Manager</option>
                    <option value="doctor" {{ old('role', $user->role) == 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="nurse" {{ old('role', $user->role) == 'nurse' ? 'selected' : '' }}>Nurse</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Mật Khẩu (Để trống nếu không thay đổi)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="{{ route('users') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection