@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chỉnh Sửa Hồ Sơ Bệnh Án</h1>

    <form action="{{ route('medical_records.update', $medicalRecord->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="visit_date">Ngày Khám</label>
            <input type="date" class="form-control" name="visit_date" value="{{ now()->format('Y-m-d') }}" readonly>
        </div>

        <div class="form-group">
            <label for="symptoms">Triệu Chứng</label>
            <textarea class="form-control" name="symptoms" required>{{ old('symptoms', $medicalRecord->symptoms) }}</textarea>
        </div>

        <div class="form-group">
            <label for="diagnosis">Chẩn Đoán</label>
            <textarea class="form-control" name="diagnosis" required>{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
        </div>

        <div class="form-group">
            <label for="treatment">Phương Pháp Điều Trị</label>
            <textarea class="form-control" name="treatment" required>{{ old('treatment', $medicalRecord->treatment) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật Hồ Sơ</button>
    </form>
</div>
@endsection