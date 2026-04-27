<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'vendor_id','category_id','name','slug','description',
        'price','sale_price','stock','unit','thumbnail','status'
    ];

    // Auto-detect URL vs storage path
    public function getThumbnailUrlAttribute(): string
    {
        if (!$this->thumbnail) {
            return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80';
        }
        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }
        return asset('storage/' . $this->thumbnail);
    }

    public function vendor()     { return $this->belongsTo(Vendor::class); }
    public function category()   { return $this->belongsTo(Category::class); }
    public function images()     { return $this->hasMany(ProductImage::class); }

    // ✅ NEW: OrderItems relationship add kiya
    public function orderItems() { return $this->hasMany(OrderItem::class); }
}
