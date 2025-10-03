<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes; // uncomment if you later add soft deletes


class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id','name','slug','description','price_cents','stock','is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

     public function category() { return $this->belongsTo(Category::class); }
     public function images()   { return $this->hasMany(ProductImage::class); }


     /**
     * URL for a cover image (first image or a placeholder).
     */
    public function getCoverUrlAttribute(): string
    {
        // grab the first image path by position or created_at
        $path = $this->images()
            ->orderBy('position')
            ->orderBy('id')
            ->value('path');

        if ($path) {
            // ensure we use the public disk -> /storage/...
            return Storage::disk('public')->url($path);
        }

        // fallback placeholder you can put at public/images/placeholder-product.jpg
        return asset('images/placeholder-product.jpg');
    }
}
