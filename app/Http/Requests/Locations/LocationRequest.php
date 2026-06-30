<?php

namespace App\Http\Requests\Locations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
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
        $user_id = $this->user()->id;

        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required',
            'category' => [
                'nullable',
                'array',
                Rule::exists('location_categories', 'id')->where('user_id', $user_id),
            ],
        ];
    }
}
