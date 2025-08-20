@extends('admin.layouts.master')

@section('title', 'Quản lý đơn hàng - Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý đơn hàng</h1>
    </div>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Phương thức</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                                @if($order->user)
                                    <br><small class="text-muted">User ID: {{ $order->user_id }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $order->customer_name }}<br>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td>{{ $order->customer_phone }}</td>
                            <td>
                                @if(strtoupper($order->payment_method) == 'COD')
                                    <span class="badge badge-info text-dark font-weight-bold">Tiền mặt</span>
                                @elseif(strtoupper($order->payment_method) == 'MOMO')
                                    <span class="badge badge-success text-dark font-weight-bold">MoMo</span>
                                @else
                                    <span class="badge badge-secondary text-dark font-weight-bold">{{ $order->payment_method }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ number_format($order->total) }} VNĐ</strong>
                                @if($order->items->count() > 0)
                                    <br><small class="text-muted">{{ $order->items->count() }} sản phẩm</small>
                                @endif
                            </td>
                            <td>
                                <select class="form-select form-select-sm status-select" 
                                        data-order-id="{{ $order->id }}" 
                                        data-current-status="{{ $order->status }}">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-order" 
                                        data-order-id="{{ $order->id }}"
                                        data-order-number="{{ $order->order_number }}">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Chưa có đơn hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa đơn hàng <strong id="deleteOrderNumber"></strong>?</p>
                <p class="text-danger">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteOrderForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
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
                    
                    // Reload dashboard if status changed to completed
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
    
    // Delete order handler
    $('.delete-order').click(function() {
        const orderId = $(this).data('order-id');
        const orderNumber = $(this).data('order-number');
        
        $('#deleteOrderNumber').text(orderNumber);
        $('#deleteOrderForm').attr('action', `/admin/orders/${orderId}`);
        $('#deleteOrderModal').modal('show');
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
