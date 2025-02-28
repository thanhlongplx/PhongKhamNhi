@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <h1>Danh Sách Nhân Viên</h1>

        <!-- Form tìm kiếm -->
        <form action="{{ route('employees.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

        <!-- Nút thêm nhân viên -->
        <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployeeModal">
            Thêm nhân viên
        </a>

        <!-- Modal thêm nhân viên -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Thêm Nhân Viên Mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="user_id">Người Dùng Nè</label>
                                <select name="user_id" id="user_id" class="form-control" required
                                    onchange="updateUserInfo()">
                                    <option value="">Chọn người dùng</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" data-name="{{ $user->name }}"
                                            data-role="{{ $user->role }}">
                                            {{ $user->id }}| {{ $user->name }}| {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="staff_code">Mã nhân viên</label>
                                <input type="text" class="form-control" name="staff_code" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Tên nhân viên</label>
                                <input type="text" class="form-control" name="name" id="employee_name" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="position">Chức vụ</label>
                                <input type="text" class="form-control" name="position" id="employee_role" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="department">Phòng ban</label>
                                <input type="text" class="form-control" name="department">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone_number">
                            </div>
                            <div class="form-group">
                                <label for="date_of_hire">Ngày tuyển dụng</label>
                                <input type="date" class="form-control" name="date_of_hire">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Thêm nhân viên</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal sửa thông tin -->
        <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Sửa Thông Tin Nhân Viên</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editEmployeeForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="position">Chức Vụ</label>
                                <select name="position" id="position" class="form-control" required>
                                    <option value="">Chọn chức vụ</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Nurse">Nurse</option>
                                    <option value="Clinic Manager">Clinic Manager</option>
                                    <!-- Thêm các chức vụ khác nếu cần -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="department">Phòng ban</label>
                                <input type="text" class="form-control" name="department" id="department">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone_number" id="phone_number">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function editEmployee(id, position, department, phone_number) {
                // Cập nhật đường dẫn cho form
                document.getElementById('editEmployeeForm').action = "/employees/" + id;

                // Cập nhật các trường trong modal
                document.getElementById('position').value = position; // Cập nhật chức vụ
                document.getElementById('department').value = department; // Cập nhật phòng ban
                document.getElementById('phone_number').value = phone_number; // Cập nhật số điện thoại
            }

            function updateUserInfo() {
                const select = document.getElementById('user_id');
                const selectedOption = select.options[select.selectedIndex];

                const name = selectedOption.getAttribute('data-name');
                const role = selectedOption.getAttribute('data-role');

                document.getElementById('employee_name').value = name;
                document.getElementById('employee_role').value = role;
            }
        </script>

        <table class="table">
            <thead>
                <tr>
                    <th>Mã Nhân Viên</th>
                    <th>Tên</th>
                    <th>Chức Vụ</th>
                    <th>Phòng Ban</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Tuyển Dụng</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->staff_code }}</td> <!-- Mã nhân viên -->
                        <td>{{ $employee->name }}</td> <!-- Tên nhân viên -->
                        <td>{{ $employee->position }}</td> <!-- Chức vụ -->
                        <td>{{ $employee->department ?? 'N/A' }}</td> <!-- Phòng ban -->
                        <td>{{ $employee->phone_number ?? 'N/A' }}</td> <!-- Số điện thoại -->
                        <td>{{ $employee->date_of_hire }}</td> <!-- Ngày tuyển dụng -->
                        <td>{{ $employee->created_at }}</td> <!-- Ngày tạo -->
                        <td>{{ $employee->updated_at }}</td> <!-- Ngày cập nhật -->
                        <td>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editEmployeeModal"
                                onclick="editEmployee('{{ $employee->id }}', '{{ $employee->position }}', '{{ $employee->department }}', '{{ $employee->phone_number }}')">
                                Sửa
                            </button>
                        </td>
                        <td>
                            <!-- Nút xóa -->
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection