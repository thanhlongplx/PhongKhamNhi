<!-- Modal Thêm Thuốc -->
<div class="modal fade" id="addMedicationModal" tabindex="-1" role="dialog" aria-labelledby="addMedicationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMedicationModalLabel">Thêm Thuốc Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('medications.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="medicine_name">Tên Thuốc</label>
                        <input type="text" class="form-control" name="medicine_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô Tả</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="dosage_form">Hình Thức</label>
                        <input type="text" class="form-control" name="dosage_form">
                    </div>
                    <div class="form-group">
                        <label for="strength">Hàm Lượng</label>
                        <input type="text" class="form-control" name="strength">
                    </div>
                    <div class="form-group">
                        <label for="side_effect">Tác Dụng Phụ</label>
                        <textarea class="form-control" name="side_effect"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="contraindications">Chống Chỉ Định</label>
                        <textarea class="form-control" name="contraindications"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá</label>
                        <input type="number" class="form-control" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity">Số Lượng Trong Kho</label>
                        <input type="number" class="form-control" name="stock_quantity" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm Thuốc</button>
                </div>
            </form>
        </div>
    </div>
</div>