@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center text-info mb-4">Hồ Sơ Bệnh Nhân</h2>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5>{{ $patient->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Ngày Sinh:</strong> {{ $patient->date_of_birth }}</p>
                        <p><strong>Giới Tính:</strong> {{ $patient->sex }}</p>
                        <p><strong>Chiều Cao:</strong> {{ $patient->height }} cm</p>
                        <p><strong>Cân Nặng:</strong> {{ $patient->weight }} kg</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tên Phụ Huynh:</strong> {{ $patient->parent_name }}</p>
                        <p><strong>Trạng Thái:</strong> {{ $patient->status }}</p>
                        <p><strong>Địa Chỉ:</strong> {{ $patient->address }}</p>
                        <p><strong>Số Điện Thoại:</strong> {{ $patient->phone_number }}</p>
                        <p><strong>Căn Cước Công Dân:</strong> {{ $patient->id_cccd }}</p>
                    </div>
                </div>

                <h5 class="mt-4">Đơn Thuốc</h5>
                @if($prescriptions->isEmpty())
                    <p>Không có đơn thuốc nào cho bệnh nhân này.</p>
                @else
                    @foreach($prescriptions as $prescription)
                    <div class="border p-3 mb-3">
                        <p><strong>Mã Hồ Sơ Bệnh Án:</strong> {{ $prescription->medical_record_id }}</p>
                        <p><strong>Ngày Khám:</strong> {{ $prescription->medicalRecord->visit_date }}</p>
                        <p><strong>Triệu Chứng:</strong> {{ $prescription->medicalRecord->symptoms }}</p>
                        <p><strong>Chẩn Đoán:</strong> {{ $prescription->medicalRecord->diagnosis }}</p>
                        <p><strong>Phác Đồ Điều Trị:</strong> {{ $prescription->medicalRecord->treatment }}</p>
                    </div>
                    @endforeach
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('patients') }}" class="btn btn-secondary">Quay Lại</a>
            </div>
        </div>
    </div>
@endsection