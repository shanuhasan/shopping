<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    static public function findById($id)
    {
        return self::where('id', $id)->first();
    }

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }

    static public function getCategory()
    {
        return self::where('parent_id', '=', 0)
            ->orWhere('parent_id', '=', '')
            ->where('status', '=', '1')
            ->get();
    }

    static public function getSubCategory()
    {
        $data = Category::select('categories.*', 'tc.category_name as subcategory_name')
            ->where('categories.which_type', 'sub_cat')
            ->leftJoin('categories as tc', 'categories.parent_id', '=', 'tc.id')
            ->get();
        return $data;
    }
    static public function getChildCategory()
    {
        $data = Category::select('categories.*', 'tc.category_name as subcategory_name', 'child_c.category_name as main_category_name')
            ->where('categories.which_type', 'child_cat')
            ->leftJoin('categories as tc', 'categories.parent_id', '=', 'tc.id')
            ->leftJoin('categories as child_c', 'tc.parent_id', '=', 'child_c.id')
            ->orderBy('categories.id', 'DESC')
            ->get();
        return $data;
    }
}
