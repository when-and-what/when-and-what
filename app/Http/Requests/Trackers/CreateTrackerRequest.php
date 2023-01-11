<?php

namespace App\Http\Requests\Trackers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTrackerRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('trackers')->where(
                    fn($query) => $query->where('user_id', $this->user()->id)
                ),
            ],
            'display_name' => 'required',
            'icon' => 'string',
        ];
    }
}
