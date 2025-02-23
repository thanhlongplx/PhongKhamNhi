@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Đơn thuốc chi tiết</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('prescriptions.create') }}" class="btn btn-primary">Thêm Đơn Thuốc</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Mã chi tiết</th>
                    <th>Mã đơn thuốc</th>
                    <th>Tên thuốc</th>
                    <th>Số lượng</th>
                    <th>Liều lượng</th>
                    <th>Tần suất</th>
                    <th>Giá</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prescriptionDetails as $prescription)
                    <tr>
                        <td>{{ $prescription->id }}</td>
                        <td>DT{{ $prescription->prescription->id ?? 'N/A' }}</td> <!-- Mã đơn thuốc -->
                        <td>{{ $prescription->medication->medicine_name ?? 'N/A' }}</td> <!-- Mã thuốc -->
                        <td>{{ $prescription->quantity }}</td> <!-- Số lượng -->
                        <td>{{ $prescription->dosage }}</td> <!-- Liều lượng -->
                        <td>{{ $prescription->frequency }}</td> <!-- Tần suất -->
                        <td>{{ $prescription->total_price }}</td> <!-- Giá -->
                        <td>{{ $prescription->created_at->format('d/m/Y H:i:s') }}</td> <!-- Ngày tạo -->

                        <td>
                            <a href="{{ route('prescription_details.edit', $prescription->id) }}"
                                class="btn btn-warning">Sửa</a>
                            <form action="{{ route('prescription_details.destroy', $prescription->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button  onclick="return confirm('Bạn có chắc chắn muốn xóa không?');" type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Phân trang -->
    </div>
@endsection