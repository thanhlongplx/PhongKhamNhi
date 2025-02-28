@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4" style="color: #007BFF;">Chỉnh Sửa Đơn Thuốc</h1>

        <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST" class="bg-light p-4 rounded shadow">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="patient_id">Bệnh Nhân</label>
                    <select class="form-control bg-lightyellow" name="patient_id" required>
                        <option value="">Chọn Bệnh Nhân</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $patient->id == $prescription->patient_id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="employee_id">Bác Sĩ</label>
                    @if (auth()->user()->role === 'admin')
                        <select class="form-control bg-lightyellow" name="employee_id" required>
                            <option value="">Chọn Bác Sĩ</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ $doctor->id == $prescription->employee_id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $prescription->doctor->name }}" readonly>
                        <input type="hidden" name="employee_id" value="{{ $prescription->employee_id }}">
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date">Ngày Kê Đơn</label>
                    <input type="date" class="form-control bg-info text-white" name="date" id="date" value="{{ $prescription->date }}" readonly required>
                </div>

                <div class="form-group col-md-6">
                    <label for="notes">Ghi Chú</label>
                    <textarea class="form-control bg-lightyellow text-dark" name="notes" rows="1">{{ $prescription->notes }}</textarea>
                </div>
            </div>

            <h3 class="text-white text-center bg-primary p-1" style="height: 50px;">CHI TIẾT ĐƠN THUỐC</h3>

            <div id="medication-details">
                @if ($prescription->details && count($prescription->details) > 0)
                    @foreach ($prescription->details as $index => $detail)
                        <div class="medication-detail mb-4">
                            <h5 class="text-success">Chi tiết thuốc {{ $index + 1 }}</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="medication_id_{{ $index }}">Thuốc:</label>
                                    <select class="form-control bg-lightyellow text-dark" name="medication_id[]" id="medication_id_{{ $index }}" required>
                                        <option value="">Chọn Thuốc</option>
                                        @foreach($medications as $medication)
                                            <option value="{{ $medication->id }}" {{ $medication->id == $detail->medication_id ? 'selected' : '' }}>
                                                {{ $medication->medicine_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="dosage_{{ $index }}">Liều lượng:</label>
                                    <input type="text" name="dosage[]" id="dosage_{{ $index }}" class="form-control bg-lightyellow text-dark" value="{{ $detail->dosage }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="frequency_{{ $index }}">Tần suất:</label>
                                    <input type="text" name="frequency[]" id="frequency_{{ $index }}" class="form-control bg-lightyellow text-dark" value="{{ $detail->frequency }}" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="quantity_{{ $index }}">Số lượng:</label>
                                    <input type="number" name="quantity[]" id="quantity_{{ $index }}" class="form-control bg-lightyellow text-dark" value="{{ $detail->quantity }}" required min="1">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="total_price_{{ $index }}">Giá Tổng:</label>
                                    <input type="text" name="total_price[]" id="total_price_{{ $index }}" class="form-control bg-lightyellow text-dark" value="{{ $detail->total_price }}" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="usage_instructions_{{ $index }}">Hướng dẫn sử dụng:</label>
                                    <input type="text" name="usage_instructions[]" id="usage_instructions_{{ $index }}" class="form-control bg-lightyellow text-dark" value="{{ $detail->usage_instructions }}">
                                </div>
                            </div>
                            <hr>
                        </div>
                    @endforeach
                @else
                    <p>Không có chi tiết đơn thuốc nào.</p>
                @endif
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="quantity">Số lượng thuốc muốn thêm:</label>
                    <input type="number" id="quantity" class="form-control" min="1" value="1" required>
                </div>
                <div class="form-group col-md-6 d-flex align-items-end">
                    <button type="button" id="add-medications" class="btn btn-secondary">Thêm Chi Tiết Thuốc</button>
                </div>
            </div>

            @if (now()->isToday() && auth()->user()->id == $prescription->employee_id)
                <button type="submit" class="btn btn-primary">Cập Nhật Đơn Thuốc</button>
            @elseif(auth()->user()->role === 'admin')
                <button type="submit" class="btn btn-primary">Cập Nhật Đơn Thuốc</button>
            @else
                <button type="button" class="btn btn-secondary" disabled>Không thể chỉnh sửa</button>
            @endif
        </form>
    </div>
@endsection

<style>
    .bg-lightyellow {
        background-color: #ffffe0; /* Màu vàng nhạt */
    }

    .form-control {
        border-radius: 0.5rem; /* Làm tròn các góc */
    }

    h1, h3 {
        font-family: 'Comic Sans MS', cursive, sans-serif; /* Phông chữ vui nhộn */
    }
</style>

<script>
    document.getElementById('add-medications').addEventListener('click', function () {
        const quantity = document.getElementById('quantity').value;
        const detailsContainer = document.getElementById('medication-details');

        for (let i = 0; i < quantity; i++) {
            const index = detailsContainer.children.length; // Đếm số lượng thuốc hiện có để tạo chỉ số mới

            detailsContainer.innerHTML += `
                <div class="medication-detail mb-4">
                    <h5 class="text-success">Chi tiết thuốc ${index + 1}</h5>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="medication_id_${index}">Thuốc:</label>
                            <select class="form-control bg-lightyellow" name="medication_id[]" id="medication_id_${index}" required>
                                <option value="">Chọn Thuốc</option>
                                @foreach($medications as $medication)
                                    <option value="{{ $medication->id }}">{{ $medication->medicine_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="dosage_${index}">Liều lượng:</label>
                            <input type="text" name="dosage[]" id="dosage_${index}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="frequency_${index}">Tần suất:</label>
                            <input type="text" name="frequency[]" id="frequency_${index}" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="quantity_${index}">Số lượng:</label>
                            <input type="number" name="quantity[]" id="quantity_${index}" class="form-control" required min="1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="total_price_${index}">Giá Tổng:</label>
                            <input type="text" name="total_price[]" id="total_price_${index}" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="usage_instructions_${index}">Hướng dẫn sử dụng:</label>
                            <input type="text" name="usage_instructions[]" id="usage_instructions_${index}" class="form-control">
                        </div>
                    </div>
                    <hr>
                </div>
            `;
        }
    });
</script>