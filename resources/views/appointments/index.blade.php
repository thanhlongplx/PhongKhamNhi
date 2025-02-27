@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh Sách Lịch Hẹn</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-3">Thêm Lịch Hẹn Mới</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bệnh Nhân</th>
                    <th>Bác Sĩ</th>
                    <th>Hồ Sơ Bệnh Án</th>
                    <th>Thời Gian Hẹn</th>
                    <th>Trạng Thái</th>
                    <th>Ghi Chú</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->id }}</td>
                        <td>{{ $appointment->patient ? $appointment->patient->name : 'N/A' }}</td>
                        <td>{{ $appointment->employee ? $appointment->employee->name : 'N/A' }}</td>
                        <td>CTHS{{ $appointment->medicalRecord ? $appointment->medicalRecord->id : 'N/A' }}</td>
                        <td>{{ $appointment->appointment_time }}</td>
                        <td>{{ $appointment->status }}</td>
                        <td>{{ $appointment->notes }}</td>
                        <td>
                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-info">Xem</a>
                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning">Chỉnh Sửa</a>
                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST"
                                style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection