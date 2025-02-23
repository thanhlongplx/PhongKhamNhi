@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thêm Chi Tiết Đơn Thuốc</h1>

    <form action="{{ route('prescription_details.store') }}" method="POST">
        @csrf

        <input type="hidden" name="prescription_id" value="{{ $prescription->id }}">

        <div class="form-group">
            <label for="medication_id">Thuốc</label>
            <select class="form-control" name="medication_id" required>
                <option value="">Chọn Thuốc</option>
                @foreach ($medications as $medication)
                    <option value="{{ $medication->id }}">{{ $medication->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Số Lượng</label>
            <input type="number" class="form-control" name="quantity" required>
        </div>

        <div class="form-group">
            <label for="dosage">Liều Lượng</label>
            <input type="text" class="form-control" name="dosage" required>
        </div>

        <div class="form-group">
            <label for="frequency">Tần Suất</label>
            <input type="text" class="form-control" name="frequency" required>
        </div>

        <div class="form-group">
            <label for="total_price">Tổng Giá</label>
            <input type="number" class="form-control" name="total_price" required>
        </div>

        <div class="form-group">
            <label for="usage_instructions">Hướng Dẫn Sử Dụng</label>
            <textarea class="form-control" name="usage_instructions"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Chi Tiết Đơn Thuốc</button>
    </form>
</div>
@endsection