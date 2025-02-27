@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
<div class="container">
    <h1>Thêm Người Dùng Mới</h1>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Tên</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="role">Vai Trò</label>
            <select class="form-control" id="role" name="role" required>
                <option value="clinic_manager">Clinic Manager</option>
                <option value="doctor">Doctor</option>
                <option value="nurse">Nurse</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Mật Khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('users') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection