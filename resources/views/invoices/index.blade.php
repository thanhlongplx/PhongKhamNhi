@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center text-info mb-4">Danh Sách Hóa Đơn</h2>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5>Hóa Đơn</h5>
            </div>
            <div class="card-body">
                <!-- Form tìm kiếm -->
                <form action="{{ route('invoices.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Tìm kiếm theo ID hoặc tên bệnh nhân..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                        </div>
                    </div>
                </form>

                @if($invoices->isEmpty())
                    <p class="text-muted">Chưa có hóa đơn nào được tạo.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ID Hồ Sơ Y Tế</th>
                                <th>Bệnh Nhân</th>
                                <th>Nhân Viên Xác Nhận</th>
                                <th>Tổng Số Tiền</th>
                                <th>Ngày</th>
                                <th>Mô Tả</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr @if ($invoice->created_at->isToday()) style="background-color: lightgreen;" @endif>
                                    <td>{{ $invoice->id }}</td>
                                    <td>{{ $invoice->medical_record_id }}</td>
                                    <td>{{ $invoice->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($invoice->total_amount, 2) }} VNĐ</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</td>
                                    <td>{{ $invoice->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('patients') }}" class="btn btn-secondary">Quay Lại</a>
            </div>
        </div>

        <!-- Thêm liên kết phân trang -->
        {{ $invoices->links('pagination::bootstrap-4') }}
    </div>
@endsection