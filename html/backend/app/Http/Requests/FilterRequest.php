<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'title' => 'nullable|string|max:125',
            'order_by' => 'nullable|in:price,created_at',
            'order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|gte:1',
            'province' => 'nullable|exists:locations,province',
        ];
    }
}
