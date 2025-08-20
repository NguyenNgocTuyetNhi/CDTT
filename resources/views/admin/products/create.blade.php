@extends('admin.layouts.master')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header custom-header">
            <h4 class="mb-0">
                📦 Thêm sản phẩm mới
            </h4>
        </div>

        <div class="card-body">
            <!-- Hiển thị lỗi validate -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Tên sản phẩm -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-bold">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên sản phẩm" value="{{ old('name') }}" required>
                    </div>

                    <!-- Slug -->
                    <div class="col-md-6 mb-3">
                        <label for="slug" class="form-label fw-bold">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" placeholder="slug-san-pham" value="{{ old('slug') }}" required>
                        <div class="form-text">Slug sẽ tự động sinh từ tên sản phẩm (bạn có thể chỉnh lại).</div>
                    </div>

                    <!-- Giá -->
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-bold">Giá (VNĐ)</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Nhập giá" value="{{ old('price') }}" min="0" required>
                    </div>

                    <!-- Danh mục -->
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label fw-bold">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ảnh -->
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label fw-bold">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>

                    <!-- Mô tả -->
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label fw-bold">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Nhập mô tả chi tiết...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Nút thao tác -->
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">
                        ⬅ Quay lại
                    </a>
                    <button type="submit" class="btn btn-secondary">
                        💾 Lưu sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script tạo slug động -->
<script>
    function generateSlug(str) {
        return str
            .toLowerCase()
            .normalize("NFD") // xử lý dấu tiếng Việt
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9\s-]/g, "")
            .trim()
            .replace(/\s+/g, "-")
            .replace(/-+/g, "-");
    }

    let slugModified = false; // check xem slug có bị admin chỉnh tay chưa

    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    // Khi gõ tên -> cập nhật slug (nếu slug chưa bị chỉnh tay)
    nameInput.addEventListener('input', function() {
        if (!slugModified) {
            slugInput.value = generateSlug(this.value);
        }
    });

    // Khi admin chỉnh tay slug -> khóa auto
    slugInput.addEventListener('input', function() {
        slugModified = true;
    });
</script>
@endsection
