@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Sửa Chi Tiết Đơn Thuốc</h1>

        <form action="{{ route('prescription_details.update', $prescription->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="prescription_id">Mã Đơn Thuốc</label>
                <select class="form-control" name="prescription_id" required>
                    <option value="">Chọn Đơn Thuốc</option>
                    @foreach ($prescriptions as $prescriptionOption)
                        <option value="{{ $prescriptionOption->id }}" {{ $prescription->prescription_id == $prescriptionOption->id ? 'selected' : '' }}>{{ $prescriptionOption->id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="medication_id">Mã Thuốc</label>
                <select class="form-control" name="medication_id" required>
                    <option value="">Chọn Thuốc</option>
                    @foreach ($medications as $medication)
                        <option value="{{ $medication->id }}" {{ $prescription->medication_id == $medication->id ? 'selected' : '' }}>{{ $medication->medicine_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Số Lượng</label>
                <input type="number" class="form-control" name="quantity" value="{{ $prescription->quantity }}" required>
            </div>

            <div class="form-group">
                <label for="dosage">Liều Lượng</label>
                <input type="text" class="form-control" name="dosage" value="{{ $prescription->dosage }}" required>
            </div>

            <div class="form-group">
                <label for="frequency">Tần Suất</label>
                <input type="text" class="form-control" name="frequency" value="{{ $prescription->frequency }}" required>
            </div>

            <div class="form-group">
                <label for="total_price">Giá</label>
                <input type="number" class="form-control" name="total_price" value="{{ $prescription->total_price }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật Chi Tiết Đơn Thuốc</button>
        </form>
    </div>
@endsection