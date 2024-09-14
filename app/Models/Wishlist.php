<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    static public function findByProductIdAndAppId($productId, $userId)
    {
        return self::where('product_id', $productId)->where('app_id', $userId)->first();
    }
}
