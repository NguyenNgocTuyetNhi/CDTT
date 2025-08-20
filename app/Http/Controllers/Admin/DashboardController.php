<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Tổng số liệu
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $totalUsers = User::count();
        
        // Tháng hiện tại
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Đơn hàng tháng hiện tại
        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        // Doanh thu tháng hiện tại (chỉ orders đã hoàn thành)
        $currentMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total');
        
        // Thống kê theo tháng (6 tháng gần nhất)
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [
                    'month' => $date->locale('vi')->isoFormat('MMM YYYY'),
                    'count' => $item->count
                ];
            });
            
        // Doanh thu theo tháng (chỉ orders đã hoàn thành)
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total) as revenue')
            ->where('status', 'completed') // Chỉ orders đã hoàn thành
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [
                    'month' => $date->locale('vi')->isoFormat('MMM YYYY'),
                    'revenue' => $item->revenue
                ];
            });
            
        // Nếu chỉ có 1 tháng dữ liệu, thêm dữ liệu mẫu để chart đẹp hơn
        if ($monthlyOrders->count() <= 1) {
            $currentDate = Carbon::now();
            $sampleData = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = $currentDate->copy()->subMonths($i);
                $monthKey = $date->locale('vi')->isoFormat('MMM YYYY');
                
                // Tìm dữ liệu thực tế
                $realData = $monthlyOrders->firstWhere('month', $monthKey);
                $sampleData[] = [
                    'month' => $monthKey,
                    'count' => $realData ? $realData['count'] : 0
                ];
            }
            $monthlyOrders = collect($sampleData);
        }
        
        // Tương tự cho revenue
        if ($monthlyRevenue->count() <= 1) {
            $currentDate = Carbon::now();
            $sampleRevenueData = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = $currentDate->copy()->subMonths($i);
                $monthKey = $date->locale('vi')->isoFormat('MMM YYYY');
                
                // Tìm dữ liệu thực tế
                $realData = $monthlyRevenue->firstWhere('month', $monthKey);
                $sampleRevenueData[] = [
                    'month' => $monthKey,
                    'revenue' => $realData ? $realData['revenue'] : 0
                ];
            }
            $monthlyRevenue = collect($sampleRevenueData);
        }
        
        // Top 5 sản phẩm bán chạy (từ orders đã hoàn thành)
        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_sold')
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            })
            ->groupBy('product_name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
        
        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Thống kê theo danh mục (bỏ status check)
        $categoryStats = Category::withCount('products')->get();
        
        // Thống kê đơn hàng theo trạng thái
        $statusStats = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->status => $item->count];
            });
            
        // Tổng số lượng bán (từ orders đã hoàn thành)
        $totalQuantitySold = OrderItem::whereHas('order', function($query) {
            $query->where('status', 'completed');
        })->sum('quantity');
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders', 
            'totalRevenue',
            'totalUsers',
            'currentMonthOrders',
            'currentMonthRevenue',
            'monthlyOrders',
            'monthlyRevenue',
            'topProducts',
            'recentOrders',
            'categoryStats',
            'statusStats',
            'totalQuantitySold'
        ));
    }
}
