@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Đăng Ký</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Mật Khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Xác Nhận Mật Khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng Ký</button>
    </form>
</div>
@endsection