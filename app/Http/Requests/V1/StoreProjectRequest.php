<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ProjectStatus;
use App\Enums\ProjectPriority;

class StoreProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:projects,name', // can it be the same for the same user? now we can not
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', ProjectStatus::values()),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:' . implode(',', ProjectPriority::values()),
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
            'name.required' => 'نام پروژه الزامی است',
            'name.max' => 'نام پروژه نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد',
            'status.required' => 'وضعیت پروژه الزامی است',
            'status.in' => 'وضعیت پروژه باید یکی از موارد ' . implode(',', ProjectStatus::labels()) .  ' باشد.',
            'start_date.required' => 'تاریخ شروع الزامی است',
            'start_date.date' => 'فرمت تاریخ شروع نامعتبر است',
            'end_date.date' => 'فرمت تاریخ پایان نامعتبر است',
            'end_date.after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع باشد',
            'priority.required' => 'اولویت پروژه الزامی است',
            'priority.in' => 'اولویت پروژه باید یکی از موارد ' . implode(',', ProjectPriority::labels()) .  ' باشد.',
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
            'name' => 'نام پروژه',
            'description' => 'توضیحات',
            'status' => 'وضعیت',
            'start_date' => 'تاریخ شروع',
            'end_date' => 'تاریخ پایان',
            'priority' => 'اولویت',
        ];
    }
}
