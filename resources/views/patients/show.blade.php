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
                        <p><strong>Ngày Sinh:</strong> <span style="color: black;">{{ $patient->date_of_birth }}</span></p>
                        <p><strong>Giới Tính:</strong> <span style="color: black;">{{ $patient->sex === 'M' ? 'Nam' : 'Nữ' }}</span></p>
                        <p><strong>Chiều Cao:</strong> <span style="color: black;">{{ $patient->height }} cm</span></p>
                        <p><strong>Cân Nặng:</strong> <span style="color: black;">{{ $patient->weight }} kg</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tên Phụ Huynh:</strong> <span style="color: black;">{{ $patient->parent_name }}</span></p>
                        <p><strong>Trạng Thái:</strong> <span style="color: black;">{{ $patient->status }}</span></p>
                        <p><strong>Địa Chỉ:</strong> <span style="color: black;">{{ $patient->address }}</span></p>
                        <p><strong>Số Điện Thoại:</strong> <span style="color: black;">{{ $patient->phone_number }}</span></p>
                        <p><strong>Căn Cước Công Dân:</strong> <span style="color: black;">{{ $patient->id_cccd }}</span></p>
                    </div>
                </div>

                <h5 class="mt-4">Hồ Sơ Bệnh Án</h5>
                @if($prescriptions->isEmpty())
                    <p>Không có hồ sơ nào cho bệnh nhân này.</p>
                @else
                    @foreach($prescriptions as $prescription)
                        <div class="border p-3 mb-3">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <p><strong>Mã Hồ Sơ Bệnh Án:</strong> <span style="color: black;">HS{{ $prescription->medical_record_id }}</span></p>
                                    <p><strong>Ngày Khám:</strong> <span style="color: black;">{{ $prescription->medicalRecord->visit_date }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Triệu Chứng:</strong> <span style="background-color: orange; color: black;">{{ $prescription->medicalRecord->symptoms }}</span></p>
                                    <p><strong>Chẩn Đoán:</strong> <span style="background-color: yellow; color: black;">{{ $prescription->medicalRecord->diagnosis }}</span></p>
                                </div>
                            </div>
                            <p><strong>Phác Đồ Điều Trị:</strong> <span style="color: black;">{{ $prescription->medicalRecord->treatment }}</span></p>

                            <h5 class="mt-4">Chi Tiết Đơn Thuốc:</h5>
                            @if($prescription->details && $prescription->details->isNotEmpty())
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="color: black;" >Tên Thuốc</th>
                                            <th style="color: black;">Liều Lượng</th>
                                            <th style="color: black;">Tần Suất</th>
                                            <th style="color: black;">Số Lượng</th>
                                            <th style="color: black;">Tổng Giá</th>
                                            <th style="color: black;">Hướng Dẫn Sử Dụng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prescription->details as $detail)
                                            <tr>
                                                <td style="color: black;">{{ $detail->medication->medicine_name }}</td>
                                                <td style="color: black;">{{ $detail->dosage }}</td>
                                                <td style="color: black;">{{ $detail->frequency }}</td>
                                                <td style="color: black;">{{ $detail->quantity }}</td>
                                                <td style="color: black;">{{ $detail->total_price }}</td>
                                                <td style="color: black;">{{ $detail->usage_instructions }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>Không có thuốc nào trong đơn thuốc này.</p>
                            @endif
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