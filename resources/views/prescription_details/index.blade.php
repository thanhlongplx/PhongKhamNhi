@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Đơn thuốc chi tiết</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'doctor')
            <a href="{{ route('prescriptions.create') }}" class="btn btn-primary">Thêm Đơn Thuốc</a>
        @endif

        <!-- Form tìm kiếm -->
        <form action="{{ route('prescription_details.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                    value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

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
                    <tr @if ($prescription->created_at->isToday()) style="background-color: lightgreen;" @endif>
                        <td>{{ $prescription->id }}</td>
                        <td>DT{{ $prescription->prescription->id ?? 'N/A' }}</td> <!-- Mã đơn thuốc -->
                        <td>{{ $prescription->medication->medicine_name ?? 'N/A' }}</td> <!-- Tên thuốc -->
                        <td>{{ $prescription->quantity }}</td> <!-- Số lượng -->
                        <td>{{ $prescription->dosage }}</td> <!-- Liều lượng -->
                        <td>{{ $prescription->frequency }}</td> <!-- Tần suất -->
                        <td>{{ $prescription->total_price }}</td> <!-- Giá -->
                        <td>{{ $prescription->created_at->format('d/m/Y H:i:s') }}</td> <!-- Ngày tạo -->

                        <td>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('prescription_details.edit', $prescription->id) }}"
                                    class="btn btn-warning">Sửa</a>
                                <form action="{{ route('prescription_details.destroy', $prescription->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc chắn muốn xóa không?');" type="submit"
                                        class="btn btn-danger">Xóa</button>
                                </form>
                            @elseif(auth()->user()->role === 'doctor' && $prescription->created_at->isToday() && auth()->user()->employee->id === $prescription->prescription->employee_id)
                                <a href="{{ route('prescription_details.edit', $prescription->id) }}"
                                    class="btn btn-warning">Sửa</a>
                                <form action="{{ route('prescription_details.destroy', $prescription->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc chắn muốn xóa không?');" type="submit"
                                        class="btn btn-danger">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Thêm liên kết phân trang -->
        {{ $prescriptionDetails->links('pagination::bootstrap-4') }}
    </div>
@endsection