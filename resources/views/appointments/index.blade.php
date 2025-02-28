@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh Sách Lịch Hẹn</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('appointments.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                    value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bệnh Nhân</th>
                    <th>Bác Sĩ</th>
                    <th>Hồ Sơ Bệnh Án</th>
                    <th>Thời Gian Hẹn</th>
                    <th>Trạng thái</th>
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
                                    @if(
                                        auth()->user()->role === 'nurse' &&
                                        $appointment->patient->status !== 'Đợi khám' &&
                                        $appointment->status !== 'Hoàn thành' &&
                                        (now()->isSameDay($appointment->appointment_time) || now()->greaterThan($appointment->appointment_time))
                                    )
                                                        <form action="{{ route('patients.setWaiting', $appointment->patient->id) }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-info">Chuyển Thành Đợi Khám</button>
                                                        </form>
                                    @endif
                                </td>
                            </tr>
                @endforeach
            </tbody>
        </table>
        <div>
    {{ $appointments->links() }} <!-- Phân trang -->
</div>
    </div>
@endsection