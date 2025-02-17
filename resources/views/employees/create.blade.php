@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
    <div class="container">
        <h1>Thêm Nhân Viên</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employees.store') }}" method="POST">
            @csrf <!-- Thêm csrf token -->
            <div class="form-group">
    <label for="user_id">Người Dùng</label>
    <select name="user_id" id="user_id" class="form-control" required>
        <option value="">Chọn người dùng</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
</div>
            <div class="form-group">
                <label for="staff_code">Mã Nhân Viên</label>
                <input type="text" class="form-control" name="staff_code" required>
            </div>
            <div class="form-group">
                <label for="name">Tên Nhân Viên</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label for="position">Chức Vụ</label>
                <input type="text" class="form-control" name="position" required>
            </div>
            <div class="form-group">
                <label for="department">Phòng Ban</label>
                <input type="text" class="form-control" name="department">
            </div>
            <div class="form-group">
                <label for="phone_number">Số Điện Thoại</label>
                <input type="text" class="form-control" name="phone_number">
            </div>
            <div class="form-group">
                <label for="date_of_hire">Ngày Tuyển Dụng</label>
                <input type="date" class="form-control" name="date_of_hire">
            </div>
            <button type="submit" class="btn btn-primary">Thêm Nhân Viên</button>
        </form>
    </div>
@endsection