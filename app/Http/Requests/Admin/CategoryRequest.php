<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id ?? null;

        return [
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:categories,slug,' . $categoryId,
            'description'      => 'nullable|string',
            'icon_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_id'        => 'nullable|exists:categories,id',
            'sort_order'       => 'nullable|integer|min:0',
            'is_featured'      => 'boolean',
            'status'           => 'boolean',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'ক্যাটাগরির নাম দিন',
            'name.max'           => 'ক্যাটাগরির নাম ২৫৫ অক্ষরের বেশি হতে পারবে না',
            'slug.unique'        => 'এই স্লাগ ইতিমধ্যে ব্যবহার করা হয়েছে',
            'icon_image.image'   => 'শুধু ছবি আপলোড করুন',
            'icon_image.max'     => 'ছবির সাইজ ২MB এর কম হতে হবে',
            'banner_image.image' => 'শুধু ছবি আপলোড করুন',
            'banner_image.max'   => 'ছবির সাইজ ২MB এর কম হতে হবে',
            'parent_id.exists'   => 'প্যারেন্ট ক্যাটাগরি বিদ্যমান নেই',
        ];
    }
}
