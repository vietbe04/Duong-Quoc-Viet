<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
        ];

        // Unique slug validation
        if ($this->isMethod('POST')) {
            $rules['slug'] = 'nullable|string|max:255|unique:courses,slug';
        } else {
            $rules['slug'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('courses', 'slug')->ignore($this->route('course')),
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'instructor_id.required' => 'Vui lòng chọn giảng viên.',
            'instructor_id.exists' => 'Giảng viên không tồn tại.',
            'title.required' => 'Tên khóa học không được để trống.',
            'title.max' => 'Tên khóa học không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'thumbnail.image' => 'File phải là hình ảnh.',
            'thumbnail.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'thumbnail.max' => 'Hình ảnh không được vượt quá 5MB.',
            'price.required' => 'Giá khóa học không được để trống.',
            'price.numeric' => 'Giá khóa học phải là số.',
            'price.min' => 'Giá khóa học không được âm.',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'sale_price.min' => 'Giá khuyến mãi không được âm.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 500 ký tự.',
            'level.required' => 'Vui lòng chọn cấp độ.',
            'level.in' => 'Cấp độ không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
