<?php

namespace App\Http\Requests\Podcasts;

use Illuminate\Foundation\Http\FormRequest;

class EpisodePlayRequest extends FormRequest
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
            'podcast' => 'required|string',
            'episode' => 'required|string',
            'seconds' => 'required|integer',
            'played_at' => 'date_format:Y-m-d H:i:s',
        ];
    }
}
