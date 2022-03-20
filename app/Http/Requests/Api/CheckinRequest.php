<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CheckinRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'location' => 'integer|exists:locations,id',
            'latitude' => 'numeric|required_without:location',
            'longitude' => 'numeric|required_without:location',
            'name' => 'nullable',
            'note' => 'nullable',
            'date' => 'date_format:Y-m-d H:i:s'
        ];
    }
}
