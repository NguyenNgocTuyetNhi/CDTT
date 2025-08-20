@extends('admin.layouts.master')

@section('title', 'Chi tiết đơn hàng - Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Mã đơn hàng:</h6>
                            <p class="text-primary">{{ $order->order_number }}</p>
                            
                            <h6 class="font-weight-bold">Ngày đặt:</h6>
                            <p>{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                            
                            <h6 class="font-weight-bold">Phương thức thanh toán:</h6>
                            @if(strtoupper($order->payment_method) == 'COD')
                                <span class="badge badge-info text-dark font-weight-bold">Tiền mặt</span>
                            @elseif(strtoupper($order->payment_method) == 'MOMO')
                                <span class="badge badge-success text-dark font-weight-bold">MoMo</span>
                            @else
                                <span class="badge badge-secondary text-dark font-weight-bold">{{ $order->payment_method }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Trạng thái:</h6>
                            <select class="form-select status-select" 
                                    data-order-id="{{ $order->id }}" 
                                    data-current-status="{{ $order->status }}">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            
                            <h6 class="font-weight-bold mt-3">Ghi chú:</h6>
                            <p>{{ $order->notes ?: 'Không có ghi chú' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết sản phẩm</h6>
                </div>
                <div class="card-body">
                    @if($order->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->product)
                                                <br><small class="text-muted">ID: {{ $item->product_id }}</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->price) }} VNĐ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>{{ number_format($item->total) }} VNĐ</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Không có sản phẩm nào</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Info & Summary -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Họ tên:</h6>
                    <p>{{ $order->customer_name }}</p>
                    
                    <h6 class="font-weight-bold">Email:</h6>
                    <p>{{ $order->customer_email }}</p>
                    
                    <h6 class="font-weight-bold">Số điện thoại:</h6>
                    <p>{{ $order->customer_phone }}</p>
                    
                    <h6 class="font-weight-bold">Địa chỉ:</h6>
                    <p>{{ $order->customer_address }}</p>
                    
                    @if($order->user)
                        <hr>
                        <h6 class="font-weight-bold">Tài khoản:</h6>
                        <p class="text-info">{{ $order->user->name }} (ID: {{ $order->user_id }})</p>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tổng kết đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->subtotal) }} VNĐ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span>{{ number_format($order->shipping_fee) }} VNĐ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Thuế:</span>
                        <span>{{ number_format($order->tax) }} VNĐ</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-primary">{{ number_format($order->total) }} VNĐ</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Status change handler
    $('.status-select').change(function() {
        const orderId = $(this).data('order-id');
        const newStatus = $(this).val();
        const currentStatus = $(this).data('current-status');
        
        if (newStatus === currentStatus) return;
        
        // Show loading
        const $select = $(this);
        const originalValue = currentStatus;
        $select.prop('disabled', true);
        
        $.ajax({
            url: `/admin/orders/${orderId}/status`,
            method: 'PUT',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update current status
                    $select.data('current-status', newStatus);
                    
                    // Show success message
                    showAlert('success', response.message);
                    
                    // Reload page if status changed to completed
                    if (newStatus === 'completed') {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                }
            },
            error: function(xhr) {
                // Revert to original value
                $select.val(originalValue);
                showAlert('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
            },
            complete: function() {
                $select.prop('disabled', false);
            }
        });
    });
    
    // Alert function
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alertHtml);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 3000);
    }
});
</script>
@endsection
