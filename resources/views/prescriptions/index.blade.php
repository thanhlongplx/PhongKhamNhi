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
                    @foreach ($prescriptions as $prescription)
                        <tr>
                            <td>DT{{ $prescription->id }}</td>
                            <td>{{ $prescription->patient->name }}</td>
                            <td>{{ $prescription->doctor->name }}</td>
                            <td>HS{{ $prescription->medical_record_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($prescription->date)->format('d-m-Y') }}</td> <!-- Sửa phần này -->
                            <td>{{ $prescription->notes }}</td>
                            <td>
                                @if ($prescription->created_at->isToday())
                                    @if ((auth()->user()->role === 'doctor' && auth()->user()->employee->id === $prescription->employee_id) || auth()->user()->role === 'admin')
                                        <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning">Sửa</a>
                                        <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                                        </form>
                                    @endif
                                @elseif(auth()->user()->role === 'admin')
                                    <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning">Sửa</a>
                                    <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                                    </form>
                                @endif
                                <br>
                                @if (auth()->user()->role === 'doctor' && auth()->user()->employee->id === $prescription->employee_id && $prescription->created_at->isToday())
                                    <a href="{{ route('medical_records.edit', $prescription->medical_record_id) }}"
                                        class="btn btn-info">Chỉnh Sửa Hồ Sơ Bệnh Án</a>
                                @elseif(auth()->user()->role === 'admin')
                                    <a href="{{ route('medical_records.edit', $prescription->medical_record_id) }}"
                                        class="btn btn-info">Chỉnh Sửa Hồ Sơ Bệnh Án</a>
                                @else
                                    <p>Bác sĩ khác</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $prescriptions->links() }} <!-- Phân trang -->
    </div>
@endsection