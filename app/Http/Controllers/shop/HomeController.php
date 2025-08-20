<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;

class HomeController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Lấy banner từ database
        $banners = Banner::active()->get();
        // Lấy sản phẩm nổi bật từ database
        $featuredProducts = Product::featured()->take(8)->get();
        //phân trang nếu nhiều sản phẩm nổi bật 
        $featuredProducts = Product::featured()->paginate(8);

        // Lấy danh mục từ database
        $categories = Category::active()->get();
        return view('home', compact('banners', 'featuredProducts', 'categories'));
    }
}
