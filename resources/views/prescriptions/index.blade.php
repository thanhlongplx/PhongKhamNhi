@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Danh Sách Đơn Thuốc</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'doctor')
            <a href="{{ route('prescriptions.create') }}" class="btn btn-primary">Thêm Đơn Thuốc</a>
        @endif
        <form action="{{ route('prescriptions') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Tìm kiếm theo mã đơn thuốc, bệnh nhân, bác sĩ..." value="{{ request()->input('search') }}">
                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover mt-3">
                <thead class="thead-light">
                    <tr>
                        <th>Code</th>
                        <th>Bệnh Nhân</th>
                        <th>Bác Sĩ</th>
                        <th>Mã hồ sơ bệnh án</th>
                        <th>Ngày Kê Đơn</th>
                        <th>Triệu chứng</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
    {{-- Đơn thuốc của tất cả bệnh nhân nếu là admin --}}
    @if (auth()->user()->role === 'admin')
        @foreach ($paginatedPrescriptions as $prescription)
            <tr style="color: black;">
                <td>DT{{ $prescription->id }}</td>
                <td>{{ $prescription->patient->name }}</td>
                <td>{{ $prescription->doctor->name }}</td>
                <td>HS{{ $prescription->medical_record_id }}</td>
                <td>{{ \Carbon\Carbon::parse($prescription->date)->format('d-m-Y') }}</td>
                <td>{{ $prescription->notes }}</td>
                <td>
                    <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning">Sửa</a>
                    <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                    </form>
                    <br>
                    <a href="{{ route('medical_records.edit', $prescription->medical_record_id) }}" class="btn btn-info">Chỉnh Sửa Hồ Sơ Bệnh Án</a>
                </td>
            </tr>
        @endforeach
    @else
        {{-- Đơn thuốc của bác sĩ hiện tại --}}
        @foreach ($paginatedPrescriptions as $prescription)
            @if ($prescription->employee_id === auth()->user()->employee->id)
                <tr style="color: black; background-color: lightgreen;">
                    <td>DT{{ $prescription->id }}</td>
                    <td>{{ $prescription->patient->name }}</td>
                    <td>{{ $prescription->doctor->name }}</td>
                    <td>HS{{ $prescription->medical_record_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($prescription->date)->format('d-m-Y') }}</td>
                    <td>{{ $prescription->notes }}</td>
                    <td>
                        @if ($prescription->created_at->isToday())
                            <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                            </form>
                            <br>
                            <a href="{{ route('medical_records.edit', $prescription->medical_record_id) }}" class="btn btn-info">Chỉnh Sửa Hồ Sơ Bệnh Án</a>
                        @else
                            <p>Chỉ chỉnh sửa đơn thuốc cùng ngày</p>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach

        {{-- Đơn thuốc của các bác sĩ khác --}}
        @foreach ($paginatedPrescriptions as $prescription)
            @if ($prescription->employee_id !== auth()->user()->employee->id)
                <tr style="color: black; background-color: lightblue;">
                    <td>DT{{ $prescription->id }}</td>
                    <td>{{ $prescription->patient->name }}</td>
                    <td>{{ $prescription->doctor->name }}</td>
                    <td>HS{{ $prescription->medical_record_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($prescription->date)->format('d-m-Y') }}</td>
                    <td>{{ $prescription->notes }}</td>
                    <td>
                        @if ($prescription->created_at->isToday())
                            @if (auth()->user()->role === 'doctor' && auth()->user()->employee->id === $prescription->employee_id)
                                <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning">Sửa</a>
                                <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                                </form>
                            @else
                                <p>Quyền chỉnh sửa thuộc về bác sĩ khác</p>
                            @endif
                        @else
                            <p>Chỉ chỉnh sửa đơn thuốc cùng ngày</p>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
    @endif
</tbody>
            </table>
        </div>


        {{ $paginatedPrescriptions->links('pagination::bootstrap-4') }}
    </div>
@endsection