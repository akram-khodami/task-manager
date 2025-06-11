<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFolderRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('folders')->where(function ($query) {
                    return $query->where('project_id', $this->project_id);
                })
            ],
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')->where(function ($query) {
                    return $query->where('owner_id', auth()->id());
                })
            ],
            'parent_id' => 'nullable|exists:folders,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام پوشه الزامی است',
            'name.max' => 'نام پوشه نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد',
            'project_id.required' => 'انتخاب پروژه الزامی است',
            'project_id.exists' => 'پروژه انتخاب شده معتبر نیست',
            'parent_id.exists' => 'پوشه والد انتخاب شده معتبر نیست',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام پوشه',
            'project_id' => 'پروژه',
            'parent_id' => 'پوشه والد',
        ];
    }
}
