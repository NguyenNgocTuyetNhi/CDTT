@extends('admin.layouts.master')

@section('title', 'Thêm danh mục')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <!-- Header -->
        <div class="card-header custom-header">
            <h2 class="mb-0">Thêm danh mục</h2>
        </div>

        <!-- Body -->
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" name="slug" value="{{ old('slug') }}" placeholder="Để trống để tự động tạo">
                    <div class="form-text">Slug sẽ được tạo tự động từ tên danh mục nếu để trống</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                               value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Kích hoạt danh mục
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-secondary">Lưu</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light border">Quay lại</a>
            </form>
        </div>
    </div>
</div>

<!-- CSS đặt cuối -->
<style>
    .custom-header {
        background: linear-gradient(90deg, #6c757d, #495057); 
        color: #fff !important;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 15px 20px;
        border-radius: 0.5rem 0.5rem 0 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        border-bottom: 2px solid rgba(255, 255, 255, 0.3);
    }
    .custom-header h2 {
        color: #fff !important;
    }
</style>

<script>
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = document.getElementById('slug');
    if (slug.value === '') {
        slug.value = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
});
</script>
@endsection
