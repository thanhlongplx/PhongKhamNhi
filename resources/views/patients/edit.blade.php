@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Sửa Thông Tin Bệnh Nhân</h2>
        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Tên Bệnh Nhân</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $patient->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="date_of_birth">Ngày Sinh</label>
                <input type="date" class="form-control" name="date_of_birth"
                    value="{{ old('date_of_birth', $patient->date_of_birth) }}" required>
                @error('date_of_birth')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sex">Giới Tính</label>
                <select class="form-control" name="sex" required>
                    <option value="M" {{ $patient->sex == 'M' ? 'selected' : '' }}>Nam</option>
                    <option value="F" {{ $patient->sex == 'F' ? 'selected' : '' }}>Nữ</option>
                    <option value="O" {{ $patient->sex == 'O' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('sex')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Trạng Thái</label>
                <select class="form-control" name="status" required>
                    <option value="Đợi khám" {{ $patient->status == 'Đợi khám' ? 'selected' : '' }}>Đợi khám</option>
                    <option value="Đang khám" {{ $patient->status == 'Đang khám' ? 'selected' : '' }}>Đang khám</option>
                    <option value="Đã khám" {{ $patient->status == 'Đã khám' ? 'selected' : '' }}>Đã khám</option>
                    <option value="Hủy khám" {{ $patient->status == 'Hủy khám' ? 'selected' : '' }}>Hủy khám</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="height">Chiều Cao</label>
                <input type="number" step="0.01" class="form-control" name="height"
                    value="{{ old('height', $patient->height) }}">
                @error('height')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="weight">Cân Nặng</label>
                <input type="number" step="0.01" class="form-control" name="weight"
                    value="{{ old('weight', $patient->weight) }}">
                @error('weight')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="parent_name">Tên Phụ Huynh</label>
                <input type="text" class="form-control" name="parent_name"
                    value="{{ old('parent_name', $patient->parent_name) }}">
                @error('parent_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Địa Chỉ</label>
                <input type="text" class="form-control" name="address" value="{{ old('address', $patient->address) }}">
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone_number">Số điện thoại</label>
                <input type="text" class="form-control" name="phone_number"
                    value="{{ old('phone_number', $patient->phone_number) }}">
                @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_cccd">CCCD</label>
                <input type="text" class="form-control" name="id_cccd" value="{{ old('id_cccd', $patient->id_cccd) }}">
                @error('id_cccd')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="medical_history">Lịch Sử Bệnh</label>
                <textarea class="form-control"
                    name="medical_history">{{ old('medical_history', $patient->medical_history) }}</textarea>
                @error('medical_history')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
@endsection