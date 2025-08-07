@extends('layouts.app')

@section('title', 'Giỏ hàng - Beauty Shop')

@section('content')
    <!-- Page Header -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="fw-bold">Giỏ hàng</h1>
                    <p class="lead text-muted">Kiểm tra và thanh toán đơn hàng của bạn</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Sản phẩm trong giỏ hàng</h5>
                        </div>
                        <div class="card-body">
                            @php
                            $cartItems = [
                                [
                                    'id' => 1,
                                    'name' => 'Son môi MAC Ruby Woo',
                                    'price' => 850000,
                                    'image' => 'https://via.placeholder.com/100x100?text=MAC+Ruby+Woo',
                                    'quantity' => 2
                                ],
                                [
                                    'id' => 2,
                                    'name' => 'Kem dưỡng ẩm Innisfree',
                                    'price' => 320000,
                                    'image' => 'https://via.placeholder.com/100x100?text=Innisfree+Moisturizer',
                                    'quantity' => 1
                                ],
                                [
                                    'id' => 3,
                                    'name' => 'Sữa rửa mặt L\'Oreal',
                                    'price' => 180000,
                                    'image' => 'https://via.placeholder.com/100x100?text=LOreal+Cleanser',
                                    'quantity' => 3
                                ]
                            ];
                            @endphp

                            @if(count($cartItems) > 0)
                                @foreach($cartItems as $item)
                                <div class="row align-items-center py-3 border-bottom">
                                    <div class="col-md-2">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Mã SP: {{ $item['id'] }}</small>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary" type="button">-</button>
                                            <input type="text" class="form-control text-center" value="{{ $item['quantity'] }}">
                                            <button class="btn btn-outline-secondary" type="button">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="price">{{ number_format($item['price']) }}đ</span>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5>Giỏ hàng trống</h5>
                                    <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                                    <a href="{{ route('products') }}" class="btn btn-primary">
                                        <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Tóm tắt đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            @php
                            $subtotal = 0;
                            foreach($cartItems as $item) {
                                $subtotal += $item['price'] * $item['quantity'];
                            }
                            $shipping = 30000;
                            $tax = $subtotal * 0.1;
                            $total = $subtotal + $shipping + $tax;
                            @endphp

                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($subtotal) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($shipping) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Thuế (10%):</span>
                                <span>{{ number_format($tax) }}đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng cộng:</strong>
                                <strong class="price">{{ number_format($total) }}đ</strong>
                            </div>

                            <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
                            </button>

                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i>
                                    Thanh toán an toàn với SSL
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Coupon Code -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-body">
                            <h6 class="card-title">Mã giảm giá</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Nhập mã giảm giá">
                                <button class="btn btn-outline-primary" type="button">Áp dụng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Thông tin giao hàng</h6>
                                <div class="mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Phương thức thanh toán</h6>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="cod" checked>
                                        <label class="form-check-label" for="cod">
                                            <i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="bank">
                                        <label class="form-check-label" for="bank">
                                            <i class="fas fa-university me-2"></i>Chuyển khoản ngân hàng
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="momo">
                                        <label class="form-check-label" for="momo">
                                            <i class="fas fa-mobile-alt me-2"></i>Ví MoMo
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="vnpay">
                                        <label class="form-check-label" for="vnpay">
                                            <i class="fas fa-credit-card me-2"></i>VNPay
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Ghi chú</h6>
                                <textarea class="form-control" rows="3" placeholder="Ghi chú cho đơn hàng (không bắt buộc)"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Xác nhận đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Quantity controls
    document.querySelectorAll('.btn-outline-secondary').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            let value = parseInt(input.value);
            
            if (this.textContent === '+') {
                value++;
            } else if (this.textContent === '-' && value > 1) {
                value--;
            }
            
            input.value = value;
        });
    });

    // Remove item
    document.querySelectorAll('.btn-outline-danger').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                this.closest('.row').remove();
            }
        });
    });
</script>
@endsection 