@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-4">
                Chào mừng 
                @if(Auth::check())
                    @switch(Auth::user()->role)
                        @case('nurse')
                            điều dưỡng {{ Auth::user()->name }}
                            @break
                        @case('doctor')
                            bác sĩ {{ Auth::user()->name }}
                            @break
                        @case('admin')
                            quản trị viên {{ Auth::user()->name }}
                            @break
                        @case('clinic_manager')
                            quản lý phòng khám {{ Auth::user()->name }}
                            @break
                        @default
                            {{ Auth::user()->name }}
                    @endswitch
                @else
                    bạn
                @endif
                đến với hệ thống!
            </h1>
            <p class="lead"><strong>Chúng tôi rất hân hạnh khi có sự đồng hành của bạn trên hành trình phát triển của phòng khám</strong></p>
            <hr class="my-4">
            <h2>Quản lí nhanh các chuyên mục quản lí của bạn</h2>

            @if(Auth::check())
                @php
                    $role = Auth::user()->role;
                @endphp

                <div class="mt-4">
                    @if(in_array($role, ['clinic_manager', 'admin']))
                        <a class="collapse-item btn btn-primary btn-lg mb-2" href="/staffs">
                            <i class="fas fa-user-check"></i> Danh sách nhân viên
                        </a>
                    @endif

                    @if(in_array($role, ['nurse', 'doctor', 'clinic_manager']))
                        <a class="collapse-item btn btn-primary btn-lg mb-2" href="/patients">
                            <i class="fas fa-user"></i> Quản lí bệnh nhân
                        </a>
                    @endif

                    @if($role === 'clinic_manager')
                        <a class="collapse-item btn btn-primary btn-lg mb-2" href="/medications">
                            <i class="fas fa-pills"></i> Quản lí thuốc
                        </a>
                        <a class="collapse-item btn btn-primary btn-lg mb-2" href="/users">
                            <i class="fas fa-users"></i> Quản lí người dùng
                        </a>
                    @endif

                    @if(in_array($role, ['doctor', 'clinic_manager', 'nurse']))
                        <a class="collapse-item btn btn-primary btn-lg mb-2" href="/medical_records">
                            <i class="fas fa-file-alt"></i> Quản lí hồ sơ bệnh án
                        </a>
                        <a class="collapse-item btn btn-primary btn-lg mb-2" href="/prescriptions">
                            <i class="fas fa-file-plus"></i> Quản lí đơn thuốc
                        </a>
                    @endif

                    @if(in_array($role, ['doctor', 'clinic_manager']))
                        <a class="collapse-item btn btn-primary btn-lg mb-2" href="/prescription-details">
                            <i class="fas fa-file-medical"></i> Quản lí chi tiết đơn thuốc
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection