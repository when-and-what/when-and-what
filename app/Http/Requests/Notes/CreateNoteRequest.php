<?php

namespace App\Http\Requests\Notes;

use Illuminate\Foundation\Http\FormRequest;

class CreateNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'icon' => 'nullable|string',
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
            'note' => 'nullable|string',
            'dashboard_visible' => 'boolean',
        ];
    }
}
