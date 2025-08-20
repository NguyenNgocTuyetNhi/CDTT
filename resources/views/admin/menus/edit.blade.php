@extends('admin.layouts.master')

@section('title', 'Chỉnh sửa Menu')

@section('content')
<div class="container mt-4">
    <h2>Chỉnh sửa Menu</h2>

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên menu</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $menu->slug) }}" required>
        </div>

        <div class="mb-3">
            <label for="parent_id" class="form-label">Menu cha</label>
            <select name="parent_id" class="form-select">
                <option value="">-- Không có --</option>
                @foreach($menus as $m)
                    <option value="{{ $m->id }}" {{ $menu->parent_id == $m->id ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="order" class="form-label">Thứ tự</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', $menu->order) }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="1" {{ $menu->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ $menu->status == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
