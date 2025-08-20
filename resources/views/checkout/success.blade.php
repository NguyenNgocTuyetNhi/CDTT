@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<!-- Page Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 5rem; height: 5rem;">
                        <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h1 class="fw-bold text-success">Đặt hàng thành công!</h1>
                <p class="lead text-muted">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được xử lý.</p>
            </div>
        </div>
    </div>
</section>

<!-- Order Details -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2 text-primary"></i>
                            Thông tin đơn hàng #{{ $order->order_number }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Order Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted mb-3">Thông tin khách hàng</h6>
                                <p class="mb-1"><strong>Tên:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                                <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                                <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted mb-3">Thông tin đơn hàng</h6>
                                <p class="mb-1"><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong>Phương thức:</strong> 
                                    @if($order->payment_method === 'COD')
                                        <span class="badge bg-success">Thanh toán khi nhận hàng</span>
                                    @elseif($order->payment_method === 'MOMO')
                                        <span class="badge bg-pink">Thanh toán qua MoMo</span>
                                    @else
                                        <span class="badge bg-primary">{{ $order->payment_method }}</span>
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Trạng thái:</strong> 
                                    <span class="badge bg-warning">Đang xử lý</span>
                                </p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-3">Chi tiết sản phẩm</h6>
                            @foreach($order->items as $item)
                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-light rounded" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <p class="text-muted small mb-0">Số lượng: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold text-primary mb-0">{{ number_format($item->price, 0, ',', '.') }}₫</p>
                                        <p class="text-muted small mb-0">Tổng: {{ number_format($item->total, 0, ',', '.') }}₫</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Total -->
                        <div class="border-top pt-3">
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tạm tính:</span>
                                        <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Phí vận chuyển:</span>
                                        <span class="text-success">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Thuế:</span>
                                        <span>{{ number_format($order->tax, 0, ',', '.') }}₫</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong class="fs-5">Tổng cộng:</strong>
                                        <strong class="fs-5 text-primary">{{ number_format($order->total, 0, ',', '.') }}₫</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-home me-2"></i>
                        Về trang chủ
                    </a>
                    <a href="{{ route('products') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.bg-pink {
    background-color: #ED2B7F !important;
}
</style>
@endsection
