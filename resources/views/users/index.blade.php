@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
<div class="container">
    <h1>Danh Sách Người Dùng</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai Trò</th>
                <th>Ngày Tạo</th>
                <th>Ngày Cập Nhật</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection