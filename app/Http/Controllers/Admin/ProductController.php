<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->has('keyword') && $request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }
        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        $productCount = Product::count();
        $activeCount = Product::where('is_active', true)->count();
        $inactiveCount = Product::where('is_active', false)->count();
        $featuredCount = Product::where('is_featured', true)->count();
        return view('admin.products.index', compact('products', 'productCount', 'activeCount', 'inactiveCount', 'featuredCount'));
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

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'price.required' => 'Vui lòng nhập giá',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'image.required' => 'Vui lòng chọn ảnh sản phẩm',
            'image.image' => 'File phải là ảnh',
            'image.mimes' => 'Ảnh phải có định dạng: JPG, PNG, GIF',
            'image.max' => 'Ảnh không được quá 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        
        // Lấy tên danh mục từ category_id
        $category = Category::find($request->category_id);
        $data['category'] = $category->name;
        unset($data['category_id']); // Xóa category_id vì không cần
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/product'), $imageName);
            $data['image'] = 'images/product/' . $imageName;
        }

        $data['is_active'] = true;
        $data['is_featured'] = false;

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'price.required' => 'Vui lòng nhập giá',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'image.image' => 'File phải là ảnh',
            'image.mimes' => 'Ảnh phải có định dạng: JPG, PNG, GIF',
            'image.max' => 'Ảnh không được quá 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::findOrFail($id);
        $data = $request->all();
        
        // Lấy tên danh mục từ category_id
        $category = Category::find($request->category_id);
        $data['category'] = $category->name;
        unset($data['category_id']); // Xóa category_id vì không cần
        
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu là ảnh local
            if ($product->image && !str_starts_with($product->image, 'http') && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/product'), $imageName);
            $data['image'] = 'images/product/' . $imageName;
        }
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}