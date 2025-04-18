<?php

namespace App\Http\Requests\Locations\Checkins;

use Illuminate\Foundation\Http\FormRequest;

class EditCheckin extends FormRequest
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
            'note' => 'nullable',
            'date' => 'date_format:Y-m-d\TH:i',
        ];
    }
}
