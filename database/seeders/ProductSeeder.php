<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo danh mục
        $categories = [
            ['name' => 'Son môi', 'slug' => 'son-moi', 'icon' => 'fas fa-lipstick'],
            ['name' => 'Chăm sóc da', 'slug' => 'cham-soc-da', 'icon' => 'fas fa-spa'],
            ['name' => 'Làm sạch', 'slug' => 'lam-sach', 'icon' => 'fas fa-soap'],
            ['name' => 'Trang điểm', 'slug' => 'trang-diem', 'icon' => 'fas fa-palette'],
            ['name' => 'Nước hoa', 'slug' => 'nuoc-hoa', 'icon' => 'fas fa-spray-can'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Tạo sản phẩm
        $products = [
            [
                'name' => 'Son môi MAC Ruby Woo',
                'description' => 'Son môi cao cấp với màu đỏ ruby nổi bật, lên màu đẹp và bền màu.',
                'price' => 850000,
                'old_price' => 950000,
                'image' => 'https://via.placeholder.com/300x300?text=MAC+Ruby+Woo',
                'category' => 'Son môi',
                'brand' => 'MAC',
                'stock' => 50,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Kem dưỡng ẩm Innisfree',
                'description' => 'Kem dưỡng ẩm chiết xuất từ thiên nhiên, phù hợp mọi loại da.',
                'price' => 320000,
                'old_price' => 380000,
                'image' => 'https://via.placeholder.com/300x300?text=Innisfree+Moisturizer',
                'category' => 'Chăm sóc da',
                'brand' => 'Innisfree',
                'stock' => 30,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Sữa rửa mặt L\'Oreal',
                'description' => 'Sữa rửa mặt dịu nhẹ, làm sạch sâu không gây khô da.',
                'price' => 180000,
                'old_price' => 220000,
                'image' => 'https://via.placeholder.com/300x300?text=LOreal+Cleanser',
                'category' => 'Làm sạch',
                'brand' => 'L\'Oreal',
                'stock' => 100,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Phấn phủ Maybelline',
                'description' => 'Phấn phủ trang điểm mịn màng, kiềm dầu hiệu quả.',
                'price' => 280000,
                'old_price' => 320000,
                'image' => 'https://via.placeholder.com/300x300?text=Maybelline+Powder',
                'category' => 'Trang điểm',
                'brand' => 'Maybelline',
                'stock' => 75,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Son môi L\'Oreal Paris',
                'description' => 'Son môi L\'Oreal với công thức mới, bền màu và không khô môi.',
                'price' => 250000,
                'old_price' => 300000,
                'image' => 'https://via.placeholder.com/300x300?text=LOreal+Lipstick',
                'category' => 'Son môi',
                'brand' => 'L\'Oreal',
                'stock' => 60,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Serum dưỡng da Innisfree',
                'description' => 'Serum dưỡng da chống lão hóa, làm mờ nếp nhăn.',
                'price' => 450000,
                'old_price' => 520000,
                'image' => 'https://via.placeholder.com/300x300?text=Innisfree+Serum',
                'category' => 'Chăm sóc da',
                'brand' => 'Innisfree',
                'stock' => 25,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Mascara Maybelline',
                'description' => 'Mascara tạo độ cong và dày mi tự nhiên.',
                'price' => 220000,
                'old_price' => 260000,
                'image' => 'https://via.placeholder.com/300x300?text=Maybelline+Mascara',
                'category' => 'Trang điểm',
                'brand' => 'Maybelline',
                'stock' => 80,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Nước hoa MAC',
                'description' => 'Nước hoa cao cấp với hương thơm độc đáo và bền lâu.',
                'price' => 1200000,
                'old_price' => 1400000,
                'image' => 'https://via.placeholder.com/300x300?text=MAC+Perfume',
                'category' => 'Nước hoa',
                'brand' => 'MAC',
                'stock' => 15,
                'is_featured' => false,
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 