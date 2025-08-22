@extends('layouts.app')

@section('title', 'Sản phẩm - Beauty Shop')

@section('content')
    <!-- Page Header -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="fw-bold">Sản phẩm mỹ phẩm</h1>
                    <p class="lead text-muted">Khám phá bộ sưu tập mỹ phẩm đa dạng, chất lượng cao</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Bộ lọc</h5>
                            <!-- Search -->
                            <form method="GET" action="{{ route('products') }}" id="filterForm">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Tìm sản phẩm..." value="{{ request('search') }}">
                                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="discounted" id="discounted" value="1" {{ request('discounted') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="discounted">Chỉ sản phẩm đang giảm giá</label>
                                    </div>
                                </div>
                            <!-- Categories -->
                            <div class="mb-4">
                                <h6>Danh mục</h6>
                                    @foreach($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category{{ $category->id }}" value="{{ $category->name }}" {{ request('category') == $category->name ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                    @endforeach
                                    <!-- Price Range -->
                                    <div class="mb-4 mt-3">
                                        <h6>Khoảng giá</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="price_range" id="price1" value="under_200k" {{ request('price_range') == 'under_200k' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="price1">Dưới 200.000đ</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="price_range" id="price2" value="200k_500k" {{ request('price_range') == '200k_500k' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="price2">200.000đ - 500.000đ</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="price_range" id="price3" value="500k_1m" {{ request('price_range') == '500k_1m' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="price3">500.000đ - 1.000.000đ</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="price_range" id="price4" value="over_1m" {{ request('price_range') == 'over_1m' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="price4">Trên 1.000.000đ</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Áp dụng bộ lọc</button>
                            </form>
                            </div>

                            <!-- Brand -->
                            <div class="mb-4">
                                <h6>Thương hiệu</h6>
                                @php
                                $brands = \App\Models\Product::where('is_active', true)
                                    ->distinct()
                                    ->pluck('brand')
                                    ->filter()
                                    ->values();
                                @endphp
                                @foreach($brands as $brand)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="brand" id="brand{{ $loop->index }}" 
                                           value="{{ $brand }}" 
                                           {{ request('brand') == $brand ? 'checked' : '' }}>
                                    <label class="form-check-label" for="brand{{ $loop->index }}">{{ $brand }}</label>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Sort and View Options -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <span class="text-muted">Hiển thị {{ $products->firstItem() }}-{{ $products->lastItem() }} của {{ $products->total() }} sản phẩm</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <select class="form-select" style="width: auto;" onchange="window.location.href=this.value">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => '']) }}">Sắp xếp theo</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary active">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="row g-4">
                        @forelse($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card product-card h-100">
                                <div class="position-relative">
                                    @php
                                        $img = $product->image;
                                        $isUrl = Str::startsWith($img, ['http://', 'https://']);
                                    @endphp
                                    <img src="{{ $isUrl ? $img : asset($img) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                                    @if($product->hasDiscount())
                                    <span class="position-absolute top-0 start-0 badge bg-danger m-2">Giảm {{ $product->getDiscountPercentage() }}%</span>
                                    @endif
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <button class="btn btn-sm btn-light rounded-circle">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="price">{{ $product->getFormattedPrice() }}</div>
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                           
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <span class="category-badge mb-2">{{ $product->category }}</span>
                                    <h6 class="card-title">
                                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted mb-2">{{ $product->brand }}</small>
                                    <div class="mb-3">
                                        <span class="price">{{ $product->getFormattedPrice() }}</span>
                                        @if($product->hasDiscount())
                                        <span class="old-price ms-2">{{ $product->getFormattedOldPrice() }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-auto">
                                        <div class="row g-2">
                                            <div class="col-8">
                                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-4">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-secondary w-100">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5 text-muted">Không tìm thấy sản phẩm phù hợp.</div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <nav class="mt-5">
                        {{ $products->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Submit search on Enter key
    const searchInputEl = document.getElementById('searchInput');
    if (searchInputEl) {
        searchInputEl.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    }
    // Add to cart functionality
    document.querySelectorAll('.btn-primary').forEach(button => {
        if (button.textContent.includes('Thêm vào giỏ')) {
            button.addEventListener('click', function() {
                // Add animation
                this.innerHTML = '<i class="fas fa-check me-2"></i>Đã thêm';
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-primary');
                }, 2000);
            });
        }
    });

    // Wishlist functionality
    document.querySelectorAll('.fa-heart').forEach(heart => {
        heart.addEventListener('click', function() {
            this.classList.toggle('text-danger');
        });
    });
</script>
@endsection 