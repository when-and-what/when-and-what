<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendingCheckinUpdateRequest extends FormRequest
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
            'location' => 'nullable|required_without:newlocation|exists:locations,id',
            'date' => 'required',
            'note' => 'nullable',
            'newlocation' => 'boolean',
            'name' => 'required_with:newlocation',
            'category' => 'nullable',
            'latitude' => 'required_with:newlocation|numeric',
            'longitude' => 'required_with:newlocation|numeric',
        ];
    }
}
