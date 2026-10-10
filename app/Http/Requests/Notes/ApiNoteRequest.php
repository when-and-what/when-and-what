<?php

namespace App\Http\Requests\Notes;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApiNoteRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'icon' => 'nullable|string',
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'published_at' => 'required|date_format:Y-m-d',
            'is_all_day' => ['required', 'boolean'],
            'dashboard_visible' => ['required', 'boolean'],
            'note' => 'nullable|string',
        ];
    }
}
