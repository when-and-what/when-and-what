<?php

namespace App\Http\Requests\Api\Checkins;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckinRequest extends FormRequest
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
            'location' => 'required|integer|exists:locations,id',
            'note' => 'nullable',
            'date' => 'date_format:Y-m-d H:i:s',
        ];
    }
}
