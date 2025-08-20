<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Hiển thị danh sách menu
    public function index() 
    {
        $menus = Menu::orderBy('order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    // Trang thêm menu
    public function create() 
    {
        $menus = Menu::all(); // để chọn menu cha nếu có
        return view('admin.menus.create', compact('menus'));
    }

    // Xử lý thêm menu
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
        ]);

        Menu::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id ?? null,
            'order' => $request->order ?? 0,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu đã được thêm thành công!');
    }

    // Trang chỉnh sửa menu
    public function edit($id) 
    {
        $menu = Menu::findOrFail($id);
        $menus = Menu::where('id', '!=', $id)->get(); // loại bỏ chính nó khỏi danh sách cha
        return view('admin.menus.edit', compact('menu', 'menus'));
    }

    // Xử lý cập nhật menu
    public function update(Request $request, $id) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $id,
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id ?? null,
            'order' => $request->order ?? 0,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu đã được cập nhật!');
    }

    // Xử lý xóa menu
    public function destroy($id) 
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu đã bị xóa!');
    }
}
