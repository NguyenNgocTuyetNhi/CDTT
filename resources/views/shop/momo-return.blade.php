@extends('layouts.app')

@section('title', 'Xử lý thanh toán MOMO')

@section('content')
<div class="min-vh-100 bg-light d-flex align-items-center justify-content-center p-4">
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow">
            <div class="card-body text-center p-5">
                @if(!request('resultCode'))
                    <!-- Loading state -->
                    <div class="mb-4">
                        <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <h2 class="h3 fw-bold text-dark mb-3">
                        Đang xử lý thanh toán...
                    </h2>
                    <p class="text-muted mb-0">
                        Vui lòng không tắt hoặc làm mới trang này.<br>
                        Bạn sẽ được chuyển hướng tự động sau khi xử lý xong.
                    </p>
                @else
                    <!-- Result state -->
                    @if(request('resultCode') === '0')
                        <div class="mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 4rem; height: 4rem;">
                                <i class="fas fa-check text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h2 class="h3 fw-bold text-success mb-3">
                            Thanh toán thành công!
                        </h2>
                        <p class="text-muted mb-4">
                            Đơn hàng của bạn đã được xử lý thành công.
                        </p>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('home') }}" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-home me-2"></i>
                                Về trang chủ
                            </a>
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 4rem; height: 4rem;">
                                <i class="fas fa-times text-danger" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h2 class="h3 fw-bold text-danger mb-3">
                            Thanh toán thất bại
                        </h2>
                        <p class="text-muted mb-4">
                            {{ request('message', 'Có lỗi xảy ra trong quá trình thanh toán.') }}
                        </p>
                        <a href="{{ route('shop.checkout') }}" class="btn btn-danger btn-lg px-4">
                            <i class="fas fa-redo me-2"></i>
                            Thử lại
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
