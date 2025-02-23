@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center text-info mb-4">Danh Sách Bệnh Nhân</h2>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPatientModal">
            Thêm Bệnh Nhân
        </button>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped table-hover">
            <thead>
                <tr class="table-primary">
                    <th>Tên</th>
                    <th>Ngày Sinh</th>
                    <th>Giới Tính</th>
                    <th>Chiều Cao</th>
                    <th>Cân Nặng</th>
                    <th>Tên Phụ Huynh</th>
                    <th>Trạng thái</th>
                    <th>Địa chỉ</th>
                    <th>SDT</th>
                    <th>CCCD</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patients as $patient)
                    <tr>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->date_of_birth }}</td>
                        <td>{{ $patient->sex }}</td>
                        <td>{{ $patient->height }}</td>
                        <td>{{ $patient->weight }}</td>
                        <td>{{ $patient->parent_name }}</td>
                        <td>{{ $patient->status }}</td>
                        <td>{{ $patient->address }}</td>
                        <td>{{ $patient->phone_number }}</td>
                        <td>{{ $patient->id_cccd }}</td>
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
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Ngày Sinh</label>
                            <input type="date" class="form-control" name="date_of_birth" required max="{{ date('Y-m-d') }}">
                            @error('date_of_birth')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sex">Giới Tính</label>
                            <select class="form-control" name="sex" required>
                                <option value="M">Nam</option>
                                <option value="F">Nữ</option>
                                <option value="O">Khác</option>
                            </select>
                            @error('sex')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="height">Chiều Cao (cm)</label>
                            <input type="number" step="0.01" class="form-control" name="height">
                            @error('height')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="weight">Cân Nặng (kg)</label>
                            <input type="number" step="0.01" class="form-control" name="weight">
                            @error('weight')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="parent_name">Tên Phụ Huynh</label>
                            <input type="text" class="form-control" name="parent_name">
                            @error('parent_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Địa Chỉ</label>
                            <input type="text" class="form-control" name="address">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone_number">
                            @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_cccd">Căn cước công dân</label>
                            <input type="text" class="form-control" name="id_cccd">
                            @error('id_cccd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Trạng Thái</label>
                            <select class="form-control" name="status" required>
                                <option value="Đợi khám">Đợi khám</option>
                                <option value="Đang khám">Đang khám</option>
                                <option value="Đã khám">Đã khám</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="medical_history">Lịch Sử Bệnh</label>
                            <textarea class="form-control" name="medical_history"></textarea>
                            @error('medical_history')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
@endsection

<style>
    .table {
        background-color: #f9f9f9;
        border-radius: 0.5rem;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .modal-content {
        border-radius: 0.5rem;
    }

    .alert {
        margin-bottom: 20px;
    }
</style>