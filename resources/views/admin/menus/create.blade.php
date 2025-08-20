@extends('admin.layouts.master')

@section('title', 'Thêm Menu')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">➕ Thêm Menu</h2>

    <!-- Form thêm menu -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf

                <!-- Tên Menu -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Tên Menu</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên menu" required>
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label for="slug" class="form-label fw-bold">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" placeholder="vd: san-pham" required>
                </div>

                <!-- Thứ tự -->
                <div class="mb-3">
                    <label for="order" class="form-label fw-bold">Thứ tự</label>
                    <input type="number" class="form-control" id="order" name="order" value="1" min="1">
                </div>

                <!-- Trạng thái -->
                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1">Hiển thị</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                <!-- Nút lưu -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
                    <button type="submit" class="btn btn-primary">💾 Lưu Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
