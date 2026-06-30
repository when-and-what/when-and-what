<?php

namespace App\Http\Requests\Locations\Checkins;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckin extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'location' => 'required|integer|exists:locations,id',
            'note' => 'nullable',
            'date' => 'nullable|date_format:Y-m-d\TH:i',
        ];
    }
}
