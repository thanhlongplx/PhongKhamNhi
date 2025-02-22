@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh Sách Đơn Thuốc</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('prescriptions.create') }}" class="btn btn-primary">Thêm Đơn Thuốc</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Bệnh Nhân</th>
                    <th>Bác Sĩ</th>
                    <th>Mã hồ sơ bệnh án</th>
                    <th>Ngày Kê Đơn</th>
                    <th>Ghi Chú</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prescriptions as $prescription)
                    <tr>
                        <td>DT{{ $prescription->id }}</td>
                        <td>{{ $prescription->patient->name }}</td>
                        <td>{{ $prescription->doctor->name }}</td>
                        <td>{{ $prescription->medical_record_id }}</td>
                        <td>{{ $prescription->date }}</td>
                        <td>{{ $prescription->notes }}</td>
                        <td>
                            <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $prescriptions->links() }} <!-- Phân trang -->
    </div>
@endsection