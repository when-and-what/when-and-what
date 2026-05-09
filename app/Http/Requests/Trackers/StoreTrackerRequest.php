<?php

namespace App\Http\Requests\Trackers;

use App\Enums\TrackerType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreTrackerRequest extends FormRequest
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
        return [
            'type' => ['required', Rule::enum(TrackerType::class)],
            'name' => 'required',
            'code' => ['required', Rule::unique('trackers')->where(function (Builder $query) {
                $query->where('user_id', Auth::id());
            })],
            'unit' => ['required'],
            'color' => ['nullable'],
            'icon' => ['nullable'],
        ];
    }
}
