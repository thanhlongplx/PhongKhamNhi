@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Chỉnh Sửa Lịch Hẹn</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('appointments.update') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $appointment->id }}">

            <div class="form-group">
                <label for="patient_id">Bệnh Nhân</label>
                <select name="patient_id" id="patient_id" class="form-control" disabled>
                    <option value="">Chọn bệnh nhân</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="employee_id">Nhân Viên (Bác Sĩ)</label>
                <select name="employee_id" id="employee_id" class="form-control" disabled>
                    <option value="">Chọn nhân viên</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $appointment->employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="medical_record_id">Hồ Sơ Bệnh Án</label>
                <input type="text" name="medical_record_id" class="form-control" 
                       value="{{ $appointment->medical_record_id }}" readonly>
            </div>

            <div class="form-group">
                <label for="appointment_time">Thời Gian Lịch Hẹn</label>
                <input type="datetime-local" name="appointment_time" id="appointment_time" class="form-control"
                    value="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="form-group">
                <label for="status">Trạng Thái</label>
                <select name="status" id="status" class="form-control" disabled>
                    <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Đã Lên Lịch</option>
                    <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Đã Hoàn Thành</option>
                    <option value="canceled" {{ $appointment->status == 'canceled' ? 'selected' : '' }}>Đã Hủy</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notes">Ghi Chú</label>
                <textarea name="notes" id="notes" class="form-control" readonly>{{ $appointment->notes }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Cập Nhật</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection