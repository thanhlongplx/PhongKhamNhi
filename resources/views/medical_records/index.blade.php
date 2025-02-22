@extends('layouts.app') <!-- Kế thừa từ layout chính -->

@section('content')
    <div class="container">
        <h1>Danh Sách Hồ Sơ Bệnh Án</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Hồ Sơ</th>
                    <th>Ngày Khám</th>
                    <th>Triệu Chứng</th>
                    <th>Chẩn Đoán</th>
                    <th>Phác Đồ Điều Trị</th>
                    <th>Tên Bệnh Nhân</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medicalRecords as $record)
                    <tr>
                        <td>{{ $record->id }}</td> <!-- ID hồ sơ -->
                        <td>{{ $record->visit_date }}</td> <!-- Ngày khám -->
                        <td>{{ $record->symptoms }}</td> <!-- Triệu chứng -->
                        <td>{{ $record->diagnosis }}</td> <!-- Chẩn đoán -->
                        <td>{{ $record->treatment }}</td> <!-- Phác đồ điều trị -->
                        <td>
                            @if($record->prescriptions->isNotEmpty())
                                {{ $record->prescriptions->first()->patient->name }} <!-- Lấy ID của đơn thuốc đầu tiên -->
                            @else
                                N/A
                            @endif
                        </td> <!-- Tên bệnh nhân -->
                        <td>{{ $record->created_at }}</td> <!-- Ngày tạo -->
                        <td>{{ $record->updated_at }}</td> <!-- Ngày cập nhật -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection