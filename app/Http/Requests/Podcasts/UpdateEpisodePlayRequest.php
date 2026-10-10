<?php

namespace App\Http\Requests\Podcasts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpisodePlayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('play'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'played_at' => 'required|date_format:Y-m-d\TH:i',
        ];
    }
}
