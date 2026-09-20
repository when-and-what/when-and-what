<?php

namespace App\Http\Requests\Locations\Checkins;

use Illuminate\Foundation\Http\FormRequest;

class CreatePendingCheckinRequest extends FormRequest
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
            'latitude' => 'numeric|required',
            'longitude' => 'numeric|required',
            'name' => 'nullable',
            'note' => 'nullable',
            'date' => 'required|date_format:Y-m-d\TH:i',
            'browser_timezone' => 'required|timezone',
        ];
    }
}
