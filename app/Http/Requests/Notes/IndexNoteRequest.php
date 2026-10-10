<?php

namespace App\Http\Requests\Notes;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Query strings send booleans as "true" / "false", which the boolean rule rejects.
     */
    protected function prepareForValidation(): void
    {
        foreach (['all_day', 'dashboard_visible'] as $key) {
            if (! $this->has($key)) {
                continue;
            }

            $value = filter_var($this->input($key), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
            if ($value !== null) {
                $this->merge([$key => $value]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Published on or after this date, in the user's timezone.
            'from' => ['nullable', 'date_format:Y-m-d'],
            // Published on or before this date, in the user's timezone.
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'all_day' => ['nullable', 'boolean'],
            'dashboard_visible' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'in:published_at,-published_at'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
