<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;
use Illuminate\Validation\Rule;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;

class StoreTaskRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $folderId = $this->input('folder_id');
                    if (
                        $folderId && Task::where('folder_id', $folderId)
                        ->where('title', $value)
                        ->exists()
                    ) {
                        $fail('یک کار با این عنوان در این پوشه وجود دارد.');
                    }
                },
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', TaskStatus::values()),
            'due_date' => 'nullable|date',
            'priority' => 'required|in:' . implode(',', TaskPriority::values()),
            'folder_id' => [
                'nullable',
                Rule::exists('folders', 'id')->where(function ($query) {
                    $query->whereIn('project_id', function ($subquery) {
                        $subquery->select('id')
                            ->from('projects')
                            ->where('owner_id', auth()->id());
                    });
                }),
            ],
            'assigned_to' => 'nullable|exists:users,id',
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
            'title.required' => 'عنوان کار الزامی است',
            'title.max' => 'عنوان کار نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد',
            'status.required' => 'وضعیت کار الزامی است',
            'status.in' => 'وضعیت کار باید یکی از موارد ' . implode(',', TaskPriority::labels()) . ' باشد.',
            'due_date.date' => 'فرمت تاریخ سررسید نامعتبر است',
            'priority.in' => 'اولویت کار باید یکی از موارد ' . implode(',', TaskPriority::labels()) . ' باشد.',
            'folder_id.exists' => 'پوشه انتخاب شده معتبر نیست',
            'assigned_to.exists' => 'کاربر اختصاص داده شده معتبر نیست',
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
            'title' => 'عنوان کار',
            'description' => 'توضیحات',
            'status' => 'وضعیت',
            'due_date' => 'تاریخ سررسید',
            'priority' => 'اولویت',
            'folder_id' => 'پوشه',
            'assigned_to' => 'کاربر اختصاص داده شده',
        ];
    }
}
