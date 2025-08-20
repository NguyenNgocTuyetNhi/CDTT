@extends('admin.layouts.master')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-dark font-weight-bold">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Tạo báo cáo
        </a>
    </div>

   

    <!-- Content Row -->
    <div class="row">
        <!-- Tổng sản phẩm -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                TỔNG SẢN PHẨM
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark">{{ $totalProducts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng đơn hàng -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                TỔNG ĐƠN HÀNG
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng doanh thu -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                TỔNG DOANH THU
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark">{{ number_format($totalRevenue) }} VNĐ</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng người dùng -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                TỔNG NGƯỜI DÙNG
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Thống kê đơn hàng theo tháng -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê đơn hàng theo tháng</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh thu theo tháng -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo tháng</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Top 5 sản phẩm bán chạy -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 sản phẩm bán chạy</h6>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-dark font-weight-bold">Sản phẩm</th>
                                        <th class="text-dark font-weight-bold">Số lượng bán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                    <tr>
                                        <td class="text-dark">{{ $product->product_name }}</td>
                                        <td class="text-dark font-weight-bold">{{ $product->total_sold }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Chưa có dữ liệu bán hàng</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đơn hàng gần đây</h6>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-dark font-weight-bold">Mã đơn</th>
                                        <th class="text-dark font-weight-bold">Khách hàng</th>
                                        <th class="text-dark font-weight-bold">Tổng tiền</th>
                                        <th class="text-dark font-weight-bold">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="text-dark">{{ $order->order_number }}</td>
                                        <td class="text-dark">{{ $order->customer_name }}</td>
                                        <td class="text-dark font-weight-bold">{{ number_format($order->total) }} VNĐ</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge badge-warning text-dark font-weight-bold">Chờ xử lý</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge badge-info text-dark font-weight-bold">Đang xử lý</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge badge-success text-dark font-weight-bold">Hoàn thành</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge badge-danger text-dark font-weight-bold">Đã hủy</span>
                                            @else
                                                <span class="badge badge-secondary text-dark font-weight-bold">{{ $order->status ?? 'N/A' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Chưa có đơn hàng nào</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Thống kê theo danh mục -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê theo danh mục</h6>
                </div>
                <div class="card-body">
                    @if($categoryStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-dark font-weight-bold">Danh mục</th>
                                        <th class="text-dark font-weight-bold">Số sản phẩm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryStats as $category)
                                    <tr>
                                        <td class="text-dark">{{ $category->name }}</td>
                                        <td class="text-dark font-weight-bold">{{ $category->products_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Chưa có danh mục nào</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thống kê đơn hàng theo trạng thái -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê đơn hàng theo trạng thái</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Chờ xử lý
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-dark">
                                                {{ $statusStats['pending'] ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Đang xử lý
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-dark">
                                                {{ $statusStats['processing'] ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-cog fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Hoàn thành
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-dark">
                                                {{ $statusStats['completed'] ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Đã hủy
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-dark">
                                                {{ $statusStats['cancelled'] ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-times fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Tổng số lượng bán -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tổng số lượng bán (Orders đã hoàn thành)</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h2 class="text-success font-weight-bold">{{ number_format($totalQuantitySold) }}</h2>
                        <p class="text-dark">Sản phẩm đã bán thành công</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Orders Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
const ordersChart = new Chart(ordersCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyOrders->pluck('month')) !!},
        datasets: [{
            label: 'Số đơn hàng',
            data: {!! json_encode($monthlyOrders->pluck('count')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            fill: true,
            pointBackgroundColor: 'rgb(75, 192, 192)',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointRadius: 8,
            pointHoverRadius: 10,
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    color: '#2c3e50',
                    font: {
                        weight: 'bold',
                        size: 14
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    color: '#2c3e50',
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.1)',
                    lineWidth: 1
                }
            },
            x: {
                ticks: {
                    color: '#2c3e50',
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.1)',
                    lineWidth: 1
                }
            }
        },
        elements: {
            line: {
                tension: 0.3
            }
        }
    }
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
        datasets: [{
            data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ],
            borderWidth: 3,
            borderColor: '#fff',
            hoverBorderWidth: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#2c3e50',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    padding: 20,
                    usePointStyle: true
                }
            }
        },
        cutout: '60%'
    }
});

console.log('Charts loaded successfully');
console.log('Orders data:', {!! json_encode($monthlyOrders) !!});
console.log('Revenue data:', {!! json_encode($monthlyRevenue) !!});
</script>
@endsection
