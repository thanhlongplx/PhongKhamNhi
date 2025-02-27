@extends('layouts.app')

@section('content')
    <div class="container">


        <!-- Nút thêm bệnh nhân -->
        @if(auth()->user()->role === 'doctor')
        @else
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPatientModal">
                Thêm Bệnh Nhân
            </button>
        @endif

        @if(auth()->user()->role === 'doctor')
            <a href="/prescriptions" type="button" class="btn btn-primary mb-3">
                Bắt đầu khám chữa bệnh
            </a>
        @endif

        <!-- Nút tìm nhanh bệnh nhân đợi khám -->
        <form action="{{ route('patients') }}" method="GET" class="form-inline">
    <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm bệnh nhân...">
    <button type="submit" class="btn btn-primary">Tìm Kiếm hoặc Đặt Lại</button>
</form>

        <div class="mb-3 d-flex">


            <div class="dropdown mb-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tìm Kiếm Nhanh
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="/patients?search=Đợi+khám" style="background-color: #FFFFE0;">
                        Đợi Khám
                    </a>
                    <a class="dropdown-item" href="/patients?search=chưa+thanh+toán"
                        style="background-color: #F08080; color: white;">
                        Chưa thanh toán
                    </a>
                    <a class="dropdown-item" href="/patients?search=Tái+khám" style="background-color: #FFFFE0;">
                        Tái khám
                    </a>
                </div>
            </div>




        </div>

        <!-- Thông báo thành công -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Bảng danh sách bệnh nhân -->
        @if ($patients->count() > 0)
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-dark " style="color: black;">
                        <th>Tên</th>
                        <th>Tuổi</th>
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
                        <tr style="color:black; background-color: 
                                                                        {{ 
                                                                            $patient->status === 'Tái khám' ? '#90EE90' :
                        ($patient->status === 'Đã khám' ? 'white' :
                            ($patient->status === 'Đợi khám' ? '#FFFFE0' :
                                ($patient->status === 'Đã khám, chưa thanh toán' || $patient->status === 'Đã khám, hẹn tái khám' ? '#F08080' : 'transparent'))) 
                                                                        }}">

                            <td>{{ $patient->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($patient->date_of_birth)->age }}</td>
                            <td>{{ $patient->sex === 'F' ? 'Nữ' : 'Nam' }}</td>
                            <td>{{ $patient->height }}</td>
                            <td>{{ $patient->weight }}</td>
                            <td>{{ $patient->parent_name }}</td>

                            <td>
                                @if(auth()->user()->role !== 'doctor' && ($patient->status === 'Đã khám, chưa thanh toán' || $patient->status === 'Đã khám, hẹn tái khám'))
                                    <form onsubmit="return confirm('Xác nhận thanh toán cho bệnh nhân {{ $patient->name }}?');"
                                        action="{{ route('patients.clickChecked', $patient->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                                    </form>
                                @endif
                                @if ($patient->created_at->isToday() == false && $patient->status === 'Đợi khám')
    Bệnh nhân đợi khám trễ
@elseif ($patient->status === 'Đã khám, hẹn tái khám(Đã thanh toán)')
    Đã khám, hẹn tái khám
@else
    {{ $patient->status }}
@endif
                            </td>

                            <td>{{ $patient->address }}</td>
                            <td>{{ $patient->phone_number }}</td>
                            <td>{{ $patient->id_cccd }}</td>

                            <td>
    @if (auth()->user()->role === 'admin' || (auth()->user()->role === 'clinic_manager' && $patient->created_at >= now()->subDays(30)))
        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">Sửa</a>
        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
        </form>
    @elseif (auth()->user()->role === 'doctor' || auth()->user()->role === 'nurse') 
        @if ($patient->created_at->isToday() && auth()->user()->role === 'nurse' && $patient->status !== 'Đã khám' && $patient->status !== 'Đã khám, hẹn tái khám(Đã thanh toán)'&& $patient->status !== 'Đã khám, hẹn tái khám')
            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">Sửa</a>
            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
            </form>
        @endif

        @if($patient->created_at->isToday() === false)
            <span class="text-muted">Chỉ chỉnh sửa bệnh nhân trong ngày</span>
        @elseif(
            ($patient->status === 'Đã khám' && $patient->created_at->isToday()) || 
            ($patient->status === 'Đã khám, hẹn tái khám' && $patient->created_at->isToday()) || 
            ($patient->status === 'Đã khám, hẹn tái khám(Đã thanh toán)' && $patient->created_at->isToday())
        )
            <span class="text-muted">Bảo toàn thông tin sau khám</span>
        @endif
    @else
        <span class="text-muted">Sau 30 ngày, quản lý không thể chỉnh sửa</span>
    @endif

    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-info btn-sm">Xem Hồ Sơ</a>

    {{-- Trường ẩn cho tìm kiếm --}}
    <input type="hidden" class="patient-date-of-birth" value="{{ $patient->date_of_birth }}">
    <input type="hidden" class="patient-created-at" value="{{ $patient->created_at }}">
</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <div>
            <div class="d-flex justify-content-center">
        {{ $patients->links('pagination::bootstrap-4') }} <!-- Sử dụng Bootstrap 4 để tạo kiểu -->
    </div>
            </div>
        @else
            <tr>
                <td colspan="11" class="text-center">Không tìm thấy bệnh nhân nào.</td>
            </tr>
        @endif
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tên Bệnh Nhân</label>
                                    <input type="text" class="form-control" name="name" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="date_of_birth">Ngày Sinh</label>
                                    <input type="date" class="form-control" name="date_of_birth" required
                                        max="{{ date('Y-m-d') }}">
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
                            </div>
                            <div class="col-md-6">
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
                                        <option value="Tái khám">Tái khám</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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