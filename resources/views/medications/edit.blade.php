<!-- Modal Sửa Thuốc -->
<div class="modal fade" id="editMedicationModal" tabindex="-1" role="dialog" aria-labelledby="editMedicationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMedicationModalLabel">Sửa Thông Tin Thuốc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editMedicationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_medicine_name">Tên Thuốc</label>
                        <input type="text" class="form-control" name="medicine_name" id="edit_medicine_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Mô Tả</label>
                        <textarea class="form-control" name="description" id="edit_description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_dosage_form">Hình Thức</label>
                        <input type="text" class="form-control" name="dosage_form" id="edit_dosage_form">
                    </div>
                    <div class="form-group">
                        <label for="edit_strength">Hàm Lượng</label>
                        <input type="text" class="form-control" name="strength" id="edit_strength">
                    </div>
                    <div class="form-group">
                        <label for="edit_side_effect">Tác Dụng Phụ</label>
                        <textarea class="form-control" name="side_effect" id="edit_side_effect"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_contraindications">Chống Chỉ Định</label>
                        <textarea class="form-control" name="contraindications" id="edit_contraindications"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_price">Giá</label>
                        <input type="number" class="form-control" name="price" id="edit_price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_stock_quantity">Số Lượng Trong Kho</label>
                        <input type="number" class="form-control" name="stock_quantity" id="edit_stock_quantity" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function editMedication(id) {
        // Gửi yêu cầu để lấy thông tin thuốc
        fetch(`/medications/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                // Điền thông tin vào modal
                document.getElementById('editMedicationForm').action = `/medications/${data.id}`;
                document.getElementById('edit_medicine_name').value = data.medicine_name;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_dosage_form').value = data.dosage_form;
                document.getElementById('edit_strength').value = data.strength;
                document.getElementById('edit_side_effect').value = data.side_effect;
                document.getElementById('edit_contraindications').value = data.contraindications;
                document.getElementById('edit_price').value = data.price;
                document.getElementById('edit_stock_quantity').value = data.stock_quantity;

                // Mở modal
                $('#editMedicationModal').modal('show');
            });
    }
</script>