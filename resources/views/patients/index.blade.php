@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Danh Sách Bệnh Nhân</h2>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPatientModal">
            Thêm Bệnh Nhân
        </button>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Ngày Sinh</th>
                    <th>Giới Tính</th>
                    <th>Chiều Cao</th>
                    <th>Cân Nặng</th>
                    <th>Tên Phụ Huynh</th>
                    <th>Địa Chỉ</th>
                    <th>Trạng thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patients as $patient)
                    <tr>
                        <td>{{ $patient->name}}</td>
                        <td>{{ $patient->date_of_birth}}</td>
                        <td>{{ $patient->sex }}</td>
                        <td>{{ $patient->height }}</td>
                        <td>{{ $patient->weight }}</td>
                        <td>{{ $patient->parent_name }}</td>
                        <td>{{ $patient->address }}</td>
                        <td>{{ $patient->status }}</td>
                        <td>
                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $patients->links() }} <!-- Phân trang -->
    </div>
@endsection

<!-- Modal Thêm Bệnh Nhân -->
<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-labelledby="addPatientModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientModalLabel">Thêm Bệnh Nhân Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('patients.store') }}" method="POST" id="addPatientForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Tên Bệnh Nhân</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Ngày Sinh</label>
                        <input type="date" class="form-control" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="sex">Giới Tính</label>
                        <select class="form-control" name="sex" required>
                            <option value="M">Nam</option>
                            <option value="F">Nữ</option>
                            <option value="O">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="height">Chiều Cao</label>
                        <input type="number" step="0.01" class="form-control" name="height">
                    </div>
                    <div class="form-group">
                        <label for="weight">Cân Nặng</label>
                        <input type="number" step="0.01" class="form-control" name="weight">
                    </div>
                    <div class="form-group">
                        <label for="parent_name">Tên Phụ Huynh</label>
                        <input type="text" class="form-control" name="parent_name">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ</label>
                        <input type="text" class="form-control" name="address">
                    </div>
                    <!-- Thêm dropdown trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng Thái</label>
                        <select class="form-control" name="status" required>
                            <option value="Đợi khám">Đợi khám</option>
                            <option value="Đang khám">Đang khám</option>
                            <option value="Đã khám">Đã khám</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="medical_history">Lịch Sử Bệnh</label>
                        <textarea class="form-control" name="medical_history"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm Bệnh Nhân</button>
                </div>
            </form>
        </div>
    </div>
</div>