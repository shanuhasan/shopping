<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    static public function getDefaultBanners()
    {
        $banners = Banner::where('status', 1)
            ->where('type', 'default')
            ->select(DB::raw("id,heading as heading,sub_heading,if(image IS NOT NUll,concat('/uploads/banner/',image),'') as image"))
            ->get();

        return $banners;
    }
}
