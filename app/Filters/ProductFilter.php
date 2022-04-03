<?php

namespace App\Filters;

use App\Models\Category;

class ProductFilter extends QueryFilter
{
    public function product_name($productName = '')
    {
        return $this->builder
            ->where('name', 'LIKE', '%' . $productName . '%');
    }

    public function category_id($id = null)
    {
        $category = Category::find($id);
        return $this->builder->when($category, function ($query) use ($category) {
            $query->whereIn('id', $category->products->pluck('id'));
        });
    }

    public function category_name($categoryName = null)
    {
        $categoryId = Category::select('id')->where('name', '=', $categoryName)->first();
        $category = Category::find($categoryId->id);
        return $this->builder->when($category, function ($query) use ($category) {
            $query->whereIn('id', $category->products->pluck('id'));
        });
    }

    public function price_from($price_from = null)
    {
        return $this->builder->when($price_from, function ($query) use ($price_from) {
            $query->where('price', '>', $price_from);
        });
    }

    public function price_to($price_to = null)
    {
        return $this->builder->when($price_to, function ($query) use ($price_to) {
            $query->where('price', '<', $price_to);
        });
    }

    public function is_published($is_published = 1)
    {
        return $this->builder
            ->where('is_published', '=', $is_published);
    }

    public function is_deleted($is_deleted = 0)
    {
        return $this->builder
            ->where('is_deleted', '=', $is_deleted);
    }
}
