<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    static public function getOrders()
    {
        return self::orderBy('id', 'ASC')->get();
    }

    static public function getCurrentMonthOrders()
    {
        return self::orderBy('id', 'ASC')->get();
    }
}
