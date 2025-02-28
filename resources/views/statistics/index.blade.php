@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Thống Kê</h1>

        <div class="row">
            <!-- Thống Kê Tài Chính -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thống Kê Tài Chính</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold">Doanh Thu Hàng Tháng:</h6>
                        <ul class="list-group">
                            @foreach($monthlyRevenue as $revenue)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $revenue->month }}
                                    <span class="badge badge-success">{{ number_format($revenue->total, 2) }} VNĐ</span>
                                </li>
                            @endforeach
                        </ul>

                        <h6 class="font-weight-bold mt-3">Chi Phí Điều Trị Trung Bình:</h6>
                        <p class="font-weight-bold">{{ number_format($averageTreatmentCost, 2) }} VNĐ</p>

                        <h6 class="font-weight-bold">Tỉ Lệ Tăng Trưởng %:</h6>
                        <p class="font-weight-bold">{{ number_format($growthRate, 2) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Thống Kê Khám Chữa Bệnh -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Thống Kê Khám Chữa Bệnh</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold">Tổng Số Bệnh Nhân:</h6>
                        <p class="font-weight-bold">{{ $totalPatients }}</p>

                        <h6 class="font-weight-bold">Độ Tuổi Trung Bình:</h6>
                        <p class="font-weight-bold">{{ number_format($averageAge, 2) }} tuổi</p>

                        <h6 class="font-weight-bold">Giới Tính Bệnh Nhân:</h6>
                        <ul class="list-group">
                            @foreach($genderStats as $gender)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $gender->sex }}
                                    <span class="badge badge-info">{{ $gender->count }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <h6 class="font-weight-bold">Bác Sĩ và Số Lượng Bệnh Nhân Đã Kê Đơn:</h6>
                        <ul class="list-group">
                            @foreach($doctorStats as $doctor)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Bác sĩ {{ $doctor->employee_id }}
                                    <span class="badge badge-warning">{{ $doctor->patient_count }} bệnh nhân</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Thống Kê Thuốc -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Thống Kê Thuốc</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold">Thuốc Được Kê Nhiều Nhất:</h6>
                        <ul class="list-group">
                            @foreach($mostPrescribedDrugs as $drug)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $drug->medication->medicine_name }}
                                    <span class="badge badge-danger">{{ $drug->count }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <h6 class="font-weight-bold mt-3">Số Lượng Thuốc Trong Kho:</h6>
                        <ul class="list-group">
                            @foreach($drugStock as $medication)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $medication->medicine_name }}
                                    <span class="badge badge-primary">{{ $medication->stock_quantity }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tính Toán Nguy Cơ Phá Sản -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Nguy Cơ Phá Sản</h5>
                    </div>
                    <div class="card-body">
                        @php
                            // Khởi tạo biến lương
                            $doctorSalary = 6000000; // Lương bác sĩ
                            $nurseSalary = 4000000; // Lương điều dưỡng
                            $managerSalary = 5000000; // Lương quản lý phòng khám

                            // Đếm số lượng bác sĩ, y tá và quản lý phòng khám
                            $doctorCount = 0;
                            $nurseCount = 0;
                            $managerCount = 0;

                            // Giả sử $employees là danh sách nhân viên đã được lấy từ cơ sở dữ liệu
                            foreach ($employees as $employee) {
                                switch (strtolower(trim($employee->position))) {
                                    case 'doctor':
                                        $doctorCount++;
                                        break;
                                    case 'nurse':
                                        $nurseCount++;
                                        break;
                                    case 'clinic_manager':
                                        $managerCount++;
                                        break;
                                }
                            }

                            // Ghi log số lượng nhân viên
                            Log::info('Số lượng bác sĩ: ' . $doctorCount);
                            Log::info('Số lượng điều dưỡng: ' . $nurseCount);
                            Log::info('Số lượng quản lý phòng khám: ' . $managerCount);

                            // Tính tổng chi phí lương dựa trên số lượng nhân viên
                            $totalSalary = ($doctorCount * $doctorSalary) + ($nurseCount * $nurseSalary) + ($managerCount * $managerSalary);

                            // Tính doanh thu tháng (giả sử lấy doanh thu tháng đầu tiên)
                            $monthlyRevenueAmount = $monthlyRevenue->first()->total ?? 0;

                            // Tính nguy cơ phá sản
                            $bankruptcyRisk = $monthlyRevenueAmount > 0 ? ($totalSalary / $monthlyRevenueAmount) * 100 : 100;

                            // Ghi log tổng lương và nguy cơ phá sản
                            Log::info('Tổng lương: ' . $totalSalary);
                            Log::info('Nguy cơ phá sản: ' . $bankruptcyRisk . '%');
                        @endphp

                        <h6 class="font-weight-bold">Tổng Chi Phí Lương:</h6>
                        <p class="font-weight-bold">{{ number_format($totalSalary, 2) }} VNĐ</p>

                        <h6 class="font-weight-bold">Doanh Thu Tháng:</h6>
                        <p class="font-weight-bold">{{ number_format($monthlyRevenueAmount, 2) }} VNĐ</p>

                        <h6 class="font-weight-bold">Nguy Cơ Phá Sản:</h6>
                        <p class="font-weight-bold">{{ number_format($bankruptcyRisk, 2) }}%</p>
                        @if ($bankruptcyRisk > 100)
                            <p class="text-danger">Cảnh báo: Nguy cơ phá sản cao!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu Đồ -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Doanh Thu Hàng Tháng</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Tổng Số Bệnh Nhân</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="patientsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Thuốc Được Kê Nhiều Nhất</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="drugsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu Đồ Doanh Thu Hàng Tháng
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyRevenue->pluck('month')),
                datasets: [{
                    label: 'Doanh Thu (VNĐ)',
                    data: @json($monthlyRevenue->pluck('total')),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu Đồ Tổng Số Bệnh Nhân
        const patientsCtx = document.getElementById('patientsChart').getContext('2d');
        const patientsChart = new Chart(patientsCtx, {
            type: 'line',
            data: {
                labels: ['Tổng Số Bệnh Nhân'],
                datasets: [{
                    label: 'Bệnh Nhân',
                    data: [{{ $totalPatients }}],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu Đồ Thuốc Được Kê Nhiều Nhất
        const drugsCtx = document.getElementById('drugsChart').getContext('2d');
        const drugsChart = new Chart(drugsCtx, {
            type: 'pie',
            data: {
                labels: @json($mostPrescribedDrugs->map(function ($drug) {
                    return $drug->medication ? $drug->medication->medicine_name : 'Không xác định';
                })),
                datasets: [{
                    label: 'Số Lượng Kê Đơn',
                    data: @json($mostPrescribedDrugs->pluck('count')),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection