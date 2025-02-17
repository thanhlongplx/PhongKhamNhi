@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh Sách Thuốc</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMedicationModal">
            Thêm Thuốc
        </button>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Mã Thuốc</th>
                    <th>Tên Thuốc</th>
                    <th>Mô Tả</th>
                    <th>Hình Thức</th>
                    <th>Hàm Lượng</th>
                    <th>Tác Dụng Phụ</th>
                    <th>Chống Chỉ Định</th>
                    <th>Giá</th>
                    <th>Số Lượng Trong Kho</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medications as $medication)
                    <tr>
                        <td>{{ $medication->id }}</td>
                        <td>{{ $medication->medicine_name }}</td>
                        <td>{{ $medication->description ?? 'N/A' }}</td>
                        <td>{{ $medication->dosage_form ?? 'N/A' }}</td>
                        <td>{{ $medication->strength ?? 'N/A' }}</td>
                        <td>{{ $medication->side_effect ?? 'N/A' }}</td>
                        <td>{{ $medication->contraindications ?? 'N/A' }}</td>
                        <td>{{ number_format($medication->price, 2) }} VNĐ</td>
                        <td>{{ $medication->stock_quantity }}</td>
                        <td>{{ $medication->created_at }}</td>
                        <td>{{ $medication->updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editMedicationModal"
                                    onclick="editMedication('{{ $medication->id }}')">
                                Sửa
                            </button>
                            <form action="{{ route('medications.destroy', $medication->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa thuốc này?');">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('medications.add') <!-- Modal thêm thuốc -->
    @include('medications.edit') <!-- Modal sửa thuốc -->
@endsection