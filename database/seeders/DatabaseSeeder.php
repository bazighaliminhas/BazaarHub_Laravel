<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ──────────────────────────────
        User::updateOrCreate(['email' => 'admin@bazaarhub.pk'], [
            'name'     => 'Super Admin',
            'email'    => 'admin@bazaarhub.pk',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // ── Categories ───────────────────────────────
        $categories = [
            ['name' => 'Vegetables',   'slug' => 'vegetables',   'icon' => '🥦'],
            ['name' => 'Fruits',       'slug' => 'fruits',       'icon' => '🍎'],
            ['name' => 'Electronics',  'slug' => 'electronics',  'icon' => '📱'],
            ['name' => 'Clothes',      'slug' => 'clothes',      'icon' => '👗'],
            ['name' => 'Shoes',        'slug' => 'shoes',        'icon' => '👟'],
            ['name' => 'Books',        'slug' => 'books',        'icon' => '📚'],
            ['name' => 'Beauty',       'slug' => 'beauty',       'icon' => '💄'],
            ['name' => 'Home & Living','slug' => 'home-living',  'icon' => '🏠'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // ── Vendors ───────────────────────────────────
        $vendors = [
            ['shop_name' => 'Fresh Farm Store',   'name' => 'Ali Hassan',    'email' => 'ali@vendor.com'],
            ['shop_name' => 'TechZone PK',         'name' => 'Sara Ahmed',    'email' => 'sara@vendor.com'],
            ['shop_name' => 'Fashion Hub',         'name' => 'Usman Khan',    'email' => 'usman@vendor.com'],
            ['shop_name' => 'Shoe Palace',         'name' => 'Fatima Malik',  'email' => 'fatima@vendor.com'],
        ];

        foreach ($vendors as $v) {
            Vendor::updateOrCreate(['email' => $v['email']], [
                'shop_name'   => $v['shop_name'],
                'name'        => $v['name'],
                'email'       => $v['email'],
                'password'    => Hash::make('vendor123'),
                'status'      => 'active',
                'description' => 'Welcome to ' . $v['shop_name'] . ' — quality products at best prices!',
            ]);
        }

        // ── Products ──────────────────────────────────
        $products = [
            // Fresh Farm Store (vendor 1) — Vegetables
            ['name'=>'Fresh Tomatoes',    'cat'=>'vegetables',  'price'=>120,  'sale'=>90,  'vendor'=>1, 'stock'=>100, 'unit'=>'kg',
             'img'=>'https://images.unsplash.com/photo-1546470427-e26264be0b0d?w=600&q=80'],
            ['name'=>'Green Spinach',     'cat'=>'vegetables',  'price'=>80,   'sale'=>60,  'vendor'=>1, 'stock'=>80,  'unit'=>'bunch',
             'img'=>'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=600&q=80'],
            ['name'=>'Organic Carrots',   'cat'=>'vegetables',  'price'=>150,  'sale'=>null,'vendor'=>1, 'stock'=>60,  'unit'=>'kg',
             'img'=>'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=600&q=80'],
            ['name'=>'Red Apples',        'cat'=>'fruits',      'price'=>300,  'sale'=>250, 'vendor'=>1, 'stock'=>50,  'unit'=>'kg',
             'img'=>'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?w=600&q=80'],
            ['name'=>'Fresh Mangoes',     'cat'=>'fruits',      'price'=>400,  'sale'=>350, 'vendor'=>1, 'stock'=>40,  'unit'=>'kg',
             'img'=>'https://images.unsplash.com/photo-1553279768-865429fa0078?w=600&q=80'],
            ['name'=>'Bananas',           'cat'=>'fruits',      'price'=>100,  'sale'=>null,'vendor'=>1, 'stock'=>120, 'unit'=>'dozen',
             'img'=>'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=600&q=80'],

            // TechZone PK (vendor 2) — Electronics
            ['name'=>'Samsung Earbuds',   'cat'=>'electronics', 'price'=>3500, 'sale'=>2999,'vendor'=>2, 'stock'=>30,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&q=80'],
            ['name'=>'USB-C Fast Charger','cat'=>'electronics', 'price'=>1200, 'sale'=>999, 'vendor'=>2, 'stock'=>50,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600&q=80'],
            ['name'=>'Wireless Mouse',    'cat'=>'electronics', 'price'=>1800, 'sale'=>1499,'vendor'=>2, 'stock'=>25,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600&q=80'],
            ['name'=>'Mechanical Keyboard','cat'=>'electronics','price'=>5500, 'sale'=>4999,'vendor'=>2, 'stock'=>15,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&q=80'],

            // Fashion Hub (vendor 3) — Clothes
            ['name'=>'Men Casual Shirt',  'cat'=>'clothes',     'price'=>1500, 'sale'=>1199,'vendor'=>3, 'stock'=>40,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&q=80'],
            ['name'=>'Women Kurti',       'cat'=>'clothes',     'price'=>1800, 'sale'=>1499,'vendor'=>3, 'stock'=>35,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1594938298603-c8148c4b4357?w=600&q=80'],
            ['name'=>'Denim Jeans',       'cat'=>'clothes',     'price'=>2500, 'sale'=>1999,'vendor'=>3, 'stock'=>30,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&q=80'],
            ['name'=>'Summer Dress',      'cat'=>'clothes',     'price'=>2200, 'sale'=>null,'vendor'=>3, 'stock'=>20,  'unit'=>'piece',
             'img'=>'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&q=80'],

            // Shoe Palace (vendor 4) — Shoes
            ['name'=>'Nike Running Shoes','cat'=>'shoes',       'price'=>8500, 'sale'=>6999,'vendor'=>4, 'stock'=>20,  'unit'=>'pair',
             'img'=>'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80'],
            ['name'=>'Casual Sneakers',   'cat'=>'shoes',       'price'=>4500, 'sale'=>3999,'vendor'=>4, 'stock'=>25,  'unit'=>'pair',
             'img'=>'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=600&q=80'],
            ['name'=>'Leather Sandals',   'cat'=>'shoes',       'price'=>2500, 'sale'=>null,'vendor'=>4, 'stock'=>30,  'unit'=>'pair',
             'img'=>'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=600&q=80'],
        ];

        $catMap    = Category::pluck('id', 'slug');
        $vendorIds = Vendor::pluck('id')->values();

        foreach ($products as $i => $p) {
            Product::updateOrCreate(['slug' => Str::slug($p['name'])], [
                'vendor_id'   => $vendorIds[$p['vendor'] - 1] ?? $vendorIds[0],
                'category_id' => $catMap[$p['cat']],
                'name'        => $p['name'],
                'slug'        => Str::slug($p['name']),
                'description' => 'Premium quality ' . $p['name'] . ' available at BazaarHub. Fresh, authentic and delivered to your doorstep.',
                'price'       => $p['price'],
                'sale_price'  => $p['sale'],
                'stock'       => $p['stock'],
                'unit'        => $p['unit'],
                'thumbnail'   => $p['img'],
                'status'      => 'active',
            ]);
        }
    }
}
