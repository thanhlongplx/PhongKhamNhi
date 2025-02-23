@extends('layouts.app')

@section('content')
    <div style="background-color: cornflowerblue; border-radius: 50px; padding: 20px" class=" text-black-50 container">
        <h1 class="text-center mb-4">Thêm Đơn Thuốc Mới</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('prescriptions.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="patient_id">Bệnh Nhân</label>
                    <select class="form-control" name="patient_id" required>
                        <option value="">Chọn Bệnh Nhân</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->id }} | {{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="employee_id">Bác Sĩ</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date">Ngày Kê Đơn</label>
                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" readonly required>
                </div>

                <div class="form-group col-md-6">
                    <label for="notes">Ghi Chú</label>
                    <textarea class="form-control" name="notes"></textarea>
                </div>
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

            <div id="medication-details"></div>

            <button type="submit" class="btn btn-primary">Thêm Đơn Thuốc</button>
        </form>
    </div>

    <script>
        document.getElementById('add-medications').addEventListener('click', function () {
            const quantity = document.getElementById('quantity').value;
            const detailsContainer = document.getElementById('medication-details');

            detailsContainer.innerHTML = '';

            for (let i = 0; i < quantity; i++) {
                detailsContainer.innerHTML += `
                                                <div style="background-color: white; margin-bottom: 50px; border-radius: 50px; padding: 20px" class=" medication-detail">
                                                    <h5>Chi tiết thuốc ${i + 1}</h5>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="medication_id_${i}">Thuốc:</label>
                                                            <select class="form-control" name="medication_id[]" id="medication_id_${i}" required>
                                                                <option value="">Chọn Thuốc</option>
                                                                @foreach($medications as $medication)
                                                                    <option value="{{ $medication->id }}">{{ $medication->medicine_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="dosage_${i}">Liều lượng:</label>
                                                            <input type="text" name="dosage[]" id="dosage_${i}" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="frequency_${i}">Tần suất:</label>
                                                            <input type="text" name="frequency[]" id="frequency_${i}" class="form-control" required>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="quantity_${i}">Số lượng:</label>
                                                            <input type="number" name="quantity[]" id="quantity_${i}" class="form-control" required min="1">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="total_price_${i}">Giá Tổng:</label>
                                                            <input type="text" name="total_price[]" id="total_price_${i}" class="form-control" required>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="usage_instructions_${i}">Hướng dẫn sử dụng:</label>
                                                            <input type="text" name="usage_instructions[]" id="usage_instructions_${i}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                            `;
            }
        });
    </script>
@endsection