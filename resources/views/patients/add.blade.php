<!-- Modal Thêm Bệnh Nhân -->
<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-labelledby="addPatientModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientModalLabel">Thêm Bệnh Nhân Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('patients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Tên Bệnh Nhân</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Ngày Sinh</label>
                        <input type="date" class="form-control" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="sex">Giới Tính</label>
                        <select class="form-control" name="sex" required>
                            <option value="">Chọn giới tính</option>
                            <option value="M">Nam</option>
                            <option value="F">Nữ</option>
                            <option value="O">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="height">Chiều Cao (cm)</label>
                        <input type="number" class="form-control" name="height" required>
                    </div>
                    <div class="form-group">
                        <label for="weight">Cân Nặng (kg)</label>
                        <input type="number" class="form-control" name="weight" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_name">Tên Phụ Huynh</label>
                        <input type="text" class="form-control" name="parent_name">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ</label>
                        <input type="text" class="form-control" name="address">
                    </div>
                    <div class="form-group">
                        <label for="medical_history">Lịch Sử Bệnh</label>
                        <textarea class="form-control" name="medical_history"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm Bệnh Nhân</button>
                </div>
            </form>
        </div>
    </div>
</div>