@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Sửa Thông Tin Bệnh Nhân</h2>
        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Thay đổi thành PUT nếu bạn đang sử dụng PUT -->

            <div class="form-group">
                <label for="name">Tên Bệnh Nhân</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $patient->name) }}" required>
            </div>

            <div class="form-group">
                <label for="date_of_birth">Ngày Sinh</label>
                <input type="date" class="form-control" name="date_of_birth"
                    value="{{ old('date_of_birth', $patient->date_of_birth) }}" required>
            </div>

            <div class="form-group">
                <label for="sex">Giới Tính</label>
                <select class="form-control" name="sex" required>
                    <option value="M" {{ $patient->sex == 'M' ? 'selected' : '' }}>Nam</option>
                    <option value="F" {{ $patient->sex == 'F' ? 'selected' : '' }}>Nữ</option>
                    <option value="O" {{ $patient->sex == 'O' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Trạng Thái</label>
                <select class="form-control" name="status" required>
                    <option value="Đợi khám" {{ $patient->status == 'Đợi khám' ? 'selected' : '' }}>Đợi khám</option>
                    <option value="Đang khám" {{ $patient->status == 'Đang khám' ? 'selected' : '' }}>Đang khám</option>
                    <option value="Đã khám" {{ $patient->status == 'Đã khám' ? 'selected' : '' }}>Đã khám</option>
                </select>
            </div>

            <div class="form-group">
                <label for="height">Chiều Cao</label>
                <input type="number" step="0.01" class="form-control" name="height"
                    value="{{ old('height', $patient->height) }}">
            </div>

            <div class="form-group">
                <label for="weight">Cân Nặng</label>
                <input type="number" step="0.01" class="form-control" name="weight"
                    value="{{ old('weight', $patient->weight) }}">
            </div>

            <div class="form-group">
                <label for="parent_name">Tên Phụ Huynh</label>
                <input type="text" class="form-control" name="parent_name"
                    value="{{ old('parent_name', $patient->parent_name) }}">
            </div>

            <div class="form-group">
                <label for="address">Địa Chỉ</label>
                <input type="text" class="form-control" name="address" value="{{ old('address', $patient->address) }}">
            </div>

            <div class="form-group">
                <label for="medical_history">Lịch Sử Bệnh</label>
                <textarea class="form-control"
                    name="medical_history">{{ old('medical_history', $patient->medical_history) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
@endsection