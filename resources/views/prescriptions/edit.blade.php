@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chỉnh Sửa Đơn Thuốc</h1>

    <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="patient_id">Bệnh Nhân</label>
            <select class="form-control" name="patient_id" required>
                <option value="">Chọn Bệnh Nhân</option>
                @foreach ($patients as $patient)
                    <option value="{{ $patient->id }}" {{ $patient->id == $prescription->patient_id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="employee_id">Bác Sĩ</label>
            <select class="form-control" name="employee_id" required>
                <option value="">Chọn Bác Sĩ</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $doctor->id == $prescription->employee_id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">Ngày Kê Đơn</label>
            <input type="date" class="form-control" name="date" value="{{ $prescription->date }}" required>
        </div>

        <div class="form-group">
            <label for="notes">Ghi Chú</label>
            <textarea class="form-control" name="notes">{{ $prescription->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật Đơn Thuốc</button>
    </form>
</div>
@endsection