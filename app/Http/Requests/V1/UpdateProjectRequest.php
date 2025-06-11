<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255|unique:projects,name,' . $this->project->id . ',id',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:active,completed,on_hold',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'sometimes|in:low,medium,high',
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
            'name.max' => 'نام پروژه نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد',
            'status.in' => 'وضعیت پروژه باید یکی از موارد active، completed یا on_hold باشد',
            'start_date.date' => 'فرمت تاریخ شروع نامعتبر است',
            'end_date.date' => 'فرمت تاریخ پایان نامعتبر است',
            'end_date.after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع باشد',
            'priority.in' => 'اولویت پروژه باید یکی از موارد low، medium یا high باشد',
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
