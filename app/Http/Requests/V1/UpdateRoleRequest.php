<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:roles,name,' . $this->role->id . ',id',
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1',//enum?active,inactive
        ];
    }

    public function messages(): array
    {
        return [
            'is_active.in' => 'The active status must be 0 (inactive) or 1 (active).',
        ];
    }
}
