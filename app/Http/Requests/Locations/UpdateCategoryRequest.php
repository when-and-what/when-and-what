<?php

namespace App\Http\Requests\Locations;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => [
                'required',
                Rule::unique('location_categories')->where(function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                })->ignore($this->route('category')),
            ],
            'emoji' => 'nullable',
        ];
    }
}
