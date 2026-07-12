<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id ?? null;

        return [
            'category_id'          => 'required|exists:categories,id',
            'brand_id'             => 'nullable|exists:brands,id',
            'name'                 => 'required|string|max:255',
            'slug'                 => 'nullable|string|unique:products,slug,' . $productId,
            'short_description'    => 'nullable|string|max:500',
            'description'          => 'nullable|string',
            'specifications'       => 'nullable|string',
            'sku'                  => 'required|string|unique:products,sku,' . $productId,
            'barcode'              => 'nullable|string',
            'mpn'                  => 'nullable|string',
            'price'                => 'required|numeric|min:0',
            'discount_price'       => 'nullable|numeric|min:0|lt:price',
            'discount_type'        => 'nullable|in:fixed,percent',
            'discount_start_date'  => 'nullable|date',
            'discount_end_date'    => 'nullable|date|after_or_equal:discount_start_date',
            'stock'                => 'required|integer|min:0',
            'stock_alert_quantity' => 'nullable|integer|min:0',
            'unit'                 => 'nullable|string|max:50',
            'weight'               => 'nullable|numeric|min:0',
            'weight_unit'          => 'nullable|string|in:kg,gm,g,lb,oz,liter,ml',
            'is_featured'          => 'boolean',
            'status'               => 'boolean',
            'meta_title'           => 'nullable|string|max:60',
            'meta_description'     => 'nullable|string|max:160',
            'meta_keywords'        => 'nullable|string',
            'images'               => 'nullable|array',
            'images.*'             => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'ক্যাটাগরি নির্বাচন করুন',
            'name.required'        => 'পণ্যের নাম দিন',
            'sku.required'         => 'SKU দিন',
            'sku.unique'           => 'এই SKU ইতিমধ্যে ব্যবহার করা হয়েছে',
            'price.required'       => 'মূল্য দিন',
            'price.numeric'        => 'সঠিক মূল্য দিন',
            'stock.required'       => 'স্টক পরিমাণ দিন',
            'discount_price.lt'    => 'ডিসকাউন্ট মূল্য মূল্যের চেয়ে কম হতে হবে',
            'images.*.image'       => 'শুধু ছবি আপলোড করুন',
            'images.*.max'         => 'ছবির সাইজ 2MB এর কম হতে হবে',
        ];
    }
}
