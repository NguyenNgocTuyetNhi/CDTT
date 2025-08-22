@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<!-- Page Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="fw-bold">Thanh toán</h1>
                <p class="lead text-muted">Hoàn tất đơn hàng của bạn</p>
            </div>
        </div>
    </div>
</section>

<!-- Checkout Section -->
<section class="py-5">
    <div class="container">
        @if ($errors->any())
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Form thông tin khách hàng -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            Thông tin giao hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Alert Messages -->
                        <div id="alert-container" class="mb-4 d-none">
                            <div id="alert-content" class="alert"></div>
                        </div>

                        <form id="checkout-form">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        Họ và tên <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        class="form-control"
                                        required
                                    >
                                    <span class="error-message text-danger small"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        Số điện thoại <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="phone" 
                                        name="phone" 
                                        class="form-control"
                                        required
                                    >
                                    <span class="error-message text-danger small"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="form-control"
                                        required
                                    >
                                    <span class="error-message text-danger small"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">
                                        Địa chỉ giao hàng <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        id="address" 
                                        name="address" 
                                        rows="3" 
                                        class="form-control"
                                        required
                                    ></textarea>
                                    <span class="error-message text-danger small"></span>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Phương thức thanh toán <span class="text-danger">*</span>
                                </label>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-2 payment-method-card" data-payment="COD">
                                            <div class="card-body text-center p-3">
                                                <input type="radio" name="payment" value="COD" class="form-check-input" checked>
                                                <div class="mt-2">
                                                    <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                                    <h6 class="mb-1">Thanh toán khi nhận hàng</h6>
                                                    <small class="text-muted">(COD)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-2 payment-method-card" data-payment="MOMO">
                                            <div class="card-body text-center p-3">
                                                <input type="radio" name="payment" value="MOMO" class="form-check-input">
                                                <div class="mt-2">
                                                    <div class="bg-pink rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">Mo</span>
                                                    </div>
                                                    <h6 class="mb-1">Thanh toán qua MoMo</h6>
                                                    <small class="text-muted">Ví điện tử</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" id="submit-button" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-credit-card me-2"></i>
                                    <span id="button-text">Đặt hàng</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2 text-primary"></i>
                            Thông tin đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            @foreach($cart as $item)
                                <div class="d-flex align-items-start border-bottom pb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <img 
                                            src="{{ asset( $item['thumbnail']) }}" 
                                            alt="{{ $item['name'] }}"
                                            class="rounded" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                        >
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item['name'] }}</h6>
                                        <p class="text-muted small mb-1">Số lượng: {{ $item['qty'] }}</p>
                                        <p class="fw-bold text-primary mb-0">
                                            {{ number_format($item['price_buy'], 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold">
                                            {{ number_format($item['price_buy'] * $item['qty'], 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($total, 0, ',', '.') }}₫</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span class="text-success">Miễn phí</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong class="fs-5">Tổng cộng:</strong>
                                    <strong class="fs-5 text-primary">{{ number_format($total, 0, ',', '.') }}₫</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.payment-method-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border-color: #e9ecef !important;
}

.payment-method-card:hover {
    border-color: #007bff !important;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 123, 255, 0.075);
}

.payment-method-card input[type="radio"]:checked + div {
    color: #007bff;
}

.payment-method-card.selected {
    border-color: #007bff !important;
    background-color: #f8f9ff;
}

.bg-pink {
    background-color: #ED2B7F;
}

.btn-pink {
    background-color: #ED2B7F !important;
    border-color: #ED2B7F !important;
}

.btn-pink:hover {
    background-color: #d91f6f !important;
    border-color: #d91f6f !important;
}

.sticky-top {
    z-index: 1020;
}

.space-y-3 > * + * {
    margin-top: 1rem;
}

.space-y-3 > *:first-child {
    margin-top: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy thông tin user từ localStorage để điền form (chỉ để hiển thị)
    const userStr = localStorage.getItem('user');
    if (userStr) {
        try {
            const user = JSON.parse(userStr);
            if (user) {
                document.getElementById('name').value = user.name;
                document.getElementById('email').value = user.email;
            }
        } catch (e) {
            console.error('Error parsing user data:', e);
        }
    }

    // Xóa các dữ liệu thanh toán cũ khi vào trang checkout
    localStorage.removeItem('momoOrderProcessed');
    localStorage.removeItem('pendingMomoOrder');
    localStorage.removeItem('isProcessingCOD');

    const form = document.getElementById('checkout-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const alertContainer = document.getElementById('alert-container');
    const alertContent = document.getElementById('alert-content');
    let isProcessing = false;

    // Xử lý chọn phương thức thanh toán
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function() {
            // Bỏ chọn tất cả
            document.querySelectorAll('.payment-method-card').forEach(c => {
                c.classList.remove('selected');
                c.querySelector('input[type="radio"]').checked = false;
            });
            
            // Chọn card này
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            
            // Cập nhật nút
            updateButtonStyle();
        });
    });

    function updateButtonStyle() {
        const selectedPayment = document.querySelector('input[name="payment"]:checked').value;
        if (selectedPayment === 'MOMO') {
            submitButton.className = 'btn btn-lg px-5';
            submitButton.classList.add('btn-pink');
            submitButton.style.backgroundColor = '#ED2B7F';
            submitButton.style.borderColor = '#ED2B7F';
            buttonText.textContent = 'Thanh toán qua MoMo';
        } else {
            submitButton.className = 'btn btn-primary btn-lg px-5';
            buttonText.textContent = 'Đặt hàng';
        }
    }

    function showAlert(message, type = 'danger') {
        alertContainer.classList.remove('d-none');
        alertContent.className = `alert alert-${type}`;
        alertContent.textContent = message;
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        alertContainer.classList.add('d-none');
    }

    async function handleMomoPayment(formData) {
        // Lưu thông tin đơn hàng tạm thời
        const pendingOrder = {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            address: formData.get('address'),
            cart: @json($cart),
            total: @json($total),
            timestamp: Date.now()
        };
        localStorage.setItem('pendingMomoOrder', JSON.stringify(pendingOrder));

        // Gọi API để lấy URL thanh toán MoMo
        const response = await fetch('{{ route("shop.placeorder") }}', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra khi xử lý thanh toán MoMo');
        }
    }

    async function handleCODPayment(formData) {
        // Kiểm tra xem có đơn hàng đang xử lý không
        if (localStorage.getItem('isProcessingCOD')) {
            throw new Error('Đã có đơn hàng đang được xử lý');
        }

        localStorage.setItem('isProcessingCOD', 'true');
        
        try {
            const response = await fetch('{{ route("shop.placeorder") }}', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                showAlert('Đặt hàng thành công! Đang chuyển hướng...', 'success');
                localStorage.removeItem('cart');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra khi đặt hàng');
            }
        } finally {
            localStorage.removeItem('isProcessingCOD');
        }
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (isProcessing) {
            showAlert('Đang xử lý đơn hàng, vui lòng đợi...');
            return;
        }

        clearErrors();
        isProcessing = true;
        submitButton.disabled = true;
        buttonText.textContent = 'Đang xử lý...';

        try {
            const formData = new FormData(this);
            const paymentMethod = formData.get('payment');

            if (!formData.get('phone') || !formData.get('address')) {
                throw new Error('Vui lòng điền đầy đủ số điện thoại và địa chỉ!');
            }

            if (paymentMethod === 'MOMO') {
                await handleMomoPayment(formData);
            } else {
                await handleCODPayment(formData);
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert(error.message || 'Có lỗi xảy ra khi xử lý đơn hàng');
        } finally {
            isProcessing = false;
            submitButton.disabled = false;
            updateButtonStyle();
        }
    });

    // Khởi tạo style nút
    updateButtonStyle();
});
</script>
@endsection
