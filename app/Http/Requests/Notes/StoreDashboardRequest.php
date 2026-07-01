<?php

namespace App\Http\Requests\Notes;

use Illuminate\Foundation\Http\FormRequest;

class StoreDashboardRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'icon' => 'nullable|string',
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
        ];
    }
}
