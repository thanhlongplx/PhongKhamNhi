@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sửa Đơn Thuốc</h1>

    <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="medical_record_id">Hồ Sơ Bệnh Án</label>
            <select class="form-control" name="medical_record_id" required>
                <option value="">Chọn Hồ Sơ Bệnh Án</option>
                @foreach ($medicalRecords as $record)
                    <option value="{{ $record->id }}" {{ $prescription->medical_record_id == $record->id ? 'selected' : '' }}>{{ $record->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="patient_id">Bệnh Nhân</label>
            <select class="form-control" name="patient_id" required>
                @foreach ($patients as $patient)
                    <option value="{{ $patient->id }}" {{ $prescription->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="employee_id">Bác Sĩ</label>
            <select class="form-control" name="employee_id" required>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $prescription->employee_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
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