@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
<div class="container">
    <h1>Danh Sách Người Dùng</h1>
    
    @if (auth()->user()->role === 'clinic_manager' || auth()->user()->role === 'admin')
        <!-- Nút Thêm Người Dùng -->
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Thêm Người Dùng</a>
    @endif

    <!-- Form tìm kiếm -->
    <form action="{{ route('users') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai Trò</th>
                <th>Ngày Tạo</th>
                <th>Ngày Cập Nhật</th>
                @if (auth()->user()->role === 'clinic_manager' || auth()->user()->role === 'admin')
                    <th>Hành Động</th> <!-- Cột mới cho các hành động -->
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td> <!-- Tên người dùng -->
                    <td>{{ $user->email }}</td> <!-- Email -->
                    <td>{{ $user->role }}</td> <!-- Vai trò -->
                    <td>{{ $user->created_at }}</td> <!-- Ngày tạo -->
                    <td>{{ $user->updated_at }}</td> <!-- Ngày cập nhật -->
                    @if (auth()->user()->role === 'clinic_manager' || auth()->user()->role === 'admin')
                        <td>
                            <!-- Nút Sửa -->
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            
                            <!-- Nút Xóa -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Thêm liên kết phân trang -->
    {{ $users->links('pagination::bootstrap-4') }}
</div>
@endsection