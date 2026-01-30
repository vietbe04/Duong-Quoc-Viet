<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
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
        $courseId = $this->route('course') ? $this->route('course')->id : $this->input('course_id');

        $rules = [
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|string|max:500',
            'video_type' => 'required|in:youtube,vimeo,upload',
            'content' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_preview' => 'boolean',
            'status' => 'required|in:active,inactive',
        ];

        // Video file upload
        if ($this->video_type === 'upload') {
            $rules['video_file'] = 'nullable|mimes:mp4,avi,mov,wmv|max:512000'; // 500MB max
        }

        // Unique slug validation within course
        if ($this->isMethod('POST')) {
            $rules['slug'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('lessons')->where(function ($query) use ($courseId) {
                    return $query->where('course_id', $courseId);
                }),
            ];
        } else {
            $rules['slug'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('lessons')->where(function ($query) use ($courseId) {
                    return $query->where('course_id', $courseId);
                })->ignore($this->route('lesson')),
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
            'title.required' => 'Tên bài học không được để trống.',
            'title.max' => 'Tên bài học không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại trong khóa học này.',
            'video_url.max' => 'URL video không được vượt quá 500 ký tự.',
            'video_type.required' => 'Vui lòng chọn loại video.',
            'video_type.in' => 'Loại video không hợp lệ.',
            'video_file.mimes' => 'File video phải có định dạng: mp4, avi, mov, wmv.',
            'video_file.max' => 'File video không được vượt quá 500MB.',
            'duration.integer' => 'Thời lượng phải là số nguyên.',
            'duration.min' => 'Thời lượng không được âm.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự không được âm.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
