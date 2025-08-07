<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // Lọc theo danh mục
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Lọc theo thương hiệu
        if ($request->has('brand') && $request->brand) {
            $query->where('brand', $request->brand);
        }

        // Lọc theo khoảng giá
        if ($request->has('price_range')) {
            switch ($request->price_range) {
                case 'under_200k':
                    $query->where('price', '<', 200000);
                    break;
                case '200k_500k':
                    $query->whereBetween('price', [200000, 500000]);
                    break;
                case '500k_1m':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case 'over_1m':
                    $query->where('price', '>', 1000000);
                    break;
            }
        }

        // Sắp xếp
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::active()->get();

        return view('products', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
} 