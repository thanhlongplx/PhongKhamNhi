<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Bảng nhân viên
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            ;
            $table->string('staff_code')->unique(); // Mã nhân viên
            $table->string('name', 100); // Tên nhân viên
            $table->string('position'); // Chức vụ
            $table->string('department')->nullable(); // Phòng ban
            $table->string('phone_number', 15)->nullable(); // Số điện thoại
            $table->date('date_of_hire')->nullable(); // Ngày tuyển dụng
            $table->timestamps();
        });

        // Bảng bệnh nhân
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->date('date_of_birth');
            $table->char('sex', 1); // M, F, O
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('parent_name', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->text('medical_history')->nullable();
            $table->timestamps();
        });

        // Bảng hồ sơ bệnh án
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date'); // Ngày khám
            $table->text('symptoms')->nullable(); // Triệu chứng
            $table->text('diagnosis')->nullable(); // Chẩn đoán của bác sĩ
            $table->text('treatment')->nullable(); // Phương pháp điều trị
            $table->timestamps();
        });

        // Bảng bệnh nhân-hồ sơ bệnh án
        Schema::create('patient_medical_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Bảng thuốc
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_name', 100); // Tên thuốc
            $table->text('description')->nullable(); // Mô tả thuốc
            $table->string('dosage_form', 50)->nullable(); // Hình thức thuốc
            $table->string('strength', 50)->nullable(); // Hàm lượng thuốc
            $table->text('side_effect')->nullable(); // Tác dụng phụ
            $table->text('contraindications')->nullable(); // Chống chỉ định
            $table->decimal('price', 10, 2)->nullable(); // Giá thuốc
            $table->integer('stock_quantity')->default(0); // Số lượng thuốc trong kho
            $table->timestamps();
        });

        // Bảng đơn thuốc
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id(); // Trường id (khóa chính)
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade'); // Liên kết tới hồ sơ bệnh án
            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Liên kết tới nhân viên
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // Liên kết tới bệnh nhân
            $table->text('notes')->nullable(); // Ghi chú
            $table->date('date'); // Ngày kê đơn
            $table->timestamps();
        });

        // Bảng chi tiết đơn thuốc
        Schema::create('prescription_details', function (Blueprint $table) {
            $table->id(); // Trường id (khóa chính)
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade'); // Liên kết tới đơn thuốc
            $table->foreignId('medication_id')->constrained()->onDelete('cascade'); // Liên kết tới thuốc
            $table->integer('quantity'); // Số lượng thuốc
            $table->string('dosage', 50)->nullable(); // Liều dùng
            $table->string('frequency', 50)->nullable(); // Tần suất sử dụng
            $table->decimal('total_price', 10, 2); // Tổng giá của đơn thuốc
            $table->text('usage_instructions')->nullable(); // Hướng dẫn sử dụng
            $table->timestamps();
        });

        // Bảng hóa đơn
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade'); // Liên kết tới hồ sơ bệnh án
            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Liên kết tới nhân viên
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // Liên kết tới bệnh nhân
            $table->decimal('total_amount', 10, 2); // Tổng số tiền trong hóa đơn
            $table->date('date'); // Ngày phát hành hóa đơn
            $table->text('description')->nullable(); // Mô tả nội dung hóa đơn
            $table->timestamps();
        });

        // Bảng lịch khám
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // Liên kết tới bệnh nhân
            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Liên kết tới nhân viên
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade'); // Liên kết tới hồ sơ bệnh án
            $table->dateTime('appointment_time'); // Thời gian khám
            $table->string('status', 20)->nullable(); // Trạng thái cuộc hẹn
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('prescription_details');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('patient_medical_record');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('employees');
    }
};