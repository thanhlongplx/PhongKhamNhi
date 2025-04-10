@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
    <div class="container">
        <h1>Danh Sách Hồ Sơ Bệnh Án</h1>
        @if(auth()->user()->role === 'doctor')
            <a href="{{ route('prescriptions.create') }}" class="btn btn-primary">Tiếp tục khám (Quay lại tạo đơn thuốc)</a>
        @endif

        <!-- Form tìm kiếm -->
        <form action="{{ route('medical_records.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Tìm kiếm theo mã hồ sơ, triệu chứng, chẩn đoán, tên bệnh nhân..." value="{{ request()->input('search') }}">
                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Mã chi tiết hồ sơ</th>
                    <th>Ngày Khám</th>
                    <th>Triệu Chứng</th>
                    <th>Chẩn Đoán</th>
                    <th>Phác Đồ Điều Trị</th>
                    <th>Tên Bệnh Nhân</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medicalRecords as $record)
                    <tr>
                        <td>CTHS{{ $record->id }}</td> <!-- ID hồ sơ -->
                        <td>{{ $record->visit_date }}</td> <!-- Ngày khám -->
                        <td>{{ $record->symptoms }}</td> <!-- Triệu chứng -->
                        <td>{{ $record->diagnosis }}</td> <!-- Chẩn đoán -->
                        <td>{{ $record->treatment }}</td> <!-- Phác đồ điều trị -->
                        <td>
                            @if($record->prescriptions->isNotEmpty())
                                {{ $record->prescriptions->first()->patient->name }} <!-- Tên bệnh nhân -->
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $record->created_at }}</td> <!-- Ngày tạo -->
                        <td>{{ $record->updated_at }}</td> <!-- Ngày cập nhật -->
                        <td>
                            @if (auth()->user()->role === 'admin' || (auth()->user()->role === 'clinic_manager' && $record->created_at >= now()->subDays(30)))
                                <a href="{{ route('medical_records.edit', $record->id) }}" class="btn btn-warning">Sửa</a>
                                <form action="{{ route('medical_records.destroy', $record->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                                </form>
                            @elseif ((auth()->user()->role === 'doctor') && $record->created_at->isToday())
                                @foreach ($record->prescriptions as $prescription)
                                    @if (auth()->user()->employee->id === $prescription->employee_id)
                                        <a href="{{ route('medical_records.edit', $record->id) }}" class="btn btn-warning">Sửa</a>
                                        <form action="{{ route('medical_records.destroy', $record->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                                        </form>
                                    @endif
                                @endforeach
                            @elseif(auth()->user()->role === 'nurse')
                                <span class="text-muted">Điều dưỡng không có quyền chỉnh sửa hồ sơ bệnh nhân</span>
                            @else
                                <span class="text-muted">Bác sĩ không sửa được hồ sơ sau 1 ngày</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Thêm liên kết phân trang -->
        {{ $medicalRecords->links('pagination::bootstrap-4') }}
    </div>
@endsection