@extends('layouts.app')

@section('content')
    <div style="background-color: cornflowerblue; border-radius: 50px; padding: 20px" class="text-black-50 container">
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
                    @if(auth()->user()->role === 'admin')
                        <select class="form-control" name="employee_id" required>
                            <option value="">Chọn bác sĩ</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id ?? '' }}">
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date">Ngày Kê Đơn</label>
                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" readonly required>
                </div>

                <div class="form-group col-md-6">
                    <label for="notes">Triệu chứng</label>
                    <textarea class="form-control" name="notes" required></textarea>
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

            <h5>Tổng Giá Đơn Thuốc: <span id="total-invoice">0</span> VNĐ</h5>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="follow-up-checkbox">
                <label class="form-check-label" for="follow-up-checkbox">Hẹn tái khám</label>
            </div>
            <div class="form-group" id="follow-up-date-container" style="display: none;">
                <label for="follow_up_date">Ngày Tái Khám:</label>
                <input type="date" class="form-control" name="follow_up_date" id="follow_up_date">
            </div>

            <button type="submit" class="btn btn-primary">Thêm Đơn Thuốc</button>
        </form>
    </div>

    <script>
        document.getElementById('add-medications').addEventListener('click', function () {
            const quantity = document.getElementById('quantity').value; // Lấy số lượng từ input
            const detailsContainer = document.getElementById('medication-details');

            // Xóa các chi tiết thuốc đã tồn tại trước đó
            detailsContainer.innerHTML = '';

            for (let i = 0; i < quantity; i++) {
                const index = detailsContainer.children.length; // Chỉ số chi tiết thuốc

                detailsContainer.innerHTML += `
                    <div style="background-color: white; margin-bottom: 50px; border-radius: 50px; padding: 20px" class="medication-detail">
                        <h5>Chi tiết thuốc ${index + 1}</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="medication_id_${index}">Thuốc:</label>
                                <select class="form-control medication-select" name="medication_id[]" id="medication_id_${index}" required>
                                    <option value="">Chọn Thuốc</option>
                                    @foreach($medications as $medication)
                                        <option value="{{ $medication->id }}" data-price="{{ $medication->price }}">{{ $medication->medicine_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="quantity_${index}">Số lượng:</label>
                                <input type="number" name="quantity[]" id="quantity_${index}" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="total_price_${index}">Giá Tổng:</label>
                                <input type="text" name="total_price[]" id="total_price_${index}" class="form-control" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dosage_${index}">Liều lượng:</label>
                                <input type="text" name="dosage[]" id="dosage_${index}" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="frequency_${index}">Tần suất:</label>
                                <input type="text" name="frequency[]" id="frequency_${index}" class="form-control" required>
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

            updateTotalInvoice(); // Cập nhật tổng giá đơn thuốc
        });

        // Tính toán giá tổng
        document.getElementById('medication-details').addEventListener('change', function (e) {
            if (e.target.matches('.medication-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.dataset.price;
                const index = e.target.id.split('_')[1]; // Lấy chỉ số từ ID

                const quantityInput = document.getElementById(`quantity_${index}`);
                const totalPriceInput = document.getElementById(`total_price_${index}`);

                if (quantityInput.value) {
                    totalPriceInput.value = (quantityInput.value * price).toFixed(2);
                }
                updateTotalInvoice(); // Cập nhật tổng giá đơn thuốc
            }
        });

        // Cập nhật tổng giá khi thay đổi số lượng
        document.getElementById('medication-details').addEventListener('input', function (e) {
            if (e.target.matches('input[type="number"]')) {
                const index = e.target.id.split('_')[1];
                const quantity = e.target.value;
                const medicationSelect = document.getElementById(`medication_id_${index}`);
                const price = medicationSelect.options[medicationSelect.selectedIndex]?.dataset.price;

                if (price) {
                    const totalPriceInput = document.getElementById(`total_price_${index}`);
                    totalPriceInput.value = (quantity * price).toFixed(2);
                    updateTotalInvoice(); // Cập nhật tổng giá đơn thuốc
                }
            }
        });

        // Hàm cập nhật tổng giá hóa đơn
        function updateTotalInvoice() {
            let total = 0;
            const totalPriceInputs = document.querySelectorAll('input[name="total_price[]"]');
            totalPriceInputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total-invoice').innerText = total.toFixed(2);
        }

        // Hiện/ẩn input ngày tái khám
        document.getElementById('follow-up-checkbox').addEventListener('change', function () {
            const followUpDateContainer = document.getElementById('follow-up-date-container');
            followUpDateContainer.style.display = this.checked ? 'block' : 'none';
        });
    </script>
@endsection