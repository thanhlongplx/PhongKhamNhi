<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicinesController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionDetailController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Auth\RegisterController; // Import RegisterController nếu cần
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\Prescription_detailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('layouts.app');
});


// Routes cho nhân viên
Route::get('/staffs', [EmployeeController::class, 'index'])->name('staffs.index');
Route::resource('employees', EmployeeController::class);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');

// Routes cho bệnh nhân
Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::resource('patients', PatientController::class);
Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
Route::post('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');


// Routes cho thuốc
Route::get('/medications', [MedicationController::class, 'index'])->name('medications.index');
Route::post('/medications', [MedicationController::class, 'store'])->name('medications.store');

Route::resource('medications', MedicationController::class);


// Routes cho người dùng
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Routes cho đơn thuốc
Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
Route::resource('prescriptions', PrescriptionController::class);



// Routes cho chi tiết đơn thuốc
Route::get('/prescription-details', [PrescriptionDetailController::class, 'index'])->name('prescription_details.index');
Route::resource('prescription_details', PrescriptionDetailController::class);


// Routes cho hồ sơ bệnh án
Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
// Routes cho lịch hẹn
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

// Routes cho hóa đơn
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

//Xử lí đăng kí, đăng nhập
// Route cho trang đăng ký
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Route cho đăng nhập
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

// Route cho trang chính (sau khi đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home'); // Tạo view home.blade.php
    })->name('home');


    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Các route khác sử dụng layout
    Route::get('/welcome', [AuthController::class, 'showWelcomePage'])->name('welcome');
    Route::get('/staffs', [EmployeeController::class, 'index'])->name('staffs');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/medications', [MedicationController::class, 'index'])->name('medications');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/prescription-details', [PrescriptionDetailController::class, 'index'])->name('prescription-details');
    Route::get('/medical_records', [MedicalRecordController::class, 'index'])->name('medical_records');
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');
});

// Route mặc định cho trang chính, chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
Route::get('/', function () {
    return redirect()->route('login');
});


//Phân quyền

